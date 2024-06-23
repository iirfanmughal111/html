<?php
namespace Tapatalk\Listener;

use XF\App;
use XF\Mvc\RouteMatch;
use XF\PreEscaped;
use XF\Repository\AddOn;

class Hook
{
    public static function templateHook (\XF\Template\Templater $templater, &$type, &$template, array &$params)
    {
        if ($type == 'public' && strtoupper($template) == 'PAGE_CONTAINER') {
            $app = \XF::app();

            $options = $app->options();

            /** @var AddOn $addOnRepo */
            $addOnRepo = $app->repository('XF:AddOn');
            $tapatalk_addon = $addOnRepo->finder('XF:AddOn')->where([
                'addon_id' => 'Tapatalk'
            ])->fetchOne();

            if($tapatalk_addon && isset($tapatalk_addon['version_string']) && isset($tapatalk_addon['active']))
            {
                if (!$tapatalk_addon['active']) {
                    return;
                }
            }else{
                return;
            }

            $tapatalk_dir = self::getTapatalkDirName($app);
            //$forum_root = dirname(dirname(dirname(dirname(__FILE__))));
            $forum_root = \XF::getRootDirectory();
            //$app_location = self::getCurrentFullUri($app);
            $pageType = self::getCurrentPageType($templater, $app);
            $app_location = self::get_scheme_url($app,$templater,$pageType, $id_value);
            if ($pageType == 'index') {
                $pageType = 'home';
            }
            if ($pageType != 'other' && $app_location) {

                $headContentArray = [];
                $headContentArray['app_head_include'] = self::setupHeadInc($app, $templater, $forum_root, $tapatalk_dir, $pageType, $app_location);

                $head = isset($params['head']) ? $params['head'] : '';
                $tapatalkHead = [];

                foreach ($headContentArray as $headMetaName => $htmlVal) {
                    $tapatalkHead[$headMetaName] = new PreEscaped($htmlVal, 'html');
                }

                if (!$head) $head = [];
                $head+= $tapatalkHead;

                $params['head'] = $head;  // overwrite
            }
        }
    }
      /**
     * @param \XF\App $app
     * @param \XF\Template\Templater $templater
     * @param $location
     * @param $id_value
     * @return string
     */
    public static function get_scheme_url($app, $templater,  &$location, &$id_value)
    {
        $param_arr = self::get_scheme_url_params($app, $templater, $location, $id_value);
        $queryString = http_build_query($param_arr);
        $baseUrl = self::tt_get_board_url().'?';
        $baseUrl = preg_replace('/https?:\/\//', '', $baseUrl);
        $url =  $baseUrl . $queryString;
        $url = preg_replace('/^(https|http):\/\//isU', '', $url);
        return $url;
    }
    /**
     * @param \XF\App $app
     * @param \XF\Template\Templater $templater
     * @param $location
     * @param $id_value
     * @return string
     */
    public static function get_scheme_url_params($app, $templater,  &$location, &$id_value)
    {
        $options = $app->options();
        $param_arr = array();
        $TT_bannerControlData = isset($options->tapatalk_banner_control) ? $options->tapatalk_banner_control :  array('banner_enable' => -1);
        $TT_bannerControlData = !empty($TT_bannerControlData) ? unserialize($TT_bannerControlData) : false;

        if($TT_bannerControlData!== false && !empty($TT_bannerControlData['forum_id']) && is_array($TT_bannerControlData) && $TT_bannerControlData['forum_id'] > 0 ){
            $param_arr['ttfid'] = $TT_bannerControlData['forum_id'];
        } else {
            $param_arr['ttfid'] = 0;
        }

        $baseUrl = self::tt_get_board_url().'?';
        $baseUrl = preg_replace('/https?:\/\//', '', $baseUrl);
        $visitor = \XF::visitor();
        $options = $app->options();
        if($visitor['user_id'] != 0)
            $baseUrl .= 'user_id='.$visitor['user_id'].'&';

        $path = $app->request()->getRoutePath();

        $location = 'index';
        $other_info = '';
        $split_rs = preg_split('/\//', $path);
        if(!empty($split_rs) && is_array($split_rs))
        {
            $action = isset($split_rs[0]) && !empty($split_rs[0])?  $split_rs[0] : '';
            $title = isset($split_rs[1]) && !empty($split_rs[1])?  $split_rs[1] : '';
            $other = isset($split_rs[2]) && !empty($split_rs[2])?  $split_rs[2] : '';
            if(!empty($action))
            {

                switch($action)
                {
                    case 'threads':
                    case 'topic':
                        $location = 'topic';
                        $id_name = 'tid';
                        $perPage = $options->messagesPerPage;
                        break;
                    case 'forums':
                        $location = 'forum';
                        $id_name = 'fid';
                        $perPage = $options->discussionsPerPage;
                        break;
                    case 'members':
                    case 'profile':
                        $location = 'profile';
                        $id_name = 'uid';
                        $perPage = $options->membersPerPage;
                        break;
                    case 'conversations':
                    case 'message':
                        $location = 'message';
                        $id_name = 'mid';
                        $perPage = $options->discussionsPerPage;
                    case 'online':
                        $location = 'online';
                        $perPage = $options->membersPerPage;
                    case 'search':
                        $location = 'search';
                        $perPage = $options->searchResultsPerPage;
                    case 'login':
                        $location = 'login';
                    default:
                        break;
                }

                if(preg_match('/(page=|page-)(\d+)/', $other, $match)){
                    $page = $match[2];
                }else{
                    $page = 1;
                }

                $other_info = '';
                if(!empty($title) && $location != 'index')
                {
                    if(preg_match('/\./',$title,$match))
                    {
                        $departs = preg_split('/\./', $title);
                        if(isset($id_name) && !empty($id_name) && isset($departs[1]) && !empty($departs[1]))
                        {
                            $other_info .= $id_name.'='.intval($departs[1]);
                            $id_value = intval($departs[1]);
                        }
                    } else if (preg_match('/^\d+$/', $title, $match)){
                        if (isset($id_name) && !empty($id_name)){
                            $other_info .= $id_name.'='.intval($match[0]);
                            $id_value = intval($match[0]);
                        }
                    }
                }
                if (!empty($page)){
                    if(!empty($other_info)){
                        $other_info .= '&';
                    }
                    if (!isset($perPage)) $perPage = 20;
                    $other_info .= 'page='.$page.'&perpage='.(intval($perPage) ? intval($perPage) : 20);
                }
            }
        }
        else
        {
            $location = 'other';
        }
        $param_arr['location'] = $location;
        if(isset($id_name) && !empty($id_name))
        {
            $param_arr[$id_name] = $id_value;
        }
        if (!empty($page)){
            $param_arr['page'] = $page;
            if (!isset($perPage)) $perPage = 20;
            $param_arr['perpage'] = (intval($perPage) ? intval($perPage) : 20);
        }
        return $param_arr;
    }

