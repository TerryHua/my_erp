<?php
class strings {
    /**
     * 
     * 截取字符串，多余的用...表示
     * @param string $string
     * @param integer $length
     * @param string $etc
     * @param string $code
     */
    public static function truncate($string, $length = 80, $etc = '...', $code = 'UTF-8') {
        if ($length == 0)
            return '';
        if ($code == 'UTF-8') {
            $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
        } else {
            $pa = "/[\x01-\x7f]|[\xa1-\xff][\xa1-\xff]/";
        }
        preg_match_all ( $pa, $string, $t_string );
        if (count ( $t_string [0] ) > $length)
            return join ( '', array_slice ( $t_string [0], 0, $length ) ) . $etc;
        return join ( '', array_slice ( $t_string [0], 0, $length ) ) . $etc;
    }
    
    /**
     * 
     * 汉字转换成拼音
     * @param void $key
     */
    public static function pinYin($string, $code = 'gbk') {
        $_DataKey = "a|ai|an|ang|ao|ba|bai|ban|bang|bao|bei|ben|beng|bi|bian|biao|bie|bin|bing|bo|bu|ca|cai|can|cang|cao|ce|ceng|cha" . "|chai|chan|chang|chao|che|chen|cheng|chi|chong|chou|chu|chuai|chuan|chuang|chui|chun|chuo|ci|cong|cou|cu|" . "cuan|cui|cun|cuo|da|dai|dan|dang|dao|de|deng|di|dian|diao|die|ding|diu|dong|dou|du|duan|dui|dun|duo|e|en|er" . "|fa|fan|fang|fei|fen|feng|fo|fou|fu|ga|gai|gan|gang|gao|ge|gei|gen|geng|gong|gou|gu|gua|guai|guan|guang|gui" . "|gun|guo|ha|hai|han|hang|hao|he|hei|hen|heng|hong|hou|hu|hua|huai|huan|huang|hui|hun|huo|ji|jia|jian|jiang" . "|jiao|jie|jin|jing|jiong|jiu|ju|juan|jue|jun|ka|kai|kan|kang|kao|ke|ken|keng|kong|kou|ku|kua|kuai|kuan|kuang" . "|kui|kun|kuo|la|lai|lan|lang|lao|le|lei|leng|li|lia|lian|liang|liao|lie|lin|ling|liu|long|lou|lu|lv|luan|lue" . "|lun|luo|ma|mai|man|mang|mao|me|mei|men|meng|mi|mian|miao|mie|min|ming|miu|mo|mou|mu|na|nai|nan|nang|nao|ne" . "|nei|nen|neng|ni|nian|niang|niao|nie|nin|ning|niu|nong|nu|nv|nuan|nue|nuo|o|ou|pa|pai|pan|pang|pao|pei|pen" . "|peng|pi|pian|piao|pie|pin|ping|po|pu|qi|qia|qian|qiang|qiao|qie|qin|qing|qiong|qiu|qu|quan|que|qun|ran|rang" . "|rao|re|ren|reng|ri|rong|rou|ru|ruan|rui|run|ruo|sa|sai|san|sang|sao|se|sen|seng|sha|shai|shan|shang|shao|" . "she|shen|sheng|shi|shou|shu|shua|shuai|shuan|shuang|shui|shun|shuo|si|song|sou|su|suan|sui|sun|suo|ta|tai|" . "tan|tang|tao|te|teng|ti|tian|tiao|tie|ting|tong|tou|tu|tuan|tui|tun|tuo|wa|wai|wan|wang|wei|wen|weng|wo|wu" . "|xi|xia|xian|xiang|xiao|xie|xin|xing|xiong|xiu|xu|xuan|xue|xun|ya|yan|yang|yao|ye|yi|yin|ying|yo|yong|you" . "|yu|yuan|yue|yun|za|zai|zan|zang|zao|ze|zei|zen|zeng|zha|zhai|zhan|zhang|zhao|zhe|zhen|zheng|zhi|zhong|" . "zhou|zhu|zhua|zhuai|zhuan|zhuang|zhui|zhun|zhuo|zi|zong|zou|zu|zuan|zui|zun|zuo";
        $_DataValue = "-20319|-20317|-20304|-20295|-20292|-20283|-20265|-20257|-20242|-20230|-20051|-20036|-20032|-20026|-20002|-19990" . "|-19986|-19982|-19976|-19805|-19784|-19775|-19774|-19763|-19756|-19751|-19746|-19741|-19739|-19728|-19725" . "|-19715|-19540|-19531|-19525|-19515|-19500|-19484|-19479|-19467|-19289|-19288|-19281|-19275|-19270|-19263" . "|-19261|-19249|-19243|-19242|-19238|-19235|-19227|-19224|-19218|-19212|-19038|-19023|-19018|-19006|-19003" . "|-18996|-18977|-18961|-18952|-18783|-18774|-18773|-18763|-18756|-18741|-18735|-18731|-18722|-18710|-18697" . "|-18696|-18526|-18518|-18501|-18490|-18478|-18463|-18448|-18447|-18446|-18239|-18237|-18231|-18220|-18211" . "|-18201|-18184|-18183|-18181|-18012|-17997|-17988|-17970|-17964|-17961|-17950|-17947|-17931|-17928|-17922" . "|-17759|-17752|-17733|-17730|-17721|-17703|-17701|-17697|-17692|-17683|-17676|-17496|-17487|-17482|-17468" . "|-17454|-17433|-17427|-17417|-17202|-17185|-16983|-16970|-16942|-16915|-16733|-16708|-16706|-16689|-16664" . "|-16657|-16647|-16474|-16470|-16465|-16459|-16452|-16448|-16433|-16429|-16427|-16423|-16419|-16412|-16407" . "|-16403|-16401|-16393|-16220|-16216|-16212|-16205|-16202|-16187|-16180|-16171|-16169|-16158|-16155|-15959" . "|-15958|-15944|-15933|-15920|-15915|-15903|-15889|-15878|-15707|-15701|-15681|-15667|-15661|-15659|-15652" . "|-15640|-15631|-15625|-15454|-15448|-15436|-15435|-15419|-15416|-15408|-15394|-15385|-15377|-15375|-15369" . "|-15363|-15362|-15183|-15180|-15165|-15158|-15153|-15150|-15149|-15144|-15143|-15141|-15140|-15139|-15128" . "|-15121|-15119|-15117|-15110|-15109|-14941|-14937|-14933|-14930|-14929|-14928|-14926|-14922|-14921|-14914" . "|-14908|-14902|-14894|-14889|-14882|-14873|-14871|-14857|-14678|-14674|-14670|-14668|-14663|-14654|-14645" . "|-14630|-14594|-14429|-14407|-14399|-14384|-14379|-14368|-14355|-14353|-14345|-14170|-14159|-14151|-14149" . "|-14145|-14140|-14137|-14135|-14125|-14123|-14122|-14112|-14109|-14099|-14097|-14094|-14092|-14090|-14087" . "|-14083|-13917|-13914|-13910|-13907|-13906|-13905|-13896|-13894|-13878|-13870|-13859|-13847|-13831|-13658" . "|-13611|-13601|-13406|-13404|-13400|-13398|-13395|-13391|-13387|-13383|-13367|-13359|-13356|-13343|-13340" . "|-13329|-13326|-13318|-13147|-13138|-13120|-13107|-13096|-13095|-13091|-13076|-13068|-13063|-13060|-12888" . "|-12875|-12871|-12860|-12858|-12852|-12849|-12838|-12831|-12829|-12812|-12802|-12607|-12597|-12594|-12585" . "|-12556|-12359|-12346|-12320|-12300|-12120|-12099|-12089|-12074|-12067|-12058|-12039|-11867|-11861|-11847" . "|-11831|-11798|-11781|-11604|-11589|-11536|-11358|-11340|-11339|-11324|-11303|-11097|-11077|-11067|-11055" . "|-11052|-11045|-11041|-11038|-11024|-11020|-11019|-11018|-11014|-10838|-10832|-10815|-10800|-10790|-10780" . "|-10764|-10587|-10544|-10533|-10519|-10331|-10329|-10328|-10322|-10315|-10309|-10307|-10296|-10281|-10274" . "|-10270|-10262|-10260|-10256|-10254";
        $_TDataKey = explode ( '|', $_DataKey );
        $_TDataValue = explode ( '|', $_DataValue );
        $_Data = array_combine ( $_TDataKey, $_TDataValue );
        arsort ( $_Data );
        reset ( $_Data );
        if ($code != 'gbk')
            $string = strings::U2_Utf8_Gb ( $string );
        $_Res = '';
        for($i = 0; $i < strlen ( $string ); $i ++) {
            $_P = ord ( substr ( $string, $i, 1 ) );
            if ($_P > 160) {
                $_Q = ord ( substr ( $string, ++ $i, 1 ) );
                $_P = $_P * 256 + $_Q - 65536;
            }
            $_Res .= strings::pinyingChange ( $_P, $_Data );
        }
        return preg_replace ( "/[^a-z0-9]*/", '', $_Res );
    }
    public static function pinYinFirst($zh) {
        $zh = str_replace ( array (' ', '/', '"' ), '', $zh );
        $ret = "";
        
        if (iconv ( "UTF-8", "gbk", $zh )) {
            $s1 = iconv ( "UTF-8", "gbk", $zh );
        } else {
            $s1 = '';
        }
        if (iconv ( "gbk", "UTF-8", $s1 )) {
            $s2 = iconv ( "gbk", "UTF-8", $s1 );
        }
        if (! $s2) {
            return '';
        }
        if ($s2 == $zh) {
            $zh = $s1;
        }
        for($i = 0; $i < strlen ( $zh ); $i ++) {
            $s1 = substr ( $zh, $i, 1 );
            $p = ord ( $s1 );
            if ($p > 160) {
                $s2 = substr ( $zh, $i ++, 2 );
                $ret .= strings::getfirstchar ( $s2 );
            } else {
                $ret .= $s1;
            
            }
        }
        return $ret;
    }
    public static function getfirstchar($s0) {
        $fchar = ord ( $s0 {0} );
        if ($fchar >= ord ( "A" ) and $fchar <= ord ( "z" ))
            return strtoupper ( $s0 {0} );
        $s1 = @iconv ( "UTF-8", "gbk", $s0 );
        $s2 = @iconv ( "gbk", "UTF-8", $s0 );
        if ($s2 == $s0) {
            $s = $s1;
        } else {
            $s = $s0;
        }
        $asc = ord ( $s {0} ) * 256 + ord ( $s {1} ) - 65536;
        if ($asc >= - 20319 and $asc <= - 20284)
            return "A";
        if ($asc >= - 20283 and $asc <= - 19776)
            return "B";
        if ($asc >= - 19775 and $asc <= - 19219)
            return "C";
        if ($asc >= - 19218 and $asc <= - 18711)
            return "D";
        if ($asc >= - 18710 and $asc <= - 18527)
            return "E";
        if ($asc >= - 18526 and $asc <= - 18240)
            return "F";
        if ($asc >= - 18239 and $asc <= - 17923)
            return "G";
        if ($asc >= - 17922 and $asc <= - 17418)
            return "H";
        if ($asc >= - 17417 and $asc <= - 16475)
            return "J";
        if ($asc >= - 16474 and $asc <= - 16213)
            return "K";
        if ($asc >= - 16212 and $asc <= - 15641)
            return "L";
        if ($asc >= - 15640 and $asc <= - 15166)
            return "M";
        if ($asc >= - 15165 and $asc <= - 14923)
            return "N";
        if ($asc >= - 14922 and $asc <= - 14915)
            return "O";
        if ($asc >= - 14914 and $asc <= - 14631)
            return "P";
        if ($asc >= - 14630 and $asc <= - 14150)
            return "Q";
        if ($asc >= - 14149 and $asc <= - 14091)
            return "R";
        if ($asc >= - 14090 and $asc <= - 13319)
            return "S";
        if ($asc >= - 13318 and $asc <= - 12839)
            return "T";
        if ($asc >= - 12838 and $asc <= - 12557)
            return "W";
        if ($asc >= - 12556 and $asc <= - 11848)
            return "X";
        if ($asc >= - 11847 and $asc <= - 11056)
            return "Y";
        if ($asc >= - 11055 and $asc <= - 10247)
            return "Z";
        return null;
    
    }
    public static function pinyingChange($_Num, $_Data) {
        if ($_Num > 0 && $_Num < 160) {
            return chr ( $_Num );
        } elseif ($_Num < - 20319 || $_Num > - 10247) {
            return '';
        } else {
            foreach ( $_Data as $k => $v ) {
                if ($v <= $_Num)
                    break;
            }
            return $k;
        }
    }
    public static function U2_Utf8_Gb($_C) {
        $string = '';
        if ($_C < 0x80) {
            $string .= $_C;
        } elseif ($_C < 0x800) {
            $string .= chr ( 0xC0 | $_C >> 6 );
            $string .= chr ( 0x80 | $_C & 0x3F );
        } elseif ($_C < 0x10000) {
            $string .= chr ( 0xE0 | $_C >> 12 );
            $string .= chr ( 0x80 | $_C >> 6 & 0x3F );
            $string .= chr ( 0x80 | $_C & 0x3F );
        } elseif ($_C < 0x200000) {
            $string .= chr ( 0xF0 | $_C >> 18 );
            $string .= chr ( 0x80 | $_C >> 12 & 0x3F );
            $string .= chr ( 0x80 | $_C >> 6 & 0x3F );
            $string .= chr ( 0x80 | $_C & 0x3F );
        }
        return iconv ( 'UTF-8', 'gbk', $string );
    }
    /**
     * 
     * 判断是否是汉字
     * @param unknown_type $str
     */
    public static function isgb($str) {
        if (strlen ( $str ) >= 2) {
            $str = strtok ( $str, "" );
            if ((ord ( $str [0] ) < 161) || (ord ( $str [0] ) > 247)) {
                return false;
            } else {
                if ((ord ( $str [1] ) < 161) || (ord ( $str [1] ) > 254)) {
                    return false;
                } else {
                    return true;
                }
            }
        } else {
            return false;
        }
    }
    
