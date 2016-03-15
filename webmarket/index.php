<?php
define ( 'HIGHPHP_PROJECT_DIR', realpath( dirname(__FILE__)).'/');
define ( 'HIGHPHP_ROOT_DIR', realpath ( dirname ( __FILE__ ) ).'/..' );
define ( 'HIGHPHP_DEBUG', 2047 );//错误级别
define ( 'HIGHPHP_WEB_DIR', strtolower ( preg_replace ( '/[\w]+.php/', '', strtolower ( $_SERVER ['SCRIPT_NAME'] ) ) ) );
define ( 'HIGHPHP_LOG_DIR', HIGHPHP_ROOT_DIR . '/log/' );
define ( 'HIGHPHP_LOG_LEVEL', 0 ); //日志级别 0:Info Warning and Error 1:Warning AND Error 2:only Error 3:No log 
define ( 'HIGHPHP_CACHE_DIR', HIGHPHP_ROOT_DIR . '/cache/' );
define ( 'HIGHPHP_WWW_HOST', 'http://' . $_SERVER ['HTTP_HOST'] . HIGHPHP_WEB_DIR);
define ( 'HIGHPHP_USER_IMG', HIGHPHP_WWW_HOST . 'upload/images/users/' );
define ( 'HIGHPHP_UPLOAD_DIR', HIGHPHP_ROOT_DIR . '/upload/' );
define ( 'HIGHPHP_PRE_PAGE', @$_SERVER ['HTTP_REFERER'] );
define ( 'HIGHPHP_THIS_PAGE', @$argv [1]?$argv [1]:'http://' . $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'] );
define ( 'HIGHPHP_LIB_DIR', HIGHPHP_ROOT_DIR . '/lib/' );
define ( 'HIGHPHP_WEB_PATH', HIGHPHP_ROOT_DIR . '/' );
define ( 'HIGHPHP_PROJECT_PATH', HIGHPHP_ROOT_DIR . '/app/project/' );
define ( 'HIGHPHP_TEMP_PATH', HIGHPHP_ROOT_DIR . '/app/templates/' );
error_reporting ( HIGHPHP_DEBUG );
include HIGHPHP_ROOT_DIR . '/config/define.php';
include HIGHPHP_LIB_DIR . 'framework.php';
ini_set('max_execution_time', '0');
session_start();
framework::$projects = array('market', 'www', 'erp');
framework::$project = 'market';
framework::$cacheTime = 10; //开启缓存的缓存时间，0为不开启
framework::$cachePage = array (HIGHPHP_WWW_HOST, 'http://localhost/frame/index/test*' ); //缓存页面 结束带*则表示满足规则的所有的都缓存
framework::$debug = 0; //开启调试模式 0不开，1开启
framework::run ();