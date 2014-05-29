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
	  #rightMain form{padding-left:10px;padding-top:10px;border:1px solid black;}
	  #rightMain form p{border-bottom:10px solid white;}
	  #rightMain form p span{width:80px;display:inline-block;}
	  #rightMain form p #username,#content{border:1px solid black;width:300px;height:30px;line-height:30px;display:inline-block;}
	  #rightMain form p #submit{border:1px solid black;background:white;width:120px;text-align:center;height:30px;line-height:30px;}
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
	    <h2>添加美图</h2>
	    <form method="post" enctype="multipart/form-data" action="<?php echo $siteUrl;?>system/sex/addDo">
		  <p>
		    <span>UID：</span>
			<input type="text" id="username" name="user_id" value=1 placeholder="请填写1-4之间任意一个user id" />
		  </p>
		  <p>
		    <span>描述&nbsp;：</span>
			<input type="text" id="content" placeholder="趣图一句话描述" name="content" />
		  </p>
		  <p>
		    <span>图片&nbsp;：</span>
			<input type="file" name="pic" />
		  </p>
		  <p><input id="submit" type="submit" value="添加" /></p>
		</form>
	  </div>
	</div>
  </body>
</html>