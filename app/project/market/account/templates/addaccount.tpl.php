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
 

  <div class="main_r">
    <div class="l_title">

    </div>
    
      <div class="cboth"></div>
 
  </div>
  <div class="main_r1">
    <div class="sheet2">
	<form action="<?php echo HIGHPHP_WWW_HOST;?>account/addaccount.html" id="applyForm" method="post" >
      <table  border="0" cellspacing="0" cellpadding="0" class="sum_table3" id="table1" style="width:100%;">
       
			
		
		<tr>   
          <td class="td_left_title">后台用户名：</td>
			<td class="td_right_input"><input type="text" name="user_name" value="" class="input1" /></td>
        
        </tr> 
			
		<tr>   
          <td class="td_left_title">用户密码：</td>
			<td class="td_right_input"><input type="password" name="user_pwd" value="" class="input1" /></td> 
        </tr> 
		<tr>   
          <td class="td_left_title">重复密码：</td>
			<td class="td_right_input"><input type="password" name="user_pwd_re" value="" class="input1" /></td> 
        </tr> 
		<tr>
			<td class="td_left_title">用户角色：</td>
			<td class="td_right_input">
				<select name="role_id" class="input1"   >
				<option value="" >请选择</option>
				<?php foreach ($role_list as $key => $row) { ?>
				
				<option value="<?php echo $key; ?>"><?php echo $row; ?></option>
				<?php } ?>
				</select>
			</td>
		</tr>   
          
           <tr>
			<td class="td_left_title">备注：</td>
			<td class="td_right_input">
				 <textarea rows="3" cols="30" name="remark"></textarea>
			</td>
		</tr>  
         
		<tr>
			<td colspan="2">
			<input type="button" value="确认添加" class="search_btn" onClick="submitEditForm();"  />
			<input type="button" value="关闭" onclick="WinOpenclose();" class="search_btn" /></td>
		</tr>	
		
	  </table> 
    

    </div>
    <div class="cboth"></div>
  </div>
<?php include framework::temp('market/footer'); ?>