    /**
     * @param $PreEscaped
     * @param array $tapatalkHead
     * @param $xfOriginData
     * @return PreEscaped
     */
    protected static function clearOriginHeadContent($PreEscaped, $tapatalkHead, &$xfOriginData)
    {
        if (!$xfOriginData || !is_array($xfOriginData)) {
            $xfOriginData = [
                'og:url' => '',
                'og:title' => '',
                'og:description' => '',
                'og:image' => '',
                'og:type' => '',
            ];
        }
        /** @var PreEscaped $PreEscaped */
        if ($PreEscaped && ($PreEscaped instanceof PreEscaped)) {

            $metaArray = explode("\n\t", $PreEscaped->value);
            $replaceNewValue = '';
            foreach ($metaArray as $v) {
                $v = trim(str_replace('\t', '', $v));
                if ($v) {
                    if (preg_match('#<meta[^>]+property="(og:|twitter:)title"[^>]*content="([^">]+)"#siU', $v, $match))
                    {
                        $xfOriginData['og:title'] = isset($match[2]) && $match[2] ? $match[2] : '';
                    }elseif (preg_match('#<meta[^>]+property="(og:|twitter:)description"[^>]*content="([^">]+)"#siU', $v, $match))
                    {
                        $xfOriginData['og:description'] = isset($match[2]) && $match[2] ? $match[2] : '';
                    }elseif (preg_match('#<meta[^>]+property="(og:|twitter:)image"[^>]*content="([^">]+)"#siU', $v, $match))
                    {
                        $xfOriginData['og:image'] = isset($match[2]) && $match[2] ? $match[2] : '';
                    }elseif (preg_match('#<meta[^>]+property="(og:|twitter:)type"[^>]*content="([^">]+)"#siU', $v, $match))
                    {
                        $xfOriginData['og:type'] = isset($match[2]) && $match[2] ? $match[2] : '';
                    }

                    if (preg_match('#<meta[^>]+property="(og:|twitter:)([\w]+)"[^>]*content="([^">]+)"#siU', $v, $match) && isset($tapatalkHead['twitter'])) {
                        continue;
                    } elseif (preg_match('#<meta[^>]+property="(og:|facebook:)([\w]+)"[^>]*content="([^">]+)"#siU', $v, $match) && isset($tapatalkHead['facebook'])) {
                        continue;
                    } elseif (preg_match('#<meta[^>]+property="(og:|google:)([\w]+)"[^>]*content="([^">]+)"#siU', $v, $match) && isset($tapatalkHead['google'])) {
                        continue;
                    }

                }
                $replaceNewValue.= "\n\t" . $v;
            }

            $PreEscaped->value = $replaceNewValue;
        }
        return $PreEscaped;
    }
    /**
     * @param \XF\App $app
     * @return string
     */
    protected static function getTapatalkDirName($app)
    {
        $options = $app->options();
        return $tapatalk_dir = ($options->tp_directory) ? $options->tp_directory : 'mobiquo';
    }

