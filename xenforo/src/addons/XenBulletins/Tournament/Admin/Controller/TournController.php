<?php

namespace XenBulletins\Tournament\Admin\Controller;

use XF\Mvc\ParameterBag;
use XenBulletins\Tournament\Entity\Tournament;
use XF\Admin\Controller\AbstractController;

class TournController extends AbstractController {

    public function actionIndex() {

        $page = $this->filterPage();

        $perPage = 50;
        $actual_link = "$_SERVER[HTTP_HOST]";
        $records = $this->Finder('XenBulletins\Tournament:Tournament')->where('tourn_domain', $actual_link);

        $total = $records->total();

        $this->assertValidPage($page, $perPage, $total, 'tournament');

        $records->limitByPage($page, $perPage);

        $viewParams = [
            'records' => $records->fetch(),
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total
        ];

        return $this->view('XenBulletins\Tournament:TournController', 'add_tournament', $viewParams);
    }

    public function actionNewEntry() {

        $viewParams = [];

        return $this->view('XenBulletins\Tournament:view', 'tournament_fields', $viewParams);
    }

    protected function checkValidations($thread_ids, $column_ids) {
        if (!count($thread_ids)) {
            throw new \XF\PrintableException(\xf::phrase('empty_thread_ids_message'));
        }
        if (!count($column_ids)) {
            throw new \XF\PrintableException(\xf::phrase('empty_coulumn_ids_message'));
        }
    }

    protected function tagSaveProcess(Tournament $record) {
        $form = $this->formAction();

        $input = $this->filter([
            'tourn_title' => 'STR',
            'tourn_startdate' => 'STR',
            'tourn_enddate' => 'STR',
            'tourn_starttime' => 'STR',
            'tourn_endtime' => 'STR',
            'tourn_main_price' => 'STR',
            'tourn_desc' => 'STR',
            'tourn_prizes' => 'ARRAY',
        ]);

        $timezone = "Europe/London";



        $tz = new \DateTimeZone($timezone);

        $dateTime = new \DateTime("@" . strtotime($input['tourn_startdate']), $tz);

        list($hours, $minutes) = explode(':', $input['tourn_starttime']);


        $dateTime->setTime($hours, $minutes);
        $stimestamp = $dateTime->getTimestamp();


        $tz = new \DateTimeZone($timezone);

        $dateTime = new \DateTime("@" . strtotime($input['tourn_enddate']), $tz);

        list($hours, $minutes) = explode(':', $input['tourn_endtime']);


        $dateTime->setTime($hours, $minutes);
        $etimestamp = $dateTime->getTimestamp();






        $record->tourn_title = $input['tourn_title'];
        $record->tourn_startdate = $stimestamp;
        $record->tourn_enddate = $etimestamp;
        //  $record->tourn_starttime  = strtotime($input['tourn_starttime']);
        // $record->tourn_endtime  = strtotime($input['tourn_endtime']);
        $record->tourn_main_price = $input['tourn_main_price'];
        $record->tourn_desc = $input['tourn_desc'];

        $recordName = $this->actionFind($record->tourn_title);

        if ($recordName == '') {
            $actual_link = "$_SERVER[HTTP_HOST]";
            $record->tourn_domain = $actual_link;

            $tourn_partos = $this->filter('tourn_parto', 'array-str');
            $tourn_partos = array_filter(array_values(array_unique($tourn_partos)));

            $tourn_partts = $this->filter('tourn_partt', 'array-str');
            $tourn_partts = array_filter(array_values(array_unique($tourn_partts)));

            $fieldChoicesCombined = [];

            foreach ($tourn_partos AS $key => $choice) {
                if (isset($tourn_partts[$key]) && $tourn_partts[$key] !== '') {
                    $fieldChoicesCombined[$choice] = $tourn_partts[$key];
                }
            }

            $record->tourn_prizes = $fieldChoicesCombined;
            $record->save();

            return $form;
        } else {
            if ($record->tourn_id == $recordName->tourn_id) {
                $actual_link = "$_SERVER[HTTP_HOST]";
                $record->tourn_domain = $actual_link;

                $tourn_partos = $this->filter('tourn_parto', 'array-str');
                $tourn_partos = array_filter(array_values(array_unique($tourn_partos)));

                $tourn_partts = $this->filter('tourn_partt', 'array-str');
                $tourn_partts = array_filter(array_values(array_unique($tourn_partts)));

                $fieldChoicesCombined = [];

                foreach ($tourn_partos AS $key => $choice) {
                    if (isset($tourn_partts[$key]) && $tourn_partts[$key] !== '') {
                        $fieldChoicesCombined[$choice] = $tourn_partts[$key];
                    }
                }

                $record->tourn_prizes = $fieldChoicesCombined;
                $record->save();
                return $form;
            } else {
                $phraseKey = $recordName->tourn_title . "'s Record already exists.";
                throw $this->exception(
                        $this->notFound(\XF::phrase($phraseKey))
                );
            }
        }
    }

    public function actionSave(ParameterBag $params) {
        $this->assertPostOnly();

        if ($params->tourn_id) {
            $record = $this->assertFunctionExists($params->tourn_id);
        } else {
            $record = $this->em()->create('XenBulletins\Tournament:Tournament');
        }

        $this->tagSaveProcess($record)->run();

        if ($this->isPost()) {

            $uploads['icon'] = $this->request->getFile('icon', false, false);
            $uploads['header'] = $this->request->getFile('header', false, false);

            if ($uploads['header']) {
                $uploadService = $this->service('XenBulletins\Tournament:Upload', $record);

                if (!$uploadService->setImageFromUpload($uploads ['header'])) {
                    return $this->error($uploadService->getError());
                }

                if (!$uploadService->uploadTournamentImage($uploads['header'], 'header')) {
                    return $this->error(\XF::phrase('new_image_could_not_be_processed'));
                }
            }
            if ($uploads ['icon']) {
                $uploadService = $this->service('XenBulletins\Tournament:Upload', $record);

                if (!$uploadService->setImageFromUpload($uploads ['icon'])) {
                    return $this->error($uploadService->getError());
                }

                if (!$uploadService->uploadTournamentImage($uploads ['icon'], 'icon')) {
                    return $this->error(\XF::phrase('new_image_could_not_be_processed'));
                }
            }
        }

        return $this->redirect($this->buildLink('tourn'));
    }

    public function actionDelete(ParameterBag $params) {

        $record = $this->assertFunctionExists($params->tourn_id);

        $plugin = $this->plugin('XF:Delete');

        return $plugin->actionDelete(
                        $record, $this->buildLink('tourn/delete', $record), $this->buildLink('tourn/edit', $record), $this->buildLink('tourn'), $record->tourn_title
        );
    }

    protected function assertFunctionExists($id, $with = null, $phraseKey = null) {

        return $this->assertRecordExists('XenBulletins\Tournament:Tournament', $id, $with, $phraseKey);
    }

    public function actionFind($title) {
        $title = \XF::finder('XenBulletins\Tournament:Tournament')->where('tourn_title', $title)->fetchOne();
        return $title;
    }

    public function actionEdit(ParameterBag $params) {

        $record = $this->assertFunctionExists($params->tourn_id);
        return $this->functionAddEdit($record);
    }

    public function functionAddEdit($record) {

        $viewParams = [
            'record' => $record
        ];
        return $this->view('practive:View', 'tournament_fields', $viewParams);
    }

}
