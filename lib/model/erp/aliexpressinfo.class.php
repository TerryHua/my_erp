<?php
/**
 * 信息管理模块
 * @author huaxiaofeng
 * @version 1.0 2014-11-11 22:50:31
 */
framework::plugin('mysql');
framework::plugin('https');
framework::model('market/product');
class aliexpressinfo
{
    public $db;
    public $db_bak;  

    //网页标题
    public $pageTitle = '/<h1 class=\"product-name\" itemprop=\"name\">(.*)<\/h1>/iUs';
    //好评
    public $pageRate = '/<span id=\"product-star" class=\"product-star">(.*)<\/span>/iUs';
    //货币
    public $currency = '/<span itemprop=\"priceCurrency\" content=\"USD\">(.*)<\/span>/iUs';
    //价格
    public $price = '/<span id=\"sku-price\" itemprop=\"price\">(.*)<\/span>/iUs';
    //如果是打折的原价价格格式
    public $price2 = '/<span id=\"sku-price\">(.*)<\/span>/iUs';
    //打折价
    public $discountPrice = '/<span id=\"sku-discount-price\" itemprop=\"price\">(.*)<\/span>/iUs';
    //产品规格
    public $specifics = '/<div class=\"ui-box-body\">(.*)<\/div>/iUs';
    //产品自定义描述
    public $description = '/<div class=\"ui-box-body\">(.*)<\/div>/iUs';
    //用户评论人数
    public $reviewUser = '/<span itemprop=\"reviewCount\">(.*)<\/span>/iUs';
    //获取重量
    public $weight = '/<dd class =\"pnl-packaging-weight\" rel=\"(.*)\">/iUs';
    //长宽高
    public $size = '/<dd class=\"pnl-packaging-size\" rel=\"(.*)\">/iUs';
    //链接
    public $pagelink = '/ class=\"picRind \" href=\"(.*)\" ><img/iUs';

    //获取查询框里的关键词
    public $searchText = '/autocomplete=\"off\" value=\"(.*)\" name=\"SearchText\" class=\"search-key\" id=\"search-key/iUs';

    //通过搜索获取链接
    public $pageLinkSearch = '/product \" href=\"(.*)\" title=\"/iUs'; 

    //获取主图
    public $mainpic = '/window.runParams.mainBigPic = \"(.*)\";.?/iUs';

    
    public function __construct()
    {
        $this->db = mysql::getInstance('mysql');    
        $this->db_bak = mysql::getInstance('mysql_bk');
    }    


    /**获取用户是否通过输入框搜索*/
    public function getSearchText($content)
    {
        preg_match($this->searchText, $content, $arr);
        return $arr[1];
    }


    public function getPageLinkBySearch($content)
    {
        preg_match_all($this->pageLinkSearch, $content, $arr);
        return $arr; 
    }


    /**通过类目浏览的获取多个产品的链接*/
    public function getPageLink($content)
    {
 
        preg_match_all($this->pagelink, $content, $arr);
        return $arr;
    }




