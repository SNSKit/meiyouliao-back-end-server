<?php
/*
    file name: topic.php
    useage: get post del edit topic 
    time: 2014.4.27 21:10
*/
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Test extends CI_Controller{
  public function index(){
    echo '<a href="itms-services://?action=download-manifest&url=https://dl.dropboxusercontent.com/s/vje47jgatfcmcsu/Meiyouliao.plist">点击下载</a>';
  }
  
  public function getrms(){
    $data['siteUrl']=$this->config->base_url();
    $this->load->view('test/getrms',$data);
  }
  
  public function addrm(){
    $data['siteUrl']=$this->config->base_url();
    $this->load->view('test/addrm',$data);  
  }
  
  public function login(){
    $data['siteUrl']=$this->config->base_url();
    $this->load->view('test/login',$data);
  }  
  
  public function t(){
    echo base64_encode(file_get_contents('1.png'));
  }
  
  public function s(){
    $this->load->database();
    $sql="select * from yue_user where user_id=1";
	$r=$this->db->query($sql);
	$r=$r->result_array();
	specho($r);
  }
  public function edit(){
    $data['siteUrl']=$this->config->base_url();
    $this->load->view('test/edit',$data);
  }
  
  public function sc(){
    $rs=file_put_contents('.'.DIRECTORY_SEPARATOR.'static'.DIRECTORY_SEPARATOR.'avatar'.DIRECTORY_SEPARATOR.'1'.DIRECTORY_SEPARATOR.'sd1.jpg',base64_decode('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'));
	print_r($rs);
  }
  
  // 添加用户..
  public function all(){
    $data['siteUrl']=$this->config->base_url();
    $this->load->view('test/all',$data);
  }
  
}