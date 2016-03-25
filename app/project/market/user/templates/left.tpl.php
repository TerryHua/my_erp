<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>联盟管理后台</title>

<script type="text/javascript" src="<?php echo HIGHPHP_WWW_HOST; ?>js/jquery/jquery-1.7.2.js"></script>  
<script type="text/javascript">
 
var menu_array = new Array();
<?php  $i = 0; 
	foreach ($menu_list as $menu) { ?>
	menu_array[<?php echo $i?>] = <?php echo $menu['id']; ?>;
<?php 
	$i++;
}?>

function showsubmenu(sid)
{


	for (var i = 0; i < menu_array.length; i++) {

	    whichEl = '#submenu' + menu_array[i];
	   
		  if (menu_array[i] == sid ) {
			  $(whichEl).show('fast');
		  } else {
			  $(whichEl).hide('fast');
		  } 
	}
}
</script>

<link href="<?php echo HIGHPHP_WWW_HOST; ?>css/style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background:#516a7f;
}
-->
</style>
</head>
<body onload="showsubmenu(menu_array[0]);">
  <div style="margin:0 auto;text-align:center;height:27px;background-color:#283842;line-height:27px;"> 
	
   <img src="<?php echo HIGHPHP_WWW_HOST; ?>images/top_menu01.png" style="margin:5px 0;"  /> 
	</div>
<div id="nav">
	 <ul>
	 	<?php foreach ($menu_list as $pkey => $menu) {?>
	 	<li ><a href="javascript:void(0);" onclick="showsubmenu(<?php echo $menu['id'];?>);" ><?php echo $menu['name'];?></a>
	 	
	 	 	<ul id="submenu<?php echo $menu['id'];?>" style="display:none;">
	 	 	<?php foreach ($menu['child_menu'] as $ckey => $child) {?>
	 	 	<li><a href="<?php echo HIGHPHP_WWW_HOST; ?><?php echo $child['module']; ?>/<?php echo $child['action'];?>.html" 
	 	 	 target="showmain" ><?php echo $child['name']?></a></li> 
	 	 	<?php }?>
	 		</ul>
	 		</li>
	 	<?php }?>  
            </ul>
</div>
</body>
</html>
