<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>联盟管理后台</title>
<link href="<?php echo HIGHPHP_WWW_HOST; ?>css/style.css" rel="stylesheet" type="text/css" />
</head>


<body>
<div id="menu"> 
    <div class="menu_l">当前位置：<a href="#">管理首页</a> > <a href="#">系统首页</a></div> 
    <div class="menu_r">
    <a href="<?php echo HIGHPHP_WWW_HOST; ?>user/right.html" target="showmain"><img src="<?php echo HIGHPHP_WWW_HOST; ?>images/top_menu02.png" width="22" height="16" />首页</a>
    <a href="<?php echo $_SERVER['HTTP_REFERER']?>"  target="showmain"><img src="<?php echo HIGHPHP_WWW_HOST; ?>images/top_menu03.png" width="22" height="16" />后退</a> 
    <a href="<?php echo $_SERVER['REQUEST_URI'];?>"  target="showmain"><img src="<?php echo HIGHPHP_WWW_HOST; ?>images/top_menu05.png" width="22" height="16" />刷新</a>
   
    </div>     
</div>
 
  <div class="main_r1">
     &nbsp;&nbsp;<?php echo $_SESSION['user_name']; ?>,欢迎使用有信联盟平台
    <div class="cboth"></div>
  </div>
</body>
