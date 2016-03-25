<?php include framework::temp('market/header'); ?>


<body>

<div id="menu"> 
    <div class="menu_l">当前位置：<a href="#">信息管理</a> > <a href="#">客户留言</a></div> 
    <div class="menu_r">
     
     <a href="<?php echo HIGHPHP_WWW_HOST; ?>user/right.html" target="showmain"><img src="<?php echo HIGHPHP_WWW_HOST; ?>images/top_menu02.png" width="22" height="16" />首页</a>
    <a href="Javascript:window.history.go(-1)"><img src="<?php echo HIGHPHP_WWW_HOST; ?>images/top_menu03.png" width="22" height="16" />后退</a> 
    <a href="<?php echo $_SERVER['REQUEST_URI'];?>"  target="showmain"><img src="<?php echo HIGHPHP_WWW_HOST; ?>images/top_menu05.png" width="22" height="16" />刷新</a>
   
    </div>     
</div>

  <div class="main_r">
    <div class="l_title">
    	<form action="" method="get">
    	<input type="hidden" name="act" value="search" />
    	     
		   查询日期：<input type="text" id="start_date" name="start_date" size="24" value="<?php echo $condition['start_date']; ?>" 
						     class="dateinput"  onfocus="WdatePicker({onpicked:function(){end_date.focus();},maxDate:'#F{$dp.$D(\'end_date\')}'})"/>
						     至 <input type="text" id="end_date" name="end_date" size="24" value="<?php echo $condition['end_date']; ?>" 
						        class="dateinput" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'start_date\')}'})" />

						      <input type="submit" class="search_btn" value="查&nbsp;询" />
    	
      </form>
    </div>
    
      <div class="cboth"></div>
 
  </div>
  <div class="main_r1">
    <div class="sheet2">
      <table  border="0" cellspacing="0" cellpadding="0" class="sum_table2" id="table1">
       
			
		
		<tr>  
          <th>联系人姓名</th>
          <th>邮箱</th>
          <th>电话</th>
          <th>传真</th>
          <th>地址</th> 
		  <th>消息内容</th>
		  <th>时间</th> 
		  <th>操作</th>
        
        </tr> 
		<?php foreach ($data['list'] as $row) {?>
		  <tr>
			<td><?php echo $row['name']; ?></td>			 
			<td><?php echo $row['email']; ?></td>
			<td><?php echo $row['telephone']; ?></td>
			<td><?php echo $row['fax']; ?></td>
			<td><?php echo $row['address']; ?></td>
			<td><?php echo $row['message']; ?></td>
			<td><?php echo $row['add_time']; ?></td>    
			<td> 
				 
				<a href="<?php echo HIGHPHP_WWW_HOST; ?>feedback/delfeedback?id=<?php echo $row['id']; ?>"   onclick="if(!confirm('确定删除?'))return false;">删除</a>
				 		
			</td>
		 	 
		  </tr>
		<?php } ?> 
	  </table> 
      <div class="page">
      <div class="page_l"> </div>
      <div class="page_r"><?php echo $data['html']; ?></div>
      <div class="cboth"></div>
      </div>

    </div>
    <div class="cboth"></div>
  </div>
</body>