    /**
     * @param \XF\App $app
     * @param \XF\Template\Templater $templater
     * @param $forum_root
     * @param $tapatalk_dir
     * @param $pageType
     * @param $app_location
     * @return array|mixed
     */
    protected static function setupHeadInc($app,$templater, $forum_root, $tapatalk_dir, $pageType, $app_location)
    {
        $options = $app->options();

        $app_banner_enable = 1;//$options->full_banner;
        $google_indexing_enabled = 1;//$options->google_indexing_enabled;
        $facebook_indexing_enabled = 0;//$options->facebook_indexing_enabled;
        $twitter_indexing_enabled = 0;//$options->twitter_indexing_enabled;

        $TT_bannerControlData = isset($options->tapatalk_banner_control) ? $options->tapatalk_banner_control :  array('banner_enable' => -1);
        if (!is_array($TT_bannerControlData) && $TT_bannerControlData) {
            $TT_bannerControlData = unserialize($TT_bannerControlData);
        }
        if (!is_array($TT_bannerControlData)) {
            $TT_bannerControlData = [];
        }
        $TT_bannerControlData['banner_enable'] = $app_banner_enable = (isset($TT_bannerControlData['banner_enable']) ? $TT_bannerControlData['banner_enable'] : 0);
        $TT_bannerControlData['google_enable'] = $google_indexing_enabled = (isset($TT_bannerControlData['google_enable']) ? $TT_bannerControlData['google_enable'] : 0);

        $page_type = $pageType;
        // head
        if(isset($TT_bannerControlData['byo_info']) && !empty($TT_bannerControlData['byo_info']))
        {
            $app_rebranding_id = isset($TT_bannerControlData['byo_info']['app_rebranding_id']) ? $TT_bannerControlData['byo_info']['app_rebranding_id'] : '';
            $app_url_scheme = isset($TT_bannerControlData['byo_info']['app_url_scheme']) ? $TT_bannerControlData['byo_info']['app_url_scheme'] : '';

            $app_android_id = isset($TT_bannerControlData['byo_info']['app_android_id']) ? $TT_bannerControlData['byo_info']['app_android_id'] : '';
            //$app_android_description = $TT_bannerControlData['byo_info']['app_android_description'];

            $app_ios_id = isset($TT_bannerControlData['byo_info']['app_ios_id']) ? $TT_bannerControlData['byo_info']['app_ios_id'] : '';
            //$app_ios_description = $TT_bannerControlData['byo_info']['app_ios_description'];
        }
        $ttForumId = isset($TT_bannerControlData['forum_id']) ? $TT_bannerControlData['forum_id'] : '';
        $app_piwik_id = isset($TT_bannerControlData['piwik_id']) ? $TT_bannerControlData['piwik_id'] : 0;
        $app_banner_version_id = isset($TT_bannerControlData['banner_version']) ? $TT_bannerControlData['banner_version'] : 0;

        $locationParams = self::get_scheme_url_params($app, $templater, $location, $id_value);
        if(in_array($locationParams['location'], array('forum','topic','post')))
        {
            if (!$app) $app = \XF::app();
            $request = $app->request();
            $app_sharelink_url = $request->getFullBasePath() . '/' . $request->getRoutePath();
            $app_sharelink_location = $locationParams['location'];
            $app_sharelink_ttforumid = $ttForumId;
            $app_sharelink_fid = isset($locationParams['fid']) ? $locationParams['fid'] : null;
            $app_sharelink_tid = isset($locationParams['tid']) ? $locationParams['tid'] : null;
            $app_sharelink_pid = isset($locationParams['pid']) ? $locationParams['pid'] : null;
        }

        global $app_head_include;
        if (!function_exists('tt_getenv')){
            if (!$app) $app = \XF::app();
            $request = $app->request();
            $smartBannerPath = $request->getFullBasePath() . '/' . $tapatalk_dir .'/smartbanner/';
            $headIncFile = $forum_root . '/' . $tapatalk_dir .'/smartbanner/head.inc.php';
            if (file_exists($headIncFile)) {
                include_once($headIncFile);
            }else if (file_exists($headIncFile = dirname($_SERVER['SCRIPT_FILENAME']) . '/' . $tapatalk_dir .'/smartbanner/head.inc.php')){
                include_once($headIncFile);
            }
        }
        if(isset($app_head_include)){
            return $app_head_include;
        }
        return '';
    }

