<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
	<style>
      body,div,dl,dt,dd,ul,li,h1,h2,h3,h4,h5,h6,p,pre,code,form,fieldset,legend,input,button,textarea,blockquote,img{margin:0px;padding:0px;}
	  table{border-collapse:collapse;border-spacing:0px;}
	  fieldset,img{border:none;}
	  html,body{font-family:"Microsoft Yahei";}
	  a:link{text-decoration:none;color:black;}
	  a:visited{color:black;}
	  .fl{float:left;}
	  .fr{float:right;}
	  .clearfix:after {visibility:hidden;display:block;font-size:0;content:" ";clear:both;height:0;}
	  * html .clearfix{zoom:1;}
	  *:first-child+html .clearfix{zoom:1;} 
	  .clear{clear:both;}
	  .tac{text-align:center;}
	  .tar{text-align:right;}
	  .tal{text-align:left;}
	  ul{list-style-type:none;}
	  #banner,#main{width:1000px;margin:20px auto 0px;}
	  #banner{font-size:20px;border-bottom:1px solid black;}
	  #banner li{margin-right:20px;}
	  #main{border-top:10px solid white;}
	  #main #leftMain{width:120px;}
	  #main #leftMain #quick{font-size:17px;}
	  #main #leftMain #quick li{border-bottom:5px solid white;}
	  #main #rightMain{width:880px;}
	  #main #rightMain .page{font-size:20px;}
	  #rightMain table{border:1px solid black;}
	  #rightMain table td{border:1px solid black;}
	  #rightMain table #tableTitle{height:50px;font-size:18px;}
	</style>
  </head>
  <body>
    <ul id="banner" class="clearfix">
	  <li class="fl"><a href="<?php echo $siteUrl;?>system/home">首页</a></li>
	  <li class="fl"><a href="<?php echo $siteUrl;?>system/users">用户管理</a></li>
	  <li class="fl"><a href="<?php echo $siteUrl;?>system/topic">段子管理</a></li>
	  <li class="fl"><a href="<?php echo $siteUrl;?>system/pic">趣图管理</a></li>
	  <li class="fl"><a href="<?php echo $siteUrl;?>system/sex">美图管理</a></li>
	</ul>
	<div id="main" class="clearfix">
	  <div class="fl" id="leftMain">
	    <ul id="quick">
		  <li><a href="<?php echo $siteUrl;?>system/users">所有用户</a></li>
		  <li><a href="<?php echo $siteUrl;?>system/users/add">添加用户</a></li>
		  <li><a href="<?php echo $siteUrl;?>system/users/find">查询用户</a></li>
		</ul>
	  </div>
	  <div class="fr" id="rightMain">
	    <h2>用户列表</h2>
	    <table class="tac">
		  <tr id="tableTitle">
		    <td width="50">UID</td>
		    <td width="100">用户名</td>
			<td width="170">Email</td>
			<td width="100">注册时间</td>
			<td width="100">最近登录</td>
			<td width="140">注册IP</td>
			<td width="140">最近IP</td>
		  </tr>
	      <?php foreach($arrUsers as $key=>$item){?>
			<td><?php echo $item['uid'];?></td>
			<td><?php echo $item['username'];?></td>
			<td><?php echo $item['email'];?></td>
			<td><?php echo date('Y-m-d h:i:s',$item['addtime']);?></td>
			<td><?php echo date('Y-m-d h:i:s',$item['uptime']);?></td>
			<td><?php echo long2ip($item['regip']);?></td>
			<td><?php echo long2ip($item['logip']);?></td>
		  </tr>
		  <?php }?>
		</table>
		<div class="page"><?php echo $page;?></div>
	  </div>
	</div>
  </body>
</html>