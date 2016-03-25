<?php include framework::temp('market/header'); ?>
  


<script type="text/javascript" >

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
	            } else {
	                alert(json.message);
	                $('#applyForm').resetForm();
	            }
	            $('.submit').attr('disabled',false);
	            location.href = "#";
	        }
	    });
}


</script>
 


<body>
<div id="menu"> 
    <div class="menu_l">当前位置：<a href="#">系统管理</a> > <a href="#">密码修改</a></div> 
    <div class="menu_r">
    <a href="<?php echo HIGHPHP_WWW_HOST; ?>user/right.html" target="showmain"><img src="<?php echo HIGHPHP_WWW_HOST; ?>images/top_menu02.png" width="22" height="16" />首页</a>
    <a href="Javascript:window.history.go(-1)"><img src="<?php echo HIGHPHP_WWW_HOST; ?>images/top_menu03.png" width="22" height="16" />后退</a> 
    <a href="<?php echo $_SERVER['REQUEST_URI'];?>"  target="showmain"><img src="<?php echo HIGHPHP_WWW_HOST; ?>images/top_menu05.png" width="22" height="16" />刷新</a>
   
    </div>     
</div>

  <div class="main_r">
    <div class="l_title">

    </div>
    
      <div class="cboth"></div>
 
  </div>
  <div class="main_r1">
    <div class="sheet2">
	<form action="<?php echo HIGHPHP_WWW_HOST;?>account/changepwd.html" id="applyForm" method="post" >
      <table  border="0" cellspacing="0" cellpadding="0" class="sum_table3" id="table1" style="width:60%;">
       
			 
		<tr>   
         	<td class="td_left_title">后台用户名：</td> 
			<td class="td_right_input"> <?php echo $user_name; ?></td>        
        </tr> 
			
		<tr>   
          <td class="td_left_title">用户密码：</td>
			<td class="td_right_input"><input type="password" name="user_pwd" value="" class="input1" /></td> 
        </tr> 
		 
		<tr>
			<td class="td_left_title">新密码：</td>			
			<td class="td_right_input"><input type="password" name="user_pwd_new" value="" class="input1" /></td> 
		</tr>   
        
        <tr>
			<td class="td_left_title">重复密码：</td>			
			<td class="td_right_input"><input type="password" name="user_pwdnew_re" value="" class="input1" /></td> 
		</tr>   
     	<tr>
			<td colspan="2"><input type="button" value="确认修改" class="search_btn" onClick="submitEditForm();"  /></td>
		</tr>	
		
	  </table> 
    </form>

    </div>
    <div class="cboth"></div>
  </div>
<?php include framework::temp('market/footer'); ?>