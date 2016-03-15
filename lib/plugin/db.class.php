<?php
class db
{
    public $max_cache_time = ''; 
    public $cache_dir      = '';
    public $query_log      = array();
    public $root_path      = '';
    public $error_message  = array();
    public $version        = '';
    public $starttime      = 0;
    public $timeline       = 0;
    public $timezone       = 0;
    public $_link_id       = NULL;
    public $_settings      = array();
    public $_query_count   = 0;
    public $_dbhash        = '';
    public $_platform      = '';
    public $mysql_config_cache_file_time = 0;
    public $mysql_disable_cache_tables = array();
    public function connect($dbhost, $dbuser, $dbpw, $dbname = '', $charset = 'utf8', $pconnect = 0, $quiet = 0,$cachetime)
    {
    	$this->cache_dir = ROOT_DIR . '/cache/db/';
    	$this->max_cache_time = $cachetime;
        if ($pconnect)
        {
            if (!($this->_link_id = @mysql_pconnect($dbhost, $dbuser, $dbpw)))
            {
                if (!$quiet)
                {
                    $this->ErrorMsg();
                }
                return false;
            }
        }
        else
        {           
        	$this->_link_id = @mysql_connect($dbhost, $dbuser, $dbpw, true);
            if (!$this->_link_id)
            {
                if (!$quiet)
                {
                    $this->ErrorMsg();
                }

                return false;
            }
        }
        $this->_dbhash  = md5(ROOT_DIR . $dbhost . $dbuser . $dbpw . $dbname);
        $this->version  = mysql_get_server_info($this->_link_id);
        if ($this->version > '4.1')
        {
            if ($charset != 'latin1')
            {
                mysql_query("SET character_set_connection=$charset, character_set_results=$charset, character_set_client=binary", $this->_link_id);
            }
            if ($this->version > '5.0.1')
            {
                mysql_query("SET sql_mode=''", $this->_link_id);
            }
        }
        $sqlcache_config_file = $this->cache_dir . 'config_file_' . $this->_dbhash . '.php';
        if (is_file($sqlcache_config_file))
        {
            include($sqlcache_config_file);
        }
        $this->starttime = time();
        if ($this->max_cache_time && $this->starttime > $this->mysql_config_cache_file_time + $this->max_cache_time)
        {
            if ($dbhost != '.')
            {
                $result = mysql_query("SHOW VARIABLES LIKE 'basedir'", $this->_link_id);
                $row    = mysql_fetch_assoc($result);
                if (!empty($row['Value']{1}) && $row['Value']{1} == ':' && !empty($row['Value']{2}) && $row['Value']{2} == "\\")
                {
                    $this->_platform = 'WINDOWS';
                }
                else
                {
                    $this->_platform = 'OTHER';
                }
            }
            else
            {
                $this->_platform = 'WINDOWS';
            }
            if ($this->_platform == 'OTHER' &&
                ($dbhost != '.' && strtolower($dbhost) != 'localhost:3306' && $dbhost != '127.0.0.1:3306') ||
                (PHP_VERSION >= '5.1' && date_default_timezone_get() == 'UTC'))
            {
                $result = mysql_query("SELECT UNIX_TIMESTAMP() AS timeline, UNIX_TIMESTAMP('" . date('Y-m-d H:i:s', $this->starttime) . "') AS timezone", $this->_link_id);
                $row    = mysql_fetch_assoc($result);
                if ($dbhost != '.' && strtolower($dbhost) != 'localhost:3306' && $dbhost != '127.0.0.1:3306')
                {
                    $this->timeline = $this->starttime - $row['timeline'];
                }

                if (PHP_VERSION >= '5.1' && date_default_timezone_get() == 'UTC')
                {
                    $this->timezone = $this->starttime - $row['timezone'];
                }
            }
            $content = '<' . "?php\r\n" .
                       '$this->mysql_config_cache_file_time = ' . $this->starttime . ";\r\n" .
                       '$this->timeline = ' . $this->timeline . ";\r\n" .
                       '$this->timezone = ' . $this->timezone . ";\r\n" .
                       '$this->_platform = ' . "'" . $this->_platform . "';\r\n?" . '>';
            @file_put_contents($sqlcache_config_file, $content, LOCK_EX);
        }
        /* 选择数据库 */
        if ($dbname)
        {
            if (mysql_select_db($dbname, $this->_link_id) === false )
            {
                if (!$quiet)
                {
                    $this->ErrorMsg();
                }
                return false;
            }
            else
            {
                return true;
            }
        }
        else
        {
            return true;
        }
    }

