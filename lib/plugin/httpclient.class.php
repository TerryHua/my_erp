<?php
framework::plugin('https');
class httpclient{
    public static function activeReport($uid,$act){
        https::curlRequest(IM_URL.'active_report?sn=2&sign='. md5 ('2k1oET&Yh7@EQnp2XdTP1o/Vo=' ).'&uid='.$uid.'&act='.$act);
    }
}