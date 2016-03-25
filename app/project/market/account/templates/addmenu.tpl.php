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
	<form action="<?php echo HIGHPHP_WWW_HOST;?>account/addmenu.html" id="applyForm" method="post" >
      <table  border="0" cellspacing="0" cellpadding="0" class="sum_table3" id="table1" style="width:100%;">
        
		
		<tr>   
          <td class="td_left_title">菜单名称：</td>
			<td class="td_right_input"><input type="text" name="menu_name" value="" class="input1" /></td>
        
        </tr> 
			
		<tr>   
          <td class="td_left_title">控制器名称：</td>
			<td class="td_right_input"><input type="text" name="module" value="" class="input1" /></td> 
        </tr> 
		<tr>   
          <td class="td_left_title">方法名称：</td>
			<td class="td_right_input"><input type="text" name="action_name" value="" class="input1" /></td> 
        </tr> 
		<tr>
			<td class="td_left_title">父级菜单：</td>
			<td class="td_right_input">
				<select name="pid" class="input1" >
				<option value="0" >无</option>
				<?php foreach ($parent_menu as $row) { ?>
				
				<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
				<?php } ?>
				</select>
			</td>
		</tr>
		<tr>   
          <td class="td_left_title">排序（从小到大）：</td>
			<td class="td_right_input"><input type="text" name="order_num" value="99" class="input1" /></td> 
        </tr> 
		<tr>   
          <td class="td_left_title">状态：</td>
			<td class="td_right_input">
			<select name="menu_status" class="input1" >
			<option value="0">不可用</option>			
			<option value="1">可用</option>
			</select>
			</td> 
        </tr> 
		<tr>   
          <td class="td_left_title">备注：</td>
			<td class="td_right_input"> 
			<input type="text" name="remark" class="input1" />
			</td> 
        </tr> 
		<tr>
			<td colspan="2">
				<input type="button" value="确认添加" class="search_btn" onclick="submitEditForm();"  />
				<input type="button" value="关闭" onclick="WinOpenclose();" class="search_btn" />
			</td>
		</tr>	
		
	  </table> 
    

    </div>
    <div class="cboth"></div>
  </div>

<?php include framework::temp('market/footer'); ?>