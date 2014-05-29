<?php
/*
    file name: topic.php
    useage: get post del edit topic 
    time: 2014.4.27 21:10
*/
if(!defined('BASEPATH')) exit('No direct script access allowed');
class yuetopic extends CI_Model{
  private $topicTable='yue_topic';
  private $topicRemarkTable='yue_topic_remark';
  private $topicActionTable='yue_topic_action';
  private $userTopicTable='yue_user_topic';
  private $yueUserTable='yue_user';
  private $userTable='user';

  public function __construct(){
    parent::__construct();
	  $this->load->model('yueUser');
	  $this->load->library('form_validation');
  }

  // list all topic order by create_time desc..
  // $siteUrl,$page,$segment,$perNum=15
  public function allTopics(){
    // pagination
    $page=$this->input->post('page');
    $perNum=$this->input->post('perNum');
    // the total num of topics..
    $sql="select count(topic_id) as totalTopic from $this->topicTable";
    $totalTopic=$this->db->query($sql);
    $totalTopic=$totalTopic->result_array();
    $totalPage=$totalTopic[0]['totalTopic'];
    $totalPage=ceil($totalPage/$perNum);
    // temp page COMPARE total page..
    if($page>$totalPage){
      $nextPage=-1;
      $arrTopics=array();
    }else if($page<=$totalPage){
      if($page==$totalPage){
        $nextPage=-1;
      }else if($page<$totalPage){
        $nextPage=$page+1;
      }
      $tmpPoint=($page-1)*$perNum;
      $sql="select 
              t.topic_id,t.content,t.num_praise,t.num_trample,t.num_remark,t.create_time,
              yu.user_id,yu.nickname,yu.avatar
            from 
              $this->topicTable as t
            left join
              $this->yueUserTable as yu
			on t.user_id=yu.user_id
			order by t.topic_id desc
            limit {$tmpPoint},{$perNum}";
      $arrTopics=$this->db->query($sql);
	  if(!$arrTopics){
	    return array(
		  'code'=>4,
		  'msg'=>'List topics:false',
		  'debug'=>'List topics:'.$this->db->_error_message()
		);
	  }else if($arrTopics){
	    $arrTopics=$arrTopics->result_array();
		foreach($arrTopics as $key=>&$item){
	      $item['user']['user_id']=$item['user_id'];
	      $item['user']['nickname']=$item['nickname'];
		  if($item['avatar']==0){
		    $avatar=$this->config->base_url().'static/avatar/df.png';
		  }else{
		    $avatar=$this->config->base_url().'static/avatar/'.$item['avatar'].'/'.$item['user_id'].'.jpg';
		  }
		  $item['user']['avatar']=$avatar;
		  unset($item['user_id']);
		  unset($item['nickname']);
		  unset($item['avatar']);
		}
	  }
    }
    return array(
      'code'=>8,
      'msg'=>'List topics:true',
	  'nextPage'=>$nextPage,
      'total'=>$totalPage,
      'topics'=>$arrTopics
    );
  }