    public function select_database($dbname)
    {
        return mysql_select_db($dbname, $this->_link_id);
    }

    public function set_mysql_charset($charset)
    {
        /* 如果mysql 版本是 4.1+ 以上，需要对字符集进行初始化 */
        if ($this->version > '4.1')
        {
            if (in_array(strtolower($charset), array('gbk', 'big5', 'utf-8', 'utf8')))
            {
                $charset = str_replace('-', '', $charset);
            }
            if ($charset != 'latin1')
            {
                mysql_query("SET character_set_connection=$charset, character_set_results=$charset, character_set_client=binary", $this->_link_id);
            }
        }
    }
    public function fetch_array($query, $result_type = MYSQL_ASSOC)
    {
        return mysql_fetch_array($query, $result_type);
    }
    public function query($sql, $type = '', $times=0)
    {
        if ($this->_link_id === NULL)
        {
            $this->connect($this->_settings['dbhost'], $this->_settings['dbuser'], $this->_settings['dbpw'], $this->_settings['dbname'], $this->_settings['charset'], $this->_settings['pconnect']);
            $this->_settings = array();
        }
        $this->_query_count++;
        if ($this->_query_count <= 99)
        {
            $this->query_log[] = $sql;            
        }
        /* 当当前的时间大于类初始化时间的时候，自动执行 ping 这个自动重新连接操作 */
        if (PHP_VERSION >= '4.3' && time() > $this->starttime + 1)
        {
            mysql_ping($this->_link_id);
        }
        if (!($query = mysql_query($sql, $this->_link_id)))
        {
            $errno = mysql_errno();
            $error = mysql_error();
            if (($errno == 126 || $errno == 145 || $errno == 1062 || $errno == 1194 || $errno == 1034 || $errno == 1035)
                && $times == 0 && (preg_match('/\'\.?\\\\?([\w_]+)\\\\?([\w_]+)\'/', $error, $match) !== false))
            {          
                /* 如果错误类型为可修复，则尝试修复这个表 */
                if (isset($match[2]))
                {
                    mysql_query("REPAIR TABLE $match[2]");
                    $query = $this->query($sql, $type, 1);
                }
            }
            elseif ($errno == 2006)
            {
                $this->ErrorMsg();exit;
            }
            else
            {
                if ($type != 'SILENT')
                {
                    $trace  = debug_backtrace();
                    $msg    = 'MySQL Error[' .mysql_errno($this->_link_id). ']: ' . mysql_error($this->_link_id). "\nMySQL Query:" .$sql;
                    $msg    .= "\nWrong File:  " .$trace[0]['file']. "[" .$trace[0]['line']. "]";
                    trigger_error($msg, E_USER_ERROR);
                    return false;
                }
            }
        }
        //记录sql log
        if (defined('DEBUG_MODE') && (DEBUG_MODE & 8) == 8)
        {
            $logfilename = $this->root_path . 'data/mysql_query_' . $this->_dbhash . '_' . date('Y_m_d') . '.log';
            $str = $sql . "\n\n";
            file_put_contents($logfilename, $str, FILE_APPEND, LOCK_EX);                       
        }

        return $query;
    }

   

    public function affected_rows()
    {
        return mysql_affected_rows($this->_link_id);
    }

    public function error()
    {
        return mysql_error($this->_link_id);
    }

