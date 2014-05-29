<?php
/*
    file name: topic.php
    useage: for admin use
    time: 2014.5.1 20:58
*/
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Topic extends CI_Controller{
  private $topicTable='yue_topic';
  private $topicRemarkTable='yue_topic_remark';
  private $topicActionTable='yue_topic_action';
  private $userTopicTable='yue_user_topic';
  private $yueUserTable='yue_user';
  private $userTable='user';

  public function __construct(){
    parent::__construct();
    $this->load->database();
    $this->load->library('session');
	$this->load->model('user');
	// 验证用户登录状态... ...
	if($this->user->isAdmin()==4){
	  header('Location:'.$this->config->base_url().'index.php/system/ilogin');
	  exit;
	}
	$this->load->model('yueTopic');
  }

  // view a topic ...
  public function edit($topic_id){
    $sql="select 
	        t.topic_id,t.content,t.num_praise,t.num_trample,t.num_remark,t.create_time,
			yu.avatar,yu.nickname,yu.user_id
		  from 
		    $this->topicTable as t
		  left join
		    $this->yueUserTable as yu
		  on
		    t.user_id=yu.user_id
		  where 
		    t.topic_id={$topic_id}";
	$topic=$this->db->query($sql);
	$topic=$topic->result_array();
	if($topic[0]['avatar']==0){
	  $topic[0]['avatar']=$this->config->base_url().'static/avatar/df.png';
	}else{
	  $topic[0]['avatar']=$this->config->base_url().'static/avatar/'.$topic[0]['avatar'].'/'.$topic[0]['user_id'].'.jpg';
	}
	$data=$topic[0];
	$data['siteUrl']=$this->config->base_url();
	$this->load->view('system/editTopic',$data);
  }
  public function editDo(){
    $topic_id=$this->input->post('topic_id');
	$content=$this->input->post('content');
	$num_praise=$this->input->post('num_praise');
	$num_trample=$this->input->post('num_trample');
	$sql="update 
	        $this->topicTable 
		  set 
		    content='{$content}',num_praise={$num_praise},num_trample={$num_trample}
		  where 
		    topic_id={$topic_id}";
	if($this->db->query($sql)){
	  echo '<script>alert("ok");history.back();</script>';
	}else{
	  echo 'fail';
	}
  }
  
  // add a topic ~~
  public function add(){
    $data['siteUrl']=$this->config->base_url();
	$this->load->view('system/addTopic',$data);
  }
  public function addDo(){
    $user_id=$this->input->post('user_id');
    $content=$this->input->post('content');
	// Main topic data...
    $create_time=time();
    // sql sentence...AND TRANSACTION begin!
    $this->db->trans_begin();
    // insert topic data
    $sql="insert into $this->topicTable(user_id,content,create_time) values({$user_id},'{$content}',{$create_time})";
    $this->db->query($sql);
    $topic_id=$this->db->insert_id();
    // insert user_topic data
    $sql="insert into 
            $this->userTopicTable(user_id,topic_id,create_time) 
            values({$user_id},{$topic_id},{$create_time})";
    $this->db->query($sql);
    if($this->db->trans_status()===false){
      $this->db->trans_rollback();
      echo 'Add topic:false';
    }else{
      $this->db->trans_commit();
      echo 'Add topic:true';
	}
  }
  
  // list all topic ...
  public function index($page=1){
    $perNum=15;
    // pagination ...
	$sql="select count(topic_id) as count from $this->topicTable";
	$totalNum=$this->db->query($sql);
	$totalNum=$totalNum->result_array();
	$totalNum=$totalNum[0]['count'];   // the total number of the topic
	$this->load->library('pagination');
	$config['base_url']=$this->config->base_url().'system/topic/index';
    $config['total_rows']=$totalNum;
	$config['per_page']=$perNum;
	$config['uri_segment']=4;
	$this->pagination->initialize($config);
	// create the links ~~
	$data['page']=$this->pagination->create_links();
	if($page==1){
	  $temppt=$page-1;
	}else{
	  $temppt=$page;
	}
	$sql="select 
	        t.topic_id,t.content,t.num_praise,t.num_trample,t.num_remark,t.create_time,
		    yu.nickname,yu.user_id
		  from
		    $this->topicTable as t
		  left join
		    $this->yueUserTable as yu
		  on
		    t.user_id=yu.user_id
		  order by 
		    t.topic_id desc
		  limit $temppt,$perNum
		  ";
	$result=$this->db->query($sql);
	$data['topics']=$result->result_array();
	$data['siteUrl']=$this->config->base_url();
	$this->load->view('system/allTopics',$data);
  }
  
}