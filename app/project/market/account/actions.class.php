<?php
/**
 * 账号管理 模块
 * @author huaxiaofeng 2013-6-3 11:02:04
 */
class accountActions extends framework 
{
	public function __construct()
	{
		framework::model('market/auth');
		$auth = new auth();		
		framework::model('market/adminuser');
		framework::plugin('Validator');
		$this->sysuser = new adminuser();
	}
	
	/**
	 * 后台账户管理  
	 */
	public function actionsIndex()
	{ 
		 
		$condition = array(
				'user_name' => framework::get('user_name'), 
				'group_id' => framework::get('group_id'),
		); 
		
		$rs = $this->sysuser->getByCondition($condition); 
		  
		framework::assign('condition', $condition);
		framework::assign('rs', $rs);
	}	 	
	
	/**
	 * 添加后台用户 
	 */
	public function actionsAddaccount()
	{
		framework::model('market/account');
		$account = new account();
		$role_list = $account->role;
		foreach ($role_list as $key => $val) {
			if ($key<$_SESSION['role_id']) {
				unset($role_list[$key]);
			}
		}
		$msg = array();
		
	 	if (framework::post()) { 
	 		$user_name = framework::post('user_name');
			$user_pwd = framework::post('user_pwd');
			$user_pwd_re = framework::post('user_pwd_re');
			$role_id = framework::post('role_id'); 
			$remark = framework::post('remark');
				
	        $validate[] = array('name' => '后台用户名','value' => $user_name,'regex' => array('require','length[1,255]'));
	        $validate[] = array('name' => '用户密码','value' => $user_pwd,'regex' => array('require'));
	        $validate[] = array('name' => '用户角色','value' => $role_id,'regex' => array('require'));
	        $validate[] = array('name' => '备注', 'value' => $remark, 'regex' => array('require'));
			
	        $error = Validator::formValidator($validate);
			if ($user_pwd != $user_pwd_re) {
				$error[] = '两次输入密码不一致';
			}
			if ($this->sysuser->getByUsername($user_name)) {
				$error[] = '账号已存在';
			}
	 		if ($role_id< $_SESSION['role_id']) {
				$error[] = '您分配的角色权限大于自己的角色权限';
			}
			if (empty($error)) {
				$create_array = array(
						'username' => $user_name,
						'password' => md5($user_pwd),
						'role_id' => $role_id,	 
						'remark' => $remark,	
						'add_time' => date("Y-m-d H:i:s"),	
				);		
				 
				$user_id = $this->sysuser->create($create_array);		
				if ($user_id) {
					$account->setUserPowerByRole($user_id, $role_id);
					$msg['message'] = '添加后台用户成功';
				} else {
					$msg['message'] = '添加后台用户失败';
				}
			} else {
				$msg['error'] = $error;
			}
			die(json_encode($msg));
	 	}   
	 	framework::assign('role_list', $role_list);
		
	}
	
	
	public function actionsEditaccount()
	{	
		framework::model('market/account');
		$account = new account();
		$role_list = $account->role;
		foreach ($role_list as $key => $val) {
			if ($key<$_SESSION['role_id']) {
				unset($role_list[$key]);
			}
		}
		$id = framework::get('id');
		$msg = array();
		
	 	if (framework::post()) { 
	 		$user_id = framework::post('user_id');
	 		$user_name = framework::post('user_name');
			$user_pwd = framework::post('user_pwd'); 
			$role_id = framework::post('role_id'); 		        
			$remark = framework::post('remark');
			
	        $validate[] = array('name' => '用户角色','value' => $role_id,'regex' => array('require'));
	        $validate[] = array('name' => '备注', 'value' => $remark, 'regex' => array('require'));
			
	        $error = Validator::formValidator($validate);
			if ($role_id< $_SESSION['role_id']) {
				$error[] = '您分配的角色权限大于自己的角色权限';
			}
			if (empty($error)) {
				$create_array = array(
						'username' => $user_name,
						'role_id' => $role_id,	 
						'remark' => $remark,					
				);				
				if (trim($user_pwd)) {
					$create_array['password'] = md5($user_pwd);
				}
				 
				if ($this->sysuser->update($create_array, $user_id)) {
					$account->setUserPowerByRole($user_id, $role_id);
					$msg['message'] = '修改后台用户成功';
				} else {
					$msg['message'] = '修改后台用户失败';
				}
			} else {
				$msg['error'] = $error;
			}
			die(json_encode($msg));
	 	}   
	 	$sysuser_row = $this->sysuser->get($id);
	 	framework::assign('sysuser_row', $sysuser_row);
	 	framework::assign('role_list', $role_list);
	 	
	
	}
	
