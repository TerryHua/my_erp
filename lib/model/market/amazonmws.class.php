<?php
/**
 * 亚马逊相关配置表model
 * @author huaxiaofeng
 * @version 1.0
 */
framework::plugin('mysql');
class amazonmws
{
    public $db;
    public $db_bak;  
    
    public function __construct()
    {
        $this->db = mysql::getInstance('mysql');    
        $this->db_bak = mysql::getInstance('mysql_bk'); 
    }


    /**
     * 通过ID获取亚马逊配置单条记录
     */
    public function getMwsConfig($id)
    {
        $sql = "SELECT * FROM amazon_mws_config where id='$id'";
        return $this->db_bak->getRow($sql);
    }
              
    /**
     * 添加亚马逊市场配置记录
     */
    public function createMwsConfig($data)
    { 
        return $this->db->insert('amazon_mws_config', $data, true);
    }
    
    public function updateMwsConfig($data, $id)
    {
        $where = 'WHERE id = '.$id;
        return $this->db->updateData('amazon_mws_config', $data, $where);
    }
    
    /**
     * 删除亚马逊单条记录
     */
    public function deleteMwsConfig($id)
    {
        if ($id == '') {
            return false;
        }
        $sql = "DELETE FROM amazon_mws_config WHERE id='$id'";
        return $this->db->delete($sql);     
    }
    
    
    /**
     * 获取亚马逊配置列表
     */
    public function getMwsConfigList($condition)
    {
        framework::plugin('Page');      
         
        $sql = "SELECT * FROM amazon_mws_config WHERE 1 ";
        
        if (isset($condition['code']) && ''!=$condition['code']) {
            $sql .= " AND code = '".$condition['code']."'";
        } 
        if (isset($condition['market_place_id']) && ''!=$condition['market_place_id']) {
            $sql .= " AND market_place_id = '".$condition['market_place_id']."'";
        }
        if (isset($condition['market_name']) && ''!= $condition['market_name'] ) {
            $sql .= " AND market_name='". $condition['market_name']."'";
        }   
          
        //设置分页信息
        $page = new Page ();
        $page->uriStyle = '?';
        $page->eachNums = 15;
        $page->orderBy = 'id';
        $page->desc = 'desc'; 
        $list = $page->getPage ( $sql, 0, 'mysql_bk' );
        $html = $page->htmlPage ();
         
        //返回搜索结果和分页信息
        return array ('list' => $list, 'html' => $html, 'total' => $page->allNums, 'eachNums' => $page->eachNums);
    }

    /**
     * 通过市场code获取相关配置信息
     */
    public function getMwsConfigRowByCode($code)
    {
        $sql = "SELECT * FROM amazon_mws_config WHERE code='{$code}'";
        return $this->db_bak->getRow($sql);
    }


    /**
     * @param $id
     * @return array|bool
     */
    public function getMwsUserConfig($id)
    {
        $sql = "SELECT * FROM amazon_mws_user_config where id='$id'";
        return $this->db_bak->getRow($sql);
    }
              
    public function getAllMwsUserConfigByAdmin($adminId)
    {
        $sql = "SELECT * FROM amazon_mws_user_config WHERE admin_id='$adminId'";
        return $this->db_bak->getAll($sql);
    }

    /**
     * 用户对应接口配置信息
     */
    public function createMwsUserConfig($data)
    { 
        return $this->db->insert('amazon_mws_user_config', $data, true);
    }
    
    public function updateMwsUserConfig($data, $id)
    {
        $where = 'WHERE id = '.$id;
        return $this->db->updateData('amazon_mws_user_config', $data, $where);
    }
    
    /**
     * 删除结算单记录
     */
    public function deleteMwsUserConfig($id)
    {
        if ($id == '') {
            return false;
        }
        $sql = "DELETE FROM amazon_mws_user_config WHERE id='$id'";
        return $this->db->delete($sql);     
    }

    /**
     * 根据用户Id和市场id获取配置信息
     */
    public function getUserConfigByAdminId($admin_id, $marketId)
    {
        $sql = "SELECT * FROM amazon_mws_user_config WHERE admin_id='{$admin_id}' AND market_place_id='{$marketId}'";
        return $this->db_bak->getRow($sql);
    }
}