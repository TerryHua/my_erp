<?php
/**
 * 意见反馈莫可
 * @author huaxiaofeng 2014-11-11 22:27:04
 */

class feedbackActions extends framework 
{
	public function __construct()
	{ 
		framework::model('market/auth');
		$auth = new auth();
		framework::plugin('Validator');
		framework::model('market/functions'); 
		framework::model('market/feedback'); 
		$this->feedback_obj = new feedback(); 
	}
	
	 
	/**
	 * 意见反馈列表
	 */
	public function actionsFeedbacklist()
	{
 
		$data = array();
		
		$start_date = framework::get('start_date')?framework::get('start_date'):date('Y-m-01');
		$end_date = framework::get('end_date')?framework::get('end_date'):date('Y-m-d'); 
		$condition = array( 
					'start_date' => $start_date,
					'end_date' => $end_date,	 
			);	

		$data = $this->feedback_obj->getFeedbackList($condition); 
 		  
		framework::assign('condition', $condition);
		framework::assign('data', $data);
		framework::view('feedbacklist');						 
	}       
	
	
	
	 
	
	
	public  function actionsEditmac()
	{
		$id = framework::get('id');
		$msg = array();
		framework::model('market/sysuser');
		framework::model('market/user');	
		$sysuser = new sysuser();
		$user = new user();
		$mac_detail = array();	//mac地址详细列表
		
		
	 	if (framework::post()) {  
	 		$id = framework::post('mac_id');
	 		$union_id = framework::post('union_id');
			$admin_id = framework::post('admin_user');  
			$reg_start_time = framework::post('reg_start_time');   
			$reg_end_time = framework::post('reg_end_time'); 
			$bill_num = framework::post('bill_num');  
			$data_type = framework::post('data_type');
 
	        $validate[] = array('name' => '修改记录ID','value' => $id,'regex' => array('require'));
	        $validate[] = array('name' => '渠道ID','value' => $union_id,'regex' => array('require')); 	        
	        $validate[] = array('name' => '数据类型','value' => $data_type,'regex' => array('require'));
	         
	        $error = Validator::formValidator($validate);
			$union_info = $user->getUserById($union_id);
			if (empty($union_info)) {
				$error[] = '渠道ID号错误';
			}
			  
			if (empty($error)) { 
				
				$udpate_array = array( 
								'union_id' => $union_id,
								'admin_id' => $admin_id,
								'balance_type' => $union_info['balance_type'],
								'bill_num' => $bill_num,
								'data_type' => $data_type,
				);	
			 	
				
				if ($this->finance_obj->updateMaclist($udpate_array, $id) ) {				 
					$msg['message'] = '修改成功';
				} else {
					$msg['message'] = '修改成功';
				}
			} else {
				$msg['error'] = $error;
			}
			echo json_encode($msg);
			return false;
	 	}  
	 	$row = $this->finance_obj->getMacList($id);
 
		$admin_user = $sysuser->getUserList();		
		framework::assign('admin_user', $admin_user);
		framework::assign('row', $row);
	}
	
