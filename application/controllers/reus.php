<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
    rest api:用户管理
	2014.5.7 9.39
*/
class Reus extends CI_Controller{
  // 自定义指定用户数据表以及管理员数据表
  public function __construct(){
    parent::__construct();
	$this->load->database();
	// load yueUser model which includes User model...
	$this->load->model('yueUser');
	$this->load->library('session');
  }
  
  // register user ...
  public function register(){
    $result=$this->yueUser->register();
	if($result['code']==8){
	  echo json_encode(array('user'=>$result['user'])); 
	}else if($result['code']==4){
	  echo json_encode(
	    array(
	      'code'=>4,
		  'msg'=>'注册失败',
		  'debug'=>$result['debug']
		)
	  );
	}else if($result['code']==6){
	  echo json_encode(array(
	    'code'=>6,
		'msg'=>'该用户名或邮箱已被占用',
		'debug'=>'Create user:user has exists'
	  ));
	}
  }
  
  // login action ..
  public function login(){
    $result=$this->yueUser->login();
	if($result['code']==8){
	  echo json_encode(array(
	    'user'=>$result['user']
	  ));
	}else if($result['code']==4){
	  echo json_encode(array(
	    'code'=>4,
		'msg'=>'Login:false',
		'debug'=>$result['debug']
	  ));
	}else if($result['code']==6){
	  echo json_encode(array(
	    'code'=>6,
		'msg'=>'用户名或密码不正确',
		'debug'=>$result['debug']
	  ));
	}
  }
  
  // edit user information ..
  public function edit(){
    $result=$this->yueUser->edit();
	if($result['code']==8){
	  echo json_encode(array('user'=>$result['user']));
	}else if($result['code']==4){
	  echo json_encode(array(
	    'code'=>4,
		'msg'=>'Edit user:false',
		'debug'=>$result['debug']
	  ));
	}
  }
  
}