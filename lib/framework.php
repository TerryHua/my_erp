<?php
class framework {
    public static $timestart = 0; //程序执行开始时间
    public static $memStart;//内测使用
    public static $project; //
    public static $projects; //允许访问的工程名
    public static $module; //模块名称
    public static $action; //动作名称
    public static $getArr = array (); //请求数组
    public static $debug = 1; //是否调试模式
    public static $val = array ();
    public static $viewed = false; //
    public static $cacheTime = 86400; //秒
    public static $cachePage = array (); //缓存页面
    public static $logstatus = false; //写日志状态
    public static $lastLogtime = 0;//上次写日志时间
    public static $logfp; //写日志对象
    public static $cip; //客户端ip
    public static function run() {
        self::$timestart = microtime ( true );
        self::$cip = self::getCIp ();
        self::$memStart = memory_get_usage();
        unset ( $_GET );
        $uri2 = strstr ( HIGHPHP_THIS_PAGE, '?' ); 
        $parmarr = explode ( '/', str_replace ( array ($uri2, HIGHPHP_WWW_HOST, 'index.php/', 'index.php', '.html', '.htm' ), '', HIGHPHP_THIS_PAGE ) );
        $i = 1;

        if (!self::$project) self::$project = 'index'; //默认项目
        if (!self::$module) self::$module = 'index'; //默认模块
        if (!self::$action) self::$action = 'index'; //默认动作

        if ($parmarr [0] == 'dev') {
            self::$debug = 1;
            array_shift ( $parmarr );
        }

        if (! empty ( $parmarr ) and in_array ( $parmarr [0], self::$projects ) and count ( $parmarr ) % 3 == 0) { //所有的项目
            $i = 2;
            self::$project = $parmarr [0]; 

        }
        $m = $i + 1;
        $n = $i - 1;
        $o = $n + 1;
        foreach ( $parmarr as $k => $v ) {
            if ($k > $i) {
                if (($k + $n) % 2 == 0 and $v) {
                    self::$getArr [$v] = @$parmarr [$k + 1];
                }
            } elseif ($k == $n and $v) {
                self::$module = $v;
            } elseif ($k == $o and $v) {
                self::$action = $v;
            }
        }
 

        if ($uri2) {
            $arr1 = explode ( '&', substr ( $uri2, 1 ) );
            foreach ( $arr1 as $v ) {
                $arr2 = split ( '=', $v );
                self::$getArr [$arr2 [0]] = $arr2 [1];
            }
        }

        $isCachePage = 0;
        if (self::$cachePage) {
            foreach ( self::$cachePage as $v ) {
                if (substr ( $v, - 1 ) != '*') {
                    if (HIGHPHP_THIS_PAGE == $v) {
                        $isCachePage = 1;
                        break;
                    }
                } else {
                    if (strstr ( HIGHPHP_THIS_PAGE, substr ( $v, 0, - 1 ) )) {
                        $isCachePage = 1;
                        break;
                    }
                }
            }
        }
        if ($isCachePage and self::$cacheTime > 0) {            
            $md5k = md5 ( HIGHPHP_THIS_PAGE );
            $dir = HIGHPHP_CACHE_DIR. 'page/'.framework::$project.'/'.substr($md5k,0,1).'/'.substr($md5k,1,1).'/'.substr($md5k,1,1).'/';
            if (!is_dir($dir)) mkdir($dir, 0777, 1);
            $cacheFile = $dir.$md5k.'.tpl'; 
            if (file_exists ( $cacheFile ) and (filemtime ( $cacheFile ) + self::$cacheTime > time ())) {
                echo file_get_contents ( $cacheFile );
            } else {
                ob_start ();
                self::app ();
                $c = ob_get_contents ();
                file_put_contents ( $cacheFile, $c );
            }
        } else { 
            self::app ();
        }
        
        self::end ();
    }
    public static function assign($p, $res, $add = false) {
        if ($add) {
            self::$val [$p] = self::$val [$p] . $res;
        } else {
            self::$val [$p] = $res;
        }
    }
    public static function view($tplFileName = null) {
        foreach ( self::$val as $k => $v ) {
            $$k = $v;
        }
        self::$viewed = true;
        
        include self::temp ( $tplFileName );
    }
    /**
     * 
     * 获取模版文件路径
     * @param unknown_type $tplFileName
     */
    public static function temp($tplFileName = null) {
        $tplFilePath = HIGHPHP_PROJECT_PATH . self::$project . '/' . self::$module . '/templates/';
        if ($tplFileName) {
            $trueFilePath = $tplFilePath . $tplFileName . '.tpl.php';
        } else {
            $trueFilePath = $tplFilePath . self::$action . '.tpl.php';
        }        
        if (! file_exists ( $trueFilePath )) {
            if ($tplFileName) {
                $trueFilePath = HIGHPHP_TEMP_PATH . $tplFileName . '.tpl.php';
            }
            if (! file_exists ( $trueFilePath )) {
                self::end ( "Template file $trueFilePath is not exist", 2 );
            }
        }        
        return $trueFilePath;
    }
    //$type= page |db |all
    public static function clearCache($type = 'all') {
        $dir = HIGHPHP_CACHE_DIR;
        if ($type != 'all') {
            $dir = $dir . $type . '/';
        }
        self::deleteCacheDir ( $dir );
    }
    //强制删除缓存文件 $dir：文件夹目录 $dirDaysBefore：是指定 删除文件夹内多少天前的数据
    public static  function deleteCacheDir($dir,$dirDaysBefore=null) {
        //echo $dir . "<br>";
        if (is_dir ( $dir )) {
            echo "$dir \n";
            $dh = opendir ( $dir ); //打开目录  //列出目录中的所有文件并去掉 . 和 ..  
            while ( false !== ($file = readdir ( $dh )) ) {
                if ($file != "." && $file != "..") {
                    $fullpath = $dir . $file;     
                    if (! is_dir ( $fullpath ) ) {  
                        if ($dirDaysBefore){                
                            $t = strtotime(date("Y-m-d 23:59:59",strtotime ( "-$dirDaysBefore days" )));
                            if (filemtime ( $fullpath ) > $t) {                                                            
                                continue;
                            }
                        }                      
                        echo "DEL FILE: $fullpath \n";
                        self::logWrite('CLEAR CACHE '.$fullpath,2);
                        unlink ( $fullpath ); //删除目录中过期的文件
                        usleep(5000);
                    } else {
                        self::deleteCacheDir ( $fullpath . '/' ,$dirDaysBefore);
                    }
                }
            }
        }
    }
    private static function app() {   

        $file = HIGHPHP_PROJECT_PATH . self::$project . '/' . self::$module . '/actions.class.php';
        
        if (! file_exists ( $file ))
            self::end ( "Class file $file is not exist", 2 );

        include $file;
        $module = self::$module . 'Actions';
        if (! class_exists ( $module ))
            self::end ( "Class $module is not exist  in $file", 2 );
 

        $m = new $module ();
        $a = 'actions' . ucfirst ( self::$action );
        if (! method_exists ( $m, $a )) {
            self::end ( " function $a() is not exist in $file", 2 );
        }
  
        $res = $m->$a ();
        if ($res !== false and self::$viewed === false) {
            self::view ();
        }
    }
    public static function end($msg = null, $level = 0) {
        $logmsg = "%s Request:%s [%s] %s runtime in %s ms ";
        $timeend = microtime ( true );
        $runtime = round ( ($timeend - self::$timestart) * 1000, 1 );
        if ($runtime>500){
            $level=1;
        }
        if ($level == 0) {
            $lmsg = 'Info';
            $tcolor = '#fff';
        }
        if ($level == 1) {
            $lmsg = 'Warning';
            $tcolor = 'yellow';
        }
        if ($level == 2) {
            $lmsg = 'Error';
            $tcolor = 'red';
        }
        
        $logmsg = sprintf ( $logmsg, self::$cip, HIGHPHP_THIS_PAGE, $lmsg, $msg, $runtime );
        self::logWrite ( $logmsg, $level );
        if (self::$debug == 1) {
            if (!strstr(HIGHPHP_THIS_PAGE, 'http')){
                echo "$msg \n";
                return;
            }
            $pmsg = '<div style="font-family:verdana, Arial, Tahoma, Helvetica, sans-serif;position: fixed;z-index: 1000;width:100%%; font-size:13px;bottom:0px;">
            <div style="-webkit-border-radius: 10px;-moz-border-radius: 10px;border-radius: 10px;border:solid 3px #bbb;background:#000;color:#fff;width:1000px;margin-left:auto;margin-right:auto;">
            <span style="padding:10px;display:block;"><strong style="color:%s">Frame %s:</strong> %s</span> 
            <span style="padding:10px;color:#888;display:block;font-size:12px;">Process runtime in <strong style="color:#fff">%s</strong> ms [%s]</span>
            </div>
            <div>';
            $printmsg = sprintf ( $pmsg, $tcolor, $lmsg, $msg, $runtime, self::$cip );
            echo $printmsg;
        } else {
            if (HIGHPHP_THIS_PAGE != HIGHPHP_WWW_HOST and $level == 2 and self::$debug == 0 and preg_match ( '/^http/', HIGHPHP_THIS_PAGE )) {
                self::location ( HIGHPHP_WWW_HOST );
            }
        }     
        die ();    
    }
    public static function logWrite($msg, $level = 0, $pre = 'runlog',$timeformat='H',$closefpAfterWrite=false) {
        if ($level < HIGHPHP_LOG_LEVEL) {
            return false;
        }        
        if (self::$logstatus==true and $timeformat!=''){
            if (date("$timeformat",self::$lastLogtime)!=  date ( $timeformat )){
                self::$logstatus = false;
            }
        }
        if (self::$logstatus == false) {
            $filepath = HIGHPHP_LOG_DIR .self::$project.'/'. date ( "Ymd" ) . '/';
            if (! file_exists ( $filepath )) {
                mkdir ( $filepath, 0777, 1 );
            }
            $f = $filepath . $pre . date ( $timeformat ) . '.log';
            self::$logfp = fopen ( $f, 'a+' );
            self::$logstatus = true;
            self::$lastLogtime = time();
        }
        $msg = date ( "Y-m-d H:i:s " ) . $msg . "\n";
        fwrite ( self::$logfp, $msg );
        if ($closefpAfterWrite==true){
            self::$logstatus = false;
        }
    }
    public function writeFile($msg,$pre=''){
        
    }
    public static function getCIp() {
        $ip = false;
        if (! empty ( $_SERVER ["HTTP_CLIENT_IP"] )) {
            $ip = $_SERVER ["HTTP_CLIENT_IP"];
        }
        if (! empty ( $_SERVER ['HTTP_X_FORWARDED_FOR'] )) {
            $ips = explode ( ", ", $_SERVER ['HTTP_X_FORWARDED_FOR'] );
            if ($ip) {
                array_unshift ( $ips, $ip );
                $ip = FALSE;
            }
            for($i = 0; $i < count ( $ips ); $i ++) {
                if (! eregi ( '^(10|172\.16|192\.168)\.', $ips [$i] )) {
                    $ip = $ips [$i];
                    break;
                }
            }
        }
        $cip = $ip ? $ip : @$_SERVER ['REMOTE_ADDR'];
        return $cip;
    }
    public static function model($modelClassName) {
        $file = HIGHPHP_LIB_DIR . 'model/' . $modelClassName . '.class.php';
        if (! file_exists ( $file ))
            if (! file_exists ( $file ))
                self::end ( "model Class $modelClassName file $file is not exist", 2 );
        return include_once $file;
    }
    public static function plugin($pluginClassName) {
        $file = HIGHPHP_LIB_DIR . 'plugin/' . $pluginClassName . '.class.php';
        if (! file_exists ( $file ))
            if (! file_exists ( $file ))
                self::end ( "plug-in Class $pluginClassName file $file is not exist", 2 );
        return include_once $file;
    }
    /** 
     * 获取config文件夹中的配置文件.inc内容
     * @param string $file
     * @param string $config
     * @param string $parm
     */
    public static function getConfig($file, $config, $parm) {
        $array = self::configLoad ( $config, HIGHPHP_ROOT_DIR . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . $file );
        if ($array == false) {
            self::end ( "get config file $file is not exist", 2 );
        }
        return $array [$parm];
    }
    public static function configLoad($par, $path) {
        $arr = parse_ini_file ( $path, true );
        if (count ( @$arr [$par] ) > 0) {
            return $arr [$par];
        } else {
            return false;
        }
    }
    public static function addCss($css) {
        $css = '<link type="text/css" href="' . $css . '" rel="stylesheet" media="screen" />' . " \n";
        self::assign ( 'css', $css, true );
    }
    public static function addJs($js) {
        $js = '<script type="text/javascript" src="' . $js . '"></script>' . " \n";
        self::assign ( 'js', $js, true );
    }
    public static function location($url) {
        header ( "Location: " . $url );
        self::end ();
    }
    public static function get($col = null, $default = null) {
        if ($col) {
            $v = @self::$getArr [$col];
            if (! $v and $default!==null) {
                return $default;
            }
            return addslashes ( urldecode(trim ( $v ) ));
        } else {
            $arr = array ();
            foreach ( self::$getArr as $k => $v ) {
                $arr [$k] = addslashes ( urldecode(trim ( $v ) ));
            }
            return $arr;
        }
    }
    public static function post($col = null) {
        if ($col) {
        	if (is_array(@$_POST[$col])) {
        		foreach ($_POST[$col] as $k => $v) {
        			$_POST[$col][$k] = addslashes ( trim ( $v ) );
        		} 
        		return $_POST[$col];
        	} else {
            	return addslashes ( trim ( @$_POST [$col] ) );
        	}
        } else {
            $arr = array ();
            foreach ( $_POST as $k => $v ) {
            	if (is_array($v)) {
            		foreach ($v as $ckey => $cv) {
            			$v [$ckey] = addslashes($cv);
            		}
            		$arr [$k] = $v;
            	} else {
            		$arr [$k] = addslashes ( trim ( $v ) );	
            	} 
            }
            return $arr;
        }
    }
    public static function getMemUse(){
        $me2 = memory_get_usage();	
        return substr((($me2-self::$memStart)/1024/1024),0,5);
    }
}