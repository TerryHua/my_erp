<?php
class userActions extends framework 
{
	public function __construct()
	{
		framework::model('market/auth');
		$auth = new auth(0);
	}
	
	
	public function actionsIndex()
	{ 
		 
	}
	
	
	public function actionsLogin()
	{
		
	}
	
	
	/**
	 * 显示头部内容
	 */
	public function actionsTop()
	{
		framework::model('market/account');
		$account_obj = new account();
		$role_list = $account_obj->role;
		framework::assign('role_list', $role_list);
		framework::assign('role_id', $_SESSION['role_id']);
		framework::assign('user_name', $_SESSION['user_name']);
	}
	
	/**
	 * 显示左侧内容
	 */
	public function actionsCenter()
	{
		
	}
	
	public function actionsDown()
	{
		$week = array('星期天','星期一','星期二','星期三','星期四','星期五','星期六');
		$current_week = date('w');
		$day = date('Y').'年'.date('m').'月'.date('d').' '.$week[$current_week];
		framework::assign('date', $day);
	}
	
	public function actionsLeft()
	{
		framework::model('market/account');
		$account = new account();
		 
		$menu_list = $account->getUserMenu(null);
		  
		foreach ($menu_list as $key => $value) {
			$child_menu = $account->getUserMenu($value['id']);
			
			if (!empty($child_menu)) { 
				foreach ($child_menu as $child_key => $row) {
					if ($_SESSION['role_id'] == '1' or $account->checkPower($row['module'], $row['action'], $_SESSION['user_id'])) {
							
					} else {
						unset($child_menu[$child_key]);
					}
				}
				if (empty($child_menu)) {
					unset($menu_list[$key]);
				} else {
					$menu_list[$key]['child_menu'] = $child_menu;
				}
			} else {
				unset($menu_list[$key]);
			}
		}
 
		framework::assign('menu_list', $menu_list);
	}
	

	/**
	 * 用户登录欢迎首页
	 */
	public function actionsRight()
	{
		
	}
	
	/**
	 * 隐藏左侧导航栏页面
	 */
	public function actionsHiddenbtn()
	{
		
	}
	
	/**
	 * 没有权限访问该页面
	 * Enter description here ...
	 */
	public function actionsNopower()
	{
		
	}
	
	
}