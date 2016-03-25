<?php
/**
 * 产品模块
 * @author huaxiaofeng
 * @version 1.0 2014-11-18 22:28:32
 */
framework::plugin('mysql');
class product
{
    public $db;
    public $db_bak;  
    
    public function __construct()
    {
        $this->db = mysql::getInstance('mysql');    
        $this->db_bak = mysql::getInstance('mysql_bk'); 
    }    
    
          
          
           
    public function getProductCate($id)
    {
        $sql = "SELECT * FROM product_cate where id='$id'";
        return $this->db_bak->getRow($sql);
    }
              
    /**
     * 添加结算单
     */
    public function createProductCate($data)
    { 
        return $this->db->insert('product_cate', $data, true);
    }
    
    public function updateProductCate($data, $id)
    {
        $where = 'WHERE id = '.$id;
        return $this->db->updateData('product_cate', $data, $where); 
    }
    
    /**
     * 删除结算单记录
     */
    public function deleteProductCate($id)
    {
        if ($id == '') {
            return false;
        }
        $sql = "delete from product_cate where id='$id'";
        return $this->db->delete($sql);     
    }
    
    
    /**
     * 获取产品分类列表
     */
    public function getProductCateList($condition)
    {
        framework::plugin('Page');      
         
        $sql = "SELECT * FROM product_cate WHERE 1 "; 
        
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
      
    public function getAllProductCate()
    {
        $sql = "SELECT * FROM product_cate ";
        return $this->db_bak->getAll($sql);
    }
    
    public function getCateByParentId($parent_id)
    {
        $sql = "SELECT * FROM product_cate WHERE parent_id='".$parent_id."'";
        return $this->db_bak->getAll($sql);
    }





    /**
     * 获取产品列表
     */
    public function getProductistCondition($condition)
    {
        framework::plugin('Page');      
         
        $sql = "SELECT * FROM product_list WHERE 1 "; 
        
        if (isset($condition['product_name']) && ''!=$condition['product_name']) {
            $sql .= " AND product_name = '".$condition['product_name']."'";
        } 
        if (isset($condition['cate_id']) && ''!=$condition['cate_id']) {
            $sql .= " AND cate_id = '".$condition['cate_id']."'";
        }
        if (isset($condition['status']) && ''!= $condition['status'] ) {
            $sql .= " AND status='". $condition['status']."'";
        }   
        if (isset($condition['id']) && ''!=$condition['id']) {
            $sql .= " AND id = '".$condition['id']."'";
        } 
        if (isset($condition['terrace']) && ''!=$condition['terrace']) {
            $sql .= " AND terrace = '".$condition['terrace']."'";
        } 
        if (isset($condition['start_price']) && ''!=$condition['start_price']) {
            $sql .= " AND sale_price >= '".$condition['start_price']."'";
        } 
        if (isset($condition['end_price']) && ''!=$condition['end_price']) {
            $sql .= " AND sale_price <= '".$condition['end_price']."'";
        } 
        if (isset($condition['start_shipfee']) && ''!=$condition['start_shipfee']) {
            $sql .= " AND ship_fee >= '".$condition['start_shipfee']."'";
        } 
        if (isset($condition['end_shipfee']) && ''!=$condition['end_shipfee']) {
            $sql .= " AND ship_fee <= '".$condition['end_shipfee']."'";
        } 
        if (isset($condition['start_review_user']) && ''!=$condition['start_review_user']) {
            $sql .= " AND review_user >= '".$condition['start_review_user']."'";
        } 
        if (isset($condition['end_review_user']) && ''!=$condition['end_review_user']) {
            $sql .= " AND review_user <= '".$condition['end_review_user']."'";
        } 
        if (isset($condition['start_review_rate']) && ''!=$condition['start_review_rate']) {
            $sql .= " AND review_rate >= '".$condition['start_review_rate']."'";
        } 
        if (isset($condition['end_review_rate']) && ''!=$condition['end_review_rate']) {
            $sql .= " AND review_rate <= '".$condition['end_review_rate']."'";
        }  
        if (isset($condition['orderby']) && ''!=$condition['orderby']) {
            $orderby = $condition['orderby'];
        } else {
            $orderby = 'id';
        }
        if (isset($condition['assort']) && ''!=$condition['assort']) {
           $assort = $condition['assort'];
        } else {
            $assort = 'desc';
        }
          

        //设置分页信息
        $page = new Page ();
        $page->uriStyle = '?';
        $page->eachNums = 15;
        $page->orderBy = $orderby;
        $page->desc = $assort; 
        $list = $page->getPage ( $sql, 0, 'mysql_bk' );
        $html = $page->htmlPage ();
         
        //返回搜索结果和分页信息
        return array ('list' => $list, 'html' => $html, 'total' => $page->allNums, 'eachNums' => $page->eachNums);
    }


    public function getProductSku($id)
    {
        $sql = "SELECT * FROM product_sku where id='$id'";
        return $this->db_bak->getRow($sql);
    }
              
    /**
     * 添加结算单
     */
    public function createProductSku($data)
    { 
        return $this->db->insert('product_sku', $data, true);
    }
    
    public function updateProductSku($data, $id)
    {
        $where = 'WHERE id = '.$id;
        return $this->db->updateData('product_sku', $data, $where); 
    }
    
    /**
     * 删除结算单记录
     */
    public function deleteProductSku($id)
    {
        if ($id == '') {
            return false;
        }
        $sql = "delete from product_sku where id='$id'";
        return $this->db->delete($sql);     
    }



     public function getProductImages($id)
    {
        $sql = "SELECT * FROM product_images where id='$id'";
        return $this->db_bak->getRow($sql);
    }
              
    /**
     * 添加结算单
     */
    public function createProductImages($data)
    { 
        return $this->db->insert('product_images', $data, true);
    }
    
    public function updateProductImages($data, $id)
    {
        $where = 'WHERE id = '.$id;
        return $this->db->updateData('product_images', $data, $where); 
    }
    
    /**
     * 删除结算单记录
     */
    public function deleteProductImages($id)
    {
        if ($id == '') {
            return false;
        }
        $sql = "delete from product_images where id='$id'";
        return $this->db->delete($sql);     
    }




     public function getProductList($id)
    {
        $sql = "SELECT * FROM product_list where id='$id'";
        return $this->db_bak->getRow($sql);
    }
              
    public function getProductListBySourceId($source, $id)
    {
        $sql = "SELECT * FROM product_list WHERE reletive_id='$id' and terrace='$source'";
        return $this->db_bak->getRow($sql);
    }

    /**
     * 添加结算单
     */
    public function createProductList($data)
    { 
        return $this->db->insert('product_list', $data, true);
    }
    
    public function updateProductList($data, $id)
    {
        $where = 'WHERE id = '.$id;
        return $this->db->updateData('product_list', $data, $where); 
    }
    
    /**
     * 删除结算单记录
     */
    public function deleteProductList($id)
    {
        if ($id == '') {
            return false;
        }
        $sql = "delete from product_list where id='$id'";
        return $this->db->delete($sql);     
    }
    
}