<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
    ilogin.php
	登录入口... 
	2014.4.15 11.38
*/
class Home extends CI_Controller{
  public function __construct(){
    parent::__construct();
	$this->load->library('session');
	$this->load->model('user');
	if($this->user->isAdmin()==4){
	  header('Location:'.$this->config->base_url().'index.php/system/ilogin');
	  exit;
	}
  }
  // 后台管理登录首页 ... ...
  public function index(){
    $data['siteUrl']=$this->config->base_url();
	$data['username']=$this->session->userdata('username');
	$data['email']=$this->session->userdata('email');
	$data['ip']=$this->input->ip_address();
    $this->load->view('system/home',$data);
  }
}