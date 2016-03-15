<?php
class arrays {    
    /**
     * 
     * 按键值排序数组$arr = array=(0=>array("uid"=>2),1=>array("uid"=>1),2=>array("uid"=>3))   array_sort_by_filed($arr,"uid")
     * @param array $arr_data
     * @param string $field
     * @param bool $descending
     */
    public static function  array_sort_by_filed($arr_data, $field, $descending = true) {
        $arrSort = array ();
        foreach ( $arr_data as $key => $value ) {
            $arrSort [$key] = $value [$field];
        }
        
        if ($descending) {
            arsort ( $arrSort );
        } else {
            asort ( $arrSort );
        }
        $resultArr = array ();
        foreach ( $arrSort as $key => $value ) {
            $resultArr [] = $arr_data [$key];
        }
        return $resultArr;
    }    
    
    public static function string_to_array($string){
        $arr = array();
        eval("\$arr = ".$string.'; ');
        return $arr;
    }
}