<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
/*
   文件名称：user.php
   作用：用户增删改查以及登陆退出..
   需求类库：session database
   时间：2014.4.13 12.47
   SQL == create table user(
     uid int unsigned not null primary key auto_increment,
     salt char(6) not null,
     password char(32) not null,
     username varchar(16) not null,
     email varchar(32) not null,
     regip int unsigned not null,
     logip int unsigned not null,
     addtime int unsigned not null,
     uptime int unsigned not null
   )engine=innodb charset=utf8;
   SQL == create table admin(
     uid int unsigned not null,
     unique(uid)
   )engine=innodb charset=utf8;
*/
class User extends CI_Model{
  // 自定义指定用户数据表以及管理员数据表
  private $userTable='user';
  private $yueUserTable='yue_user';
  private $adminTable='admin';
  public function __construct(){
    parent::__construct();
  }
  public function add(){
    $username=$this->input->post('username');
    $email=$this->input->post('email');
	if($this->getUser($username)!=4 || $this->getUser($email)!=4){
	  return array(
	    'code'=>6,
		'msg'=>'Create user:user has exists',
		'debug'=>'Create user:user has exists'
	  );
	}
	$password=$this->input->post('password');
	if($this->input->post('isAdmin')=='' || $this->input->post('isAdmin')==null){
	  $isAdmin=0;  
	}else if($this->input->post('isAdmin')!=''){
	  $isAdmin=$this->input->post('isAdmin'); 
	}
	$regip=sprintf('%u',ip2long($this->input->ip_address()));
	$addtime=time();
	// 载入string helper
	$this->load->helper('string');
	$salt=random_string('alnum',6);
	$password=md5($password.$salt);
	// 组装sql
	$sql="insert into $this->userTable(salt,password,username,email,regip,logip,addtime,uptime) values('{$salt}','{$password}','{$username}','{$email}',{$regip},{$regip},{$addtime},{$addtime})";
	if($this->db->query($sql)){
	  $uid=$this->db->insert_id();
	  // 如果是管理员... 
	  if($isAdmin==1){
	    $sql="insert into $this->adminTable(uid) values({$uid})";
		if($this->db->query($sql)){
		  return array(
		    'code'=>8,
			'msg'=>'Create admin:true',
			'user'=>array(
			  'uid'=>$uid,
			  'username'=>$username,
			  'email'=>$email,
			  'isAdmin'=>$isAdmin,
			  'addtime'=>$addtime,
			  'uptime'=>$addtime
			)
		  );
		}else{
		  return array(
		    'code'=>4,
			'msg'=>'Create admin:false',
			'debug'=>'Create admin:'.$this->db->_error_message()
		  );
		}
	  }
	  return array(
	    'code'=>8,
		'msg'=>'Create user:true',
		'user'=>array(
		  'uid'=>$uid,
		  'username'=>$username,
		  'email'=>$email,
		  'isAdmin'=>$isAdmin,
		  'addtime'=>$addtime,
		  'uptime'=>$addtime
		)
	  );
	}else{
	  return array(
	    'code'=>4,
		'msg'=>'Create user:false',
		'debug'=>'Create user:'.$this->db->_error_message()
	  );
	}
  }
  // 普通用户到管理员提升..
  public function up2admin($uid){
    $sql="select uid from $this->adminTable where uid={$uid}";
	$result=$this->db->query($sql);
	$result=$result->result_array();
	if(count($result)>0){
	  return array(
	    'code'=>4,
		'msg'=>'up2admin:has own the auth'
	  );
	}else if(count($result)==0){
	  $sql="insert into $this->adminTable(uid) value({$uid})";
	  $this->db->query($sql);
	  if($this->db->affected_rows()>0){
	    return array(
		  'code'=>8,
		  'msg'=>'up2admin:true'
		);
	  }else{
	    return array(
		  'code'=>4,
		  'msg'=>'up2admin:false'
		);	    
	  }
	}
  }
  /*
    查询所有用户
	siteUrl：分页控制器方法URL
	page：页码数
	segment：页码参数所在segment位置
	perNum：每页多少个
  */
  public function allUser($siteUrl,$page,$segment,$perNum=15){
    // 载入分页配置.. 并返回页码.
	$tempPoint=$page-1;
	$sql="select count(uid) as count from $this->userTable";
	$result=$this->db->query($sql);
	$result=$result->result_array();
	$this->load->library('pagination');
	$config['base_url']=$siteUrl;
	$config['total_rows']=$result[0]['count'];
	$config['per_page']=$perNum;
	$config['uri_segment']=$segment;
	$this->pagination->initialize($config);
	// 获取当前区间数据
    $sql="select uid,username,email,regip,logip,addtime,uptime from $this->userTable limit {$tempPoint},{$perNum}";
	$arrUsers=$this->db->query($sql);
	return array(
	  'pages'=>$this->pagination->create_links(),
	  'arrUsers'=>$arrUsers->result_array()
	);
  }
  /*
    查询用户
	1. 后台查询某个用户
	2. 注册检验
  */
  public function getUser($user=0){
    if($user===0){
	  $user=$this->input->post('user');
	}
	if(strpos($user,'@')!=false){    // email查询  
      $keyword='email';
	}else if(is_numeric($user)){     // uid查询
	  $keyword='uid';
	}else if(preg_match('/[_a-zA-Z]+\d{0,}/',$user)){   // username查询
      $keyword='username';
	}else{
	  $keyword='username';
	}
	$sql="select 
	        u.uid,u.username,u.email,u.regip,u.logip,u.addtime,u.uptime,
			a.uid as isAdmin,
			yu.nickname,yu.avatar
	      from 
			$this->userTable as u
		  left join 
			$this->adminTable as a 
		  on 
			u.uid=a.uid
		  left join
		    $this->yueUserTable as yu
	      on
		    u.uid=yu.user_id
		  where u.{$keyword}='{$user}'";
	$result=$this->db->query($sql);
	$result=$result->result_array();
	if(count($result)>0){
	  // process user avatar ...
 	  if($result[0]['avatar']==0){
	    $result[0]['avatar']=$this->config->base_url().'static/avatar/df.png';
	  }else{
	    $result[0]['avatar']=$this->config->base_url().'static/avatar/'.$result[0]['avatar'].'/'.$result[0]['uid'].'.jpg';
	  }
	  return $result; 
	}else{
	  return 4;
	}
  }
  /*
    修改某个用户信息
	1. 普通用户身份--uid，action，oldPass，newPass，checkPass
  */
  public function edit(){
    $uid=$this->input->post('uid');
	$action=$this->input->post('action');
    // 首先是登陆者
    if($this->isLogin()==8){
	  if($this->isAdmin()==4){   // 普通用户
		// 验证登陆者的uid和提交的uid是否正确
		if($this->session->userdata('uid')!=$uid){
		  return 4;
		}
		if($action=='password'){  // 修改密码
		  $oldPass=$this->input->post('oldPass');
		  $newPass=$this->input->post('newPass');
		  $checkPass=$this->input->post('checkPass');
		  $sql="select salt,password from $this->userTable where uid={$uid}";
		  $result=$this->db->query($sql);
		  $result=$result->result_array();
		  if($result[0]['password']!=md5($result[0]['salt'].$oldPass)){
		    return 4;
		  }
          $sql="update $this->userTable set password='".md5($result[0]['salt'].$newPass)."' where uid={$uid}";
		}else if($action=='nopassword'){  // 修改email
		  $email=$this->input->post('email');
		  $sql="update $this->userTable set email='{$email}' where uid={$uid}";
		}
	  }else if($this->isAdmin()==8){ // 管理员登陆
	    if($action=='password'){
		  $password=$this->input->post('password');
		  $sql="select salt from $this->userTable where uid={$uid}";
		  $result=$this->db->query($sql);
		  $result=$result->result_array();
		  $sql="update $this->userTable set password='".md5($password.$result[0]['salt'])."' where uid={$uid}";
		}else if($action=='nopassword'){
		  $isAdmin=$this->input->post('isAdmin');
		  $email=$this->input->post('email');
		  $username=$this->input->post('username');
		  if($isAdmin==1){
		    $this->addAdmin($uid);
		  }else if($isAdmin==0){
            $sql="delete from $this->adminTable where uid={$uid}";		    
			$this->db->query($sql);
		  }
		  $sql="update $this->userTable set username='{$username}',email='{$email}' where uid={$uid}";
		}
	  }
	  $this->db->query($sql);
	  return array(
	    'code'=>8,
		'msg'=>'Edit user:true',
		'user'=>array(
		  'uid'=>$uid
		)
	  );
	}else{
	  return 4;
	}
  }
  // 添加管理员..
  private function addAdmin($uid){
    $sql="select * from $this->adminTable where uid={$uid}";
	$result=$this->db->query($sql);
	$result=$result->result_array();
	if(count($result)==0){
      $sql="insert into $this->adminTable(uid) values({$uid})";
	  $this->db->query($sql);
	}
  }
  // 检测是否登陆..
  public function isLogin(){
    if(isset($this->session->userdata['isLogin']) && $this->session->userdata('isLogin')==1){
	  return 8;
	}else{
	  return 4;
	}
  }
  public function isAdmin(){
    if(isset($this->session->userdata['isLogin']) && $this->session->userdata('isLogin')==1){
	  if(isset($this->session->userdata['isAdmin']) && $this->session->userdata('isAdmin')==1){
	    return 8;
	  }
	}else{
	  return 4;
	}
  }
  // 登陆...
  public function login(){
    $user=$this->input->post('username');
	$password=$this->input->post('password');
	if(strpos($user,'@')!=false){    // email登录
	  $sql="select uid,username,email,salt,password,addtime,uptime from $this->userTable where email='{$user}'";
	}else if(preg_match('/[_a-zA-Z]+\d{0,}/',$user)){   // username登陆
	  $sql="select uid,username,email,salt,password,addtime,uptime from $this->userTable where username='{$user}'";
	}else{
	  $sql="select uid,username,email,salt,password,addtime,uptime from $this->userTable where username='{$user}'";
	}
	$strUser=$this->db->query($sql);
	$strUser=$strUser->result_array();
	// this is for debug ...
	if(!$strUser){
	  return array(
	    'code'=>4,
		'msg'=>'Login:false,database error or user not exists',
		'debug'=>'Login:'.$this->db->_error_message()
	  );
	}
	if($strUser[0]['password']==md5($password.$strUser[0]['salt'])){   // 登陆成功
	  $logip=sprintf('%u',ip2long($this->input->ip_address()));
	  $uptime=time();
	  // 定位身份 普通？管理？
	  $sql="select * from $this->adminTable where uid=".$strUser[0]['uid'];
	  $strAdmin=$this->db->query($sql);
	  $strAdmin=$strAdmin->result_array();
	  if(count($strAdmin)>0){
	    $isAdmin=1;
	  }else{
	    $isAdmin=0;
	  }
	  // 首先写入session数据
	  $userdata=array(
	    'uid'=>$strUser[0]['uid'],
	    'username'=>$strUser[0]['username'],
	    'email'=>$strUser[0]['email'],
		'isLogin'=>1,
		'isAdmin'=>$isAdmin
	  );
	  $this->session->set_userdata($userdata);
	  // 然后更新表状态
	  $sql="update $this->userTable set logip={$logip},uptime={$uptime} where uid=".$strUser[0]['uid'];
	  $this->db->query($sql);
	  return array(
	    'code'=>8,
		'msg'=>'登录成功',
		'isAdmin'=>$isAdmin,
		'user'=>array(
	      'user_id'=>$strUser[0]['uid'],
		  'user_name'=>$strUser[0]['username'],
		  'email'=>$strUser[0]['email'],
		  'create_time'=>$strUser[0]['addtime'],
		  'update_time'=>$strUser[0]['uptime']
		)
	  );
	}else{    // 登陆失败。。。
	  return array(
	    'code'=>6,
		'msg'=>'Login:false,username or password wrong',
		'debug'=>'Login:wrong username or password'
	  );
	}
  }
  // 删除
  public function delete($uid){
    // 只有管理员才能删除用户... ..
    if($this->isAdmin()==8){
	  $sql="delete from $this->userTable where uid={$uid}";
	  $this->db->query($sql);
	}else{
	  return 4;exit;
	}
  }
}