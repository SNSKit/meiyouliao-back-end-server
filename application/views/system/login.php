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
	  form{border:1px solid black;width:450px;padding:10px;margin:200px auto 0px;}
	  form p{margin-top:10px;}
	  form input{width:200px;height:30px;line-height:30px;}
	</style>
  </head>
  <body>
    <form method="post" class="tac" action="<?php echo $siteUrl;?>index.php/system/ilogin/login">
	  <p>
	    <span>UserName : </span>
		<input type="text" name="user" placeholder="支持Email/用户名两种" />
	  </p>
	  <p>
	    <span>PassWord &nbsp;: </span>
		<input type="password" name="password" />
	  </p>	  
	  <p>
	    <input type="submit" value="Login" />
	  </p>
	</form>
  </body>
</html>