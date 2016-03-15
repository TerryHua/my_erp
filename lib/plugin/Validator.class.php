<?php

class Validator
{

    public static function formValidator($validateArray)
    {
        /*
        $email = 'yyprince31232122';
        $price = '-30';


        $validateArray = array();

        $validateArray[] = array('name' => '邮箱','value' => $email,'regex' => array('require','length[1,255]','email'));
        $validateArray[] = array('name' => '价格','value' => $price,'regex' => array('require','number[1,200]','integer'));
        */


        $error = array();
        foreach($validateArray as $validate){

            $name = $validate['name'];
            $value = $validate['value'];
            if(trim($value) === ''&& in_array('require',$validate['regex'])){
                $error[] = "$name ".'为必填项';
                continue;
            }elseif(trim($value) === ''){
                continue;
            }
            foreach($validate['regex'] as $regex){
                switch(true){
                    case (substr($regex,0,6) == 'length'):
                        $length = str_replace('length[','',$regex);
                        $length = str_replace(']','',$length);
                        $lengthArray = explode(",", $length);
                        $valueLength = strlen($value);
                        if($valueLength < $lengthArray[0] || $valueLength > $lengthArray[1]){
                            $error[] = "$name ".'内容长度范围为'." {$lengthArray[0]} ".'—'." {$lengthArray[1]} ";
                            break 2;
                        }elseif($lengthArray[0]==$lengthArray[1]){
                        	$error[] =  $name.' 内容长度必须为 '. $lengthArray[0];
                        }

                        	continue 2;

                    case (substr($regex,0,7) == 'number['):   //范围两边值可取
                        $length = str_replace('number[','',$regex);
                        $length = str_replace(']','',$length);
                        $lengthArray = explode(",", $length);
                        if($value < $lengthArray[0] || $value > $lengthArray[1]){
                            $error[] = "$name ".'大小范围为'." {$lengthArray[0]} ".'—'." {$lengthArray[1]}";
                            break 2;
                        }
                        continue 2;
                    case (substr($regex,0,7) == 'number('):   //范围两边值不可取
                        $length = str_replace('number(','',$regex);
                        $length = str_replace(')','',$length);
                        $lengthArray = explode(",", $length);
                        if($value <= $lengthArray[0] || $value >= $lengthArray[1]){
                            $error[] = "$name ".'大小范围为'." {$lengthArray[0]} ".'—'." {$lengthArray[1]} 之间";
                            break 2;
                        }
                        continue 2;
                    case ($regex == 'email'):   //email验证
                        if(!preg_match("/^[a-zA-Z0-9_\.]+@[a-zA-Z0-9-]+[\.a-zA-Z]+$/",$value)){
                            $error[] = "$name ".'内容不是正确的电子邮箱地址';
                            break 2;
                        }
                        continue 2;
                    case ($regex == 'integer'): //正整数（可为0）
                        if(!preg_match("/^\d+$/",$value)){
                            $error[] = "$name ".'内容不是一个正数';
                            break 2;
                        }
                        continue 2;
                    /*
                    case ($regex == 'integer1'): //正整数（不可为0）
                        if(!preg_match("/^[1-9]+\d*$/",$value)){
                            $error[] = "$name 内容不是一个不为0的正整数";
                            break 2;
                        }
                        continue 2;
                    */
                    case ($regex == 'positive'):   //正数（可为0）
                        if(!preg_match("/^\d*(.\d*)$/",$value)){
                            $error[] = "$name ".'内容不是一个正数';
                            break 2;
                        }
                        continue 2;
                    /*
                    case ($regex == 'positive1'):   //正数（不可为0）
                        if(!preg_match("/^[1-9]\d*(.\d+)*$|^0.\d*[1-9]+\d*$/",$value)){
                            $error[] = "$name 内容不是一个不为0的正数";
                            break 2;
                        }
                        continue 2;
                    */
                    case ($regex == 'telephone'): //电话号码

                        continue 2;
                    case ($regex == 'noCharacter'): //汉字
                        if(preg_match("/[\x{4e00}-\x{9fa5}]+/u",$value)){
                            $error[] = "$name ".'内容中不能有汉字';
                            break 2;
                        }
                        continue 2;
                    case (substr($regex,0,5) == 'equal'): //两值相比较
                        $compareValue = str_replace('equal[','',$regex);
                        $compareValue = str_replace(']','',$compareValue);
                        $compareArray = explode(",", $compareValue);
                        if ($compareArray[0] != $compareArray[1]) {
                            $error[] = "$name ".'与前一个值比较不相等';
                            break 2;
                        }
                        continue 2;
                    case ($regex == 'specialCharDesc'):
                        $specialChar = array("tools","sample","Electronics","Gift"," Personal gift","Personal sample");
                        if(in_array(trim($value),$specialChar)){
                            $error[] = "$name ".'内容不能有特殊字符';
                            break 2;
                        }
                        continue 2;
                    case ($regex == 'onlyNumber'):
                        if(preg_match("/^\d+$/",$value)){
                            $error[] = "$name ".'内容不能只有数字';
                            break 2;
                        }
                        continue 2;
                    case ($regex == 'msn_qq'):
                        if (!preg_match("/^[0-9]{5,15}$|^[a-zA-Z0-9_\.]+@[a-zA-Z0-9-]+\.[a-zA-Z]+$/",$value)){
                            $error[] = "$name ".'内容不是正确的MSN或QQ';
                            break 2;
                        }
                        continue 2;
                    

                }
            }
        }
        return $error;
    }