    public function createProduct($url, $cateid=0)
    {

        $promode = new product(); 

        $content = $this->getProductPageInfo($url);
        $productid = $this->getAliexpressId($content);
        $title = $this->getTitle($content);
        $price = $this->getPrice($content);
        $discountprice = $this->getDiscountPrice($content);
        $desc = $this->getDescription($content);
        $orderCount = $this->getOrderCount($content);
        $review = $this->getUserReview($content);
        $image = $this->getBigImages($content);
        $size = $this->getProductSize($content);
        $property = $this->getSpecifics($content);
        $ship_fee = $this->getShippingFee($productid);
         
        $package = $this->getPackagingDetail($content);

        if ($discountprice == '') {
            $sale_price = $price;
        } else {
            $sale_price = $discountprice;
        }
        $exsit_row = $promode->getProductListBySourceId('aliexpress', $productid);
        if (!empty($exsit_row)) {
            return false;
        }
        $mainpic = $this->getMainPic($content);

        $array = array(
                'product_name' => mysql_escape_string($title), 
                'product_info' => mysql_escape_string($desc),
                'reletive_id' => $productid,
                'add_time' => date('Y-m-d H:i:s'),
                'update_time' => date('Y-m-d H:i:s'),
                'sale_price' => $sale_price,
                'oringal_price' => $price,
                'property' => json_encode($property),
                'order_count' => $orderCount,
                'review_user' => $review['review_user'],
                'review_rate' => $review['review_rate'],
                'ship_fee' => $ship_fee['freight']['0']['price']?$ship_fee['freight']['0']['price']:0,
                'weight' => $package['weight'],
                'length' => $package['length'],
                'width' => $package['width'],
                'height' => $package['height'],
                'terrace' => 'aliexpress',
                'status' => 1,
                'link' => mysql_escape_string($url),
                'main_pic' => $mainpic,
                'cate_id' => $cateid,
            ); 


        $id = $promode->createProductList($array);

        var_dump($array);
        //插入图片
        foreach ($image as $v) {
            $array = array(
                        'product_id' => $id,
                        'img_url' => $v,
                        'img_name' => 'aliexpress',
                        'thumb_url' => $v,
                        'img_original' => 'aliexpress',
                );
            $promode->createProductImages($array);
        }

        //插入sku信息
        foreach ($size as $v) {
            $array = array(
                        'pr_id' => $id,
                        'sku_attr' => $v['skuAttr'],
                        'sku_propIds' => $v['skuPropIds'],
                        'sku_price' => str_replace(',', '', $v['skuVal']['skuPrice']),
                        'inventory' => $v['skuVal']['inventory'],
                        'avail_quantity' => $v['skuVal']['availQuantity'], 
                );
            $promode->createProductSku($array);
        }
        return true; 
    }
    
    
    public function getProductPageInfo($url)
    {
        $content = https::curlRequest($url); 
        return $content;

    }
    public function getAliexpressId($content)
    {
        $start_str = 'window.runParams.productId=';
        $end_str = 'window.runParams.userCountry=';
        $start_pos = strpos($content, $start_str) + strlen($start_str);
        $end_pos = strpos($content, $end_str) -2;
        $content = substr($content, $start_pos, $end_pos-$start_pos);

        $content = trim($content, '"'); 
        
        return $content;
    }

    //获取标题
    public function getTitle($content)
    {
        preg_match($this->pageTitle, $content, $arr);
        return $arr[1];
    }

    //获取价格
    public function getPrice($content)
    {
        $price = 0;
        $str = '/<span itemprop=\"lowPrice\">(.*)<\/span>/iUs';
        preg_match($str, $content, $arr); 
        if ($arr[1] != '') {
            $price = $arr[1];
            return $price;
        }
        preg_match($this->price, $content, $arr);
        if ($arr[1] != '') {
            $price = $arr[1];
        } else {
            preg_match($this->price2, $content, $arr);
            $price = $arr[1];
        }
        if (strpos($price, '-')!=false) {
            $arr = explode('-', $price);
            $price = $arr[0];
        }
        $price = str_replace(',', '', $price);
        return trim($price);
    }

    //获取打折价格
    public function getDiscountPrice($content)
    {
        $str = '/<span itemprop=\"lowPrice\">(.*)<\/span>/iUs';
        preg_match($str, $content, $arr); 
        if ($arr[1] != '') { 
            return $arr[1];
        }
        preg_match($this->discountPrice, $content, $arr);
        return $arr[1];
    }

    //获取产品规格属性
    public function getSpecifics($content)
    {
        $pos = strpos($content, '<h2 class="ui-box-title">Item specifics</h2>');
        $content = substr($content, $pos);
        preg_match($this->specifics, $content, $arr); 
        $str = $arr[1];

        preg_match_all('/<dt>(.*).<\/dt>/iUs', $str, $arr);
        preg_match_all('/<dd title=\"(.*)\">/iUs', $str, $arr2);
        
        if (!empty($arr[1])) { 
            return array_combine($arr[1], $arr2[1]);
        } else {
            return array();
        }
    }

