<?php
/**
 * 
 * http请求类
 * @author Administrator
 *
 */
class https {
    
    /**
     * 
     * 通过curl获取URL信息
     * 验证cookie的url post请求
     * @param String $url
     */
    public static function curlRequest($url, $postdata = '', $cockieid = '', $cookieFile = 'mycookiefile.txt',$timeOut=30) {
        $timestart = microtime ( true );
        $ch = curl_init ();
        $option = array (CURLOPT_URL => $url, CURLOPT_HEADER => 0, CURLOPT_RETURNTRANSFER => 1, CURLOPT_TIMEOUT => $timeOut );
        if ($cockieid) {
            $strCookie = 'ssid = ' . $cockieid;
            session_write_close ();
            $option [CURLOPT_COOKIE] = $strCookie;
        }
        if ($cookieFile) {
            $option [CURLOPT_COOKIEJAR] = $cookieFile;
            $option [CURLOPT_COOKIEFILE] = $cookieFile;
        }
        if ($postdata) {
            if ($postdata ['ajax']) {
                $option [CURLOPT_HTTPHEADER] = array ('X-Requested-With: XMLHttpRequest', 'Referer: ' . $postdata ['Referer'] );
                unset ( $postdata ['Referer'] );
                unset ( $postdata ['ajax'] );
            }
            $option [CURLOPT_POST] = 1;
            $option [CURLOPT_POSTFIELDS] = $postdata;
        }
        curl_setopt_array ( $ch, $option );
        $response = curl_exec ( $ch );
        curl_close ( $ch );
        $timeend = microtime ( true );
        $runtime = round ( ($timeend - $timestart) * 1000, 1 );
        if ($runtime>100 and $runtime<1000){
            framework::logWrite('Request '.$url.' [Warning] Url Requset Slow runtime in '.$runtime.' ms ',1);
        }elseif ($runtime>=1000){
            framework::logWrite('Request '.$url.' [Error] Url Requset Timeout runtime in '.$runtime.' ms ',2);
        }
        return $response;
    }
    
    /**
     * 
     * POST 方式提交到指定URL
     * @param string $url
     * @param array $data
     * @param unknown_type $optional_headers
     * @throws Exception
     */
    public static function postRequest($url, $data, $optional_headers = null) {
        $params = array ('http' => array ('method' => 'POST', 'content' => http_build_query ( $data ) ) );
        if ($optional_headers !== null) {
            $params ['http'] ['header'] = $optional_headers;
        } else {
            $params ['http'] ['header'] = 'Content-type: application/x-www-form-urlencoded';
        }
        $ctx = stream_context_create ( $params );
        $fp = @fopen ( $url, 'rb', false, $ctx );
        if (! $fp) {
            framework::end ("Problem postRequest $url",2);            
        }
        $response = @stream_get_contents ( $fp );
        if ($response === false) {
            framework::end ( "Problem postRequest while reading data from $url",2 );
        }
        return $response;
    }
    
