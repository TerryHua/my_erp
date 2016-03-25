<?php include framework::temp('market/header'); ?>
  
  

<body>


<div id="menu"> 
    <div class="menu_l">当前位置：<a href="#">系统管理</a> > <a href="#">权限列表</a></div> 
    <div class="menu_r">
     
    <a href="javascript:void(0)" onclick="WinOpen('<?php echo HIGHPHP_WWW_HOST; ?>account/addpower.html')"><img src="<?php echo HIGHPHP_WWW_HOST; ?>images/menu_add.png" />添加权限</a>
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
      <table  border="0" cellspacing="0" cellpadding="0" class="sum_table2" id="table1">
       
			
		
		<tr>   
          <th>权限名称</th> 
          <th>控制器</th>
          <th>方法名</th>
          <th>操作</th> 
        
        </tr> 
		<?php foreach ($rs['list'] as $row) {?>
		  <tr>
			<td><?php echo $row['name'] ?></td>
	 
			<td><?php echo $row['module']; ?></td>
			<td><?php echo $row['action']; ?></td>
			
			<td> 
				<a href="javascript:void(0)" onclick="WinOpen('<?php echo HIGHPHP_WWW_HOST; ?>account/editpower?id=<?php echo $row['id']; ?>')">修改</a>
				<a href="<?php echo HIGHPHP_WWW_HOST; ?>account/delpower?id=<?php echo  $row['id']; ?>" onClick="if(!confirm('确定删除?'))return false;" >删除</a></td>
		  </tr>
		 
		
		<?php } ?>
	  </table> 
     <div class="page">
      <div class="page_l"> </div>
      <div class="page_r"> <?php echo $rs['html']; ?></div>
      <div class="cboth"></div>
      </div>

    </div>
    <div class="cboth"></div>
  </div>
 
<?php include framework::temp('market/footer'); ?>