    public function errno()
    {
        return mysql_errno($this->_link_id);
    }

    public function result($query, $row)
    {
        return @mysql_result($query, $row);
    }

    public function num_rows($query)
    {
        return mysql_num_rows($query);
    }

    public function num_fields($query)
    {
        return mysql_num_fields($query);
    }

    public function free_result($query)
    {
        return mysql_free_result($query);
    }

    public function insert_id()
    {
        return mysql_insert_id($this->_link_id);
    }

    public function fetchRow($query)
    {
        return mysql_fetch_assoc($query);
    }

    public function fetch_fields($query)
    {
        return mysql_fetch_field($query);
    }

    public function version()
    {
        return $this->version;
    }

    public function ping()
    {
        return mysql_ping($this->_link_id);
        
    }

    public function escape_string($unescaped_string)
    {
        return mysql_real_escape_string($unescaped_string);
       
    }

    public function close()
    {
        return mysql_close($this->_link_id);
    }

    public function ErrorMsg($message = '')
    {
        if ($message)
        {
            echo "<b>Database info</b>: $message\n\n";
            framework::logWrite("Database info: $message",'error');
        }
        else
        {
            static $last_errno = null;
            $error = mysql_error();
            $error_no = mysql_errno();
            if ($last_errno == $error_no)
            {
                exit;
            }
            if ($last_errno === null)
            {
                $last_errno = $error_no;
            }           
            echo "<b>MySQL server error report:</b><br />";
            echo "Error:",$error, "<br />";
            echo "Errno:", $error_no, "<br />";   
            framework::logWrite("MySQL server error report:\r\n"
            ."Error:".$error."\r\n"."Errno:". $error_no,'error');         
        }
        exit;
    }

/* 仿真 Adodb 函数 */
    public function selectLimit($sql, $num, $start = 0)
    {
        if ($start == 0)
        {
            $sql .= ' LIMIT ' . $num;
        }
        else
        {
            $sql .= ' LIMIT ' . $start . ', ' . $num;
        }

        return $this->query($sql);
    }

    public function getOne($sql, $limited = false)
    {
        if ($limited == true)
        {
            $sql = trim($sql . ' LIMIT 1');
        }

        $res = $this->query($sql);
        if ($res !== false)
        {
            $row = mysql_fetch_row($res);

            if ($row !== false)
            {
                return $row[0];
            }
            else
            {
                return '';
            }
        }
        else
        {
            return false;
        }
    }

    public function getOneCached($sql, $cached = 'FILEFIRST')
    {
      

        $cachefirst = ($cached == 'FILEFIRST' || ($cached == 'MYSQLFIRST' && $this->_platform != 'WINDOWS')) && $this->max_cache_time;
        if (!$cachefirst)
        {
            return $this->getOne($sql, true);
        }
        else
        {
            $result = $this->getSqlCacheData($sql, $cached);
            if (empty($result['storecache']) == true)
            {
                return $result['data'];
            }
        }
        $arr = $this->getOne($sql, true);
        if ($arr !== false && $cachefirst)
        {
            $this->setSqlCacheData($result, $arr);
        }
        return $arr;
    }

    public function getAll($sql)
    {
        $res = $this->query($sql);
        if ($res !== false)
        {
            $arr = array();
            while ($row = mysql_fetch_assoc($res))
            {
                $arr[] = $row;
            }

            return $arr;
        }
        else
        {
            return false;
        }
    }

    /**
     *  以主键索引形式返回结果集
     *
     *  @author Garbin
     *  @param  string $sql_statement
     *  @return array
     */
    public function getAllWithIndex($sql_statement, $index_key)
    {
        $query = $this->query($sql_statement, 'UNBUFFERED');
        $rtn = array();
        while ($row = $this->fetch_array($query))
        {
            if (is_array($index_key))
            {
                $index = '';
                foreach ($index_key as $k)
                {
                    $index .= $index == '' ? $row[$k] : '_' . $row[$k];
                }
                $rtn[$index]           = $row;
            }
            else
            {
                $rtn[$row[$index_key]] = $row;
            }
        }

        return $rtn;
    }

