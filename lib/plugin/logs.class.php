<?php
/**
 * 
 * 日志函数
 * @author Administrator
 *
 */
class logs{
    public static function emsg($msg){
        if (is_array($msg)){
            $msg = var_export($msg,1);
        }
        echo date("Y-m-d H:i:s")." $msg\n";
    }
}