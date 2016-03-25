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
            
	<form action="<?php echo HIGHPHP_WWW_HOST;?>info/pageedit.html" id="applyForm" method="post" >
      <table  border="0" cellspacing="0" cellpadding="0" class="sum_table3" id="table1">
        <tr>
          <th colspan="2">修改页面信息 </th>
        </tr>
		<tr>
			<td class="td_left_title">页面标题：</td>
			<td class="td_right_input"><input type="text" name="title" value="<?php echo $edit_row['title']; ?>" style="width:200px;" />
			</td> 
			    
		</tr>
		<tr>
			<td class="td_left_title">关键词：</td>		
			<td class="td_right_input">
			<textarea style="width:200px;height:50px;" name="keywords" id="keywords" ><?php echo $edit_row['keywords']; ?></textarea>
			</td>
		</tr>
		<tr>
			<td class="td_left_title">页面描述：</td>		
			<td class="td_right_input">
			<textarea style="width:200px;height:50px;" name="description" id="description" ><?php echo $edit_row['description']; ?></textarea>
			</td>
		</tr>		
		<tr>
			<td class="td_left_title">页面编码：</td>		
			<td class="td_right_input">
			<input type="hidden" name="edit_page_code" value="<?php echo $edit_row['page_code']; ?>" />
			<input type="text" style="width:200px;" name="page_code" id="page_code" value="<?php echo $edit_row['page_code']; ?>">
			</td>
		</tr>  
		
	  <tr>
			<td  class="td_left_title">页面内容：</td>
			<td class="td_right_input">
			   <textarea name="content" style="width:200px;height:80px;" ><?php echo $edit_row['content']; ?></textarea>
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