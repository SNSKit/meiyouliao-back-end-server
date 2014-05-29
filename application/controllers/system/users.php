<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
    users.php
	用户管理控制器... 
	2014.4.15 11.38
*/
class Users extends CI_Controller{
  public function __construct(){
    parent::__construct();
	  $this->load->library('session');
	  $this->load->model('user');
	  if($this->user->isAdmin()==4){
	    header('Location:'.$this->config->base_url().'index.php/system/ilogin');
	    exit;
	  }
	  $this->load->database();
  }
  // 用户列表
  public function index($page=1){
    // 读取用户... ...
	$siteUrl=$this->config->base_url().'index.php/system/users/index';
	$arrUsers=$this->user->allUser($siteUrl,$page,4);
    // 聚合数据
    $data['siteUrl']=$this->config->base_url();
	$data['page']=$arrUsers['pages'];
	$data['arrUsers']=$arrUsers['arrUsers'];
    $this->load->view('system/allUsers',$data);
  }
  // 添加用户...
  public function add(){
    $data['siteUrl']=$this->config->base_url();
    $this->load->view('system/addUser',$data);
  }
  public function addDo(){
    $result=$this->user->add();
	  if($result['code']==8){
	    header('Location:'.$this->config->base_url().'index.php/system/users/editUser/'.$result['user']['uid']);exit;
	  }else if($result['code']==4){
	    echo '添加失败';
	  }
  }
  // 编辑用户...
  public function editUser($user){
	  $strUser=$this->user->getUser($user);
	  $data['username']=$strUser[0]['username'];
	  $data['uid']=$strUser[0]['uid'];
	  $data['email']=$strUser[0]['email'];
	  $data['isAdmin']=$strUser[0]['isAdmin'];
	  $data['siteUrl']=$this->config->base_url();
	  $this->load->view('system/editUser',$data);
  }
  public function editDo(){
    $result=$this->user->edit();
	if($result['code']==8){
	  header("Location:".$this->config->base_url().'index.php/system/users/editUser/'.$result['user']['uid']);exit;
	}else{
	  echo '编辑失败';
	}
  }
  // 查询用户
  public function find(){
    $data['siteUrl']=$this->config->base_url();
    $this->load->view('system/findUser',$data);
  }
  public function findDo(){
    $strUser=$this->user->getUser();
	  header("Location:".$this->config->base_url().'index.php/system/users/editUser/'.$strUser[0]['uid']);
	  exit;
  }
}