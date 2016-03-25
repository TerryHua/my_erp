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
	<form action="<?php echo HIGHPHP_WWW_HOST;?>account/changesuper.html" id="applyForm" method="post" >
      <table  border="0" cellspacing="0" cellpadding="0" class="sum_table3" id="table1" style="width:100%;">
       
			
		
		<tr>   
          <td class="td_left_title">用户名称：</td>
			<td class="td_right_input"><?php echo $admin_info['username']; ?>
			<input type="hidden" value="<?php echo $admin_info['id']; ?>" name="old_super"  />
			</td>
        
        </tr> 
			
		<tr>   
			<td class="td_left_title">更换责任人：</td>
			<td class="td_right_input">
				<select name="new_super">
				<option value="">请选择责任人</option>
				<?php foreach ($sys_user as $key => $user) { ?>
				<option value="<?php echo $user['id']; ?>"><?php echo $user['username']; ?></option>
				<?php } ?>
			</select>
        </tr> 
		 
		<tr>
			<td class="td_left_title">联盟账户：</td>
			<td class="td_right_input">
				<?php foreach ($user_list as $key => $list) { ?>
				<div style="width:120px;float:left;">
				<input type="checkbox" value="<?php echo $list['id']; ?>" name="user_id[]" checked /><?php echo $list['username']; ?>
				</div>
				<?php } ?>
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