    //获取产品自定义描述
    public function getDescription($content)
    {
        $str = 'window.runParams.descUrl=';
        $start_pos = strpos($content, $str) + strlen($str);
        $end_pos = strpos($content, 'window.runParams.crosslinkUrl=') -3;
        $content = substr($content, $start_pos, $end_pos-$start_pos);
        
        $url = trim($content, '"'); 
        $desc = https::curlRequest($url); 
        $desc = str_replace("window.productDescription='", '', $desc); 
       
        $desc = trim(trim($desc, ';'), "'"); 
        return $desc;
    }


    //获取产品轮播图里面的大图片
    public function getBigImages($content)
    {
        $start_str = 'window.runParams.imageBigViewURL=';
        $end_str = 'window.runParams.mainBigPic';
        $start_pos = strpos($content, $start_str) + strlen($start_str);
        $end_pos = strpos($content, $end_str) -2;
        $content = substr($content, $start_pos, $end_pos-$start_pos);

        $array = json_decode($content);
        if (!is_array($array)) {
            return array();
        } 
        return $array; 
    }

    //获取在速卖通上卖出的个数
    public function getOrderCount($content)
    {
        $start_str = 'window.runParams.productTradeCount=';
        $end_str = 'window.runParams.startValidDate';
        $start_pos = strpos($content, $start_str) + strlen($start_str);
        $end_pos = strpos($content, $end_str) -2;
        $content = substr($content, $start_pos, $end_pos-$start_pos);

        $content = trim($content, '"'); 
        $content = str_replace(',', '', $content);
        return $content?$content:0;  
    }


    //获取评论情况
    public function getUserReview($content)
    {
        //获取评价人数
        $end_str = 'of buyers enjoyed this product';
        preg_match($this->reviewUser, $content, $arr);
        $array['review_user'] = $arr[1]?$arr[1]:0;

        //获取好评率
        $end_pos = strpos($content, $end_str); 
        $content = substr($content, $end_pos-15,15 ); 
        preg_match('/<b>(.*)<\/b>/iUs', $content, $arr);

        $array['review_rate'] = str_replace('%', '', $arr[1]);
        $array['review_rate'] = $array['review_rate']?$array['review_rate']:0;
        return $array;
    }

    //获取运费
    public function getShippingFee($productid)
    {
        $time = $this->getMircotime();
      
        $url = 'http://freight.aliexpress.com/ajaxFreightCalculateService.htm?f=d&productid='.$productid;
        $url .= '&userType=cnfm&country=US&province=&city=&count=1&currencyCode=USD&sendGoodsCountry=&_='.$time;
        $content = https::curlRequest($url);
       // var_dump($content);
        $content = trim($content, '(');
        $content = trim($content, ')');
        $content = json_decode($content, true); 
        return $content;
    }

    public function getMircotime()
    {
        
        $time = explode ( " ", microtime () );  
        $time = $time [1] . ($time [0] * 1000);  
        $time2 = explode ( ".", $time );  
        $time = $time2 [0];  
        return $time;
    }

    //获取产品的包装信息 重量，长宽高
    public function getPackagingDetail($content)
    {
        preg_match($this->weight, $content, $arr);
        $array['weight'] = $arr['1'];

        preg_match($this->size, $content, $arr); 
        $size = $arr[1];

        $size_array = explode("|", $size);
        $array['length'] = $size_array[0];
        $array['width'] = $size_array[1];
        $array['height'] = $size_array[2];

        return $array;

    }

    ///获取产品的不同规格和价格
    public function getProductSize($content)
    {
        $start_str = 'var skuProducts=';
        $end_str = 'window.runParams.quantityHTML';
        $start_pos = strpos($content, $start_str) + strlen($start_str);
        $end_pos = strpos($content, $end_str) -2;
        $content = substr($content, $start_pos, $end_pos-$start_pos);
 
        $content = trim($content);
        $content = trim($content, ';'); 
        $array = json_decode($content, true); 
        if (is_array($array)) {
            return $array;
        } else {
            return array();
        }
 
    }

    public function getMainPic($content)
    {
        preg_match($this->mainpic, $content, $arr);
        return $arr[1];
    }


}