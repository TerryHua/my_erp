<?php
/*
 * ams 类库
 * author:wanluoliang date:2011-6-28
 */
class ams2 {
	var $url='http://ams.uxin.com:8080/ams2/'; //ams服务器IP
	var $key="1bb762f7ce24ceee"; //密钥
	var $codeArray=array(
		'0'=>'成功',
		'9999'=>'其他错误',
		'10000'=>'uxin号码为空',
		'1'=>'随机数错误',
		'2'=>'uxin号不存在',
		'3'=>'数据库异常',
		'4'=>'号码池为空',
		'5'=>'手机号码已经绑定了其他SKY号码',
		'6'=>'手机号码已经绑定了当前kc号码',
		'7'=>'操作太频繁，请稍候提交',
		'8'=>'验证码过期，或没有申请验证码',
		'9'=>'手机号码未绑定',
		'10'=>'密码错误',
		'11'=>'手机号码重复',
		'12'=>'MAC验证失败',
		'13'=>'sessionkey不正确',
		'14'=>'请求参数错误',
		'15'=>'MAC日期错误：访问者的当前日期与 ams服务器的日期误差超过了1天',
	);

	/*
	 * 查询SKY号绑定的手机号
	 * $account  账号
	 * $logintype 登陆类型
	 */
	function get_bind_mobile($account,$logintype='kc') {
		if(empty($account)){
			return 'result=10000';
		}
		$info="info.act?account=$account&accounttype=$logintype";
		$pma=$this->getUrlPma();
		$url=$this->url.$info.$pma;
		$result=file_get_contents($url);
		$arr=$this->get_ams_result($result);
		
		return $this->get_ams_value($arr,'number');
	}
	
	/*
	 * 用于快速登录时，查询SKY号对应的密码
	 * $account  账号
	 * $logintype 登陆类型
	 */
	function get_password($account,$logintype='kc') {
		if(empty($account)){
			return 'result=10000';
		}
		$info="info.act?account=$account&accounttype=$logintype";
		$pma=$this->getUrlPma();
		$url=$this->url.$info.$pma;
		$result=file_get_contents($url);
		$arr=$this->get_ams_result($result);
		$password = $this->get_ams_value($arr,'password');
		$password = $this->convertToChar($password);
		$password = $this->rc4($this->key,$password);
		
		return $password;
	}
	
	function getUserInfo($account,$logintype='mobile'){
		if(empty($account)){
			return 'result=10000';
		}
		
		$info="info.act?account=$account&accounttype=$logintype";
		$pma=$this->getUrlPma();
		$url=$this->url.$info.$pma;
		$result=file_get_contents($url);
		$arr=$this->get_ams_result($result);
		$password = $this->get_ams_value($arr,'password');		
		$password = $this->convertToChar($password);
		$password = $this->rc4($this->key,$password);
		
		$res = array();
		$res['password']=$password;
		$res['uid']=$this->get_ams_value($arr,'uid');
		$res['number']=$this->get_ams_value($arr,'number');
		
		return $res;		
	}
	
	function get_password1($account,$logintype) {
		if(empty($account)){
			return 'result=10000';
		}
		$info="info.act?account=$account&accounttype=$logintype";
		$pma=$this->getUrlPma();
		$url=$this->url.$info.$pma;
		$result=file_get_contents($url);
		
		/*
		$arr=$this->get_ams_result($result);
		$password = $this->get_ams_value($arr,'password');
		$password = $this->convertToChar($password);
		$password = $this->rc4($this->key,$password);
		*/
		return $result;
	}
	

	/*
	 * 注册
	 * $mobile 手机号
	 * $invitedby 推荐人
	 * $invitedflag 推荐类型
	 * $from 来源
	 */
	function reg($mobile,$invitedby,$invitedflag,$from) {
		$ip=$this->getIP();
		$brand='youxin';
		$location='youxin';
		$info="mobilereg.act?ip=$ip&number=$mobile&invitedby=$invitedby&invitedflag=$invitedflag&from=$from&location=$location&brand=$brand";		
		$pma=$this->getUrlPma();
		$url=$this->url.$info.$pma;
		$result=file_get_contents($url);
		
		return $this->replaceResult($result);
	}

