<?php include framework::temp('market/header'); ?>
  
 <script type="text/javascript">

	$(document).ready(function(){	  
		$('.sum_table1').stickyTableHeaders();
	}); 
	
</script>

<body>
<div id="menu"> 
    <div class="menu_l">当前位置：<a href="#">信息管理</a> > <a href="#">页面信息</a></div> 
    <div class="menu_r">
    <a href="javascript:void(0)" onclick="WinOpen('<?php echo HIGHPHP_WWW_HOST; ?>info/pageadd.html')"><img src="<?php echo HIGHPHP_WWW_HOST; ?>images/menu_add.png" />添加页面信息</a>
   
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
      <table  border="0" cellspacing="0" cellpadding="0" class="sum_table1" id="table1">
      	<thead>
			<tr> 
			<th>页面标题</th>
      <th>页面code</th>
			<th>关键词</th>
			<th>页面描述</th>
      <th>操作</th>  
  </tr>
  
        </thead>
        <?php if ($data) { ?>
        
       
        
        <tbody>  		
		<?php foreach ($data['list'] as $k=>$v) {?>
		<tr class="alignr">
    
			<td><?php echo $v['title'];?></td>
      <td><?php echo $v['page_code']; ?></td>
      <td><?php echo $v['keywords'];?></td>     
      <td><?php echo $v['description'];?></td>     
      <td>
      <a href="javascript:void(0)" onclick="WinOpen('<?php echo HIGHPHP_WWW_HOST; ?>info/pageedit?id=<?php echo $v['id']; ?>')">修改</a>
      <a href="<?php echo HIGHPHP_WWW_HOST; ?>info/pagedel?id=<?php echo $v['id']; ?>"   onclick="if(!confirm('确定删除?'))return false;">删除</a>
        </td>     
		</tr>

         <?php } ?>
	 
 
        
       </tbody> 
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

  
<?php include framework::temp('market/footer'); ?>  