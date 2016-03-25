<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ERP管理后台</title>
<link href="<?php echo HIGHPHP_WWW_HOST; ?>css/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo HIGHPHP_WWW_HOST; ?>js/My97DatePicker/WdatePicker.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo HIGHPHP_WWW_HOST; ?>js/jquery/jquery-1.7.2.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo HIGHPHP_WWW_HOST; ?>js/jquery/jquery.form.js"></script> 
<script language="javascript" type="text/javascript" src="<?php echo HIGHPHP_WWW_HOST; ?>js/jquery/jquery.stickytableheaders.js"></script>

<script language="javascript">
 
$(document).ready(function(){  
	
	 
    $("tr:odd").addClass("");  
    $("tr:even").addClass("td_even");


}); 

function WinOpen(url,width,height) {

       if (width == undefined) {           
           width = 500;
       };
       if (height == undefined) {
           height = 400;
       }
       
       var l = (screen.width - width) / 2; 
       var t = (screen.height - height) / 2; 
       var s = 'width=' + width + ', height=' + height + ', top=' + t + ', left=' + l; 

       s += ', toolbar=no, scrollbars=yes, menubar=no, location=no, resizable=no,location=no'; 

       window.open(url, '', s); 
}

function WinOpenclose() {

   window.opener.location.reload();window.close();

}
 
</script>
</head>