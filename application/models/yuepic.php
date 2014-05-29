<?php
/*
    file name: yuePic.php
    useage: get post del edit topic 
    time: 2014.4.27 21:10
*/
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Yuepic extends CI_Model{
  private $picTable='yue_pic';
  private $picRemarkTable='yue_pic_remark';
  private $picActionTable='yue_pic_action';
  private $userPicTable='yue_user_pic';
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
    $sql="select count(pic_id) as totalTopic from $this->picTable";
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
              t.pic_id,t.content,t.pic_name,t.ext,t.path,t.width,t.height,t.num_praise,t.num_trample,t.num_remark,t.create_time,
              u.nickname,u.avatar,u.user_id
            from 
              $this->picTable as t
            left join
              $this->yueUserTable as u
            on 
              t.user_id=u.user_id
			order by 
			  t.pic_id desc
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
		  $item['url']=$this->config->base_url().'static/pic/'.$item['path'].'/'.$item['pic_name'].$item['ext'];
		  if($item['avatar']==0){
		    $avatar=$this->config->base_url().'static/avatar/df.png';
		  }else{
		    $avatar=$this->config->base_url().'static/avatar/'.$item['avatar'].'/'.$item['user_id'].'.jpg';
		  }
		  $item['user']['avatar']=$avatar;
		  $item['user']['user_id']=$item['user_id'];
		  $item['user']['nickname']=$item['nickname'];
		  unset($item['pic_name']);
		  unset($item['path']);
		  unset($item['avatar']);
		  unset($item['user_id']);
		  unset($item['nickname']);
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
    $pic_id=$this->input->post('pic_id');
    $user_id=$this->input->post('user_id');
    $token=$this->input->post('token');
    // auth user
	$auth=$this->yueUser->isLogin($user_id,$token);
    if($auth['code']==4){
      return array(
        'code'=>4,
        'msg'=>'Auth:false',
		'debug'=>'Auth:false,token invalid'
      );
    }
    // get one topic..
    $sql="select 
            t.pic_id,t.user_id,t.content,t.pic_name,t.ext,t.path,t.width,t.height,t.num_praise,t.num_trample,t.num_remark,t.create_time,
            u.nickname,u.avatar
          from 
            $this->picTable as t
          left join
            $this->yueUserTable as u
          on
            t.user_id=u.user_id
          where 
            pic_id={$pic_id}";
    $topic=$this->db->query($sql);
	if($topic){
      $topic=$topic->result_array();
	  $topic[0]['url']=$this->config->base_url().'statit/pic/'.$topic[0]['path'].'/'.$topic[0]['pic_name'].$topic[0]['ext'];
      if($topic[0]['avatar']==0){
	    $avatar=$this->config->base_url().'static/avatar/df.png';
	  }else{
	    $avatar=$this->config->base_url().'static/avatar/'.$topic[0]['avatar'].'/'.$topic[0]['user_id'].'.jpg';
	  }
	  return array(
        'code'=>8,
        'msg'=>'Get topic:true',
        'topic'=>array(
	      'pic_id'=>$topic[0]['pic_id'],
	      'content'=>$topic[0]['content'],
		  'width'=>$topic[0]['width'],
		  'height'=>$topic[0]['height'],
		  'ext'=>$topic[0]['ext'],
		  'url'=>$topic[0]['url'],
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
		'msg'=>'Get pic:false',
		'debug'=>'Get pic:'.$this->db->_error_message()
	  );
	}
  }
  
  // report a topic ...
  public function reportPic(){
  	return array(
	  'result'=>'true'
	);
  }
  
  // get remarks
  public function getRemark(){
    //$pic_id,$page,$perNum
	$pic_id=$this->input->post('pic_id');
	$page=$this->input->post('page');
	$perNum=$this->input->post('perNum');
    // pagination ..
    $sql="select 
            count(pic_id) as totalNum 
          from 
            $this->picRemarkTable 
          where 
            pic_id={$pic_id}";
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
              $this->picRemarkTable as r
            left join
              $this->yueUserTable as u
            on 
              r.user_id=u.user_id
            where 
			  r.pic_id={$pic_id} 
			order by 
			  r.pic_id desc 
			limit 
			  {$tmpPoint},{$perNum}";
	  $arrRemarks=$this->db->query($sql);
	  if($arrRemarks){
        $arrRemarks=$arrRemarks->result_array();
	    foreach($arrRemarks as $key=>&$item){
	      $arrRemarks[$key]['user']['user_id']=$item['user_id'];
	      $arrRemarks[$key]['user']['nickname']=$item['nickname'];
		  if($item['avatar']==0){
		    $avatar=$this->config->base_url().'static/avatar/df.png';
		  }else{
		    $avatar=$this->config->base_url().'static/avatar/'.$item['avatar'].'/'.$item['user_id'].'.jpg';
		  }
	      $arrRemarks[$key]['user']['avatar']=$avatar;
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
		  'msg'=>'Get remarks:false',
		  'debug'=>'Get remarks:'.$this->db->_error_message()
		);
	  }
    }
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
      return array(
        'code'=>4,
        'msg'=>'Add topic:false',
		'debug'=>'Add topic:'.$this->db->_error_message()
      );
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
        return array(
          'code'=>8,
          'msg'=>'Add topic:true',
          'topic'=>array(
			'pic_id'=>$pic_id,
			'content'=>$content,
			'width'=>$width,
			'height'=>$height,
			'ext'=>$ext,
			'url'=>$this->config->base_url().'static/pic/'.$dirName.'/'.$picName.$ext,
			'num_praise'=>0,
			'num_trample'=>0,
			'num_remark'=>0,
			'create_time'=>$create_time,
            'user'=>array(
              'user_id'=>$user_id,
              'nickname'=>$strUser[0]['nickname'],
              'avatar'=>$strUser[0]['avatar']
            ),
		    'remarks'=>array()
          )
        );
	  }else{
	    return array(
		  'code'=>4,
		  'msg'=>'Add topic:false',
		  'debug'=>'Add topic:'.$this->upload->display_errors()
		);
	  }
    }
  }

  // delete a topic
  public function delTopic(){
  	$pic_id=$this->input->post('pic_id');
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
    $sql="select user_id from $this->picTable where pic_id={$pic_id}";
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
  	$sql="delete from $this->picTable where pic_id={$pic_id}";
  	$this->db->query($sql);
    // delete remarks
  	$sql="delete from $this->picRemarkTable where pic_id={$pic_id}";
    $this->db->query($sql);
    // delete topic action record
    $sql="delete from $this->picActionTable where pic_id={$pic_id}";
    $this->db->query($sql);
    // delete personal topic record ..
    $sql="delete from $this->userPicTable where pic_id={$pic_id}";
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
    $pic_id=$this->input->post('pic_id');
    $user_id=$this->input->post('user_id');
    $content=$this->input->post('content');
    $token=$this->input->post('token');
	$auth=$this->yueUser->isLogin($user_id,$token);
    // auth user
    if($auth['code']==4){
      return array(
        'code'=>4,
        'msg'=>'Auth:false',
		'debug'=>'Auth:false,token invalid'
      );
    }
    // get user
    $strUser=$this->user->getUser($user_id);
    // mysql transaction
    $this->db->trans_begin();
    // insert remark..
    $create_time=time();
    $sql="insert into 
            $this->picRemarkTable(pic_id,user_id,content,create_time) 
            values({$pic_id},{$user_id},'{$content}',${create_time})";
    $this->db->query($sql);
    $remark_id=$this->db->insert_id();
    // update remark number..
    $sql="update $this->picTable set num_remark=num_remark+1 where pic_id={$pic_id}";
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
    $pic_id=$this->input->post('pic_id');
    //$user_id=$this->input->post('user_id');
    //$token=$this->input->post('token');
    // 8 is praise , 4 is trample ...
    $action=$this->input->post('action');
    // auth user
	//$auth=$this->yueUser->isLogin($user_id,$token);
    //if($auth['code']==4){
      //return array(
        //'code'=>4,
        //'msg'=>'Auth:false',
		//'debug'=>'Auth:false,token invalid'
      //);
    //}
    // check if exists
    //$sql="select user_id from $this->picActionTable where pic_id={$pic_id}";
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
            //$this->picActionTable(pic_id,user_id,action,create_time)
            //values({$pic_id},{$user_id},{$action},{$create_time})";
    //$this->db->query($sql);
    if($action==8){
      $sql="update $this->picTable set num_praise=num_praise+1 where pic_id={$pic_id}";
      $this->db->query($sql);
    }else if($action==4){
      $sql="update $this->picTable set num_trample=num_trample+1 where pic_id={$pic_id}";
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