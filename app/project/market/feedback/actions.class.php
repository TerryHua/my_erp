<?php
/**
 * 意见反馈模块
 * @author huaxiaofeng 2016-3-25 11:03:57
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
	
	/**
	 * 删除意见反馈记录
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
	 


		
	
	
}