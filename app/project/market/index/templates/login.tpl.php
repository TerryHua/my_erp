<?php include framework::temp('market/header'); ?>
 

<script type="text/javascript" >

$(document).ready(function(){
	$('#user_name').focus();
});

function submitEditForm(){
 
	 $('#applyForm').ajaxSubmit({
	        dataType:'json',
	        async: false,
	        success:function(json) { 
	            var html = '';
	            if(typeof(json.error) != 'undefined'){
	                $.each(json.error,function(key,item){
	                    html += item+"\n";
	                })
	               alert(html); 
		           window.location.reload()
	               
	            } else {
	                window.location.href = "<?php echo HIGHPHP_WWW_HOST; ?>user/index";
	            }
	            $('.submit').attr('disabled',false);
	           
				// location.href = "#";
	        }
	    });
}
 
function switchImg(imgObj) {
 
	var num = 	new Date().getTime();
	var rand = Math.round(Math.random() * 10000);
	num = num + rand;
    imgObj.src = "<?php echo HIGHPHP_WWW_HOST; ?>index/authcode.html?tag=" + num;
    document.getElementById('securityCode').focus();
}
 
if (top.location != self.location){       
    top.location=self.location;       
}
</script> 

<body id="login_back">
<div class="login_main">
<form method="post" action="<?php echo HIGHPHP_WWW_HOST; ?>index/login.html" id="applyForm" onsubmit="return false;">
<?php if ($msg != '') { ?>
	<div style="color:red;margin-left:85px;text-align:center;" ><?php echo $msg; ?></div>
<?php }?>
<table style="margin:0 auto;">
<tr>
<td>
<div class="login_int">用户名：</div>
</td>
<td>
<div class="login_con"><input id="user_name" name="user_name" type="text" onfocus="this.select()" class="in_input"/></div>
<div class="cboth"></div>
</td>
</tr>
<tr>
<td>
<div class="login_int">密&nbsp;&nbsp;码：</div>
</td>
<td>
<div class="login_con"><input name="user_pwd" type="password"  onfocus="this.select()" class="in_input" /></div>
<div class="cboth"></div>
</td>
</tr>
<tr>
<td>
<div class="login_int">验证码：</div>
</td>
<td>
<div class="login_con"><input name="auth_code" type="text"  onfocus="this.select()" class="in_input1" id="securityCode"/>
 <span>
 <img  onclick="switchImg(this);"   title="点击图片更换验证码" alt="点击图片更换验证码"
	  style="cursor:pointer;" src="<?php echo HIGHPHP_WWW_HOST; ?>index/authcode.html" id="sc">
 
 </span>
 </div>
<div class="cboth"></div>
</td>
</tr>
<tr>
<td>
<div class="login_int"></div>
</td>
<td>
<div class="login_con"><input type="submit" class="login_btn" value="" onclick="submitEditForm();"  /></div>
<div class="cboth"></div>
</td>
</tr>
</table>
</form>

</div>


</body>
</html>
