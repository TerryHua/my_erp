<?php include framework::temp('market/header'); ?>


<body>

<div id="menu"> 
    <div class="menu_l">当前位置：<a href="#">产品管理</a> > <a href="#">产品列表</a></div> 
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
    	    
      产品id:<input type="text" id="id" name="id"   value="<?php echo $condition['id']; ?>" 
                 class="input1"  style="width:30px" />
      产品名称：<input type="text" id="product_name" name="product_name"   value="<?php echo $condition['product_name']; ?>" 
                 class="input1"  style="width:100px" />
      分类id:<input type="text" id="cate_id" name="cate_id"   value="<?php echo $condition['cate_id']; ?>" 
                 class="input1"  style="width:30px" />
      售价:<input type="text" id="start_price" name="start_price"   value="<?php echo $condition['start_price']; ?>" 
                 class="input1"  style="width:30px" />-<input type="text" id="end_price" name="end_price"   value="<?php echo $condition['end_price']; ?>" 
                 class="input1"  style="width:30px" />
      运费：<input type="text" id="start_shipfee" name="start_shipfee"   value="<?php echo $condition['start_shipfee']; ?>" 
                 class="input1"  style="width:30px" />-<input type="text" id="end_shipfee" name="end_shipfee"   value="<?php echo $condition['end_shipfee']; ?>" 
                 class="input1"  style="width:30px" />
      评论数: <input type="text" id="start_review_user" name="start_review_user"   value="<?php echo $condition['start_review_user']; ?>" 
                 class="input1"  style="width:30px" />-<input type="text" id="end_review_user" name="end_review_user"  
                  value="<?php echo $condition['end_review_user']; ?>"   class="input1"  style="width:30px" />
      好评率： <input type="text" id="start_review_rate" name="start_review_rate"   value="<?php echo $condition['start_review_rate']; ?>" 
                 class="input1"  style="width:30px" />-<input type="text" id="end_review_rate" name="end_review_rate"  
                  value="<?php echo $condition['end_review_rate']; ?>"   class="input1"  style="width:30px" />
      
      平台:<input type="text" id="terrace" name="terrace"   value="<?php echo $condition['terrace']; ?>" 
                 class="input1"  style="width:60px" />



		       排序：
      <select name="orderby" id="orderby"  class="s_select">
        <option value="" >无字段</option>
        <option value="sale_price" <?php if ($condition['orderby'] === 'sale_price') { echo 'selected'; } ?>>售价</option>
        <option value="ship_fee"  <?php if ($condition['orderby'] == 'ship_fee') { echo 'selected'; } ?> >运费</option>
        <option value="order_count"  <?php if ($condition['orderby'] == 'order_count') { echo 'selected'; } ?> >订单数</option>
        <option value="review_user"  <?php if ($condition['orderby'] == 'review_user') { echo 'selected'; } ?> >评论数</option>
        <option value="review_rate"  <?php if ($condition['orderby'] == 'review_rate') { echo 'selected'; } ?> >好评率</option>
        <option value="oringal_price"  <?php if ($condition['orderby'] == 'oringal_price') { echo 'selected'; } ?> >原价</option>
      </select>

       <select name="assort" id="assort"  class="s_select"> 
        <option value="desc" <?php if ($condition['assort'] === 'desc') { echo 'selected'; } ?>>降序</option>
        <option value="asc"  <?php if ($condition['assort'] == 'asc') { echo 'selected'; } ?> >升序</option>
      /option>
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
      <th>图片</th>
      <th width="15%">名称</th>
      <th>售价 | 原价</th>
      <th>订单个数</th>
      <th>好评</th>
      <th>运费</th>
      <th>规格</th>
      <th>状态</th>
      
      <th>关联id</th> 
      <th>平台</th>      
      <th>操作</th>   
    </tr> 

		<?php foreach ($rs['list'] as $row) {?>
		  <tr>
        <td><img style="width:150px; height:150px;" src="<?php echo $row['main_pic']; ?>" /></td>
			<td><a href="<?php echo $row['link']; ?>" target="_blank"><?php echo $row['product_name']; ?></a></td>		
      <td><?php echo $row['sale_price']; ?> | <?php echo $row['oringal_price']; ?></td>  
      <td><?php echo $row['order_count']; ?></td>
      <td><?php echo $row['review_user']; ?> | <?php echo $row['review_rate']; ?>%</td>
      <td><?php echo $row['ship_fee']; ?></td>
      <td><?php echo $row['weight']; ?> kg | <?php echo $row['length']; ?>x <?php echo $row['width']; ?> x<?php echo $row['height'] ; ?>cm  </td>
      <td><?php if ($row['status'] == '1') { echo '可用'; } else { echo '不可用';} ?>

      <td><?php echo $row['reletive_id']; ?></td>
      <td><?php echo $row['terrace']; ?></td>
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
