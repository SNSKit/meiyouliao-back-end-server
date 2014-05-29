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
	  #rightMain form{margin-bottom:10px;padding-left:10px;padding-top:10px;border:1px solid black;}
	  #rightMain form p{border-bottom:20px solid white;}
	  #rightMain form p span{width:auto;display:inline-block;}
	  #rightMain form p span img{width:30px;height:30px;}
	  #rightMain form p span input[type=text]{width:50px;}
	  #rightMain form p #username,#password,#email{border:1px solid black;width:300px;height:30px;line-height:30px;display:inline-block;}
	  #rightMain form p .submit{border:1px solid black;background:white;width:120px;text-align:center;height:30px;line-height:30px;}
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
		  <li><a href="<?php echo $siteUrl;?>system/topic/">所有段子</a></li>
		  <li><a href="<?php echo $siteUrl;?>system/topic/add">添加段子</a></li>
		  <li><a href="<?php echo $siteUrl;?>system/topic/find">查询段子</a></li>
		</ul>
	  </div>
	  <div class="fr" id="rightMain">
	    <h2>查看段子</h2>
	    <form method="post" action="<?php echo $siteUrl;?>system/topic/editDo">
		  <p>
		    <span>昵称：</span>
			<span><?php echo $nickname;?></span>
		  </p>
		  <p>
		    <span>头像：</span>
			<span><img src="<?php echo $avatar;?>" /></span>
		  </p>
		  <p>
		    <span>数据：</span>
		    <span>赞：<input type="text" name="num_praise" value="<?php echo $num_praise;?>" /></span>&nbsp;&nbsp;
			<span>踩：<input type="text" name="num_trample" value="<?php echo $num_trample;?>" /></span>&nbsp;&nbsp;
			<span>评：<?php echo $num_remark;?></span>
		  </p>
		  <p>
		    <span>内容：</span>
			<textarea rows="20" cols="40" name="content"><?php echo $content;?></textarea>
		  </p>
		  <p>
			<input type="hidden" name="topic_id" value="<?php echo $topic_id;?>" >
		    <input class="submit" type="submit" value="编辑段子" />
		  </p>
		</form>
	  </div>
	</div>
  </body>
</html>