	public function actionsDelaccount()
	{
		$id = framework::get('id');
		 
		if ($this->sysuser->delete($id)) {
			
		}
		framework::location(HIGHPHP_WWW_HOST.'account/index');
	}
	
	
	
	
	/**
	 * 左侧菜单 列表
	 */
	public function actionsMenu()
	{
		framework::model('market/account');
		$account = new account();
		
		$condition = array(
				'status' => framework::get('status'),
				'pid' => 0,
		); 
		
		$rs = $account->getByCondition($condition);
		
		foreach ($rs as $key => $row) {
			$condition['pid'] = $row['id'];
			$child = $account->getByCondition($condition);
			$rs[$key]['child'] = $child;			
		} 
		framework::assign('condition', $condition);
		framework::assign('rs', $rs);
		
	}
	
	/**
	 * 添加菜单栏
	 */
	public function actionsAddmenu()
	{
		framework::model('market/account');
		$account = new account();
		
	 	if (framework::post()) {
	 		$menu_name = framework::post('menu_name');
			$module = framework::post('module');
			$action_name = framework::post('action_name');
			$pid = framework::post('pid');
			$order_num = framework::post('order_num');
			$status = framework::post('menu_status');
			$remark = framework::post('remark');
			
			$validate[] = array('name' => '菜单名称','value' => $menu_name,'regex' => array('require'));
			$validate[] = array('name' => '控制器名称','value' => $module,'regex' => array('require'));
			$validate[] = array('name' => '方法名称','value' => $action_name,'regex' => array('require'));
	        $validate[] = array('name' => '状态','value' => $status,'regex' => array('require')); 
			
	        $error = Validator::formValidator($validate);
			if (empty($error)) {
				$update_array = array(
								'name' => $menu_name,
								'module' => $module,
								'action' => $action_name,
								'order_num' => $order_num,
								'pid' => $pid,
								'status' => $status,
								'remark' => $remark,
				);
				if ($account->addMenu($update_array)) {
					$msg['message'] = '添加菜单成功';
				} else {
					$msg['message'] = '添加菜单失败';
				}	
				
			} else {
				$msg['error'] = $error;
			}			
			die(json_encode($msg)); 
	 		
	 	}
	 	$condition = array('pid' => 0);
	 	$parent_menu = $account->getByCondition($condition);
	 	framework::assign('parent_menu', $parent_menu);
		
			
	}
	
	/**
	 * 菜单栏修改
	 */
	public function actionsEditmenu()
	{
		 
		framework::model('market/account');
		$account = new account();
		$menu_id = framework::get('id');
	 	if (framework::post()) {
	 		$id = framework::post('menu_id');
	 		$menu_name = framework::post('menu_name');
			$module = framework::post('module');
			$action_name = framework::post('action_name');
			$pid = framework::post('pid');
			$order_num = framework::post('order_num');
			$status = framework::post('menu_status');
			$remark = framework::post('remark');
						
			$validate[] = array('name' => '菜单名称','value' => $menu_name,'regex' => array('require'));
			$validate[] = array('name' => '控制器名称','value' => $module,'regex' => array('require'));
			$validate[] = array('name' => '方法名称','value' => $action_name,'regex' => array('require'));
	        $validate[] = array('name' => '状态','value' => $status,'regex' => array('require')); 
			
	        $error = Validator::formValidator($validate);
	        if (empty($error)) {
				$update_array = array(
								'name' => $menu_name,
								'module' => $module,
								'action' => $action_name,
								'order_num' => $order_num,
								'pid' => $pid,
								'status' => $status,
								'remark' => $remark,
				);
				if ($account->editMenu($update_array, $id)) {
					$msg['message'] = '修改菜单成功';
				} else {
					$msg['message'] = '修改菜单失败';
				}
	        } else {
	        	$msg['error'] = $error;
	        }
			die(json_encode($msg)); 
	 	}
	 	$menu_row = $account->getMenu($menu_id);
	 	$condition = array('pid' => 0);
	 	$parent_menu = $account->getByCondition($condition);
	 	framework::assign('parent_menu', $parent_menu);
	 	framework::assign('menu_row', $menu_row);
		
	}
	
