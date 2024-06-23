<?php

namespace FS\BunnyIntegration\XF\Service;

use XF\Mvc\FormAction;

class BunnyServ extends \XF\Service\AbstractService
{
    protected $bunnyLibraryId;
    protected $bunnyVideoId;
    protected $bunnyAccessKey;

    public function createBunnyVideo($videoTitle)
    {
        $this->bunnyLibraryId = intval(\XF::options()->fs_bi_libraryId);
        $this->bunnyAccessKey = \XF::options()->fs_bi_accessKey;

        $curl = curl_init();

        $data = json_encode(array(
            "title" => $videoTitle,
        ));

        curl_setopt($curl, CURLOPT_URL, "https://video.bunnycdn.com/library/" . $this->bunnyLibraryId . "/videos");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'AccessKey: ' . $this->bunnyAccessKey,
            'Accept: application/json',
            'Content-Type: application/json',
        ]);

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $server_output = curl_exec($curl);

        $resCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $this->CheckRequestError($resCode);

        curl_close($curl);

        $createVideo = json_decode($server_output, true);

        $this->bunnyVideoId = $createVideo["guid"];

        return $createVideo;
    }

    public function uploadBunnyVideo($binaryVideo, $videoId)
    {
        $this->bunnyLibraryId = intval(\XF::options()->fs_bi_libraryId);
        $this->bunnyAccessKey = \XF::options()->fs_bi_accessKey;

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, "https://video.bunnycdn.com/library/" . $this->bunnyLibraryId . "/videos/" . $videoId);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'AccessKey: ' . $this->bunnyAccessKey,
            'Content-Type: application/octet-stream',
        ]);

        curl_setopt($curl, CURLOPT_POSTFIELDS, $binaryVideo);

        $server_output = curl_exec($curl);

        $resCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $this->CheckRequestError($resCode);

        curl_close($curl);

        $uploadVideo = json_decode($server_output, true);

        return $uploadVideo;
    }


    public function getBunnyVideo($libId, $videoId)
    {
        $this->bunnyAccessKey = \XF::options()->fs_bi_accessKey;

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, "https://video.bunnycdn.com/library/" . $libId . "/videos/" . $videoId);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'AccessKey: ' . $this->bunnyAccessKey,
        ]);

        $server_output = curl_exec($curl);

        $resCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $this->CheckRequestError($resCode);

        curl_close($curl);

        $getVideoRes = json_decode($server_output, true);

        return $getVideoRes["encodeProgress"] >= 50 ? true : false;
    }

    public function delelteBunnyVideo($libraryId, $videoId)
    {
        $this->bunnyAccessKey = \XF::options()->fs_bi_accessKey;

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, "https://video.bunnycdn.com/library/" . $libraryId . "/videos/" . $videoId);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'AccessKey: ' . $this->bunnyAccessKey,
        ]);

        $server_output = curl_exec($curl);

        $resCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $this->CheckRequestError($resCode);

        curl_close($curl);

        $deleteVideo = json_decode($server_output, true);

        return $deleteVideo;
    }

    protected function CheckRequestError($statusCode)
    {
        if ($statusCode == 404) {
            throw new \XF\PrintableException(\XF::phrase('fs_bunny_request_not_found'));
        } elseif ($statusCode == 401) {
            throw new \XF\PrintableException(\XF::phrase('fs_bunny_request_unauthorized'));
        } elseif ($statusCode == 415) {
            throw new \XF\PrintableException(\XF::phrase('fs_bunny_request_unsported_media'));
        } elseif ($statusCode == 400) {
            throw new \XF\PrintableException(\XF::phrase('fs_bunny_request_empty_body'));
        } elseif ($statusCode == 405) {
            throw new \XF\PrintableException(\XF::phrase('fs_bunny_request_method_not_allowed'));
        } elseif ($statusCode == 500) {
            throw new \XF\PrintableException(\XF::phrase('fs_bunny_request_server_error'));
        } elseif ($statusCode != 200) {
            throw new \XF\PrintableException(\XF::phrase('fs_bunny_request_not_success'));
        }
    }
}
