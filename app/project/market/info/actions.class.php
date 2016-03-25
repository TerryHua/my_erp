<?php
/**
 * 页面管理模块
 * @author huaxiaofeng 2014-11-13 22:23:03
 */

class infoActions extends framework 
{
	public function __construct()
	{ 
		 
		framework::model('market/auth');
		$auth = new auth();
		
		framework::model('market/functions');
		framework::model('market/info');
		framework::plugin('Validator');
		$this->obj = new info();
	}
	
	
	/**
	 * 页面管理列表
	 */
	public function actionsPagelist()
	{
	 
		$data = array();
		$act = framework::get('act');
		
		$start_date = framework::get('start_date');
		$end_date = framework::get('end_date');
		$page_code = framework::get('page_code'); 
		$condition = array( 
					'start_date' => $start_date,
					'end_date' => $end_date,
					'page_code' => $page_code,
			);	  
		$data = $this->obj->getDataList($condition);

		framework::assign('condition', $condition);
		framework::assign('data', $data);			 
	}
	
	
	 
	
 	/**
     * 
     * 删除 页面相关
     */
    public function actionsPagedel() 
    {
		$id = framework::get('id');
		if ($id) {			 
			if ($this->obj->delete( $id)) {  
				
			}	 		
		} 
		framework::location(HIGHPHP_WWW_HOST.'info/pagelist');
		return false;
    }
     
	/**
	 * 添加页面信息
	 */
	public function actionsPageadd()
	{
		$msg = array();
		if (framework::post()) {
			$title = framework::post('title');
	        $keywords = framework::post('keywords');
	        $page_code = framework::post('page_code');
	        $description = framework::post('description'); 
	        $content = framework::post('content');
	        
	        $validate[] = array('name' => '页面标题','value' => $title, 'regex' => array('require'));
	        $validate[] = array('name' => '关键词','value' => $keywords, 'regex' => array('require'));   
	        $validate[] = array('name' => '页面代码', 'value' => $page_code, 'regex' => array('require'));     
	        
	        $error = Validator::formValidator($validate);	        
	         
	        if ($page_code != '') {
	        	$row = $this->obj->getByPageCode($page_code);
	        	if ($row) {
	        		$error[] = '已存在对应的页面编码';
	        	}
	        }
	         
			if (empty($error)) {
				$create_array = array(
						'title' => $title,
						'keywords' => $keywords,
						'page_code' => $page_code,  
						'description' => $description,
						'content' => $content,		
						'add_time' => date('Y-m-d H:i:s'),	
				);				
				 
				if ($this->obj->create($create_array)) {
					$msg['message'] = '添加成功';
				} else {
					$msg['message'] = '添加失败';
				}
			} else {
				$msg['error'] = $error;
			}
			die(json_encode($msg)); 
		}
		
	}	
	
	/**
	 * 修改 页面信息
	 */
	public function actionsPageedit()
	{
		$msg = array();
		$id = framework::get('id');
		if (framework::post()) {
			$title = framework::post('title');
	        $keywords = framework::post('keywords');
	        $page_code = framework::post('page_code');
	        $edit_page_code = framework::post('edit_page_code');
	        $description = framework::post('description'); 
	        $content = framework::post('content');
	        
	        $validate[] = array('name' => '页面标题','value' => $title, 'regex' => array('require'));
	        $validate[] = array('name' => '关键词','value' => $keywords, 'regex' => array('require'));   
	        $validate[] = array('name' => '页面代码', 'value' => $page_code, 'regex' => array('require'));     
	         	        
	        $error = Validator::formValidator($validate);	        
	        if ($page_code != $edit_page_code) {
		        $row = $this->obj->getByPageCode($page_code);	        
				if ($row){
		            $error[] = '已存在对应的页面编码';	            
		        }	        
			}
	        
	         
			if (empty($error)) {
				$create_array = array(
						'title' => $title,
						'keywords' => $keywords,
						'page_code' => $page_code,  
						'description' => $description,
						'content' => $content,		 
				);					
				 
				if ($this->obj->updateByCode($create_array, $edit_page_code)) {
					$msg['message'] = '修改成功';
				} else {
					$msg['message'] = '修改失败';
				}
			} else {
				$msg['error'] = $error;
			}
			die(json_encode($msg)); 
		}
		$edit_row = $this->obj->getRow($id);
		
		framework::assign('edit_row', $edit_row);
	}
	
