<?php 

namespace Pad\Admin\Controller;

// use XF\Mvc\Entity\Finder;
use XF\Admin\Controller\AbstractController;

class Attendance extends AbstractController{
    public function actionIndex($params){
      
      $conditions = [
        ['user_group_id', \XF::options()->attendanceEmployeeGroup],
        ['secondary_group_ids', 'LIKE', '%' . \XF::options()->attendanceEmployeeGroup . '%'],
    ];

    $users = \XF::finder('XF:User')->whereOr($conditions)->order('user_id','DESC')->fetch();

    $input_user_id = $this->filter(['employee_user_id' => 'int']);
    

    
    if($input_user_id && $this->isPost()){
      $finder = $this->Finder('Pad:Attendance')->where('user_id',$input_user_id)->order('date','DESC')->with('User');

    }
    
else{
  $finder = $this->Finder('Pad:Attendance')->order('date','DESC')->with('User');

}
      
       $finderResult = $finder->fetch();
       $ResultTotal = $finder->total();

        $page = $params->page;
        $perPage = 30;
       
       $finderResult =  $finder->limitByPage($page, $perPage)->fetch();

        $viewParams = [
          'attendance'=>$finderResult,
          'total'=>$ResultTotal,
          'perPage'=>$perPage ,
          'page'=>$page,
          'users'=>$users,
          'user_id'=>$input_user_id

        ];
        
        return $this->view('Pad:Note/Index','IndexAdminAttendance',$viewParams);
    }


  

    public function actionView($params){
   

      if ($params->attendance_id){
        $attendance = $this->Finder('Pad:Attendance')->where('attendance_id',$params->attendance_id)->with('User')->fetchOne();

        $params = ['attendance'=>$attendance];
       
        return $this->view('Pad:Note','viewAttendance',$params);

      }
    }

   
    
}