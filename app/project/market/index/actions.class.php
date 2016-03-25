<?php

/**
 * 后台登陆控制器
 * @author huaxiaofeng
 * @version 2016-3-25 10:10:18
 */
class indexActions extends framework {
	
	public function actionsIndex()
	{
		framework::model('market/auth');
		framework::location(HIGHPHP_WWW_HOST.'user/index');
	}

	/**
	 *  验证码
	 */
	public function actionsAuthcode()
	{
		framework::plugin('Captcha');
		$session_name = framework::get('name');
		$this->captcha = new Captcha();
		if (trim($session_name)) {
			$this->captcha->session_var = $session_name;	
		}
		$this->captcha->CreateImage();
		return false;
	}


	/**
	 * 登陆控制器
	 * @author huaxiaofeng
	 * @version 1.0 2016-3-25 10:11:14
	 */
	public function actionsLogin()
	{
		$msg = ''; 
		framework::model('market/adminuser');
		$user_model = new adminuser();
		if (framework::post()) {
			
			$user_name = framework::post('user_name');
			$user_pwd = framework::post('user_pwd');
			$auth_code = framework::post('auth_code');
			 
			$user_row =  $user_model->getByUsername($user_name);
			 
			if ($auth_code == $_SESSION['captcha']) {
				if (!empty($user_row)) {
					$password = md5($user_pwd);
					if ($password == $user_row['password']) {
						 $_SESSION['role_id'] = $user_row['role_id'];
						 $_SESSION['user_name'] = $user_row['username']; 
						 $_SESSION['user_id'] = $user_row['id']; 
						 $msg['message'] = '登陆成功';
						 die(json_encode($msg));
					} else {
						$error[] = '登陆信息错误';
					}			
				} else {
					$error[] = '登陆信息错误';
				}		
			} else {
				$error[] = '验证码错误';
			}
			$msg['error'] = $error; 
			die(json_encode($msg));
		}
		framework::assign('msg', $msg);
	}

	/**
	 * 登出控制器
	 */
	public function actionsLoginout()
	{
		session_destroy(); 
		framework::location(HIGHPHP_WWW_HOST.'index/login');
	}
	
	
	
}