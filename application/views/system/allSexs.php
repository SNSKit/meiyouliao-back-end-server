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
	  #rightMain table td img{width:300px;}
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
	  <!--<li>首页</li>-->
	</ul>
	<div id="main" class="clearfix">
	  <div class="fl" id="leftMain">
	    <ul id="quick">
		  <li><a href="<?php echo $siteUrl;?>system/sex/">所有美图</a></li>
		  <li><a href="<?php echo $siteUrl;?>system/sex/add">添加美图</a></li>
		</ul>
	  </div>
	  <div class="fr" id="rightMain">
	    <h2>趣图列表</h2>
	    <table class="tac">
		  <tr id="tableTitle">
		    <td width="50">id</td>
			<td width="430">内容</td>
			<td width="200">用户</td>
			<td width="100">发表时间</td>
			<td width="100">操作</td>
		  </tr>
	      <?php foreach($topics as $key=>$item){?>
			<td><?php echo $item['sex_id'];?></td>
			<td>
			  <div><img src="<?php echo $item['img_url'];?>" /></div>
			  <div><?php echo $item['content'];?></div>
			</td>
			<td>uid:<?php echo $item['user_id'];?><br />nickname:<?php echo $item['nickname'];?></td>
			<td><?php echo date('Y-m-d h:i:s',$item['create_time']);?></td>
			<td>
			  <a href="<?php echo $siteUrl;?>system/sex/edit/<?php echo $item['sex_id'];?>">查看</a>
			  <a href="#">删除</a>
			</td>
		  </tr>
		  <?php }?>
		</table>
		<div class="page"><?php echo $page;?></div>
	  </div>
	</div>
  </body>
</html>