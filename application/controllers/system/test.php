<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
    ilogin.php
	登录入口... 
	2014.4.15 11.38
*/
class Test extends CI_Controller{
  public function __construct(){
    parent::__construct();
	  $this->load->library('session');
	  $this->load->database();
	  $this->load->model('yueUser');
  }
  
  public function b64(){
    $this->yueUser->test();
  }
  public function b64Do(){
    
  }
  
  public function add(){
    $this->load->view('test/add');
  }
  public function addDo(){
    $result=$this->yueUser->register();
	specho($result);
  }
  public function login(){
    $this->load->view('test/login');
  }
  public function loginDo(){
    $result=$this->yueUser->login();
	specho($result);
  }
  public function edit(){
    $this->load->view('test/edit');
  }
  public function editDo(){
    $this->load->model('yueUser');
	specho($this->yueUser->edit());
  }
}