<?php
/**
 * 有信联盟平台
 * @author huaxiaofeng
 * @version 1.0 2013-4-11 15:55:08
 */
framework::plugin('mysql');
class adminuser
{
    public $db;
    
    public function __construct()
    {
        $this->db = mysql::getInstance('mysql');    
        $this->db_bak = mysql::getInstance('mysql_bk');  
    }    
      
	public function get($id)
	{
		$sql = "SELECT * FROM admin_user where id='$id'";
		return $this->db_bak->getRow($sql);
	}
	
    
    /**
     * 添加后台用户
     */
    public function create($data)
    {
    	return $this->db->insert('admin_user', $data, true);
    }
    
	public function update($data, $id)
	{
		$where = 'WHERE id = '.$id;
		return $this->db->updateData('admin_user', $data, $where); 
	}
    
	/**
	 * 删除后台用户
	 */
	public function delete($id)
	{
		if ($id == '') {
			return false;
		}
		$sql = "delete from admin_user where id='$id'";
		return $this->db->delete($sql);
	}   
	 
    /**
     * 获取后台用户信息
     * @param unknown_type $login_name
     */
    public function getByUsername($login_name)
    {
    	$sql = "SELECT * FROM admin_user WHERE username='$login_name'";
        $res = $this->db_bak->getRow($sql); 
        return $res;
    }
    
    public function getUserList()
	{
		$sql = "SELECT * FROM admin_user";
		return $this->db_bak->getAll($sql);
	}
  
	public function getByRole($role_id)
	{
		$sql = "SELECT * FROM admin_user WHERE role_id='$role_id'";
		return $this->db_bak->getAll($sql);
	}
	
	public function getByCondition($condition, $page= null, $page_size=null)
	{
		framework::plugin('Page');    	
    	 
		$sql = "SELECT * FROM `admin_user` where 1 "; 
    	if (isset($condition['user_name']) && ''!=$condition['user_name']) {
    		$sql .= " AND username = '".$condition['user_name']."'";
    	} 
    	if (isset($condition['role_id']) && ''!=$condition['role_id']) {
    		$sql .= " AND role_id>='".$condition['role_id']."'";
    	}
    	if (isset($condition['group_id']) && ''!=$condition['group_id']) {
    		$sql .= " AND group_id='".$condition['group_id']."'";
    	}
		
		
		//设置分页信息
		$page = new Page ();
        $page->uriStyle = '?';
        $page->eachNums = 15;
        $page->orderBy = 'id';
        $page->desc = 'desc'; 
      
        $list = $page->getPage ( $sql, 0 , 'mysql_bk' );
        $html = $page->htmlPage ();
	     
        //返回搜索结果和分页信息
        return array ('list' => $list, 'html' => $html, 'total' => $page->allNums, 'eachNums' => $page->eachNums);
	}
	
	
	/**
	 * 根据parent id 获取旗下管理人员
	 * @return array 登录名数组
	 */
	public function getByParentId($parent_id, $role_id, $is_involve = 1)
	{
		$result = array();
		$sql = "select * from admin_user where parent_id='$parent_id' and role_id='$role_id'";
		$all = $this->db_bak->getAll($sql);
		foreach ($all as $key => $value) {
			$result[] = $value['username'];
		}
		if ($is_involve == 1) {
			$result[] = $_SESSION['user_name'];
		}
		return $result;
	}
	
    
}