    protected static function tt_html_escape($str, $special = false)
    {
        $str = addslashes(htmlspecialchars($str, ENT_NOQUOTES, "UTF-8"));
        if($special)
        {
            $str = str_replace('&amp;', '&', $str);
        }
        return $str;
    }

    protected static function tt_add_channel($url, $channel)
    {
        if (strpos($url, '?') === false)
            $url .= "?channel=$channel";
        else
            $url .= "&channel=$channel";

        return $url;
    }

    protected static function isBoot()
    {
        $USER_AGENT = self::getRequestValue('HTTP_USER_AGENT');
        return preg_match('/bot|crawl|slurp|spider/i', $USER_AGENT);
    }

    protected static function isMobile()
    {
        $USER_AGENT = self::getRequestValue('HTTP_USER_AGENT');
        return preg_match('/iPhone|iPod|iPad|Silk|Android|IEMobile|Windows Phone|Windows RT.*?ARM/i', $USER_AGENT);
    }

    /**
     * @param \XF|App $app
     * @return mixed
     */
    protected static function getCurrentFullUri($app = null)
    {
        if (!$app) $app = \XF::app();
        return $fullCurrentUrl = $app->request()->getFullRequestUri();
    }

    /**
     * @param \XF\Template\Templater $templater
     * @param \XF|App $app
     * @return mixed|string
     */
    protected static function getCurrentPageType($templater, $app)
    {
        /** @var RouteMatch $pather */
        $pather = $templater->getRouter()->routeToController($app->request()->getRoutePath(), $app->request());
        if ($pather) {
            $controllerName = $pather->getController();
            return $pageControllerName = self::resolvePageControllerName($controllerName);
        }
        return '';
    }

    protected static function resolvePageControllerName($controllerName)
    {
        $pageType = 'other';
        if (!is_string($controllerName)) {
            return $pageType;
        }

        $controllerName = str_replace('XF:', '', $controllerName);
        $controllerName = strtolower($controllerName);

        $mbqSupportName = ['home','topic', 'forum', 'profile', 'message', 'online', 'search', 'login'];
        // XF:Thread, XF:Index, XF:Page, XF:Search, XF:WhatsNewPost, XF:Login, XF:Account, XF:Member, XF:ProfilePost, XF:Conversation
        $changeXFNames = [
            'thread' => 'topic',
            'index' => 'home',
            'account' => 'profile',
            'member' => 'profile',
            'Conversation' => 'message',
        ];

        if (isset($changeXFNames[$controllerName])) {
            $controllerName = $changeXFNames[$controllerName];
        }
        if (in_array($controllerName, $mbqSupportName)) {
            $pageType = $controllerName;
        }

        return $pageType;
    }

    protected static function getRequestValue($key)
    {
        $request = \XF::app()->request();
        if ($key == 'HTTP_USER_AGENT') {
            return $request->getUserAgent();
        }
        if (substr($key,0 ,4) == 'HTTP') {
            return $request->getServer($key);
        }
        return $request->get($key);
    }

    protected static function getReferrer()
    {
        return \XF::app()->request()->getReferrer();
    }

    protected static function inAppRequest()
    {
        return self::getRequestValue('HTTP_IN_APP');
    }

    public static function handle_forum_info($forum_info){
        $result = array();
        if (empty($forum_info)){
            return $result;
        }
        $infos = preg_split('/\s*?\n\s*?/', $forum_info);
        foreach ($infos as $info){
            $value = preg_split('/\s*:\s*/', $info, 2);
            $result[trim($value[0])] = isset($value[1]) ? $value[1] : '';
        }
        return $result;
    }

    /**
     * @param \XF\App $app
     * @return string
     */
    protected static function tt_get_board_url($app = null)
    {
        if (!$app) $app = \XF::app();
        $request = $app->request();
        $boardUrl = $request->getFullBasePath();

        if (!$boardUrl){
            $boardUrl = $app->container('homePageUrl');
        }

        return $boardUrl;
    }

}