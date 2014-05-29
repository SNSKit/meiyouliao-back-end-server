<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
    ilogin.php
	登录入口... 
	2014.4.15 11.38
*/
class Ilogin extends CI_Controller{
  public function __construct(){
    parent::__construct();
    $this->load->library('form_validation');
	$this->load->database();
	$this->load->library('session');
  }
  public function index(){
    $data['siteUrl']=$this->config->base_url();
    $this->load->view('system/login',$data);
  }
  public function login(){
    $this->load->model('user');
	$result=$this->user->login();
	if($result['code']==4){
	  echo $result['msg'];
	}else if($result['code']==6){
	  echo $result['msg'];
	}else if($result['code']==8){
	  // 如果登录成功，且是管理员..后台，走起
	  if(isset($result['isAdmin']) && $result['isAdmin']==1){
	    header('Location:'.$this->config->base_url().'index.php/system/home');
	    exit;
	  }else{
	    echo 'Admin login:false,you are not administrator...';
	  } 
	}
  }
}