    public function getAllCached($sql, $cached = 'FILEFIRST')
    {
        $cachefirst = ($cached == 'FILEFIRST' || ($cached == 'MYSQLFIRST' && $this->_platform != 'WINDOWS')) && $this->max_cache_time;
        if (!$cachefirst)
        {
        	
            return $this->getAll($sql);
        }
        else
        {
        	
            $result = $this->getSqlCacheData($sql, $cached);
            if (empty($result['storecache']) == true)
            {
                return $result['data'];
            }
        }

        $arr = $this->getAll($sql);

        if ($arr !== false && $cachefirst)
        {
            $this->setSqlCacheData($result, $arr);
        }

        return $arr;
    }

    public function getRow($sql, $limited = false)
    {
        if ($limited == true)
        {
            $sql = trim($sql . ' LIMIT 1');
        }

        $res = $this->query($sql);
        if ($res !== false)
        {
            return mysql_fetch_assoc($res);
        }
        else
        {
            return false;
        }
    }

    public function getRowCached($sql, $cached = 'FILEFIRST')
    {
      

        $cachefirst = ($cached == 'FILEFIRST' || ($cached == 'MYSQLFIRST' && $this->_platform != 'WINDOWS')) && $this->max_cache_time;
        if (!$cachefirst)
        {
            return $this->getRow($sql, true);
        }
        else
        {
            $result = $this->getSqlCacheData($sql, $cached);
            if (empty($result['storecache']) == true)
            {
                return $result['data'];
            }
        }

        $arr = $this->getRow($sql, true);

        if ($arr !== false && $cachefirst)
        {
            $this->setSqlCacheData($result, $arr);
        }

        return $arr;
    }

    public function getCol($sql)
    {
        $res = $this->query($sql);
        if ($res !== false)
        {
            $arr = array();
            while ($row = mysql_fetch_row($res))
            {
                $arr[] = $row[0];
            }

            return $arr;
        }
        else
        {
            return false;
        }
    }
    public function getColCached($sql, $cached = 'FILEFIRST')
    {
        $cachefirst = ($cached == 'FILEFIRST' || ($cached == 'MYSQLFIRST' && $this->_platform != 'WINDOWS')) && $this->max_cache_time;
        if (!$cachefirst)
        {
            return $this->getCol($sql);
        }
        else
        {
            $result = $this->getSqlCacheData($sql, $cached);
            if (empty($result['storecache']) == true)
            {
                return $result['data'];
            }
        }
        $arr = $this->getCol($sql);
        if ($arr !== false && $cachefirst)
        {
            $this->setSqlCacheData($result, $arr);
        }
        return $arr;
    }   

    public function setMaxCacheTime($second)
    {
        $this->max_cache_time = $second;
    }

    public function getMaxCacheTime()
    {
        return $this->max_cache_time;
    }

    /**
     * 获取数据记录集缓存
     *
     * author redstone
     * param  string  $sql     查询语句
     * param  string  $cached  缓存选项
     * return array
     **/
    public function getSqlCacheData($sql, $cached = '')
    {
        $sql = trim($sql);
        $result = array();
        $result['filename'] = $this->cache_dir . abs(crc32($this->_dbhash . $sql)) . '_' . md5($this->_dbhash . $sql) . '.php';        
        if (file_exists($result['filename']) && ($data = file_get_contents($result['filename'])) && isset($data{23}))
        {
            $filetime = substr($data, 13, 10);
            $data     = substr($data, 23);
            if (($cached == 'FILEFIRST' && time() > $filetime + $this->max_cache_time) ||
                ($cached == 'MYSQLFIRST' && $this->table_lastupdate($this->get_table_name($sql)) > $filetime))
            {
                $result['storecache'] = true;
            }
            else
            {
                $result['data'] = @unserialize($data);
                if ($result['data'] === false)
                {
                    $result['storecache'] = true;
                }
                else
                {
                    $result['storecache'] = false;
                }
            }
        }
        else
        {
            $result['storecache'] = true;
        }

        return $result;
    }

