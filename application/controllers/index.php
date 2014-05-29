<?php
/*
    file name: index.php
    useage: the website homepage ...
    time: 2014.5.14 21.27
*/
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Index extends CI_Controller{
  public function __construct(){
    parent::__construct();
  }
  public function index(){
    $data['siteUrl']=$this->config->base_url();
    $this->load->view('web/index',$data);
  }
}