    /**
     * 判断字符串中是否有中文
     * @param string $str
     */
    public static function checkGBK($str) {
        if (! preg_match ( "/(.*)[\x7f-\xff]/", $str )) { //兼容gb2312,utf-8
            return true;
        } else {
            return false;
        }
    }
    
    public static function shorturl($input) {
        $base32 = array ('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5' );
        $hex = md5 ( 'kc' . $input . 'gl' );
        $hexLen = strlen ( $hex );
        $subHexLen = $hexLen / 8;
        $output = array ();
        for($i = 0; $i < $subHexLen; $i ++) {
            $subHex = substr ( $hex, $i * 8, 8 );
            $int = 0x3FFFFFFF & (1 * ('0x' . $subHex));
            $out = '';
            for($j = 0; $j < 6; $j ++) {
                $val = 0x0000001F & $int;
                $out .= $base32 [$val];
                $int = $int >> 5;
            }
            $output = $out;
        }
        return $output;
    }
    /**
     * 拆分字符串，可以拆分中文，
     * @param string $str
     * @return array
     */
    public static function chSplit($str) {
        $splikey = array ();
        $ascLen = strlen ( $str );
        for($i = 0; $i < $ascLen; $i ++) {
            $c = ord ( substr ( $str, 0, 1 ) );
            if (ord ( substr ( $str, 0, 1 ) ) > 252) {
                $p = 5;
            } elseif ($c > 248) {
                $p = 4;
            } elseif ($c > 240) {
                $p = 3;
            } elseif ($c > 224) {
                $p = 2;
            } elseif ($c > 192) {
                $p = 1;
            } else {
                $p = 0;
            }
            $truekey = substr ( $str, 0, $p + 1 );
            if ($truekey === false) {
                break;
            }
            $splikey [] = $truekey;
            $str = substr ( $str, $p + 1 );
        }
        return $splikey;
    }
    /**
     * 判断是字符串中是否包含连续重复的字符
     * @param string $str
     * @param integer $len
     * @return boolean
     */
    public static function isRepeatStr($str, $len) {
        $arr = chSplit ( $str );
        for($i = 0; $i < count ( $arr ); $i ++) {
            if (substr_count ( $str, str_repeat ( $arr [$i], $len ) )) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * 字符串里只能包含 指定的url，或者不包含任何url，如果包含了其它的url则返回false  示例：strUrlOnly('testhttp://www.uxin001.com','uxin01.com')
     * @param unknown $str
     * @param unknown $dom
     * @return boolean
     */
    public static function strUrlOnly($str, $dom) {
        $dom = str_replace ( '.', '[-.]', $dom );
        preg_match_all ( '/http:\/\/[a-zA-Z0-9_]+[-.][a-zA-Z0-9_]+[-.][a-zA-Z0-9_]{2,5}/i', $str, $rs );
        if ($rs) {
            $urls = $rs [0];
            foreach ( $urls as $v ) {
                if ($v) {
                    if (! preg_match ( '/' . $dom . '/', $v )) {
                        return false;
                    }
                }
            }
        }
        return true;
    }
}