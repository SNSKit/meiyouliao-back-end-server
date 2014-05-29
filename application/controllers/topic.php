<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
    rest api:段子...
	2014.5.7 9.39
*/
class Topic extends CI_Controller{
  public function __construct(){
    parent::__construct();
	$this->load->database();
	// load yueUser model which includes User model...
	$this->load->model('yueTopic');
  }
  
  // add topic
  public function add(){
    $result=$this->yueTopic->addTopic();
	if($result['code']==8){
	  echo json_encode(array('topic'=>$result['topic']));
	}else if($result['code']==4){
	  echo json_encode(array(
	    'code'=>4,
		'msg'=>'发表段子失败',
		'debug'=>$result['debug']
	  ));
	}
  }
  
  // get a topic
  public function get(){
    $result=$this->yueTopic->getTopic();
	if($result['code']==8){
	  echo json_encode(array('topic'=>$result['topic']));
	}else if($result['code']==4){
	  echo json_encode(array(
	    'code'=>4,
		'msg'=>'获取段子失败',
		'debug'=>$result['debug']
	  ));
	}
  }
  
  // delete a topic
  public function del(){
    $result=$this->yueTopic->delTopic();  
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
    $result=$this->yueTopic->allTopics();
	if($result['code']==8){
	  echo json_encode(array(
	    'topics'=>array(
		  'nextPage'=>$result['nextPage'],
		  'total'=>$result['total'],
		  'topics'=>$result['topics']
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

  public function report(){
    echo json_encode(array(
	  'result'=>'true'
	));
  }

  // get remarks ..
  public function getRemark(){
    $result=$this->yueTopic->getRemark();
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
  
  
  // add remark ..
  public function addRemark(){
    $result=$this->yueTopic->addRemark();
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
    $result=$this->yueTopic->actionTopic();
	if($result['code']==8){
	  echo json_encode(array(
	    'code'=>8,
		'result'=>'true',
		'msg'=>'操作成功'
	  ));
	}else if($result['code']==4){
	  echo json_encode(array(
	    'code'=>4,
		'result'=>'false',
		'msg'=>$result['msg'],
		'debug'=>$result['debug']
	  ));
	}
  }
  
}