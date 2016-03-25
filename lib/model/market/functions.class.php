<?php
/**
 * 后台方法model，放置一些常用的函数
 * @author huaxiaofeng
 * @version 1.0 2013-4-11 15:55:08
 */
class functions
{
     
	/**
	 * 对数字格式化输出
	 */
	public static function numFormat($number, $decimals=2, $thousands_sep=',', $dec_point='.')
	{
		return number_format($number, $decimals, $dec_point, $thousands_sep);
	}
	
	/**
	 * 将秒钟转化成时分秒格式
	 * @param unknown_type $params
	 */
	public static function secFormat($params)
	{
	   if ($params) {
			$return = '';  
	         
			$hour = floor($params/3600);
			$min = floor(($params%3600)/60);
			$sec = ceil(($params%3600)%60);
			
			if ($hour) {
				return $hour.'时'.$min.'分'.$sec.'秒';
			}
			if ($min) {
				return $min.'分'.$sec.'秒';
			}
			if ($sec) {
				return $sec.'秒';
			}
	
			
	   } else {
			return '0秒';
	   }
	} 
	
	/**
	 * 将数字转换成k m g 等数字来显示
	 * @param unknown_type $size
	 */
	public static function numToChar($size, $decimals=2, $thousands_sep=',', $dec_point='.' )  
	{  
		if ($size < 1000) {  
	    	return number_format($size, $decimals, $dec_point, $thousands_sep).' ';  
	    }  
	    if ($size < 1000000) {  
	    	return number_format($size/1000, $decimals, $dec_point, $thousands_sep).' K';
	    }  
	    if ($size < 1000000000) { 
			return number_format($size / 1000000, $decimals, $dec_point, $thousands_sep).' M';  
		}  
		if ($size < 1000000000000) {  
			return number_format($size / 1000000000, $decimals, $dec_point, $thousands_sep).' G';  
	    }  
	}


	/**
	 * 描述转变为分秒' '' 模式显示
	 */
	public static function secToChar($number)
	{
		$str = '';
		$str .= str_replace(strstr($number, '.'), '', $number)."'";
		$s = floor(strstr($number, '.')*60) ;
		if ($s<10) {
			$str .= '0'.$s;
		} else {
			$str .= $s;
		}
		$str .= "''";
		return $str;
	}
	
	
	public static function secToSample($params)
	{
	  if ($params) {
			$return = '';  
	         
			$hour = floor($params/3600);
			$min = floor(($params%3600)/60);
			$sec = ceil(($params%3600)%60);
			
			if ($hour) {
				return $hour.':'.$min."'".$sec."''";
			}
			if ($min) {
				return $min."'".$sec."''";
			}
			if ($sec) {
				return $sec."''";
			}
	
			
	   } else {
			return "0''";
	   }
		
	}
	
