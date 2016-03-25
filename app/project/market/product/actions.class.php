<?php

/**
 * 产品模块
 * @author huaxiaofeng
 * @version 1.0 2014-11-09 18:28:47
 */
class productActions extends framework
{
	public function __construct()
	{
		framework::plugin('Validator');
	}

	/**
	 * 产品分类列表
	 */
	public function actionsCatelist()
	{   
		framework::model('market/product');
		$obj = new product();
		$condition = array(
				'en_name' => framework::get('en_name'),
				'status' => framework::get('status'),
		); 
		
		$rs = $obj->getProductCateList($condition);
	 
		framework::assign('condition', $condition);
		framework::assign('rs', $rs);
		
	}
	
	/**
	 * 产品分类添加
	 */
	public function actionsCateadd()
	{
		framework::model('market/product');
		$obj = new product();
	 
		$msg = array();
		
	 	if (framework::post()) { 
	 		$cn_name = framework::post('cn_name');
	 		$en_name = framework::post('en_name'); 
	 		$status = framework::post('status');
	 		$orderby = framework::post('orderby');
	 		$parent_id = framework::post('parent_id');

	        $validate[] = array('name' => '英文名称','value' => $en_name,'regex' => array('require')); 
	        
			
	        $error = Validator::formValidator($validate);
			   
			if (empty($error)) {  
				$create_array = array(
						'cn_name' => $cn_name,
						'en_name' => $en_name,
						'status' => $status,
						'orderby' => $orderby,
						'parent_id' => $parent_id,	
				);		
				if ($obj->createProductCate($create_array)) {
					$msg['message'] = '添加成功';					
				} else {
					$msg['message'] = '添加失败';
				}
			} else {
				$msg['error'] = $error;
			}
			die(json_encode($msg));
	 	} 
	 	$parent_list = $obj->getCateByParentId(0);
	 	framework::assign('parent_list', $parent_list);
	 	framework::assign('user_name', $_SESSION['user_name']); 
		
	}
	
	/**
	 * 删除用户分组记录
	 */
	public function actionsCatedel()
	{		
		framework::model('market/product');
		$obj = new product();
		$id = framework::get('id');
	 
		$obj->deleteProductCate($id);
		framework::location(HIGHPHP_WWW_HOST.'product/catelist');
	}
	
	/**
	 * 修改用户分组
	 */
	public function actionsCateedit()
	{
		 
		framework::model('market/product');
		$obj = new product();
		$id = framework::get('id');
	 	if (framework::post()) {
	 		$id = framework::post('id');
	 		$cn_name = framework::post('cn_name');
	 		$en_name = framework::post('en_name'); 
	 		$status = framework::post('status');
	 		$orderby = framework::post('orderby');
	 		$parent_id = framework::post('parent_id');
		 	        
	        $validate[] = array('name' => '英文名称','value' => $en_name,'regex' => array('require')); 
	       
	        $error = Validator::formValidator($validate);		   
			 
	        if (empty($error)) {
				$create_array = array(
						'cn_name' => $cn_name,
						'en_name' => $en_name,
						'status' => $status,
						'orderby' => $orderby,
						'parent_id' => $parent_id,	
				);		
				if ($obj->updateProductCate($create_array, $id)) {
					$msg['message'] = '修改成功';
				} else {
					$msg['message'] = '修改失败';
				}
	        } else {
	        	$msg['error'] = $error;
	        }
			die(json_encode($msg)); 
	 	} 
	 	$parent_list = $obj->getCateByParentId(0);
	 	$edit_row = $obj->getProductCate($id);
	 	framework::assign('edit_row', $edit_row);
	 	framework::assign('parent_list', $parent_list);
		
	}


	public function actionsProductlist()
	{
		framework::model('market/product');
		$obj = new product();
		$condition = array(
				'id' => framework::get('id'), 
				'product_name' => framework::get('product_name'),
				'status' => framework::get('status'),
				'orderby' => framework::get('orderby'),
				'assort' => framework::get('assort', 'desc'),
				'cate_id' => framework::get('cate_id'),
				'start_price' => framework::get('start_price'),
				'end_price' => framework::get('end_price'),
				'start_shipfee' => framework::get('start_shipfee'),
				'end_shipfee' => framework::get('end_shipfee'),
				'start_review_user' => framework::get('start_review_user'),
				'end_review_user' => framework::get('end_review_user'), 
				'start_review_rate' => framework::get('start_review_rate'),
				'end_review_rate' => framework::get('end_review_rate'),
				'terrace' => framework::get('terrace'),

		); 
		
		$rs = $obj->getProductistCondition($condition);
	 
		framework::assign('condition', $condition);
		framework::assign('rs', $rs);
	}
	
}


