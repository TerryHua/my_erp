<?php
/**
 * 邮件模块
 * @author huaxiaofeng
 * @version 1.0 2016-3-25 10:36:40
 */
framework::plugin('mysql');
class mail
{
    public $db;
    public $db_bak;  
    
    public function __construct()
    {
        $this->db = mysql::getInstance('mysql');    
        $this->db_bak = mysql::getInstance('mysql_bk'); 
    }    
    
          
    public function getMailTemplate()
    {
        $sql = "SELECT * FROM mail_template ";
        return $this->db_bak->getAll($sql);
    } 

    public function getMaxMinId()
    {
        $sql = "SELECT MIN(id) as minid, MAX(id) as maxid FROM mail_template";
        return $this->db_bak->getRow($sql);
    }

    public function getMailTemplateById($id)
    {
        $sql = "SELECT * FROM mail_template WHERE id='$id'";
        return $this->db_bak->getRow($sql);
    }
     
    public function getMailTemplateRand($id)
    {
        $sql = " SELECT * FROM mail_template WHERE id>='$id' LIMIT 1";
        return $this->db_bak->getRow($sql);
    }   
              
    /**
     * 添加邮件模板记录
     */
    public function createMailTemplate($data)
    { 
        return $this->db->insert('mail_template', $data, true);
    }
    
    public function updateMailTemplate($data, $id)
    {
        $where = 'WHERE id = '.$id;
        return $this->db->updateData('mail_template', $data, $where); 
    }
    
    /**
     * 删除邮件模板记录
     */
    public function deleteMailTemplate($id)
    {
        if ($id == '') {
            return false;
        }
        $sql = "delete from mail_template where id='$id'";
        return $this->db->delete($sql);     
    }
    
    
    /**
     * 获取邮件模板记录列表
     */
    public function getProductCateList($condition)
    {
        framework::plugin('Page');      
         
        $sql = "SELECT * FROM mail_template WHERE 1 "; 
        
        if (isset($condition['en_name']) && ''!=$condition['en_name']) {
            $sql .= " AND en_name = '".$condition['en_name']."'";
        } 
        if (isset($condition['cn_name']) && ''!=$condition['cn_name']) {
            $sql .= " AND cn_name = '".$condition['cn_name']."'";
        }
        if (isset($condition['status']) && ''!= $condition['status'] ) {
            $sql .= " AND status='". $condition['status']."'";
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
      
    public function getMailLog($id)
    {
        $sql = "SELECT * FROM mail_log WHERE id='$id' ";
        return $this->db_bak->getRow($sql);
    }

    public function createMailLog($data)
    {
        return $this->db->insert('mail_log', $data, true);
    }

    public function updateMailLog($data, $id)
    {
        $where = 'WHERE id = '.$id;
        return $this->db->updateData('mail_log', $data, $where); 
    }
    
    /**
     * 删除邮件log记录
     */
    public function deleteMailLog($id)
    {
        if ($id == '') {
            return false;
        }
        $sql = "delete from mail_log where id='$id'";
        return $this->db->delete($sql);     
    }


    
}