	/**
	 * 删除mac地址导入记录
	 */
	public function actionsDelfeedback()
	{
		$id = framework::get('id');
	 
		if ($id) {
			$info = $this->feedback_obj->getRow($id);
			if ($this->feedback_obj->delete($id)) {
				
				$log_info = $_SESSION['user_name'].' delete:'.json_encode($info);
				framework::logWrite($log_info); 
			} 
		} 
		framework::location(HIGHPHP_WWW_HOST.'feedback/feedbacklist');
		return false;
		
	}
	 


	
	/**
	 * 上传mac地址
	 */
	public function actionsUploadmac()
	{
		$msg = array();
		framework::model('market/sysuser');
		framework::model('market/user');	
		$sysuser = new sysuser();
		$user = new user();
		$mac_detail = array();	//mac地址详细列表
		
		
	 	if (framework::post()) {  
	 		$union_id = framework::post('union_id');
			$admin_id = framework::post('admin_user');  
			$reg_start_time = framework::post('reg_start_time');   
			$reg_end_time = framework::post('reg_end_time');   
			$data_type = framework::post('data_type');
 
	        $validate[] = array('name' => '注册开始时间','value' => $reg_start_time,'regex' => array('require'));
	        $validate[] = array('name' => '注册结束时间','value' => $reg_end_time,'regex' => array('require')); 
	        $validate[] = array('name' => '数据类型','value' => $data_type,'regex' => array('require')); 
	        	        
	        $error = Validator::formValidator($validate);
	        
	        //如果当前时间大于本月10号，只能结算本月数据
	        if (time()> strtotime(date('Y-m-10'))) {
		        if (strtotime($reg_start_time) < strtotime(date('Y-m-01')) ) {
					$error[] = '注册开始时间只能为本月时间';
				}
	        } else {
	          if (strtotime($reg_start_time) < strtotime(date('Y-m-01', strtotime("-1 months"))) ) {
					$error[] = '注册开始时间小于上月时间';
				}
	        }
			if (strtotime($reg_start_time) > strtotime($reg_end_time)) {
				$error[] = '注册开始时间大于注册结束时间';
			}
		 
	        
	        $union_info = $user->getUserById($union_id);
			if (empty($union_info)) {
				$error[] = '渠道ID号错误';
			}
			if (isset($_FILES['upload'])) { 
				$pathinfo = pathinfo($_FILES['upload']["name"][0]);
				if ($pathinfo['extension'] != 'csv') {
					$error[] = '上传文件类型错误，请选择csv格式文件上传';
				} else {
					if ($_FILES['upload']['error'][0] == 0) {
						$param = array(
								'file' => $_FILES['upload'],
								'path' => HIGHPHP_PROJECT_DIR.'upload/',
								'size' => 5097152,
								'type' => array('csv'),
						); 
						
						framework::plugin('upload');
						$uploadfile = new upload($param); 
						$uploadfile->upload();
						$uploadinfo = $uploadfile->getSaveInfo();
						 
						$handle = fopen($uploadinfo['0']['path'],"r");
						while ($data = fgetcsv($handle, 1000, ",")) {
						    $num = count($data); 					     
						    for ($c=0; $c < $num; $c++) {
						        $mac_detail[$data[$c]] = 1;
						    }
						}
						fclose($handle); 
					}
				}
			} else {
				$error[] = '请选择要上传的文件';
			}
			if (!empty($error)) {
				$msg['error'] = $error;
				echo (json_encode($msg));
				return false; 
			}
			
			if (empty($error)) {
				
				
				$create_array = array( 
								'union_id' => $union_id,
								'admin_id' => $admin_id,
								'balance_type' => $union_info['balance_type'],
								'reg_start_time' => $reg_start_time,
								'reg_end_time' => $reg_end_time,
								'import_num' => count($mac_detail),
								'import_user_id' => $_SESSION['user_id'],
								'data_type' => $data_type,
								'status' => 0,
								'add_time' => date('Y-m-d H:i:s'),
								'upload_filename' => $uploadinfo['0']['saveas'],
								'upload_realname' => $uploadinfo['0']['name'],
				);	
				$mac_list_id = $this->finance_obj->createMacList($create_array);			
				
				if ($mac_list_id ) {
					
					foreach ($mac_detail as $key => $value) {
						$add_mac = array(
								'umli_id' => $mac_list_id,
								'mac' => $key,
								'status' => 0,
								'add_time' => date('Y-m-d H:i:s'),
						);
						$this->finance_obj->createMacDetail($add_mac);
					} 
					$msg['message'] = '导入成功';
				} else {
					$msg['message'] = '导入失败';
				}
			} 
			echo json_encode($msg);
			return false;
	 	}  
	 	
		$admin_user = $sysuser->getUserList();		
		framework::assign('admin_user', $admin_user);
	   
	}
		
	
	
}