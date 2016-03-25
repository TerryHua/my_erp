<?php
/**
 * 判断是否登陆
 * @author huaxiaofeng
 * @version 1.0 2013-4-11 15:55:08
 */
class auth
{
	public function __construct($checkPower = 1)
	{
		$this->is_login();
		if ($checkPower == 1) {
			$this->checkPower();
		}
	}
	
	
	
	public function is_login()
	{
		if (isset($_SESSION['user_name']) && $_SESSION['user_name']!='') {
			
		} else { 
			header('Location:'.HIGHPHP_WWW_HOST.'index/login');
		}
	
	}
	
	public function checkPower()
	{
		$module = framework::$module;
		$action = framework::$action;
		
		framework::model('market/account');
		$verify_obj = new account();
		if ($_SESSION['role_id'] == '1' or $verify_obj->checkPower($module, $action, $_SESSION['user_id'])) {
			
		} else {
			framework::location(HIGHPHP_WWW_HOST.'user/nopower');
		}
	}
 
}
