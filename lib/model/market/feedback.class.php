<?php
/**
 * 意见反馈模块
 * @author huaxiaofeng
 * @version 1.0 2014-11-11 22:50:31
 */
framework::plugin('mysql');
class feedback
{
    public $db;
    public $db_bak;  
    
    public function __construct()
    {
        $this->db = mysql::getInstance('mysql');    
        $this->db_bak = mysql::getInstance('mysql_bk');
    }    
    
    
    public function getRow($id)
    {
    	$sql = "SELECT * FROM feedback where id='$id'";
    	return $this->db_bak->getRow($sql);
    }
              
	/**
	 * 添加结算单
	 */
	public function create($data)
	{ 
		return $this->db->insert('feedback', $data, true);
	}
    
	public function update($data, $id)
	{
		$where = 'WHERE id = '.$id;
		return $this->db->updateData('feedback', $data, $where); 
	}
	
	/**
	 * 删除结算单记录
	 */
	public function delete($id)
	{
		if ($id == '') {
			return false;
		}
		$sql = "delete from feedback where id='$id'";
		return $this->db->delete($sql);		
	}
	
    
    /**
     * 获取结算单列表
     */
    public function getFeedbackList($condition)
    {
    	framework::plugin('Page');    	
    	 
		$sql = "SELECT * FROM feedback where 1 "; 
    	
		if (isset($condition['start_date']) && ''!=$condition['start_date']) {
    		$sql .= " AND add_time >= '".$condition['start_date']." 00:00:00'";
    	} 
    	if (isset($condition['end_date']) && ''!=$condition['end_date']) {
    		$sql .= " AND add_time <= '".$condition['end_date']." 23:59:59'";
    	}
    	  
		//设置分页信息
		$page = new Page ();
        $page->uriStyle = '?';
        $page->eachNums = 15;
        $page->orderBy = 'id';
        $page->desc = 'desc'; 
        $list = $page->getPage ( $sql, 0, 'mysql_bk' );
        $html = $page->htmlPage ();
	     
        //返回搜索结果和分页信息
        return array ('list' => $list, 'html' => $html, 'total' => $page->allNums, 'eachNums' => $page->eachNums);
    }
      
    
    
    
}