	/**
	 * 新闻管理列表
	 */
	public function actionsNewslist()
	{
	 
		$data = array();
		$act = framework::get('act');
		
		$start_date = framework::get('start_date');
		$end_date = framework::get('end_date');
		$status = framework::get('status'); 
		$condition = array( 
					'start_date' => $start_date,
					'end_date' => $end_date,
					'status' => $status,
			);	  
		$data = $this->obj->getNewsList($condition);

		framework::assign('condition', $condition);
		framework::assign('data', $data);			 
	}
	

	/**
	 * 添加页面信息
	 */
	public function actionsNewsadd()
	{
		$msg = array();
		if (framework::post()) {
			$title = framework::post('title');
	        $keywords = framework::post('keywords');
	        $description = framework::post('description'); 
	        $content = framework::post('content');
	        $status = framework::post('status');
	        $author = framework::post('author');
	        $publish_time = framework::post('publish_time');
	        
	        $validate[] = array('name' => '新闻标题','value' => $title, 'regex' => array('require'));
	        $validate[] = array('name' => '新闻关键词','value' => $keywords, 'regex' => array('require'));    
	        
	        $error = Validator::formValidator($validate);	        
	         
	         
			if (empty($error)) {
				$create_array = array(
						'title' => $title,
						'keywords' => $keywords, 
						'description' => $description,
						'content' => $content,		
						'add_time' => date('Y-m-d H:i:s'),
						'publish_time' => $publish_time,
						'author' => $author,
						'status' => $status,	
				);				
				 
				if ($this->obj->createNews($create_array)) {
					$msg['message'] = '添加成功';
				} else {
					$msg['message'] = '添加失败';
				}
			} else {
				$msg['error'] = $error;
			}
			die(json_encode($msg)); 
		}
		
	}	

	/**
	 * 修改 页面信息
	 */
	public function actionsNewsedit()
	{
		$msg = array();
		$id = framework::get('id');
		if (framework::post()) {
			$title = framework::post('title');
	        $keywords = framework::post('keywords');
	        $description = framework::post('description'); 
	        $content = framework::post('content');
	        $status = framework::post('status');
	        $author = framework::post('author');
	        $publish_time = framework::post('publish_time');
	        $id = framework::post('id');
	        
	        $validate[] = array('name' => '新闻标题','value' => $title, 'regex' => array('require'));
	        $validate[] = array('name' => '新闻关键词','value' => $keywords, 'regex' => array('require'));    
	        
	        $error = Validator::formValidator($validate);	        
	         
	         
			if (empty($error)) {
				$create_array = array(
						'title' => $title,
						'keywords' => $keywords, 
						'description' => $description,
						'content' => $content,		 
						'publish_time' => $publish_time,
						'author' => $author,
						'status' => $status,	
				);				
				if ($this->obj->updateNews($create_array, $id)) {
					$msg['message'] = '修改成功';
				} else {
					$msg['message'] = '修改失败';
				}
			} else {
				$msg['error'] = $error;
			}
			die(json_encode($msg)); 
		}
		$edit_row = $this->obj->getNewsRow($id);		
		framework::assign('edit_row', $edit_row);
	}
	

	/** 
     * 删除 页面相关
     */
    public function actionsNewsdel() 
    {
		$id = framework::get('id');
		if ($id) {			 
			if ($this->obj->deleteNews( $id)) {  
				
			}	 		
		} 
		framework::location(HIGHPHP_WWW_HOST.'info/newslist');
		return false;
    }
	
}