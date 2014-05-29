<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
    ilogin.php
	登录入口... 
	2014.4.15 11.38
*/
class Resources extends CI_Controller{
  public function __construct(){
    parent::__construct();
	$this->load->library('session');
	$this->load->model('user');
	// 判断登录状态...非登录踢出
	if($this->user->isAdmin()==4){
	  header('Location:'.$this->config->base_url().'index.php/system/ilogin');
	  exit;
	}
	$this->load->database();
	// 载入resource model... ...
	$this->load->model('resource');
  }
  // 资源列表... ..
  public function index(){
    
  }
}