	/*
	 * $account  登陆账号
	 * $password  登陆密码
	 * $from  登陆来源
	 * $logintype  登陆方式
	 */
	function login($account,$password='',$from='wap',$loginType='kc',$md5Pwd=false) {
		if(empty($account)){
			return 'result=10000';
		}
		$ip=$this->getIP();
		if(!$md5Pwd){
			$password=$password?md5($password): "";
		}
		$terminaltype = 'youxin';
		$info="login.act?account=$account&loginType=$loginType&password=$password&from=$from&ip=$ip&terminaltype=$terminaltype";
		$pma=$this->getUrlPma();
		$url=$this->url.$info.$pma;
		$result=file_get_contents($url);
		
		//echo $this->replaceResult($result); 
		return $this->replaceResult($result);
	}

	/*
	 * $number  手机号码
	 * $uid  kc号码
	 * $password  md5转换后的密码
	 */
	function bindapply($uid,$password,$number) {
		if(empty($uid)){
			return 'result=10000';
		}
		$password=md5($password);
		$info="bindapply.act?number=$number&uid=$uid&password=$password";
		$pma=$this->getUrlPma();
		$url=$this->url.$info.$pma;
		$result=file_get_contents($url);
		
		return $this->replaceResult($result);
	}

	/*
	 * $uid  kc号码
	 * $password  密码
	 * $verifyCode  验证码，24小时内有效
	 */
	function bindsubmit($uid,$number,$verifyCode) {
		if(empty($uid)){
			return 'result=10000';
		}
		$info="bindsubmit.act?uid=$uid&number=$number&verifyCode=$verifyCode";
		$pma=$this->getUrlPma();
		$url=$this->url.$info.$pma;
		$result=file_get_contents($url);
		
		return $this->replaceResult($result);
	}

	/*
	 * $uid kc号码
	 * $password 密码
	 * $number 手机号码
	 */
	function unbind($uid,$password,$number) {
		if(empty($uid)){
			return 'result=10000';
		}
		$password=md5($password);
		$info="unbind.act?number=$number&uid=$uid&password=$password";
		$pma=$this->getUrlPma();
		$url=$this->url.$info.$pma;
		$result=file_get_contents($url);
		
		return $this->replaceResult($result);
	}

	/*
	 * $uid kc号码
	 * $oldpassword  原来的密码
	 * $newpassword  新的密码
	 */
	function changePassword($uid,$oldpassword,$newpassword) {
		if(empty($uid)){
			return 'result=10000';
		}
		//原密码 rc4加密的16进制字符串
		$oldpassword=$this->rc4($this->key,$oldpassword);
		$oldpassword=$this->convertToNumber($oldpassword);
		//新密码 rc4加密的16进制字符串
		$newpassword=$this->rc4($this->key,$newpassword);
		$newpassword=$this->convertToNumber($newpassword);

		$info="changepassword.act?uid=$uid&oldpassword=$oldpassword&newpassword=$newpassword";
		$pma=$this->getUrlPma();
		$url=$this->url.$info.$pma;
		$result=file_get_contents($url);
		
		return $this->replaceResult($result);
	}
	
	//新修改密码接口
	public function changePasswordNew($uid,$oldpassword,$newpassword){
		if(empty($uid)){
			//return 'result=10000';
			return $result['result'] = '10000';
		}
		$KEY= 'k1oET&Yh7@EQnp2XdTP1o/Vo=';
		//原密码 rc4加密的16进制字符串
		$oldpassword=$this->rc4($KEY,$oldpassword);
		$oldpassword=$this->convertToNumber($oldpassword);
		//新密码 rc4加密的16进制字符串
		$newpassword=$this->rc4($KEY,$newpassword);
		$newpassword=$this->convertToNumber($newpassword);
		
		$sn = '286';
		$sign = md5($sn.$KEY);
		$url = "http://im.uxin.com:8887/changepwd?sn=$sn&sign=$sign&uid=$uid&oldpwd=$oldpassword&newpwd=$newpassword";
		//echo $url;
		$result = file_get_contents($url);
		$result = json_decode($result,true);
		//print_r($result);
		return $result;				
	}