	public static function Validate($validate)
	{
		$name = $validate['name'];
		$error = '';
        $value = $validate['value'];
        if(trim($value) === ''&& in_array('require',$validate['regex'])){
            $error = "$name ".'为必填项';
			return $error;
        }elseif(trim($value) === ''){
               return ;
         }
		foreach($validate['regex'] as $regex){
                switch(true){
                    case (substr($regex,0,6) == 'length'):
                        $length = str_replace('length[','',$regex);
                        $length = str_replace(']','',$length);
                        $lengthArray = explode(",", $length);
                        $valueLength = strlen($value);
                        if($valueLength < $lengthArray[0] || $valueLength > $lengthArray[1]){
                            $error = "$name ".'内容长度范围为'." {$lengthArray[0]} ".'—'." {$lengthArray[1]} ";
                            break 2;
                        }
                        continue 1;
                    case (substr($regex,0,7) == 'number['):   //范围两边值可取
                        $length = str_replace('number[','',$regex);
                        $length = str_replace(']','',$length);
                        $lengthArray = explode(",", $length);
                        if($value < $lengthArray[0] || $value > $lengthArray[1]){
                            $error = "$name ".'大小范围为'." {$lengthArray[0]} ".'—'." {$lengthArray[1]}";
                            break 2;
                        }
                        continue 1;
                    case (substr($regex,0,7) == 'number('):   //范围两边值不可取
                        $length = str_replace('number(','',$regex);
                        $length = str_replace(')','',$length);
                        $lengthArray = explode(",", $length);
                        if($value <= $lengthArray[0] || $value >= $lengthArray[1]){
                            $error = "$name ".'大小范围为'." {$lengthArray[0]} ".'—'." {$lengthArray[1]} 之间";
                            break 2;
                        }
                        continue 1;
                    case ($regex == 'email'):   //email验证
                        if(!preg_match("/^[a-zA-Z0-9_\.]+@[a-zA-Z0-9-]+[\.a-zA-Z]+$/",$value)){
                            $error = "$name ".'内容不是正确的电子邮箱地址';
                            break 2;
                        }
                        continue 1;
                    case ($regex == 'integer'): //正整数（可为0）
                        if(!preg_match("/^\d+$/",$value)){
                            $error = "$name ".'内容不是一个正数';
                            break 2;
                        }
                        continue 1;
                    /*
                    case ($regex == 'integer1'): //正整数（不可为0）
                        if(!preg_match("/^[1-9]+\d*$/",$value)){
                            $error[] = "$name 内容不是一个不为0的正整数";
                            break 2;
                        }
                        continue 2;
                    */
                    case ($regex == 'positive'):   //正数（可为0）
                        if(!preg_match("/^\d*(.\d*)$/",$value)){
                            $error = "$name ".'内容不是一个正数';
                            break 2;
                        }
                        continue 1;
                    /*
                    case ($regex == 'positive1'):   //正数（不可为0）
                        if(!preg_match("/^[1-9]\d*(.\d+)*$|^0.\d*[1-9]+\d*$/",$value)){
                            $error[] = "$name 内容不是一个不为0的正数";
                            break 2;
                        }
                        continue 2;
                    */
                    case ($regex == 'telephone'): //电话号码

                        continue 1;
                    case ($regex == 'noCharacter'): //汉字
                        if(preg_match("/[\x{4e00}-\x{9fa5}]+/u",$value)){
                            $error = "$name ".'内容中不能有汉字';
                            break 2;
                        }
                        continue 1;
                    case (substr($regex,0,5) == 'equal'): //两值相比较
                        $compareValue = str_replace('equal[','',$regex);
                        $compareValue = str_replace(']','',$compareValue);
                        $compareArray = explode(",", $compareValue);
                        if ($compareArray[0] != $compareArray[1]) {
                            $error = "$name ".'与前一个值比较不相等';
                            break 2;
                        }
                        continue 1;
                    case ($regex == 'specialCharDesc'):
                        $specialChar = array("tools","sample","Electronics","Gift"," Personal gift","Personal sample");
                        if(in_array(trim($value),$specialChar)){
                            $error = "$name ".'内容不能有特殊字符';
                            break 2;
                        }
                        continue 1;
                    case ($regex == 'onlyNumber'):

                        if(preg_match("/^\d+$/",$value)){
                            $error = "$name ".'内容不能只有数字';
                            break 2;
                        }
                        continue 1;
                    case ($regex == 'msn_qq'):
                        if (!preg_match("/^[0-9]{5,15}$|^[a-zA-Z0-9_\.]+@[a-zA-Z0-9-]+\.[a-zA-Z]+$/",$value)){
                            $error = "$name ".'内容不是正确的MSN或QQ';
                            break 2;
                        }
                        continue 1;
					
                }
            }
            return $error;
	}


 
}
?>