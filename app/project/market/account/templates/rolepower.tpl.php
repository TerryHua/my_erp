<?php include framework::temp('market/header'); ?>
  
 
<script language="javascript">
 
function selectAll(obj){
    
	   if($('.'+obj).is(':checked')){
			$('.check_class_'+obj).attr('checked',true);
	   }
	   else{
			$('.check_class_'+obj).attr('checked',false); 
			
	   }

}

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

<div id="menu"> 
    <div class="menu_l">当前位置：<a href="#">系统管理</a> > <a href="#">角色权限</a></div> 
    <div class="menu_r">
     
    <a href="<?php echo HIGHPHP_WWW_HOST; ?>user/right.html" target="showmain"><img src="<?php echo HIGHPHP_WWW_HOST; ?>images/top_menu02.png" width="22" height="16" />首页</a>
    <a href="Javascript:window.history.go(-1)"><img src="<?php echo HIGHPHP_WWW_HOST; ?>images/top_menu03.png" width="22" height="16" />后退</a> 
    <a href="<?php echo $_SERVER['REQUEST_URI'];?>"  target="showmain"><img src="<?php echo HIGHPHP_WWW_HOST; ?>images/top_menu05.png" width="22" height="16" />刷新</a>
   
    </div>     
</div>




  <div class="main_r">
   
    
      <div class="cboth"></div>
 
  </div>
  <div class="main_r1">
    <div class="sheet2">

	<form action="<?php echo HIGHPHP_WWW_HOST;?>account/rolepoweredit.html" id="applyForm" method="post" >
      <table  border="0" cellspacing="0" cellpadding="0" class="sum_table3" id="table1" >
       
			
		
		<tr>   
          <th colspan="2" >选择您要分配权限的角色</th>        
        </tr> 
		
		  <tr>
			<td colspan="2">		
			
		<?php foreach ($role_list as $key => $row) {?> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;			<a href="<?php echo HIGHPHP_WWW_HOST; ?>account/rolepower?id=<?php echo $key; ?>"
	<?php if ($key == $role_id) { ?>style="color:#ff6600;"<?php } ?>
><?php echo $row; ?></a>
			<?php } ?>
			<input type="hidden" value="<?php echo $role_id; ?>" name="role_id" />
			</td>  			
		  </tr>
		  

		<?php if (isset($role_id) && $role_id!='') {?>
		  <?php foreach($power_list as $key => $row) { ?> 
			<tr>
				<td colspan="2" style="text-align:left;">
				<input type="checkbox" onClick="selectAll('<?php echo $key; ?>')" class="<?php echo $key; ?>" /><?php echo $key; ?>模块
				</td>
			</tr>
			<tr>  
			<td class="input_td" colspan="2">
			<?php foreach ($row as $child_row) { ?>
				<div style="width:200px;float:left; margin-left:50px;">
				<input type="checkbox" name="power_id[]" value="<?php echo $child_row['id']; ?>"  class="check_class_<?php echo $key; ?>"
				<?php if (isset($role_power[$child_row['id']]) && 1== $role_power[$child_row['id']]) {
					echo 'checked="checked"'; } ?> 
				  /> <span title="<?php echo $child_row['remark']; ?>" ><?php echo $child_row['name']; ?></span>
				  </div>
			<?php } ?>
			</td> 
			</tr>
		  <?php } ?>
		  <tr>
			<td colspan="2"><input type="button" value="确认提交" class="search_btn" onclick="submitEditForm();"  /></td>
		</tr>	
		  <?php } ?>
		 
	  </table> 

   	</form>

    </div>
    <div class="cboth"></div>
  </div>
 
<?php include framework::temp('market/footer'); ?>
