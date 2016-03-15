<?php
class files {
    public static function getFileName($path) {
        preg_match_all ( '/[\w]+[-.][\w]+$/', $path, $matches );
        return $matches [0] [0];
    }
    
    /**
     * 
     * 获取文件,匹配字符串
     * @param unknown_type $filefullpath
     * @param unknown_type $txt
     * @param unknown_type $order
     */
    public static function getFileByRow($filefullpath, $txt, $order = 'DESC') {
        if ($order == 'DESC'){
            $arr = explode("\n", file_get_contents($filefullpath));
            $c = count($arr);
            for ($i=$c;$i>0;$i--){
                 if (strstr ( $arr[$i], $txt )) {
                     return str_replace ( "\n", "", $arr[$i] );
                 }
            }            
            return false;
        }
        $fp = @fopen ( $filefullpath, "r" );
        $res = '';
        while ( @$line = fgets ( $fp ) ) {
            if (strstr ( $line, $txt )) {
                fclose ( $fp );
                return str_replace ( "\n", "", $line );
            }
        }
        fclose ( $fp );                
        return false;
    }
}