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
	  .clearfix:after{content:".";display:block;height:0;clear:both;visibility:hidden}
	  .clearfix{*+height:1%;}
	  .clear{clear:both;}
	  .tac{text-align:center;}
	  .tar{text-align:right;}
	  .tal{text-align:left;}
	  ul{list-style-type:none;}
	  #banner,#main{width:1000px;margin:20px auto 0px;}
	  #banner{font-size:20px;border-bottom:1px solid black;}
	  #banner li{margin-right:20px;}
	  #main{border-top:10px solid white;}
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
	  <span>您好，</span>
	  <span>用户：<?php echo $username;?>，</span>
	  <span>电邮：<?php echo $email;?>，</span>
      <span>IP：<?php echo $ip;?></span>
	  <h2>统计数据</h2>
	</div>
  </body>
</html>