  // get a topic
  public function getTopic(){
    $topic_id=$this->input->post('topic_id');
    $user_id=$this->input->post('user_id');
    $token=$this->input->post('token');
    // auth user
	$auth=$this->yueUser->isLogin($user_id,$token);
    if($auth['code']==4){
      return array(
        'code'=>4,
        'msg'=>'Auth:false'
      );
    }
    // get one topic..
    $sql="select 
            t.topic_id,t.user_id,t.content,t.num_praise,t.num_trample,t.num_remark,t.create_time,
            u.nickname,u.avatar
          from 
            $this->topicTable as t
          left join
            $this->yueUserTable as u
          on
            t.user_id=u.user_id
          where 
            topic_id={$topic_id}";
    $topic=$this->db->query($sql);
	if($topic){
      $topic=$topic->result_array();
	  if($topic[0]['avatar']==0){
	    $avatar=$this->config->base_url().'static/avatar/df.png';
	  }else{
	    $avatar=$this->config->base_url().'static/avatar/'.$topic[0]['user_id'].'/'.$topic[0]['avatar'].'.jpg';
	  }
      return array(
        'code'=>8,
        'msg'=>'Get topic:true',
        'topic'=>array(
	      'topic_id'=>$topic[0]['topic_id'],
	      'content'=>$topic[0]['content'],
	      'num_praise'=>$topic[0]['num_praise'],
	      'num_trample'=>$topic[0]['num_trample'],
		  'num_remark'=>$topic[0]['num_remark'],
		  'create_time'=>$topic[0]['create_time'],
		  'user'=>array(
		    'user_id'=>$topic[0]['user_id'],
		    'nickname'=>$topic[0]['nickname'],
		    'avatar'=>$avatar
		  )
	    )
      );
	}else{
	  return array(
	    'code'=>4,
		'msg'=>'Get topic:false',
		'debug'=>'Get topic:'.$this->db->_error_message()
	  );
	}
  }
  // get remarks
  public function getRemark(){
    //$topic_id,$page,$perNum
	$topic_id=$this->input->post('topic_id');
	$page=$this->input->post('page');
	$perNum=$this->input->post('perNum');
    // pagination ..
    $sql="select 
            count(topic_id) as totalNum 
          from 
            $this->topicRemarkTable 
          where 
            topic_id={$topic_id}";
    $totalRemark=$this->db->query($sql);
    $totalRemark=$totalRemark->result_array();   
	// the total number of the topic ...
    $totalPage=ceil($totalRemark[0]['totalNum']/$perNum);
    // if temp page over total page , then return nothing..
    if($page>$totalPage){
      $nextPage=-1;
      $arrRemarks=array();
    }else if($page<=$totalPage){
      if($page==$totalPage){
        $nextPage=-1;
      }else if($page<$totalPage){
        $nextPage=$page+1;
      }
      $tmpPoint=($page-1)*$perNum;
      // get the remark and the author ...
      $sql="select 
              r.remark_id,r.content,r.create_time,r.user_id,
              u.nickname,u.avatar
            from
              $this->topicRemarkTable as r
            left join
              $this->yueUserTable as u
            on 
              r.user_id=u.user_id
            where 
			  r.topic_id={$topic_id} 
			order by 
			  r.topic_id desc 
			limit 
			  {$tmpPoint},{$perNum}";
	  $arrRemarks=$this->db->query($sql);
	  if($arrRemarks){
        $arrRemarks=$arrRemarks->result_array();
	    foreach($arrRemarks as $key=>&$item){
	      $arrRemarks[$key]['user']['user_id']=$item['user_id'];
	      $arrRemarks[$key]['user']['nickname']=$item['nickname'];
		  $arrRemarks[$key]['user']['avatar']=$this->config->base_url().'static/avatar/'.$item['avatar'].'/'.$item['user_id'].'.jpg';
		  unset($item['user_id']);
		  unset($item['nickname']);
		  unset($item['avatar']);
	    }
		return array(
		  'code'=>8,
		  'msg'=>'Get remark:true',
		  'remarks'=>array(
		    'nextPage'=>$nextPage,
		    'total'=>$totalRemark[0]['totalNum'],
		    'remarks'=>$arrRemarks
		  )
		);
	  }else if(!$arrRemarks){
	    return array(
		  'code'=>4,
		  'msg'=>'Get remark:false',
		  'debug'=>'Get remark:'.$this->db->_error_message()
		);
	  }
    }
  }

  // report a topic ...
  public function reportTopic(){
  	return array(
	  'result'=>'true'
	);
  }
  
