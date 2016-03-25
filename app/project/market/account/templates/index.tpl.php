<?php include framework::temp('market/header'); ?>
  
  
<body>

<div id="menu"> 
    <div class="menu_l">当前位置：<a href="#">系统管理</a> > <a href="#">账号管理</a></div> 
    <div class="menu_r">
     
    <a href="javascript:void(0)" onclick="WinOpen('<?php echo HIGHPHP_WWW_HOST; ?>account/addaccount.html')"><img src="<?php echo HIGHPHP_WWW_HOST; ?>images/menu_add.png" />添加账号</a>
    <a href="<?php echo HIGHPHP_WWW_HOST; ?>user/right.html" target="showmain"><img src="<?php echo HIGHPHP_WWW_HOST; ?>images/top_menu02.png" width="22" height="16" />首页</a>
    <a href="Javascript:window.history.go(-1)"><img src="<?php echo HIGHPHP_WWW_HOST; ?>images/top_menu03.png" width="22" height="16" />后退</a> 
    <a href="<?php echo $_SERVER['REQUEST_URI'];?>"  target="showmain"><img src="<?php echo HIGHPHP_WWW_HOST; ?>images/top_menu05.png" width="22" height="16" />刷新</a>
   
    </div>     
</div>

  <div class="main_r">
    <div class="l_title">
    	<form action="" method="get">
    	<input type="hidden" name="act" value="search" />
    	     用户名：<input type="text" id="user_name" name="user_name"   value="<?php echo $condition['user_name']; ?>" 
						     class="input1"  />
		 
						    					
						      <input type="submit" class="search_btn" value="查&nbsp;询" />
    	
      </form>
    </div>
    
      <div class="cboth"></div>
 
  </div>
  <div class="main_r1">
    <div class="sheet2">
      <table  border="0" cellspacing="0" cellpadding="0" class="sum_table2" id="table1">
       
			
		
		<tr>  
          <th>用户ID号</th>
          <th>用户账号</th> 
          <th>用户类型</th> 
          <th>操作</th> 
        
        </tr> 
		<?php foreach ($rs['list'] as $row) {?>
		  <tr>
			<td><?php echo $row['id'] ?></td>
			<td><?php echo $row['username']; ?></td> 
			<td><?php  
            if($row['role_id'] == 3) {
              echo '管理员';
            } else if ($row['role_id'] == 1) {
              echo '超级管理员';
            } else {
                echo '未知';
            } 
      ?>
      </td>
			<td> 
			<a href="javascript:void(0)" onclick="WinOpen('<?php echo HIGHPHP_WWW_HOST; ?>account/editaccount?id=<?php echo $row['id']; ?>')">修改</a>
			<a href="<?php echo HIGHPHP_WWW_HOST; ?>account/delaccount?id=<?php echo $row['id']; ?>"   onclick="if(!confirm('确定删除?'))return false;">删除</a>
			<a href="<?php echo HIGHPHP_WWW_HOST; ?>account/userpower?id=<?php echo $row['id']; ?>">权限</a>
			</td>
		 	 
		  </tr>
		<?php } ?> 
	  </table> 
      <div class="page">
      <div class="page_l"> </div>
      <div class="page_r"><?php echo $rs['html']; ?></div>
      <div class="cboth"></div>
      </div>

    </div>
    <div class="cboth"></div>
  </div>
</body>
