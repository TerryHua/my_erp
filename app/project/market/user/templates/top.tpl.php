<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>联盟管理后台</title>
<link href="<?php echo HIGHPHP_WWW_HOST; ?>css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="header">
	<div id="top">
     <span>欢迎您，<?php echo $user_name; ?>，[<?php if (isset($role_list[$role_id])) {
     echo $role_list[$role_id]; } else {
     	echo '普通账户';
     }?>]
     <a href="javascript:void();"   onclick="parent.location.href='<?php echo HIGHPHP_WWW_HOST; ?>index/loginout.html'" >
     退出系统
     </a>
     </span>
   <a href="javascript:void();"   onclick="parent.location.href='<?php echo HIGHPHP_WWW_HOST; ?>user/index.html'" >
    <img src="<?php echo HIGHPHP_WWW_HOST; ?>images/logo.png" width="354" height="34" />
    </a>   
    </div>
    
   <div class="cboth"></div>
   </div>
    </div>
</div>
</body>
</html>