	/*
	 * 转换为十六进制
	 */
	function convertToNumber($str) {
		$length=strlen($str);
		for($i=0;$i<$length;$i++) {
			$istr = dechex(ord($str[$i]));
			if(strlen($istr)==1){
				$istr = '0'.$istr;
			}
			$numberStr.=$istr;
		}
		return $numberStr;
	}

	/*
	 * 十六进制转为字符
	 */
	function convertToChar($hexdata) {
		$str='';
		for($i=0;$i<strlen($hexdata);$i+=2) {
			$str.=chr(hexdec(substr($hexdata,$i,2) ) );
		}
		return $str;
	}

	/*
	 * rc4加密算法
	 * $pwd 密钥
	 * $data 要加密的数据
	 */
	function rc4($pwd,$data) { //$pwd密钥　$data需加密字符串
		$cipher='';
		$key[]="";
		$box[]="";

		$pwd_length=strlen($pwd);
		$data_length=strlen($data);

		for($i=0;$i<256;$i++) {
			$key[$i]=ord($pwd[$i%$pwd_length]);
			$box[$i]=$i;
		}

		for($j=$i=0;$i<256;$i++) {
			$j=($j+$box[$i]+$key[$i])%256;
			$tmp=$box[$i];
			$box[$i]=$box[$j];
			$box[$j]=$tmp;
		}

		for($a=$j=$i=0;$i<$data_length;$i++) {
			$a=($a+1)%256;
			$j=($j+$box[$a])%256;

			$tmp=$box[$a];
			$box[$a]=$box[$j];
			$box[$j]=$tmp;

			$k=$box[( ($box[$a]+$box[$j])%256)];
			$cipher.=chr(ord($data[$i])^$k);
		}

		return $cipher;
	}

	/*
	 * 将参数解析成数组
	 */
	function get_ams_result($pma) {
		$arr=array();
		$result=explode("?",$pma);
		$result=explode("&",$result[0]);
		foreach($result as $value) {
			$arr[]=explode("=",$value);
		}
		return $arr;
	}

	/*
	 * 取数组里的值
	 * $arr 数组
	 * $key 键名
	 */
	function get_ams_value($arr,$key) {
		$key=strtolower($key);
		foreach($arr as $value) {
			foreach($value as $value2) {
				$value2=strtolower($value2);
				if($value2==$key) {
					return $value[1];
				}
			}
		}
		return false;
	}

	/*
	 * 获取返回的状态信息
	 */
	function getReturnMsg($returnStr) {
		if(!empty($returnStr) ) {
			$arr=$this->get_ams_result($returnStr);
			$key=$this->get_ams_value($arr,'code');
			return $this->codeArray[$key];
		}
	}
	
	/*
	 * url的mac签名参数
	 */
	function getUrlPma(){
		$macip = '118.194.2.123';
		$macdate = date('Ymd');
		$macrand = date('dHis').rand(10,99999);
		$mac = md5( $macip.$macdate.$macrand.'55dffcdd6c6663e29dc59560d124edc7' );
		return '&macip='.$macip.'&macdate='.$macdate.'&macrand='.$macrand.'&mac='.$mac;
	}
	
	//替换参数code
	function replaceResult($result){
		return str_replace('code=','result=',$result);
	}
	
	//获取IP
	function getIP() { 
		if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
			$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		}else if (isset($_SERVER["HTTP_CLIENT_IP"])){
			$ip = $_SERVER["HTTP_CLIENT_IP"];
		}else if (isset($_SERVER["REMOTE_ADDR"])){
			$ip = $_SERVER["REMOTE_ADDR"];
		}else{
			$ip = "Unknown";
		}
		$ip = explode(',',$ip);
		$ip = $ip[0];
		return $ip;
	}
}

?>
