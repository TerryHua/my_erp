<?php
class mysql {
    private $conn = false;
    private $dbHost;
    private $dbUser;
    private $dbPass;
    private $dbDatabase;
    private $dbChar;
    private $dbCacheTime;
    public static $_instance = null;
    private static $_dbinc = null;
    
    private function __construct($dbConfig = 'mysql') {
        $configPath = HIGHPHP_ROOT_DIR . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'db.inc';
        $dbArray = framework::configLoad ( $dbConfig, $configPath );
        if ($dbArray == false) {
            framework::end ( "get mysql config:[$dbConfig] faild in $configPath", 2 );
        }
        $this->dbHost = $dbArray ['host'];
        $this->dbUser = $dbArray ['user'];
        $this->dbPass = $dbArray ['password'];
        $this->dbDatabase = $dbArray ['database'];
        $this->dbChar = $dbArray ['charset'];
        $this->dbCacheTime = $dbArray ['cachetime'];
        self::$_dbinc = $dbConfig;
    }
    public static function getInstance($dbConfig = 'mysql') {
        if (! self::$_instance instanceof self or self::$_dbinc != $dbConfig) {
            self::$_instance = new self ( $dbConfig );
        }
        return self::$_instance;
    }
    public function connect() {
        if ($this->conn == false) {
            $this->conn = mysql_connect ( $this->dbHost, $this->dbUser, $this->dbPass, true ) or framework::end ( 'connect mysql server [' . $this->dbHost . '] error :' . mysql_error (), 2 );
            mysql_select_db ( $this->dbDatabase, $this->conn ) or framework::end ( ' select db [' . $this->dbDatabase . '] error :' . mysql_error (), 2 );
            if ($this->dbChar) {
                $this->query ( "set names " . $this->dbChar, $this->conn );
            }
        }
    }
    private function __clone() {
    }
    public function updateData($table, $data, $where) {
        $set = ' SET ';
        $count = count ( $data );
        $i = 1;
        foreach ( $data as $k => $v ) {
            if ($i < $count) {
                $set .= "  `$k`= '$v', ";
            } else {
                $set .= "  `$k`= '$v' ";
            }
            $i ++;
        }
        $sql = "UPDATE $table $set $where ";
        $res = $this->query ( $sql );
        return $res;
    
    }
    public function delete($sql) {
        framework::logWrite ( $sql, 1, 'delete' );
        return $this->query ( $sql );
    }
    public function updateSql($sql) {
        #framework::logWrite ( $sql, 1, 'update' );
        return $this->query ( $sql );
    }
    private function query($sql) {
        $this->connect ();
        $res = mysql_query ( $sql, $this->conn );
        if (! $res) {            
            $errno = mysql_errno($this->conn);
            $errinfo = mysql_error ( $this->conn );
            framework::end ( 'Mysql Query Error:'.$sql.' error no:'.$errno.' error info:'.$errinfo, 2 );
            if ($errno==1054){
                return false;
            }
            $this->conn = false;
            $this->connect ();
            $res = mysql_query ( $sql, $this->conn );
        }
        return $res;
    }
    
    public function insert($table, $data, $return_insert_id = false) {
        $sql = $this->implodeFieldValue ( $data );
        $cmd = 'INSERT INTO';
        $sql = "$cmd $table SET $sql";
        $return = $this->query ( $sql );
        return $return_insert_id ? $this->insert_id () : $return;
    }
    public function insertSql($sql) {
        framework::logWrite ( $sql, 1, 'insertsql' );
        return $this->query ( $sql );
    }
    public function insert_id() {
        return mysql_insert_id ( $this->conn );
    }
    public function implodeFieldValue($array, $glue = ',') {
        
        $sql = $comma = '';
        foreach ( $array as $k => $v ) {
            $sql .= $comma . "`$k`='$v'";
            $comma = $glue;
        }
        return $sql;
    }
    public function getOne($sql, $cache = false, $cachetime = 0) {
        if ($cache) {
            $result = $this->getSqlCacheData ( 'getOne' . $sql, $cachetime );
            if (! empty ( $result ['data'] )) {
                return $result ['data'];
            }
        }
        $res = $this->query ( $sql );
        if ($res !== false) {
            $row = mysql_fetch_row ( $res );
            if ($row !== false) {
                if ($cache) {
                    $this->setSqlCacheData ( $result ['file'], $row [0] );
                }
                return $row [0];
            } else {
                return '';
            }
        } else {
            return false;
        }
    }
    public function getRow($sql, $cache = false, $cachetime=0) {
        if ($cache) {
            $result = $this->getSqlCacheData ( 'getRow' . $sql, $cachetime );
            if (! empty ( $result ['data'] )) {
                return $result ['data'];
            }
        }
        $res = $this->query ( $sql );
        if ($res !== false) {
            $arr = mysql_fetch_assoc ( $res );
            if ($cache) {
                $this->setSqlCacheData ( $result ['file'], $arr );
            }
            return $arr;
        } else {
            return false;
        }
    }
    public function getAll($sql, $cache = false, $cachetime = 0) {
        if ($cache) {
            $result = $this->getSqlCacheData ( 'getAll' . $sql, $cachetime );
            if (! empty ( $result ['data'] )) {            
                return $result ['data'];
            }
        }
        $res = $this->query ( $sql );
        if ($res !== false) {
            $arr = array ();
            while ( @$row = mysql_fetch_assoc ( $res ) ) {
                $arr [] = $row;
            }
            if ($cache) {
                $this->setSqlCacheData ( $result ['file'], $arr );
            }
            return $arr;
        } else {
            return false;
        }
    }
    /**
     * 获取数据记录集缓存
     *
     * author redstone
     * param  string  $sql     查询语句
     * param  string  $cached  缓存选项
     * return array
     **/
    public function getSqlCacheData($sql, $cachetime = 0) {
        $sql = trim ( $sql );
        $md5k = md5 ( $sql );
        $dir = HIGHPHP_CACHE_DIR . 'db/' .framework::$project.'/'. substr ( $md5k, 0, 1 ) . '/' . substr ( $md5k, 1, 1 ) . '/'. substr ( $md5k, 2, 1 ) . '/';
        if (! is_dir ( $dir ))
            mkdir ( $dir, 0777, 1 );
        $fpath = $dir . $md5k . '.php';
        $res = array ('data' => false, 'file' => $fpath );
        if (! $cachetime)
            $cachetime = $this->dbCacheTime;
        if (file_exists ( $fpath ) and (filemtime ( $fpath ) + $cachetime) > time ()) {
            $res ['data'] = json_decode(include $fpath,true);
        }
        return $res;
    }
    public function clearSqlCacheData($sql){
        $sql = trim ( $sql );
        $md5k = md5 ( $sql );
        $dir = HIGHPHP_CACHE_DIR . 'db/' .framework::$project.'/'. substr ( $md5k, 0, 1 ) . '/' . substr ( $md5k, 1, 1 ) . '/'. substr ( $md5k, 2, 1 ) . '/';        
        $fpath = $dir . $md5k . '.php';
        if (file_exists($fpath)) {
            return unlink($fpath);
        }
        return false;
    }
    public function setSqlCacheData($file, $data) {
        @file_put_contents ( $file, '<?php
return ' ."'". json_encode( $data ) ."'". ';', LOCK_EX );
        clearstatcache ();
    }
    public function __destruct() {
        //        mysql_close($this->conn);
    }
}