	public static function outSpan($number, $title_number)
	{
		return '<span title="'.$title_number.'" >'.$number.'</span>';
	}


	
	/**
	 * 格式化输出函数
	 * @number, 要处理的数字
	 * @type 要输出的格式 1.122,123,1 2:12 K/M/G. 3 23.23%  4 时间格式化
	 */
	public static function formatOut($number, $decimals=2, $type=1,$show_span=0)
	{
		$thousands_sep=',';
		$dec_point='.';
		$return_num = 0;
		switch ($type) {
			case 1:
				$return_num = self::numFormat($number, $decimals, $thousands_sep, $dec_point);
				break;
			case 2:
				$return_num = self::numToChar($number, $decimals, $thousands_sep, $dec_point );
				break;  
			case 3:
				$return_num = self::numFormat($number*100, $decimals, $thousands_sep, $dec_point).'%';
				break;
			case 4:
				$return_num = self::secToSample($number);
				break;
			default:
				$return_num = self::numFormat($number, $decimals, $thousands_sep, $dec_point);
				break;			
		}
		if ($show_span == 1) {
			$number = self::numFormat($number, $decimals, $thousands_sep, $dec_point);
			$return_num = self::outSpan($number, $return_num);
		}
		return $return_num;	
	}

	
	/**
	 * 将起始日期和结束日期分割成日期数组
	 */
	public static function createDayArr($start_date, $end_date)
	{
		$return = array();
		$div = strtotime($end_date)-strtotime($start_date);
		$day_lenth = $div/3600/24; 
		for ($i = 0; $i <= $day_lenth; $i++) {
			$return[] = date('Y-m-d', strtotime($end_date ."- $i days"));
		}
		return $return;
	}
	
		
	/** 
	 * 人民币小写转大写 
	 * 
	 * @param string $number 数值 
	 * @param string $int_unit 币种单位，默认"元"，有的需求可能为"圆" 
	 * @param bool $is_round 是否对小数进行四舍五入 
	 * @param bool $is_extra_zero 是否对整数部分以0结尾，小数存在的数字附加0,比如1960.30， 
	 *             有的系统要求输出"壹仟玖佰陆拾元零叁角"，实际上"壹仟玖佰陆拾元叁角"也是对的 
	 * @return string 
	 */ 
	public static function numToRmb($number = 0, $int_unit = '元', $is_round = TRUE, $is_extra_zero = FALSE) 
	{ 
	    // 将数字切分成两段 
	    $parts = explode('.', $number, 2); 
	    $int = isset($parts[0]) ? strval($parts[0]) : '0'; 
	    $dec = isset($parts[1]) ? strval($parts[1]) : ''; 
	 
	    // 如果小数点后多于2位，不四舍五入就直接截，否则就处理 
	    $dec_len = strlen($dec); 
	    if (isset($parts[1]) && $dec_len > 2) { 
	        $dec = $is_round 
	                ? substr(strrchr(strval(round(floatval("0.".$dec), 2)), '.'), 1) 
	                : substr($parts[1], 0, 2); 
	    } 
	 
	    // 当number为0.001时，小数点后的金额为0元 
	    if(empty($int) && empty($dec)) { 
	        return '零'; 
	    } 
	 
	    // 定义 
	    $chs = array('0','壹','贰','叁','肆','伍','陆','柒','捌','玖'); 
	    $uni = array('','拾','佰','仟'); 
	    $dec_uni = array('角', '分'); 
	    $exp = array('', '万'); 
	    $res = ''; 
	 
	    // 整数部分从右向左找 
	    for($i = strlen($int) - 1, $k = 0; $i >= 0; $k++) { 
	        $str = ''; 
	        // 按照中文读写习惯，每4个字为一段进行转化，i一直在减 
	        for($j = 0; $j < 4 && $i >= 0; $j++, $i--) { 
	            $u = $int{$i} > 0 ? $uni[$j] : ''; // 非0的数字后面添加单位 
	            $str = $chs[$int{$i}] . $u . $str; 
	        } 
	        //echo $str."|".($k - 2)."<br>"; 
	        $str = rtrim($str, '0');// 去掉末尾的0 
	        $str = preg_replace("/0+/", "零", $str); // 替换多个连续的0 
	        if(!isset($exp[$k])) { 
	            $exp[$k] = $exp[$k - 2] . '亿'; // 构建单位 
	        } 
	        $u2 = $str != '' ? $exp[$k] : ''; 
	        $res = $str . $u2 . $res; 
	    } 
	 
	    // 如果小数部分处理完之后是00，需要处理下 
	    $dec = rtrim($dec, '0'); 
	 
	    // 小数部分从左向右找 
	    if(!empty($dec)) { 
	        $res .= $int_unit; 
	 
	        // 是否要在整数部分以0结尾的数字后附加0，有的系统有这要求 
	        if ($is_extra_zero) { 
	            if (substr($int, -1) === '0') { 
	                $res.= '零'; 
	            } 
	        } 
	 
	        for($i = 0, $cnt = strlen($dec); $i < $cnt; $i++) { 
	            $u = $dec{$i} > 0 ? $dec_uni[$i] : ''; // 非0的数字后面添加单位 
	            $res .= $chs[$dec{$i}] . $u; 
	        } 
	        $res = rtrim($res, '0');// 去掉末尾的0 
	        $res = preg_replace("/0+/", "零", $res); // 替换多个连续的0 
	    } else { 
	        $res .= $int_unit . '整'; 
	    } 
	    return $res; 
	} 
	
    
}