	/**
	 * 删除菜单栏目
	 */
	public function actionsDelmenu()
	{		
		framework::model('market/account');
		$account = new account();
		$id = framework::get('id');
	 
		$account->delMenu($id);
		framework::location(HIGHPHP_WWW_HOST.'account/Menu');
	}
	
	
	/**
	 * 权限列表
	 */
	public function actionsPower()
	{
		framework::model('market/account');
		$account = new account(); 
		$condition = array( ); 
		
		$rs = $account->getPowerList($condition);  
		framework::assign('rs', $rs);
	}
	
	/**
	 * 添加权限
	 */
	public function actionsAddpower()
	{
		framework::model('market/account');
		$account = new account(); 
		$msg = array();
		
	 	if (framework::post()) { 
	 		$menu_name = framework::post('menu_name');
			$module = framework::post('module');
			$action_name = framework::post('action_name');
			$remark = framework::post('remark');
				 
			$validate[] = array('name' => '菜单名称','value' => $menu_name,'regex' => array('require'));
			$validate[] = array('name' => '控制器名称','value' => $module,'regex' => array('require'));
			$validate[] = array('name' => '方法名称','value' => $action_name,'regex' => array('require')); 
			
	        $error = Validator::formValidator($validate);
			if (empty($error)) {
				$update_array = array(
								'name' => $menu_name,
								'module' => $module,
								'action' => $action_name,
								'remark' => $remark,
				);
				if ($account->addPower($update_array)) {
					$msg['message'] = '添加权限成功';
				} else {
					$msg['message'] = '添加权限失败';
				}	 
			} else {
				$msg['error'] = $error;
			}			
			die(json_encode($msg)); 
	 	}  
		
	}
	
	/**
	 * 权限修改
	 */
	public function actionsEditpower()
	{
		framework::model('market/account');
		$account = new account(); 
		$id = framework::get('id');
	 	if (framework::post()) {
	 		$id = framework::post('id');
	 		$menu_name = framework::post('menu_name');
			$module = framework::post('module');
			$action_name = framework::post('action_name');
			$remark = framework::post('remark');
				 
			$validate[] = array('name' => '菜单名称','value' => $menu_name,'regex' => array('require'));
			$validate[] = array('name' => '控制器名称','value' => $module,'regex' => array('require'));
			$validate[] = array('name' => '方法名称','value' => $action_name,'regex' => array('require')); 
			
	        $error = Validator::formValidator($validate);
	        if (empty($error)) {
				$update_array = array(
								'name' => $menu_name,
								'module' => $module,
								'action' => $action_name,
								'remark' => $remark,
				);
				if ($account->editPower($update_array, $id)) {
					$msg['message'] = '修改权限成功';
				} else {
					$msg['message'] = '修改权限失败';
				}
	        } else {
	        	$msg['error'] = $error;
	        }
			die(json_encode($msg)); 
	 	}
	 	$row = $account->getPower($id);   
	 	framework::assign('row', $row);
		
	}
	
	
	/**
	 * 删除权限
	 */
	public function actionsDelpower()
	{
		framework::model('market/account');
		$account = new account();
		$id = framework::get('id');
	 
		$account->delPower($id);
		framework::location(HIGHPHP_WWW_HOST.'account/power');
	}
	
	
	
	
	/**
	 * 角色权限列表
	 */
	public function actionsRolepower()
	{
		$role_id = framework::get('id');
		framework::model('market/account');
		$account = new account(); 
		$role_list = $account->role;
		if ($role_id != '')  {
			
			$power_list = $account->getPowerAll();
			$power_array = array();
			$role_power = array();
			foreach ($power_list as $key => $row) {
				$power_array[$row['module']][] = $row;
			}
			$role_power_list = $account->getRolePower($role_id);
			foreach ($role_power_list as $row) {
				$role_power[$row['power_id']] = 1;
			}
			framework::assign('role_power', $role_power);
			framework::assign('power_list', $power_array);
		} 
		framework::assign('role_id', $role_id);
		framework::assign('role_list', $role_list);
	}
	
	
	public function actionsRolepoweredit()
	{
		framework::model('market/account');
		$account = new account(); 
		if ($_POST) {
			$id = framework::post('role_id');			
	 		$power_id_array = @$_POST['power_id'];
	 			 		
			$validate[] = array('name' => '角色ID','value' => $id,'regex' => array('require')); 
			
	        $error = Validator::formValidator($validate);
		 
			if(empty($power_id_array)) {
				$error[] = '没有选择任何权限';
			}
	        if (empty($error)) {
	        	if ($account->delRolePower($id)) {
	        		foreach ($power_id_array as $power_id) {
	        			$array = array(
	        						'role_id' => $id,
	        						'power_id' => $power_id,
	        			);
	        			$account->addRolePower($array);
	        		}
	        		$msg['message'] = '修改权限成功';
	        	} else {
	        		$msg['message'] = '修改权限失败';
	        	} 
			
	        } else {
	        	$msg['error'] = $error;
	        }
			die(json_encode($msg)); 
		}
	}
	
