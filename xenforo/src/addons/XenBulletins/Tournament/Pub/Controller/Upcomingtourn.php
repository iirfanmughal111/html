<?php

namespace XenBulletins\Tournament\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;

class Upcomingtourn extends AbstractController {

    public function actionIndex() {

        $ctime = \XF::$time;
        $actual_link = "$_SERVER[HTTP_HOST]";



        $visitor = \XF::visitor();

        $trounamentUsers = [];

        $tournamentFinder = $this->Finder('XenBulletins\Tournament:Tournament')
                ->where('tourn_enddate', '>', $ctime)->where('tourn_domain', $actual_link)
                ->order('tourn_startdate', 'asc');

        $upcomingTournament = $tournamentFinder->fetch();
        $total = $upcomingTournament->count();
        $upcomingTournamentArray = ($total) ? $upcomingTournament->toArray() : [];
        $firstTournament = [];
        if ($upcomingTournamentArray) {
            $firstTournament = reset($upcomingTournamentArray);

            $users = $this->Finder('XenBulletins\Tournament:Register')->with('User')->where('tourn_id', $firstTournament->tourn_id)->fetch();




            $trounamentUsers[$firstTournament->tourn_id]['users'] = $users;
            $trounamentUsers[$firstTournament->tourn_id]['is_register'] = $this->allowRegistration($users, $visitor);



            $key = key($upcomingTournamentArray);
            unset($upcomingTournamentArray[$key]);
        }
        $user = \XF::visitor();


        foreach ($upcomingTournamentArray as $tourn) {

            $users = $this->Finder('XenBulletins\Tournament:Register')->with('User')->where('tourn_id', $tourn->tourn_id)->fetch();


            $trounamentUsers[$tourn->tourn_id]['users'] = $users;
            $trounamentUsers[$tourn->tourn_id]['is_register'] = $this->allowRegistration($users, $visitor);


        }


        $viewParams = [
            'tournaments' => $upcomingTournamentArray,
            'total' => $total,
            'firstTournament' => $firstTournament,
            'ctime' => $ctime,
            'trounamentUsers' => $trounamentUsers,
        ];

        return $this->view('XenBulletins\Tournament:Upcomingtourn', 'upcoming_tournament', $viewParams);
    }

    private function allowRegistration($users, $visitor) {
        if ($users) {
            
            foreach ($users as $user) {
                if ($user->user_id == $visitor->user_id) {
                    return 0;
                }
            }
        }

        return 1;
    }

    
    public function actionDetails(ParameterBag $params)
    {
        $visitor=\XF::visitor();
        
        $user = $this->Finder('XenBulletins\Tournament:Register')->with('User')->where('tourn_id', $params->tourn_id)->where('user_id',$visitor->user_id)->fetchOne(); 
        
        $tournament=$this->em()->find('XenBulletins\Tournament:Tournament',$params->tourn_id);
        
        
        
        $viewParams = [
            'user' => ($user)?$user->user_id:0,
            'tournament'=>$tournament
        ];


        return $this->view('XenBulletins\Tournament:Upcomingtourn', 'tournament_details', $viewParams); 
        
        
        
    }






    public function actionregistertour(ParameterBag $params) {



        $viewParams = [
            'tournament' => $params,
        ];


        return $this->view('XenBulletins\Tournament:Upcomingtourn', 'register_tournament', $viewParams);
    }

    public function actionTournamentregister(ParameterBag $params) {


        $this->assertPostOnly();
        $ctime = \XF::$time;
        $user = \XF::visitor();
        \XF::db()->insert('xf_tournament_register', [
            'user_id' => $user->user_id,
            'tourn_id' => $params->tourn_id,
            'current_time' => $ctime,
        ]);


        return $this->redirect($this->buildLink('uptourn')."#tournament-".$params->tourn_id);
    }

    public function actionpopusers(ParameterBag $params) {

        $users = $this->Finder('XenBulletins\Tournament:Register')->with('User')->where('tourn_id', $params->tourn_id)->fetch();


        $viewParams = [
            'users' => $users,
        ];


        return $this->view('XenBulletins\Tournament:Upcomingtourn', 'popup_users', $viewParams);
    }

}
