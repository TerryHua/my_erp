<?php
/**
 * 信息管理模块
 * @author huaxiaofeng
 * @version 1.0 2014-11-11 22:50:31
 */
framework::plugin('mysql');
class info
{
    public $db;
    public $db_bak;  
    
    public function __construct()
    {
        $this->db = mysql::getInstance('mysql');    
        $this->db_bak = mysql::getInstance('mysql_bk');
    }    
    
    
    public function getRow($id)
    {
        $sql = "SELECT * FROM page_info where id='$id'";
        return $this->db_bak->getRow($sql);
    }
              
    /**
     * 添加结算单
     */
    public function create($data)
    { 
        return $this->db->insert('page_info', $data, true);
    }
    
    public function update($data, $id)
    {
        $where = 'WHERE id = '.$id;
        return $this->db->updateData('page_info', $data, $where); 
    }
    
    public function updateByCode($data, $code)
    {
        $where  = " WHERE page_code='".$code."'";
        return $this->db->updateData('page_info', $data, $where);
    }

    /**
     * 删除结算单记录
     */
    public function delete($id)
    {
        if ($id == '') {
            return false;
        }
        $sql = "delete from page_info where id='$id'";
        return $this->db->delete($sql);     
    }
    
    
    /**
     * 获取页面信息
     */
    public function getDataList($condition)
    {
        framework::plugin('Page');      
         
        $sql = "SELECT * FROM page_info where 1 "; 
        
        if (isset($condition['start_date']) && ''!=$condition['start_date']) {
            $sql .= " AND add_time >= '".$condition['start_date']." 00:00:00'";
        } 
        if (isset($condition['end_date']) && ''!=$condition['end_date']) {
            $sql .= " AND add_time <= '".$condition['end_date']." 23:59:59'";
        }
        if (isset($condition['page_code']) && ''!=$condition['page_code']) {
            $sql .= " AND page_code='". $condition['page_code']."'";
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
      
    public function getByPageCode($code)
    {
        $sql = "SELECT * FROM page_info WHERE page_code='$code'";
        return $this->db->getRow($sql);
    }



    /**
     * 获取结算单列表
     */
    public function getNewsList($condition)
    {
        framework::plugin('Page');      
         
        $sql = "SELECT * FROM news where 1 "; 
        
        if (isset($condition['start_date']) && ''!=$condition['start_date']) {
            $sql .= " AND add_time >= '".$condition['start_date']." 00:00:00'";
        } 
        if (isset($condition['end_date']) && ''!=$condition['end_date']) {
            $sql .= " AND add_time <= '".$condition['end_date']." 23:59:59'";
        }
        if (isset($condition['author']) && ''!=$condition['author']) {
            $sql .= " AND author='". $condition['author']."'";
        }
        if (isset($condition['status']) && ''!==$condition['status']) {
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
      

          
    public function getNewsRow($id)
    {
        $sql = "SELECT * FROM news where id='$id'";
        return $this->db_bak->getRow($sql);
    }

    /**
     * 添加新闻
     */
    public function createNews($data)
    { 
        return $this->db->insert('news', $data, true);
    }


    public function updateNews($data, $id)
    {
        $where = 'WHERE id = '.$id;
        return $this->db->updateData('news', $data, $where); 
    }
    
     /**
     * 删除结算单记录
     */
    public function deleteNews($id)
    {
        if ($id == '') {
            return false;
        }
        $sql = "delete from news where id='$id'";
        return $this->db->delete($sql);     
    }
    
}