  // add a topic ... ...
  public function addTopic(){
  	$user_id=$this->input->post('user_id');
  	$token=$this->input->post('token');
  	// Check user login status...
	$auth=$this->yueUser->isLogin($user_id,$token);
    if($auth['code']==4){
      return array(
        'code'=>4,
        'msg'=>'Auth:false',
		'debug'=>'Auth:false,token invalid'
      );
    }
    // get user information ...
    $strUser=$this->user->getUser($user_id);
	// Main topic data...
    $content=$this->input->post('content');
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
	  $debug=$this->db->_error_message();
      $this->db->trans_rollback();
      return array(
        'code'=>4,
        'msg'=>'Add topic:false',
		'debug'=>'Add topic:'.$debug
      );
    }else{
      $this->db->trans_commit();
      return array(
        'code'=>8,
        'msg'=>'Add topic:true',
        'topic'=>array(
          'topic_id'=>$topic_id,
          'content'=>$content,
          'create_time'=>$create_time,
          'user'=>array(
            'user_id'=>$user_id,
            'nickname'=>$strUser[0]['nickname'],
            'avatar'=>$strUser[0]['avatar']
          ),
		  'remarks'=>array()
        )
      );
    }
  }

  // delete a topic
  public function delTopic(){
  	$topic_id=$this->input->post('topic_id');
    $user_id=$this->input->post('user_id');
    $token=$this->input->post('token');
    // auth user
	$auth=$this->yueUser->isLogin($user_id,$token);
    if($auth['code']==4){
      return array(
        'code'=>4,
        'msg'=>'Auth:false',
		'debug'=>'delete:user token wrong'
      );
    }
    // auth user and topic..
    $sql="select user_id from $this->topicTable where topic_id={$topic_id}";
    $userTopic=$this->db->query($sql);
    $userTopic=$userTopic->result_array();
    if($userTopic[0]['user_id']!=$user_id){
      return array(
        'code'=>4,
        'msg'=>'Not your topic',
		'debug'=>'delete:Not your topic'
      );
    }
    // start mysql transaction ...
  	$this->db->trans_begin();
  	// delete topic
  	$sql="delete from $this->topicTable where topic_id={$topic_id}";
  	$this->db->query($sql);
    // delete remarks
  	$sql="delete from $this->topicRemarkTable where topic_id={$topic_id}";
    $this->db->query($sql);
    // delete topic action record
    $sql="delete from $this->topicActionTable where topic_id={$topic_id}";
    $this->db->query($sql);
    // delete personal topic record ..
    $sql="delete from $this->userTopicTable where topic_id={$topic_id}";
    $this->db->query($sql);
    if($this->db->trans_status()===false){
      $this->db->trans_rollback();
      return array(
        'code'=>4,
        'msg'=>'Delete topic:false',
		'debug'=>'Delete:'.$this->db->_error_message()
      );
    }else{
      $this->db->trans_commit();
      return array(
        'code'=>8,
        'msg'=>'Delete topic:true'
      );
    }
  }

  
  // add remark to topic ...
  public function addRemark(){
    $topic_id=$this->input->post('topic_id');
    $user_id=$this->input->post('user_id');
    $content=$this->input->post('content');
    $token=$this->input->post('token');
	$auth=$this->yueUser->isLogin($user_id,$token);
    // auth user
    if($auth['code']==4){
      return array(
        'code'=>4,
        'msg'=>'Auth:false'
      );
    }
    // get user
    $strUser=$this->user->getUser($user_id);
    // mysql transaction
    $this->db->trans_begin();
    // insert remark..
    $create_time=time();
    $sql="insert into 
            $this->topicRemarkTable(topic_id,user_id,content,create_time) 
            values({$topic_id},{$user_id},'{$content}',${create_time})";
    $this->db->query($sql);
    $remark_id=$this->db->insert_id();
    // update remark number..
    $sql="update $this->topicTable set num_remark=num_remark+1 where topic_id={$topic_id}";
    $this->db->query($sql);
    if($this->db->trans_status()===false){
      $this->db->trans_rollback();
      return array(
        'code'=>4,
        'msg'=>'Add remark:false',
		'debug'=>'Add remark:'.$this->db->_error_message()
      );
    }else{
	  $this->db->trans_commit();
      return array(
        'code'=>8,
        'msg'=>'Add remark:true',
        'remark'=>array(
          'remark_id'=>$remark_id,
          'content'=>$content,
          'create_time'=>$create_time,
          'user'=>array(
            'user_id'=>$user_id,
            'nickname'=>$strUser[0]['nickname'],
            'avatar'=>$strUser[0]['avatar']
          )
        )
      );
    }
  }


  // action topic
  public function actionTopic(){
    $topic_id=$this->input->post('topic_id');
    //$user_id=$this->input->post('user_id');
    //$token=$this->input->post('token');
    // 8 is praise , 4 is trample ...
    $action=$this->input->post('action');
    // auth user
	//$auth=$this->yueUser->isLogin($user_id,$token);
    //if($auth['code']==4){
      //return array(
        //'code'=>4,
        //'msg'=>'Auth:false'
      //);
    //}
    // check if exists
    //$sql="select user_id from $this->topicActionTable where topic_id={$topic_id}";
    //$strTag=$this->db->query($sql);
    //$strTag=$strTag->result_array();
    //if(isset($strTag[0]) && $strTag[0]['user_id']==$user_id){
      //return array(
        //'code'=>4,
        //'msg'=>'您已经表态过了',
		//'debug'=>'您已经表态过了'
      //);
    //}
    //$create_time=time();
    // start mysql transaction ..
    $this->db->trans_begin();
    //$sql="insert into 
            //$this->topicActionTable(topic_id,user_id,action,create_time)
            //values({$topic_id},{$user_id},{$action},{$create_time})";
    //$this->db->query($sql);
    if($action==8){
      $sql="update $this->topicTable set num_praise=num_praise+1 where topic_id={$topic_id}";
      $this->db->query($sql);
    }else if($action==4){
      $sql="update $this->topicTable set num_trample=num_trample+1 where topic_id={$topic_id}";
      $this->db->query($sql);
    }
    if($this->db->trans_status()===false){
      $this->db->trans_rollback();
      return array(
        'code'=>4,
        'msg'=>'Topic action:false',
		'debug'=>'Topic action:'.$this->db->_error_message()
      );
    }else{
      $this->db->trans_commit();
      return array(
        'code'=>8,
        'msg'=>'Topic action:true'
      );
    }
  }

}