	/**
	 * 用户权限设置
	 */
	public function actionsUserPower()
	{
		$user_id = framework::get('id');
		$role_id = framework::get('role_id');
		framework::model('market/account');
		$account = new account();  
		$role_list = $account->role;
			
		$power_list = $account->getPowerAll();
		$power_array = array();
		$user_power = array();
		foreach ($power_list as $key => $row) {
			$power_array[$row['module']][] = $row;
		}
		if ($role_id != '') {
			$role_power_list = $account->getRolePower($role_id);
			foreach ($role_power_list as $row) {
				$user_power[$row['power_id']] = 1;
			} 
		} else {
			$user_power_list = $account->getUserPower($user_id);
			foreach ($user_power_list as $row) {
				$user_power[$row['power_id']] = 1;
			} 
		}
		$user_info = $this->sysuser->get($user_id);
		framework::assign('user_info', $user_info);
		framework::assign('user_power', $user_power);
		framework::assign('power_list', $power_array);
		framework::assign('role_list', $role_list); 
		framework::assign('user_id', $user_id); 	 
		framework::assign('role_id', $role_id); 	
	}	
	
	/**
	 * 用户权限设置提交
	 */
	public function actionsUserpoweredit()
	{
		framework::model('market/account');
		$account = new account(); 
		if ($_POST) {
			$id = framework::post('user_id');			
	 		$power_id_array = @$_POST['power_id'];
	 			 		
			$validate[] = array('name' => '用户ID','value' => $id,'regex' => array('require')); 
			
	        $error = Validator::formValidator($validate);
		 
			if(empty($power_id_array)) {
				$error[] = '没有选择任何权限';
			}
	        if (empty($error)) {
	        	if ($account->delUserPower($id)) {
	        		foreach ($power_id_array as $power_id) {
	        			$array = array(
	        						'user_id' => $id,
	        						'power_id' => $power_id,
	        			);
	        			$account->addUserPower($array);
	        		}
	        		$msg['message'] = '修改权限成功';
	        	} else {
	        		$msg['message'] = '修改权限失败';
	        	} 
			
	        } else {
	        	$msg['error'] = $error;
	        }
			die(json_encode($msg)); 
		}		
		exit;
	}
	
	/**
	 * 修改自己密码 
	 */
	public function actionsChangepwd()
	{
		framework::model('market/account');
		$account = new account();
	 
		$msg = array();
		
	 	if (framework::post()) { 
	 		$user_pwd = framework::post('user_pwd');
	 		$user_pwd_new = framework::post('user_pwd_new');
			$user_pwdnew_re = framework::post('user_pwdnew_re'); 
		 	        
	        $validate[] = array('name' => '用户密码','value' => $user_pwd,'regex' => array('require'));
	        $validate[] = array('name' => '新密码','value' => $user_pwd_new,'regex' => array('require'));
	        
			
	        $error = Validator::formValidator($validate);
			if ($user_pwdnew_re != $user_pwd_new) {
				$error[] = '两次密码输入不一致';
			}
			$user_id = $_SESSION['user_id'];
			$user_info = $this->sysuser->get($user_id);
	 		if ($user_info['password'] !== md5($user_pwd) ) {
	 			$error[] = '用户密码错误';
	 		} 
			if (empty($error)) { 
				$create_array['password'] = md5($user_pwd_new);				
				if ($this->sysuser->update($create_array, $user_id)) {
					$msg['message'] = '用户修改密码成功';					
				} else {
					$msg['message'] = '用户修改密码失败';
				}
			} else {
				$msg['error'] = $error;
			}
			die(json_encode($msg));
	 	} 
	 	framework::assign('user_name', $_SESSION['user_name']);
		
	}
	
	
	
}