<?php
/*
    file name: pic.php
    useage: for admin use
    time: 2014.5.1 20:58
*/
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Pic extends CI_Controller{
  private $picTable='yue_pic';
  private $picRemarkTable='yue_pic_remark';
  private $picActionTable='yue_pic_action';
  private $userPicTable='yue_user_pic';
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
	$this->load->model('yuepic');
  }

  // view a topic ...
  public function edit($pic_id){
    $sql="select 
	        t.pic_id,t.pic_name,t.ext,t.path,t.content,t.num_praise,t.num_trample,t.num_remark,t.create_time,
			yu.avatar,yu.nickname,yu.user_id
		  from 
		    $this->picTable as t
		  left join
		    $this->yueUserTable as yu
		  on
		    t.user_id=yu.user_id
		  where 
		    t.pic_id={$pic_id}";
	$topic=$this->db->query($sql);
	$topic=$topic->result_array();
	$topic[0]['img_url']=$this->config->base_url().'static/pic/'.$topic[0]['path'].'/'.$topic[0]['pic_name'].$topic[0]['ext'];
	if($topic[0]['avatar']==0){
	  $topic[0]['avatar']=$this->config->base_url().'static/avatar/df.png';
	}else{
	  $topic[0]['avatar']=$this->config->base_url().'static/avatar/'.$topic[0]['avatar'].'/'.$topic[0]['user_id'].'.jpg';
	}
	$data=$topic[0];
	$data['siteUrl']=$this->config->base_url();
	$this->load->view('system/editPic',$data);
  }
  public function editDo(){
    $pic_id=$this->input->post('pic_id');
	$content=$this->input->post('content');
	$num_praise=$this->input->post('num_praise');
	$num_trample=$this->input->post('num_trample');
	$sql="update 
	        $this->picTable 
		  set 
		    content='{$content}',num_praise={$num_praise},num_trample={$num_trample}
		  where 
		    pic_id={$pic_id}";
	if($this->db->query($sql)){
	  echo '<script>alert("ok");history.back();</script>';
	}else{
	  echo 'fail';
	}
  }
  
  // add a topic ~~
  public function add(){
    $data['siteUrl']=$this->config->base_url();
	$this->load->view('system/addPic',$data);
  }
  public function addDo(){
  	$user_id=$this->input->post('user_id');
    // Main topic data...
    $content=$this->input->post('content');
    $create_time=time();
    // sql sentence...AND TRANSACTION begin!
    $this->db->trans_begin();
    // insert topic data
    $sql="insert into $this->picTable(user_id,content,create_time) values({$user_id},'{$content}',{$create_time})";
    $this->db->query($sql);
    $pic_id=$this->db->insert_id();
    // insert user_topic data
    $sql="insert into 
            $this->userPicTable(user_id,pic_id,create_time) 
            values({$user_id},{$pic_id},{$create_time})";
    $this->db->query($sql);
	// if mysql transaction failed ... 
    if($this->db->trans_status()===false){
      $this->db->trans_rollback();
      echo 'upload pic fail...';
	// if mysql transaction success ...
    }else{
	  // process the image upload ... 
      $dirName=ceil($pic_id/3000);  // yue_pic path field ...
	  if(!is_dir('static/pic/'.$dirName)){
	    mkdir('static/pic/'.$dirName);
	  }
	  $picName=$create_time.'_'.$pic_id;  // yue_pic pic_name field ...
	  // load CI upload library
	  $config['upload_path']='./static/pic/'.$dirName;
	  $config['allowed_types']='gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG';
	  $config['max_size']='8096';
	  $config['overwrite']=true;
	  $config['file_name']=$picName;
	  $this->load->library('upload',$config);
	  // if the upload success ...
	  if($this->upload->do_upload('pic')){
	    $fileData=$this->upload->data();
	    $width=$fileData['image_width'];
		$height=$fileData['image_height'];
		$ext=$fileData['file_ext'];
		$sql="update $this->picTable set pic_name='{$picName}',path='{$dirName}',width={$width},height={$height},ext='{$ext}' where pic_id={$pic_id}";
	    $this->db->query($sql);
		$this->db->trans_commit();
		echo 'ok!';
	  }else{
	    echo $this->upload->display_errors();
	  }
    }
  }
  
  // list all topic ...
  public function index($page=1){
    $perNum=10;
    // pagination ...
	$sql="select count(pic_id) as count from $this->picTable";
	$totalNum=$this->db->query($sql);
	$totalNum=$totalNum->result_array();
	$totalNum=$totalNum[0]['count'];   // the total number of the topic
	$this->load->library('pagination');
	$config['base_url']=$this->config->base_url().'system/pic/index';
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
	        t.pic_id,t.pic_name,t.ext,t.path,t.content,t.num_praise,t.num_trample,t.num_remark,t.create_time,
		    yu.nickname,yu.user_id
		  from
		    $this->picTable as t
		  left join
		    $this->yueUserTable as yu
		  on
		    t.user_id=yu.user_id
		  order by 
		    t.pic_id desc
		  limit $temppt,$perNum
		  ";
	$result=$this->db->query($sql);
	$result=$result->result_array();
	foreach($result as $key=>&$item){
	  $item['img_url']=$this->config->base_url().'static/pic/'.$item['path'].'/'.$item['pic_name'].$item['ext'];
	}
	$data['topics']=$result;
	$data['siteUrl']=$this->config->base_url();
	$this->load->view('system/allPics',$data);
  }
  
}