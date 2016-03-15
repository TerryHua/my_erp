<?php
/**
 * 
 * 数学运算类
 * @author Administrator
 *
 */
class maths {
    /**
     * 
     * 加密
     * @param string $password
     */
    public static function encrypt($password) {
        $encrypt = 0X55;
        $password = trim ( $password );
        $num = strlen ( $password );
        $retstr = '';
        
        for($i = 0; $i < $num; $i ++) {
            $str = substr ( $password, $i, 1 );
            $ascii = ord ( $str );
            $tmp = dechex ( intval ( $ascii ^ $encrypt ) );
            $retstr .= strlen ( $tmp ) == 1 ? '0' . $tmp : $tmp;
        }
        return strtoupper ( $retstr );
    }
    /**
     * 
     * 解密
     * @param string $password
     */
    public static function decrypt($password) {
        $encrypt = 0X55;
        $password = trim ( $password );
        $num = floor ( strlen ( $password ) / 2 );
        $retstr = '';
        
        for($i = 0; $i < $num; $i ++) {
            $str = substr ( $password, $i * 2, 2 );
            $str = base_convert ( $str, 16, 10 );
            $str = intval ( $str ^ $encrypt );
            $retstr .= chr ( $str );
        }
        return $retstr;
    }
    /**
     * 
     * 概率0~100
     * @param string $percent
     */
    public static function random($percent) {
        $one = rand ( 1, 100 );
        $total = range ( 1, 100 );
        $tmpRange = array ();
        $i = 0;
        while ( $i < $percent ) {
            array_push ( $tmpRange, $total [$i] );
            $i += 1;
        }
        foreach ( $tmpRange as $v ) {
            if ($one == $v) {
                return true;
            }
        }
        return false;
    }
    /**
     * 
     * 获取十进制对应的2进制位标识
     * @param integer $num
     * @param integer $bit
     */
    public static function getbit($num, $bit) {
        $num = decbin ( $num );
        $num = strrev ( $num );
        if (substr ( $num, ($bit - 1), 1 )) {
            return 1;
        } else {
            return 0;
        }
    }
    public static function getbitArr($num,$bitarr,$and=0){
        $i=$j=0;        
        foreach ($bitarr as $v){
            if ($v){
                $j++;
                if (self::getbit($num, $v)){
                    if ($and==0){
                        return 1;
                    }else {
                        $i++;
                    }
                }
            }
        }
        if ($i==$j and $i>0){
            return 1;
        }
        return 0;
    }
}