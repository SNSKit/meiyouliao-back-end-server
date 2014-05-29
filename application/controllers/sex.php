<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
    rest api:囧图...
	2014.5.7 9.39
*/
class Sex extends CI_Controller{
  public function __construct(){
    parent::__construct();
	$this->load->database();
	// load yueUser model which includes User model...
	$this->load->model('yueSex');
  }
  
  // add topic
  public function add(){
    $result=$this->yueSex->addTopic();
	if($result['code']==8){
	  echo json_encode(array('sex'=>$result['topic']));
	}else if($result['code']==4){
	  echo json_encode(array(
	    'code'=>4,
		'msg'=>'发表段子失败',
		'debug'=>$result['debug']
	  ));
	}
  }
  
  public function report(){
    echo json_encode(array(
	  'result'=>'true'
	));
  }
  
  // get remarks ..
  public function getRemark(){
    $result=$this->yueSex->getRemark();
	if($result['code']==8){
	  echo json_encode(array('remarks'=>$result['remarks']));
	}else if($result['code']==4){
	  echo json_encode(array(
	    'code'=>4,
		'msg'=>$result['msg'],
		'debug'=>$result['debug']
	  ));
	}
  }
  
  // get a topic
  public function get(){
    $result=$this->yueSex->getTopic();
	if($result['code']==8){
	  echo json_encode(array('sex'=>$result['topic']));
	}else if($result['code']==4){
	  echo json_encode(array(
	    'code'=>4,
		'msg'=>'获取图片失败',
		'debug'=>$result['debug']
	  ));
	}
  }
  
  // delete a topic
  public function del(){
    $result=$this->yueSex->delTopic();  
	if($result['code']==8){
	  echo json_encode(array(
	    'code'=>8,
		'msg'=>'删除成功'
	  ));
	}else if($result['code']==4){
	  echo json_encode(array(
	    'code'=>4,
		'msg'=>'获取段子失败',
		'debug'=>$result['debug']
	  ));
	}
  }
  
  // list all topic
  public function all(){
    $result=$this->yueSex->allTopics();
	if($result['code']==8){
	  echo json_encode(array(
	    'sexs'=>array(
		  'nextPage'=>$result['nextPage'],
		  'total'=>$result['total'],
		  'sexs'=>$result['topics']
		)
	  ));
	}else if($result['code']==4){
	  echo json_encode(array(
	    'code'=>4,
		'msg'=>'获取段子列表失败',
		'debug'=>$result['debug']
	  ));
	}
  }
  
  // add remark ..
  public function addRemark(){
    $result=$this->yueSex->addRemark();
	if($result['code']==8){
	  echo json_encode(array('remark'=>$result['remark']));
	}else if($result['code']==4){
	  echo json_encode(array(
	    'code'=>4,
		'msg'=>'Add remark:false',
		'debug'=>$result['debug']
	  ));
	}
  }
  
  // action a topic .. such as praise,trample and so on ..
  public function ac(){
    $result=$this->yueSex->actionTopic();
	if($result['code']==8){
	  echo json_encode(array(
	    'code'=>8,
		'msg'=>'操作成功'
	  ));
	}else if($result['code']==4){
	  echo json_encode(array(
	    'code'=>4,
		'msg'=>$result['msg'],
		'debug'=>$result['debug']
	  ));
	}
  }
  
}