    public function setSqlCacheData($result, $data)
    {
        if ($result['storecache'] === true && $result['filename'])
        {
            @file_put_contents($result['filename'], '<?php exit;?>' . time() . serialize($data), LOCK_EX);
            clearstatcache();
        }
    }

    /* 获取 SQL 语句中最后更新的表的时间，有多个表的情况下，返回最新的表的时间 */
    public function table_lastupdate($tables)
    {
        if ($this->_link_id === NULL)
        {
            $this->connect($this->_settings['dbhost'], $this->_settings['dbuser'], $this->_settings['dbpw'], $this->_settings['dbname'], $this->_settings['charset'], $this->_settings['pconnect']);
            $this->_settings = array();
        }
        $lastupdatetime = '0000-00-00 00:00:00';
        $tables = str_replace('`', '', $tables);
        $this->mysql_disable_cache_tables = str_replace('`', '', $this->mysql_disable_cache_tables);
        foreach ($tables AS $table)
        {
            if (in_array($table, $this->mysql_disable_cache_tables) == true)
            {
                $lastupdatetime = '2037-12-31 23:59:59';

                break;
            }

            if (strpos($table, '.') !== false)
            {
                $tmp = explode('.', $table);
                $sql = 'SHOW TABLE STATUS FROM `' . trim($tmp[0]) . "` LIKE '" . trim($tmp[1]) . "'";
            }
            else
            {
                $sql = "SHOW TABLE STATUS LIKE '" . trim($table) . "'";
            }
            $result = mysql_query($sql, $this->_link_id);

            $row = mysql_fetch_assoc($result);
            if ($row['Update_time'] > $lastupdatetime)
            {
                $lastupdatetime = $row['Update_time'];
            }
        }
        $lastupdatetime = strtotime($lastupdatetime) - $this->timezone + $this->timeline;
        return $lastupdatetime;
    }

    public function get_table_name($query_item)
    {
        $query_item = trim($query_item);
        $table_names = array();
        /* 判断语句中是不是含有 JOIN */
        if (stristr($query_item, ' JOIN ') == '')
        {
            /* 解析一般的 SELECT FROM 语句 */
            if (preg_match('/^SELECT.*?FROM\s*((?:`?\w+`?\s*\.\s*)?`?\w+`?(?:(?:\s*AS)?\s*`?\w+`?)?(?:\s*,\s*(?:`?\w+`?\s*\.\s*)?`?\w+`?(?:(?:\s*AS)?\s*`?\w+`?)?)*)/is', $query_item, $table_names))
            {
                $table_names = preg_replace('/((?:`?\w+`?\s*\.\s*)?`?\w+`?)[^,]*/', '\1', $table_names[1]);

                return preg_split('/\s*,\s*/', $table_names);
            }
        }
        else
        {
            /* 对含有 JOIN 的语句进行解析 */
            if (preg_match('/^SELECT.*?FROM\s*((?:`?\w+`?\s*\.\s*)?`?\w+`?)(?:(?:\s*AS)?\s*`?\w+`?)?.*?JOIN.*$/is', $query_item, $table_names))
            {
                $other_table_names = array();
                preg_match_all('/JOIN\s*((?:`?\w+`?\s*\.\s*)?`?\w+`?)\s*/i', $query_item, $other_table_names);

                return array_merge(array($table_names[1]), $other_table_names[1]);
            }
        }
        return $table_names;
    }

    /* 设置不允许进行缓存的表 */
    public function set_disable_cache_tables($tables)
    {
        if (!is_array($tables))
        {
            $tables = explode(',', $tables);
        }

        foreach ($tables AS $table)
        {
            $this->mysql_disable_cache_tables[] = $table;
        }

        array_unique($this->mysql_disable_cache_tables);
    }
}