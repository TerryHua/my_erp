<?php
/**
 * 账户信息管理模块
 * 包含权限，角色管理，角色权限管理等操作
 * @author huaxiaofeng
 * @version 1.0 2014-11-09 15:27:07
 */

framework::plugin('mysql');
class account
{
    public $db;
    
    /**
     * @var unknown_type 角色数组，key为角色id，name为角色名称
     */
    public $role = array(
    		'1' => '超级管理员',
    		'3' => '管理员',
    );
    
    public function __construct()
    {
        $this->db = mysql::getInstance('mysql');    
        $this->db_bak = mysql::getInstance('mysql_bk');
    }    
    
     
	/**
	 * 获取菜单栏
	 * @param unknown_type $condition 
	 */
	public function getByCondition($condition)
	{
		framework::plugin('Page');    	
    	 
		$sql = "SELECT * FROM `menu` where 1 "; 
    	if (isset($condition['pid']) && ''!==$condition['pid']) {
    		$sql .= " AND pid = '".$condition['pid']."'";
    	} 
		if (isset($condition['status']) && '' != $condition['status']) {
			$sql .= " AND status='".$condition['status']."'";
		}
	  
		return $this->db_bak->getAll($sql);
	}
	
	
	public function getUserMenu($parent_id = null) 
	{
		$sql = "select * from menu WHERE status = '1'";
        if($parent_id !=null && $parent_id>0){
        	$sql .= " and pid='$parent_id'";
        }else{
        	$sql .= " and pid='' or pid='0'";
        }
        $sql .= ' order by 	order_num asc';
 
        return $this->db_bak->getAll($sql);
	}
	

	/**
	 * 添加菜单栏
	 */
	public function addMenu($data)
	{
		return $this->db->insert('menu', $data);
	}
	
	/**
	 * 删除菜单栏
	 */
	public function delMenu($id)
	{
		if ($id == '') {
			return false;
		}
		$sql = "delete from menu where id='$id'";
		return $this->db->delete($sql);		
	}
	
	public function editMenu($data, $id)
	{
		$where = 'WHERE id = '.$id;
		return $this->db->updateData('menu', $data, $where); 
	}
	
	
	/**
	 * 获取单条菜单栏信息
	 */
    public function getMenu($id)
    {
    	$sql = "select * from menu where id='$id'";
    	return $this->db_bak->getRow($sql);
    }
    
    
    /**
     * 获取权限 列表记录
     */
    public function getPowerList($condition)
    {
    	framework::plugin('Page');    	
    	 
		$sql = "SELECT * FROM `power` where 1 "; 
    	 
		
		//设置分页信息
		$page = new Page ();
        $page->uriStyle = '?';
        $page->eachNums = 15;
        $page->orderBy = 'id';
        $page->desc = 'desc'; 
      
        $list = $page->getPage ( $sql, 0 ,'mysql_bk');
        $html = $page->htmlPage ();
	     
        //返回搜索结果和分页信息
        return array ('list' => $list, 'html' => $html, 'total' => $page->allNums, 'eachNums' => $page->eachNums);
    }
    
    
    /**
     * 添加权限 记录
     */
    public function addPower($data)
    {
    	return $this->db->insert('power', $data);
    }
    
    
    /**
	 * 删除权限 记录
	 */
	public function delPower($id)
	{
		if ($id == '') {
			return false;
		}
		$sql = "delete from power where id='$id'";
		return $this->db->delete($sql);		
	}
	
	public function editPower($data, $id)
	{
		$where = 'WHERE id = '.$id;
		return $this->db->updateData('power', $data, $where); 
	}
	
	/**
	 * 获取单条菜单栏信息
	 */
    public function getPower($id)
    {
    	$sql = "select * from power where id='$id'";
    	return $this->db_bak->getRow($sql);
    }
    
    
    public function getPowerAll()
    {
    	$sql = "select * from power";
    	return $this->db_bak->getAll($sql);
    }
	
    
    /**
     * 获取角色对应的权限
     * @param unknown_type $role_id
     */
    public function getRolePower($role_id)
    {
     	$sql = "select * from power_role_map where role_id='$role_id'";
    	return $this->db_bak->getAll($sql);
    }
    
    /**
     * 删除角色对应的权限
     * @param unknown_type $role_id
     */
    public function delRolePower($role_id)
    {
    	if ($role_id == '') {
			return false;
		}
		$sql = "delete from power_role_map where role_id='$role_id'";
		return $this->db->delete($sql);		
    }
    
    public function addRolePower($array)
    {
    	return $this->db->insert('power_role_map', $array);
    }
    
   /**
     * 获取用户对应的权限
     * @param unknown_type $user_id
     */
    public function getUserPower($user_id)
    {
     	$sql = "select * from power_user_map where user_id='$user_id'";
    	return $this->db_bak->getAll($sql);
    }
    
    
    /**
     * 检查用户权限
     */
    public function checkPower($module, $action, $user_id)
    {
    	if ($user_id == '') {
    		return false;
    	}
    	$sql = "select count(*) from power as a, power_user_map as b where a.id = b.power_id and 
    			a.module='$module' and a.action='$action' and b.user_id='$user_id'";
    	if ($this->db_bak->getOne($sql)) {
    		return true;
    	} else {
    		return false;
    	}
    }
    
    
 	/**
     * 删除用户对应的权限
     * @param unknown_type $user_id
     */
    public function delUserPower($user_id)
    {
    	if ($user_id == '') {
			return false;
		}
		$sql = "delete from power_user_map where user_id='$user_id'";
		return $this->db->delete($sql);		
    }
    
    /**
     * 添加用户对应权限
     * @param unknown_type $array
     */
    public function addUserPower($array)
    {
    	return $this->db->insert('power_user_map', $array);
    }
    
    
    /**
     * 根据角色给用户分配权限
     */
    public function setUserPowerByRole($user_id, $role_id) 
    {
    	$role_acl = $this->getRolePower($role_id);
    	if ($this->delUserPower($user_id)) {
    		foreach ($role_acl as $key => $acl) {
    			$array = array(
    						'user_id' => $user_id,
    						'power_id' => $acl['power_id'],
    			);
    			$this->addUserPower($array);
    		}
    	}
    }
    
    
  
     
}