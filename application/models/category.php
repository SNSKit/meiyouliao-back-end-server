<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
/*
   category.php
   通用无限分类
   2014.3.27
   所需类库：dababase
   数据表:category
		  create table category(
		    id int unsigned not null primary key auto_increment,
		    pid int unsigned not null,
		    grade int unsigned not null,
		    category varchar(8) not null,
		    path varchar(12) not null
		  )engine=innodb charset=utf8;
*/
class Category extends CI_Model{
  private $tableName='category';
  public function __construct(){
    parent::__construct();
  }
  // 添加 接受category和pid两个变量..
  public function add(){
    $category=$this->input->post('category');
	$pid=$this->input->post('pid')==''?0:$this->input->post('pid');
	// 非顶级分类.
	if($pid!=''){
	  $sql="select `id`,`path`,`grade` from $this->tableName where `id`={$pid}";
	  $strCategory=$this->db->query($sql);
	  $strCategory=$strCategory->result_array();
	  if($strCategory[0]['path']!=0){
	    $path=$strCategory[0]['path'].','.$pid;
	  }else if($strCategory[0]['path']==0){
	    $path=$pid;
	  }
	  $grade=$strCategory[0]['grade']+1;
	  $sql="insert into $this->tableName(`pid`,`grade`,`category`,`path`) value({$pid},{$grade},'{$category}','{$path}')";  
	}else if(empty($pid)){  // 顶级分类.
	  $sql="insert into $this->tableName(`pid`,`grade`,`category`,`path`) value(0,1,'{$category}',0)";
	}
	$result=$this->db->query($sql);
	if($result==1){
	  return 8;
	}else{
	  return 4;
	}
  }
  // 获取查看
  public function get($id){
    $sql="select * from category where `id`={$id}";
	$result=$this->db->query($sql);
	$result=$result->result_array();
	return $result;
  }
  // 获取某分类的父级分类
  public function getParents($id){
    $sql="select `path` from category where `id`={$id}";
	$result=$this->db->query($sql);
	$result=$result->result_array();
	$parentIDs=explode(',',$result[0]['path']);
	foreach($parentIDs as $key=>$item){
	  $sql="select `id`,`pid`,`category` from $this->tableName where `id`={$item}";
	  $result=$this->db->query($sql);
	  $result=$result->result_array();
	  $parents[$key]['id']=$result[0]['id'];
	  $parents[$key]['pid']=$result[0]['pid'];
	  $parents[$key]['category']=$result[0]['category'];
	}
	return $parents;
  }
  // 获取到所有的子分类 递归方法
  public function getSons($id){
    static $sonIDs;
    // 获取下一级pid指向该id的分类数组
    $sql="select `id` from $this->tableName where `pid`={$id}";
	$arrsonIDs=$this->db->query($sql);
	$arrsonIDs=$arrsonIDs->result_array();
	foreach($arrsonIDs as $key=>$item){
	  $sonIDs.=$item['id'].',';
	  $sonIDs=$this->getSons($item['id']);
	}
	return $sonIDs;
  }
  // 编辑 仅仅支持修改名称 不支持修改grade等级
  public function edit(){
    $id=$this->input->post('id');
    $category=$this->input->post('category');
	$sql="update $this->tableName set `category`='{$category}' where `id`={$id}";
	$result=$this->db->query($sql);
	if($result==1){
	  return 8;
	}else{
	  return 4;
	}
  }
  // 删除 获取该分类子分类id，然后通过sql语句删除.... ...
  public function delete($id){
    $sonIDs=$this->getSons($id).$id;
	$sql="delete from $this->tableName where id in({$sonIDs})";
	$this->db->query($sql);
	return 8;
  }
}