    /**
     * 
     * 异步请求url
     * @param string $url
     * @param string $type
     * @param array $params
     */
    public static function asynUrl($url, $type = 'GET', $params = array()) {
        $post_params = array ();
        foreach ( $params as $key => &$val ) {
            if (is_array ( $val ))
                $val = implode ( ',', $val );
            $post_params [] = $key . '=' . urlencode ( $val );
        }
        $post_string = '';
        if ($post_params) {
            $post_string = implode ( '&', $post_params );
        }
        $parts = parse_url ( $url );
        $fp = fsockopen ( $parts ['host'], isset ( $parts ['port'] ) ? $parts ['port'] : 80, $errno, $errstr, 30 );
        
        if ($parts ['query']) {
            if ('GET' == $type) {
                $parts ['path'] .= '?' . $parts ['query'] . '&' . $post_string;
            } else {
                $parts ['path'] .= '?' . $parts ['query'];
            }
        } else {
            if ('GET' == $type) {
                $parts ['path'] .= '?' . $post_string;
            }
        }
        $parts ['path'] = str_replace ( ' ', '%20', $parts ['path'] );
        $out = "$type " . $parts ['path'] . " HTTP/1.1\r\n";
        $out .= "Host: " . $parts ['host'] . "\r\n";
        $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out .= "Content-Length: " . strlen ( $post_string ) . "\r\n";
        $out .= "Connection: Close\r\n\r\n";
        if ('POST' == $type && isset ( $post_string ))
            $out .= $post_string;
        fwrite ( $fp, $out );
        fclose ( $fp );
    }
    /**
     * 
     * 发送socket
     * @param string $server
     * @param string $port
     * @param string $data
     */
    public static function socketData($server, $port, $data) {
        $commonProtocol = getprotobyname ( 'TCP' );
        $socket = socket_create ( AF_INET, SOCK_STREAM, $commonProtocol );
        socket_set_option ( $socket, SOL_SOCKET, SO_RCVTIMEO, array ("sec" => 1, "usec" => 0 ) );
        socket_connect ( $socket, $server, $port );
        socket_write ( $socket, $data, strlen ( $data ) ); //发送数据
        $out = @socket_read ( $socket, 2018 );
        socket_close ( $socket );
        return trim ( $out );
    }
    public static function getOS() {
        $agent = $_SERVER ['HTTP_USER_AGENT'];
        $os = false;
        if (eregi ( 'win', $agent ) && strpos ( $agent, '95' )) {
            $os = 'Windows 95';
        } else if (eregi ( 'win 9x', $agent ) && strpos ( $agent, '4.90' )) {
            $os = 'Windows ME';
        } else if (eregi ( 'win', $agent ) && ereg ( '98', $agent )) {
            $os = 'Windows 98';
        } else if (eregi ( 'win', $agent ) && eregi ( 'nt 5.1', $agent )) {
            $os = 'Windows XP';
        } else if (eregi ( 'win', $agent ) && eregi ( 'nt 5', $agent )) {
            $os = 'Windows 2000';
        } else if (eregi ( 'win', $agent ) && eregi ( 'nt 6.1', $agent )) {
            $os = 'Windows 7';
        } else if (eregi ( 'win', $agent ) && eregi ( 'nt 6', $agent )) {
            $os = 'Windows Visita';
        } else if (eregi ( 'win', $agent ) && eregi ( 'nt', $agent )) {
            $os = 'Windows NT';
        } else if (eregi ( 'win', $agent ) && ereg ( '32', $agent )) {
            $os = 'Windows 32';
        } else if (eregi ( 'linux', $agent )) {
            $os = 'Linux';
        } else if (eregi ( 'unix', $agent )) {
            $os = 'Unix';
        } else if (eregi ( 'sun', $agent ) && eregi ( 'os', $agent )) {
            $os = 'SunOS';
        } else if (eregi ( 'ibm', $agent ) && eregi ( 'os', $agent )) {
            $os = 'IBM OS/2';
        } else if (eregi ( 'Mac', $agent ) && eregi ( 'PC', $agent )) {
            $os = 'Macintosh';
        } else if (eregi ( 'PowerPC', $agent )) {
            $os = 'PowerPC';
        } else if (eregi ( 'AIX', $agent )) {
            $os = 'AIX';
        } else if (eregi ( 'HPUX', $agent )) {
            $os = 'HPUX';
        } else if (eregi ( 'NetBSD', $agent )) {
            $os = 'NetBSD';
        } else if (eregi ( 'BSD', $agent )) {
            $os = 'BSD';
        } else if (ereg ( 'OSF1', $agent )) {
            $os = 'OSF1';
        } else if (ereg ( 'IRIX', $agent )) {
            $os = 'IRIX';
        } else if (eregi ( 'FreeBSD', $agent )) {
            $os = 'FreeBSD';
        } else if (eregi ( 'teleport', $agent )) {
            $os = 'teleport';
        } else if (eregi ( 'flashget', $agent )) {
            $os = 'flashget';
        } else if (eregi ( 'webzip', $agent )) {
            $os = 'webzip';
        } else if (eregi ( 'offline', $agent )) {
            $os = 'offline';
        } else {
            $uachar1 = "/(J2ME|Java)/i";
            if (preg_match ( $uachar1, $agent )) {
                
                $os = 'java';
            }
            $uachar1 = "/(Linux|Android)/i";
            if (preg_match ( $uachar1, $agent )) {
                
                $os = 'android';
            }
            $uachar1 = "/(Series60\/2)/i";
            if (preg_match ( $uachar1, $agent )) {
                $os = 'symbian2';
            }
            $uachar1 = "/(Series60\/3)/i";
            if (preg_match ( $uachar1, $agent )) {
                $os = 'symbian3';
            }
            $uachar1 = "/(Series60\/5)/i";
            if (preg_match ( $uachar1, $agent )) {
                $os = 'symbian5';
            
            }
            $uachar1 = "/(iPhone|iOS)/i";
            if (preg_match ( $uachar1, $agent )) {
                $os = 'iphone';
            }
            $uachar1 = "/(Windows CE|IEMobile)/i";
            if (preg_match ( $uachar1, $agent )) {
                $os = 'windowsmobile';
            }
        }
        return $os;
    }
}