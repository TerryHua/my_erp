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
            
	<form action="<?php echo HIGHPHP_WWW_HOST;?>info/pageadd.html" id="applyForm" method="post" >
      <table  border="0" cellspacing="0" cellpadding="0" class="sum_table3" id="table1">
        <tr>
          <th colspan="2">添加页面信息 </th>
        </tr>
		<tr>
			<td class="td_left_title">页面标题：</td>
			<td class="td_right_input"><input type="text" name="title" value="" style="width:200px;" />
			</td>
		</tr>
		<tr>
			<td class="td_left_title">关键词：</td>		
			<td class="td_right_input">
			<textarea style="width:200px;height:50px;" name="keywords" id="keywords" ></textarea>
			</td>
		</tr>
		<tr>
			<td class="td_left_title">页面描述：</td>		
			<td class="td_right_input">
				<textarea style="width:200px;height:50px;" name="description" id="description" ></textarea> 
			</td>
		</tr>  
		
	 
		<tr>
			<td  class="td_left_title">页面编码：</td>		
			<td class="td_right_input">
			<input tyle="width:200px;"  type="text"  name="page_code" id="page_code" >
			</td>
		</tr>
		

		<tr>
			<td  class="td_left_title">页面内容：</td>
			<td class="td_right_input">
			   <textarea name="content" style="width:200px;height:80px;" ></textarea>
			</td>
		</tr>
		 
		<tr>
			<td colspan="2">
			<input type="button" value="确认添加" class="search_btn" onClick="submitEditForm();"  />
			
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