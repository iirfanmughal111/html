<?php 

namespace Pad\Pub\Controller;

use XF\Mvc\Entity\Finder;
use XF\Pub\Controller\AbstractController;
use XF\Mvc\ParameterBag;

class Attendance extends AbstractController{
    public function actionIndex($params){
      // $today = date_default_timezone_set('Asia/Karachi');;
      // echo $today->format('Y-m-d');exit;
      //  return $this->error('Unfortunately the thing you are looking for could not be found.', 404);
        // throw $this->exception($this->error('An unexpected error occurred in page'));
      //  var_dump(\XF::$time);exit; 
      //  $finder = $this->Finder('XF:User')->where('user_id','<',4)->fetch();
      // var_dump(\XF::visitor()->user_id);
        $finder = $this->Finder('Pad:Attendance')->where('user_id',\XF::visitor()->user_id)->order('date','DESC')->with('User');
        // $finder = $this->Finder('Pad:Attendance')->with('User')->fetch();

        // var_dump($finder);

       $finderResult = $finder->fetch();
       $ResultTotal = $finder->total();
// var_dump($ResultTotal);exit;

        $page = $params->page;
        $perPage = 25;

       $finderResult =  $finder->limitByPage($page, $perPage)->fetch();


        $viewParams = [
          'attendance'=>$finderResult,
          'page'=>$page,
          'total'=>$ResultTotal,
          'perPage'=>$perPage 
        ];
        return $this->view('Pad:Note/Index','indexTemplate',$viewParams);
    }

    public function actionAdd(){
        // $userFinder = $this->finder('XF:User')->whereId(2);

        /** @var \XF\ENTITY\USER $user */
      

        // $user = $userFinder->fetchOne();
        // $user = $this->em()->create('XF:User');
        // $user->bulkSet([
        //     'email'=> 'email@example.com2',
        //     'username'=> 'username2',
            
        // ]);
        // $user->save();

        // $attendance = $this->em()->create('Pad:Attendance');




        // $attendance->bulkSet([

        //     'user_id'=> 1,
        //     'date'=> '1677624444',

        //     'in_time'=> '1677624444',

        //     'out_time'=> '1677624444',

        //     'comment'=> 'email@example.com2',

            
        // ]);


        // echo '<pre>';


        //  var_dump(strtotime('15:17'));exit;

        //$attendance->save();
        $attendance = $this->Finder('Pad:Attendance')->where('user_id',\XF::visitor()->user_id)->where('date','>=',strtotime(date('y-m-d',\XF::$time)))->fetchOne();
        if ($attendance){
          return $this->error('You cannot add attendance more than once.Edit your previous entry.');
        }
        // var_dump(strtotime(date('y-m-d',\XF::$time)));exit;

// var_dump($attendance);exit;
        return $this->view('Pad:Note','addNote');
    }

    public function actionSave(){

      $input = [];
      // $input['date'] = date(\XF::$time);
      // $input['user_id'] = \XF::visitor()->user_id;
        
      $input = $this->filter([
       
        'in-time' => 'str',
        'out-time' => 'str',
        'comment' => 'str',
    ]);
   if(strtotime($input['in-time']) >=  strtotime($input['out-time'])){
        return $this->error(\XF::phrase('Office_In_Time_can _not_be_greater_than_office_leaving_time.'));
        
      }

      $attendance = $this->em()->create('Pad:Attendance');
      $attendance->bulkSet([
          'user_id'=> \XF::visitor()->user_id,
          'date'=> date(\XF::$time),
          'in_time'=> strtotime($input['in-time']),
          'out_time'=> strtotime($input['out-time']),
          'comment'=> $input['comment'],
      ]);
   


      $attendance->save();

      return $this->redirect($this->buildLink('attendance'), 'Attendance Added Successfully.', 'permanent');
      //  return $this->message('Attendance Added Successfully');
     
      //   return $this->view('Pad:Note','addNote');
    }


    public function actionTest(){
        $finder = $this->Finder('XF:User')->where('username','LIKE','user');
        $finderResult = $finder->fetch();
        $finderCount = $finder->total();
        
        $userFinder =  $this->Finder('XF:User')->whereId(1);

        //    /** @var \XF\Entity\User $user */
       /*
   
        $user =  $userFinder->fetchOne();
        $user->email = 'softhouse8219@gmail.com';
        $user->save();
        $user->bulkSet([
            email->'email';
            username->'user',
        ]);
        */

        $params = ['users'=>$finderResult];
        return $this->view('Pad:Note/Test','test',$params);
    }

    public function actionEdit($params){
   

      if ($params->attendance_id){
        $attendance = $this->Finder('Pad:Attendance')->where('attendance_id',$params->attendance_id)->with('User')->fetchOne();
        
        if ($attendance->user_id != \XF::visitor()->user_id){
          return $this->error(\XF::phrase('att_notAllowed'));
        }
        
        if ($attendance->date < ( strtotime(date('y-m-d',\XF::$time)))){
          return $this->error( \XF::phrase('att_notAllowed'));
        }
        // $finder = $this->Finder('Pad:Attendance')->whereId($params->attendance_id);
        // $finderResult = $finder->fetch();
        // $finderCount = $finder->total();
        $params = ['attendance'=>$attendance];
        // var_dump($params);exit;

       
        return $this->view('Pad:Note','addNote',$params);

      }
    }

    public function actionUpdate(){
   

      $input = [];
      // $input['date'] = date(\XF::$time);
      // $input['user_id'] = \XF::visitor()->user_id;

      $input = $this->filter([
        'attendance_id' => 'str',
        'in-time' => 'str',
        'out-time' => 'str',
        'date' => 'str',
        'comment' => 'str',
    ]);

    if(strtotime($input['in-time']) >=  strtotime($input['out-time'])){
      return $this->error('In Time can not be greater than leaving time.');
      
    }

    $attendance = $this->Finder('Pad:Attendance')->whereId($input['attendance_id'])->fetchOne();

    if ($attendance->user_id!=\XF::visitor()->user_id){
      $this->error(\XF::phrase('att_notAllowed'));
    }
    
      $attendance->bulkSet([
          'user_id'=> \XF::visitor()->user_id,
          'date'=> strtotime($input['date']),
          'in_time'=> strtotime($input['in-time']),
          'out_time'=> strtotime($input['out-time']),
          'comment'=> $input['comment'],
      ]);
   

      $attendance->save();
      return $this->redirect($this->buildLink('attendance'), 'Attendance Updated Successfully.', 'permanent');

       
        return $this->view('Pad:Note','indexTemplate');

      }

      public function actionDelete( $params)
      {
          $replyExists = $this->assertDataExists($params->attendance_id);
          
          if ($replyExists->user_id!=\XF::visitor()->user_id){
            $this->error(\XF::phrase('att_notAllowed'));
          }
          /* @var \XF\ControllerPlugin\Delete $plugin */
          $plugin = $this->plugin('XF:Delete');
          return $plugin->actionDelete(
              $replyExists,
              $this->buildLink('attendance/delete', $replyExists),
              null,
              $this->buildLink('attendance'),
              "Day: ".date('Y-m-d',$replyExists->date)."  From 
              :". date('h: m a',$replyExists->in_time)." To:". date('h: m a',$replyExists->out_time)." - ".(\XF::visitor()->username),
          );
      }

      /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \CRUD\XF\Entity\Crud
     */
    protected function assertDataExists($id, array $extraWith = [], $phraseKey = null)
    {
        return $this->assertRecordExists('Pad:Attendance', $id, $extraWith, $phraseKey);
    }
    
}