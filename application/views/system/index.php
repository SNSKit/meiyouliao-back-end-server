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
	  form{border:1px solid black;width:450px;padding:10px;margin:50px auto 0px;}
	  form p{margin-top:10px;}
	  form textarea{resize:none;width:450px;height:240px;}
	  form input{width:200px;height:30px;line-height:30px;}
	</style>
  </head>
  <body>
    <form method="post" action="<?php echo $siteUrl;?>index.php/system/panel/add">
	  <p>
	    <span>资源名称:</span>
		<input type="text" name="resource_name" />
	  </p>
	  <p>
	    <span>资源内容:</span>
		<input type="text" name="resource_url" />
	  </p>	  
	  <p>
	    <span>资源简介:</span>
		<textarea name="content"></textarea>
	  </p>
	  <p>
	    <span>资源售价:</span>
		<input type="text" name="price" value=0 />
	  </p>
	  <p>
	    <span>资源标签:</span>
		<input type="text" name="tag" placeholder="多个标签使用空格隔开" />
	  </p>	  
	  <p>
	    <input type="submit" value="增加" />
	  </p>
	</form>
  </body>
</html>