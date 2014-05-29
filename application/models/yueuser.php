<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
/*
   文件名称：yueUser.php 模型。。。
   作用：资源的管理...
   注意：本文件按照REST模式书写，不涉及任何登录状态等...
         所以网站后台请在控制器中书写登陆等管理权限...
   时间：2014.4.13 12.47
*/
class Yueuser extends CI_Model{
  private $userTable='user';
  private $yueUserTable='yue_user';
  public function __construct(){
    parent::__construct();
	// 加载 user 模型..
	$this->load->model('user');
  }
  
  // yy注册
  public function register(){
    $result=$this->user->add();
	// 如果基本资料表添加成功...
	if($result['code']==8){
	  $token=md5(time().$result['user']['uid']);
	  $expire=7776000;
	  $destDir=ceil($result['user']['uid']/3000);
	  // if dir do not exists..then create it..
	  if(!is_dir('static'.DIRECTORY_SEPARATOR.'avatar'.DIRECTORY_SEPARATOR.$destDir)){
	    mkdir('static'.DIRECTORY_SEPARATOR.'avatar'.DIRECTORY_SEPARATOR.$destDir);
	  }
	  $sql="insert into $this->yueUserTable(user_id,nickname,token,expire) values({$result['user']['uid']},'{$result['user']['username']}','{$token}',{$expire})";
	  if($this->db->query($sql)){
	    return array(  // 返回给客户端信息.. 
	      'code'=>8,
		  'msg'=>'Create user:true',
		  'user'=>array(
		    'user_id'=>$result['user']['uid'],
		    'user_name'=>$result['user']['username'],
		    'email'=>$result['user']['email'],
		    'nickname'=>$result['user']['username'],
			//                                  $avatarPath='static/avatar/'.$destDir.'/';
		    'avatar'=>$this->config->base_url().'static/avatar/df.png',
		    'token'=>$token,
		    'money'=>0,
		    'score'=>0,
		    'create_time'=>$result['user']['addtime'],
		    'update_time'=>$result['user']['addtime'],
		    'expire'=>$expire
		  )
	    );
	  }else{
	    $debug=$this->db->_error_message();
	    $sql="delete from $this->userTable where uid=".$result['user']['uid'];
		$this->db->query($sql);
	    return array(
		  'code'=>4,
		  'msg'=>'Create user:false',
		  'debug'=>'Create user:'.$debug
		);
	  }
	}else if($result['code']==4){
	  return array(
	    'code'=>4,
		'msg'=>'Create user:false',
		'debug'=>$result['debug']
	  );
	}else if($result['code']==6){
	  return array(
	    'code'=>6,
		'msg'=>'Create user:user has exists',
		'debug'=>'Create user:user has exists'
	  );
	}
  }
  // yy登录
  public function login(){
    $result=$this->user->login();
	if($result['code']==8){  //登录成功.. 
	  $sql="select token,nickname,avatar,score,money,num_collection,num_resource,expire from $this->yueUserTable where user_id=".$result['user']['user_id'];
	  $strYueUser=$this->db->query($sql);
	  $strYueUser=$strYueUser->result_array();
	  if($strYueUser[0]['avatar']==0){
	    $avatar=$this->config->base_url().'static/avatar/df.png';
	  }else{
	    // process user avatar ...
	    $destDir=ceil($result['user']['user_id']/3000);
	    $avatarPath='static/avatar/'.$destDir.'/';
	    $avatar=$this->config->base_url().$avatarPath.$result['user']['user_id'].'.jpg';
	  }
	  return array(
	    'code'=>8,
		'msg'=>'Login:true',
		'user'=>array(
	      'user_id'=>$result['user']['user_id'],
		  'user_name'=>$result['user']['user_name'],
		  'email'=>$result['user']['email'],
		  'create_time'=>$result['user']['create_time'],
		  'update_time'=>$result['user']['update_time'],
		  'token'=>$strYueUser[0]['token'],
		  'nickname'=>$strYueUser[0]['nickname'],
		  'avatar'=>$avatar,
	      'score'=>$strYueUser[0]['score'],
		  'money'=>$strYueUser[0]['money'],
		  'expire'=>$strYueUser[0]['expire']
		)
	  );
	}else if($result['code']==4){  // 登录失败.
	  return array(
	    'code'=>4,
		'msg'=>'Login:false',
		'debug'=>$result['debug']
	  );
	}else if($result['code']==6){
	  return array(
	    'code'=>6,
		'msg'=>'Login:false',
		'debug'=>$result['debug']
	  );
	}
  }
  // 验证..
  public function isLogin($user_id,$token){
    $sql="select token from $this->yueUserTable where user_id={$user_id}";
	$result=$this->db->query($sql);
	$result=$result->result_array();
	if($result[0]['token']!=$token){
	  return array(
	    'code'=>4,
		'msg'=>'Auth:false'
	  );
	}
  }
  // 编辑..
  public function edit(){
    //$this->load->library('form_validation');
    $user_id=$this->input->post('user_id');
    $token=$this->input->post('token');
	$auth=$this->isLogin($user_id,$token);
	if($auth['code']==4){
	  return array(
	    'code'=>4,
		'msg'=>'Auth:false',
		'debug'=>'Auth:false,token wrong or inavilable'
	  );
	}
	$action=$this->input->post('action');
	// start mysql transaction ...
	$this->db->trans_begin();
	// 修改普通资料..
	if($action=='nopassword'){
	  $email=$this->input->post('email');
	  $nickname=$this->input->post('nickname');
	  // 接受 base64 过的图片的base64代码 ..
	  $avatar=$this->input->post('avatar_base64');
	  $sql="update $this->userTable set email='{$email}' where uid={$user_id}";
	  $this->db->query($sql);
	  $sql="update $this->yueUserTable set nickname='{$nickname}' where user_id={$user_id}";
	  $this->db->query($sql);
	  // 根据 user_id 计算文件夹 ... DIRECTORY_SEPARATOR
	  $destDir=ceil($user_id/3000);
	  // if dir do not exists..then create it..
	  if(!is_dir('.'.DIRECTORY_SEPARATOR.'static'.DIRECTORY_SEPARATOR.'avatar'.DIRECTORY_SEPARATOR.$destDir)){
	    mkdir('.'.DIRECTORY_SEPARATOR.'static'.DIRECTORY_SEPARATOR.'avatar'.DIRECTORY_SEPARATOR.$destDir);
	  }
	  $fsPath='.'.DIRECTORY_SEPARATOR.'static'.DIRECTORY_SEPARATOR.'avatar'.DIRECTORY_SEPARATOR.$destDir.DIRECTORY_SEPARATOR;
	  // write JPEG type avatar!
	  file_put_contents($fsPath.$user_id.'.jpg',base64_decode($avatar));
	  $sql="update $this->yueUserTable set avatar={$destDir} where user_id={$user_id}";
	  $this->db->query($sql);
	}else if($action=='password'){  // 修改密码.. 
	  $password=$this->input->post('password');
	  $sql="select salt from $this->userTable where uid={$user_id}";
	  $strUser=$this->db->query($sql);
	  $strUser=$strUser->result_array();
	  $sql="update $this->userTable set password='".md5($password.$strUser[0]['salt'])."' where uid={$user_id}";
	  $this->db->query($sql);
	}else if($action=='avatar'){
	  // 接受 base64 过的图片的base64代码 ..
	  $avatar=$this->input->post('avatar');
	  // 根据 user_id 计算文件夹 ... DIRECTORY_SEPARATOR
	  $destDir=ceil($user_id/3000);
	  // if dir do not exists..then create it..
	  if(!is_dir('static'.DIRECTORY_SEPARATOR.'avatar'.DIRECTORY_SEPARATOR.$destDir)){
	    mkdir('static'.DIRECTORY_SEPARATOR.'avatar'.DIRECTORY_SEPARATOR.$destDir);
	  }
	  $fsPath='static'.DIRECTORY_SEPARATOR.'avatar'.DIRECTORY_SEPARATOR.$destDir.DIRECTORY_SEPARATOR;
	  // write JPEG type avatar!
	  file_put_contents($fsPath.$user_id.'.jpg',base64_decode($avatar));
	  $sql="update $this->yueUserTable set avatar={$destDir} where user_id={$user_id}";
	  $this->db->query($sql);
	}
    if($this->db->trans_status()===false){
	  $debug=$this->db->_error_message();
	  $this->db->trans_rollback();
	  return array(
		'code'=>4,
		'msg'=>'Edit user:false',
		'debug'=>'Edit user:'.$debug
	  );
	}else{
	  $this->db->trans_commit();
	  $sql="select 
		      u.username,u.email,u.addtime,u.uptime,
		      y.token,y.nickname,y.avatar,y.score,y.money,y.num_resource,y.num_collection,y.expire
			from 
			  $this->userTable as u
			left join 
			  $this->yueUserTable as y
			on 
			  u.uid=y.user_id
			where
			  uid={$user_id}";
      $strUser=$this->db->query($sql);
	  $strUser=$strUser->result_array();
	  if($strUser[0]['avatar']==0){
	    $avatar=$this->config->base_url().'static/avatar/df.png';
	  }else{
	    $destDir=ceil($user_id/3000);
	    $avatarPath='static/avatar/'.$destDir.'/';
	    $avatar=$this->config->base_url().$avatarPath.$user_id.'.jpg';
	  }
	  return array(
	    'code'=>8,
		'msg'=>'Edit user:true',
		'user'=>array(
	      'user_id'=>$user_id,
		  'user_name'=>$strUser[0]['username'],
		  'nickname'=>$strUser[0]['nickname'],
		  'avatar'=>$avatar,
	      'email'=>$strUser[0]['email'],
		  'create_time'=>$strUser[0]['addtime'],
		  'update_time'=>$strUser[0]['uptime'],
	      'score'=>$strUser[0]['score'],
		  'money'=>$strUser[0]['money'],
		  'token'=>$strUser[0]['token'],
		  'expire'=>$strUser[0]['expire']
		)
	  );
	}
  }
}