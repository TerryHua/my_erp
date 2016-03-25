<?php include framework::temp('market/header'); ?>


<body>

<div id="menu"> 
    <div class="menu_l">当前位置：<a href="#">产品管理</a> > <a href="#">分类列表</a></div> 
    <div class="menu_r">

     <a href="javascript:void(0)" onclick="WinOpen('<?php echo HIGHPHP_WWW_HOST; ?>product/cateadd.html')"><img src="<?php echo HIGHPHP_WWW_HOST; ?>images/menu_add.png" />添加分类</a>
   
     <a href="<?php echo HIGHPHP_WWW_HOST; ?>user/right.html" target="showmain"><img src="<?php echo HIGHPHP_WWW_HOST; ?>images/top_menu02.png" width="22" height="16" />首页</a>
    <a href="Javascript:window.history.go(-1)"><img src="<?php echo HIGHPHP_WWW_HOST; ?>images/top_menu03.png" width="22" height="16" />后退</a> 
    <a href="<?php echo $_SERVER['REQUEST_URI'];?>"  target="showmain"><img src="<?php echo HIGHPHP_WWW_HOST; ?>images/top_menu05.png" width="22" height="16" />刷新</a>
   
    </div>     
</div>

  <div class="main_r">
    <div class="l_title">
    	<form action="" method="get">
    	<input type="hidden" name="act" value="search" />
    	    

      英文名：<input type="text" id="en_name" name="en_name"   value="<?php echo $condition['en_name']; ?>" 
                 class="input1"  />
		       状态：
      <select name="status" id="select"  class="s_select">
        <option value="" >全部</option>
        <option value="0" <?php if ($condition['status'] === '0') { echo 'selected'; } ?>>不可用</option>
        <option value="1"  <?php if ($condition['status'] == '1') { echo 'selected'; } ?> >可用</option>
      </select>

						      <input type="submit" class="search_btn" value="查&nbsp;询" />
    	
      </form>
    </div>
    
      <div class="cboth"></div>
 
  </div>
  <div class="main_r1">
    <div class="sheet2">
      <table  border="0" cellspacing="0" cellpadding="0" class="sum_table2" id="table1">
       
			
		
		<tr>  
      <th>中文名称</th>
      <th>英文名称</th> 
      <th>排序</th> 
      <th>状态</th>
		  <th>操作</th>        
    </tr> 

		<?php foreach ($rs['list'] as $row) {?>
		  <tr>
			<td><?php echo $row['cn_name']; ?></td>			 
			<td><?php echo $row['en_name']; ?></td>
      <td><?php echo $row['orderby']; ?></td>  
      <td><?php if ($row['status'] == '1') { echo '可用'; } else { echo '不可用';} ?>
			<td> 
				  <a href="javascript:void(0)" onclick="WinOpen('<?php echo HIGHPHP_WWW_HOST; ?>product/cateedit?id=<?php echo $row['id']; ?>')">修改</a>
    
				<a href="<?php echo HIGHPHP_WWW_HOST; ?>product/catedel?id=<?php echo $row['id']; ?>"   onclick="if(!confirm('确定删除?'))return false;">删除</a>
				 		
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
