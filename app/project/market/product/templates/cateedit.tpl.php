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
	            }
	            $('.submit').attr('disabled',false);
	            location.href = "#";
	        }
	    });
}

</script>

<body>
<div class="wrap">

 
 <div class="main_r">
    <div class="l_title">
 
    </div>
</div>

 <div class="main_r1">

    <div class="sheet2">
            
	<form action="<?php echo HIGHPHP_WWW_HOST;?>product/cateedit.html" id="applyForm" method="post" >
      <table  border="0" cellspacing="0" cellpadding="0" class="sum_table3" id="table1">
        <tr>
          <th colspan="2">添加产品分类</th>
        </tr>
		<tr>
			<td class="td_left_title">中文名称：</td>
			<td class="td_right_input">
				<input type="hidden" name="id" value="<?php echo $edit_row['id']; ?>" />
				<input type="text" name="cn_name" value="<?php echo $edit_row['cn_name']; ?>" />
			</td>
		</tr>
		<tr>
			<td class="td_left_title">英文名称：</td>		
			<td class="td_right_input">
				<input type="text" name="en_name" value="<?php echo $edit_row['en_name']; ?>" /> 
			</td>
		</tr>
		<tr>
			<td class="td_left_title">排序：</td>		
			<td class="td_right_input"> 
				<input type="text" name="orderby" value="<?php echo $edit_row['orderby']; ?>"  />  
			</td>
		</tr>  
		
	 
		<tr>
			<td  class="td_left_title">父类：</td>		
			<td class="td_right_input">
  				<select name="parent_id" >
				<option value="0" >无父类</option >
  				<?php foreach ($parent_list as $v) { ?>
  				<option value="<?php echo $v['id']; ?>"
  				<?php if ($edit_row['parent_id'] == $v['id']) { echo 'selected'; } ?>
  				 ><?php echo $v['en_name'].' '.$v['cn_name']; ?></option>
  				<?php } ?> 
				</select>  
			</td>
		</tr>
		
 
		<tr>
			<td  class="td_left_title">状态：</td>
			<td class="td_right_input">
			    <select name="status" >
			    <option value="0" <?php if ($edit_row['status'] === '0') { echo 'selected';} ?> >不可用</option >
				<option value="1" <?php if ($edit_row['status'] === '1') { echo 'selected';} ?>>可用</option >  
				 
				</select> 
			</td>
		</tr>
		 
		<tr>
			<td colspan="2">
			<input type="button" value="确认修改" class="search_btn" onClick="submitEditForm();"  />
			
				<input type="button" value="关闭" onClick="WinOpenclose();" class="search_btn" />
			</td>

		</tr>
 
      </table> 

	  </form>
 

    </div>
    <div class="cboth"></div>
         <div class="page">
      <div class="page_l"></div>
		<div class="page_r"></div>
		<div class="cboth"></div>
      </div>
  </div>
  </div>

  

<?php include framework::temp('market/footer'); ?>