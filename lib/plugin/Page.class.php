<?php
class Page {
	public $db;
	//初始化分页信息
	public $fromNum = 0; //从第哪一条记录开始
	public $eachNums = 10; //每页显示纪录的数量
	public $orderBy = 'id'; //排列字段
	public $desc = 'DESC'; //排列顺序 DESC/ASC
	

	//获取分页基本信息
	public $allPage = ''; //总页数
	public $allNums = ''; //总记录数
	public $thisPage = '1'; //当前页码
	public $nextPage = ''; //下一页码
	

	//获取显示效果
	public $showMiddleNums = 1; //是否显示中间页码
	public $middleNums = ''; //显示中间的页码1,2,3,4...99,100
	public $html = '';
	
	public $sql = '';
	
	public $sqlwhere = '';
	//分页URI样式
	public $uriStyle = '/'; //http://www.xxx.com/page/1.html支持两种模式 ? 和/   //select a.* from
	
    public function dealSQL($sql){
        $tag = array('select','from','where','and');
        foreach ($tag as $k=>$v){
            $sql = str_replace($v, strtoupper($v), $sql);
        }
        return $sql;
    }
	public function getPage($sql, $cache = 0, $dbinc = null) {
		if ($dbinc) {
			$this->db = mysql::getInstance($dbinc);  
		} else {
			$this->db = mysql::getInstance();  
		}
		$sql = $this->dealSQL($sql);
		
		if ($cache) {
			
			$sql4 = strstr ( $sql, 'FROM' );
			$this->sqlwhere = $sql4;
			$sql2 = 'SELECT COUNT(*)  ' . $sql4;
			$all = $this->db->getOne ( $sql2 );
		} else {
			
			$sql4 = strstr ( $sql, 'FROM' );
			$this->sqlwhere = $sql4;
			$sql2 = 'SELECT COUNT(*)  ' . $sql4;
			$all = $this->db->getOne ( $sql2 );
		
		}
		$thisPage = @framework::get('page'); 
		if ($thisPage != '') {
			$this->thisPage = $thisPage;
		}
		if ($this->thisPage != $this->allPage) {
			$this->nextPage = $this->thisPage + 1;
		}
		$this->allNums = $all;
		$this->allPage = ceil ( $this->allNums / $this->eachNums );
		
		if ($this->orderBy != '') {
			$sql = $sql . " ORDER BY " . $this->orderBy . " " . $this->desc;
		}
		$sql = $sql . " LIMIT " . (($this->thisPage - 1) * $this->eachNums) . "," . $this->eachNums;
		$this->sql = $sql;
		
		if ($cache) {
			$rs = $this->db->getAll ( $sql, true );
		} else {
			$rs = $this->db->getAll ( $sql );
		}
		return $rs;
	}
	public function sumCol($col, $as = null) {
		if ($as == null)
			$as = $col;
		$sql = "SELECT SUM($col) AS $as " . $this->sqlwhere;
		$rs = $this->db->getOne ( $sql );
		return $rs;
	}
	public function htmlPage() {
		$thisPage = $this->thisPage;
		$allPage = $this->allPage;
		$blank = '&nbsp;';
		if ($allPage == 0){
			return;
		}else {
			$allPageHtml = '<span>共'.$allPage.'页，</span>';
		}
		if ($thisPage == 1) {
			$firstPage = '<span>首页</span>';
		} else {
			
			$firstPage = '<a href="' . self::changeUri ( 1 ) . ' ">首页</a>';
		}
		
		if ($thisPage == 1) {
			$prePage = '<span>上一页</span>';
		} else {
			$prePage = '<a href="' . self::changeUri ( $this->thisPage - 1 ) . '">上一页</a>';
		}
		
		if ($thisPage == $allPage or $allPage == 0) {
			$nextPage = '<span>下一页</span>';
		} else {
			$nextPage = '<a href="' . self::changeUri ( $this->nextPage ) . '">下一页</a>';
		}
		if ($thisPage == $allPage or $allPage == 0) {
			$endPage = '<span>尾页</span>';
		} else {
			$endPage = '<a href="' . self::changeUri ( $allPage ) . '">尾页</a>';
		}
		if ($this->showMiddleNums == 1) {
			if ($this->allPage > 5) {
				if ($this->allPage >= ($this->thisPage + 5)) {
					$max = $this->thisPage + 4;
				} else {
					$max = $this->allPage;
				}
				$min = ($this->thisPage - 5);
				if ($min <= 1) {
					$min = 1;
				}
				for($i = $min; $i <= $max; $i ++) {
					
					if ($this->thisPage == $i) {
						$this->middleNums = $this->middleNums . '<span class="thispage">' . $i . '</span>' . $blank;
					} else {
						$this->middleNums = $this->middleNums . '<a href="' . self::changeUri ( $i ) . '" >' . $i . '</a>' . $blank;
					}
				
				}
			} else {
				for($i = 1; $i <= $this->allPage; $i ++) {
					if ($this->thisPage == $i) {
						$this->middleNums = $this->middleNums . '<span  class="thispage">' . $i . '</span>' . $blank;
					} else {
						$this->middleNums = $this->middleNums . '<a href="' . self::changeUri ( $i ) . '" >' . $i . '</a>' . $blank;
					}
				}
			
			}
		} else {
			$this->middleNums = '';
		}
		
		$rs = $allPageHtml.$firstPage . $blank . $blank . $prePage . $blank . $this->middleNums . $blank . $nextPage . $blank . $endPage;
		
		return str_replace ( '?do=create', '', $rs );
	}
	public function changeUri($topage) {
		
		$uri = $_SERVER ['REQUEST_URI'];
		$uriStyle = '\\' . $this->uriStyle;
		if ($this->uriStyle != '?') {
			
			if (preg_match ( "/" . $uriStyle . "page" . $uriStyle . "[0-9]/", $uri )) {
				$rs = preg_replace ( '/' . $uriStyle . 'page' . $uriStyle . '[0-9]*/', "/page/" . $topage, $uri );
			} else {
				if (preg_match ( '/page\=[0-9]*/', $uri )) {
					$rs = preg_replace ( '/page\=[0-9]*/', 'page=' . $topage, $uri );
				} else {
					$rs = preg_replace ( '/\.(html|htm)/', '', $uri . $this->uriStyle . 'page' . $this->uriStyle . $topage ) . '.html';
				
				}
			}
			
			return str_replace ( '//', '/', $rs );
		} else {
			if (preg_match ( '/page\=[0-9]/', $uri )) {
				return preg_replace ( '/page\=[0-9]*/', 'page=' . $topage, $uri );
			} else {
				if (preg_match ( '/\?[\w]+\=[\w]*/', $uri )) {
					return $uri . '&page=' . $topage;
				} else {
					return $uri . $this->uriStyle . 'page=' . $topage;
				}
			}
		}
	}
}
?>