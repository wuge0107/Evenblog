<?php
namespace Core;
/**
 *
 * 统一redis的配置与数据存储规范，便于扩展与修改
 *
 * # redis通常用于热数据与消息列队等场景
 * # list内存储array是采用json格式
 *
 */

class RedisDriver
{

    protected $redis; // redis对象
    protected $ip     = ''; // redis服务器ip地址
    protected $port   = ''; // redis服务器端口
    protected $passwd = ''; // redis密码

    public function __construct()
    {
        $this->redis = new \Redis();
        $this->connect();
    }

    // 连接redis服务器
    public function connect()
    {
        $this->ip   = conf::get('config','redis_ip');
        $this->port = conf::get('config','redis_port');
        $this->passwd = conf::get('config','redis_auth');
     
        $state = $this->redis->connect($this->ip, $this->port);
        if ($state == false) {
            die('redis connect failure');
        }
        if (!is_null($this->passwd)) {
            $this->redis->auth($this->passwd);
        }
    }

    // 设置一条String
    public function setStr($key, $text, $expire = null)
    {
        $key = 'string:' . $key;
        $this->redis->set($key, $text);
        if (!is_null($expire)) {
            $this->redis->setTimeout($key, $expire);
        }
    }

    // 获取一条String
    public function getStr($key)
    {
        $key  = 'string:' . $key;
        $text = $this->redis->get($key);
        return empty($text) ? null : $text;
    }

    // 删除一条String
    public function delStr($key)
    {
        $key = 'string:' . $key;
        $this->redis->del($key);
    }

    // 设置一条Hash
    public function setHash($key, $arr, $expire = null)
    {
        $key = 'hash:' . $key;
        $this->redis->hMset($key, $arr);
        if (!is_null($expire)) {
            $this->redis->setTimeout($key, $expire);
        }
    }

    // 获取一条Hash，$fields可为字符串或数组
    public function getHash($key, $fields = null)
    {
        $key = 'hash:' . $key;
        if (is_null($fields)) {
            $arr = $this->redis->hGetAll($key);
        } else {
            if (is_array($fields)) {
                $arr = $this->redis->hmGet($key, $fields);
                foreach ($arr as $key => $value) {
                    if ($value === false) {
                        unset($arr[$key]);
                    }
                }
            } else {
                $arr = $this->redis->hGet($key, $fields);
            }
        }
        return empty($arr) ? null : (is_array($arr) ? $arr : array($fields => $arr));
    }

    // 删除一条Hash，$field为字符串
    public function delHash($key, $field = null)
    {
        $key = 'hash:' . $key;
        if (is_null($field)) {
            $this->redis->del($key);
        } else {
            $this->redis->hDel($key, $field);
        }
    }

    // 在Hash的field内增加一个值 (值之间使用“,”分隔)
    public function fieldAddVal($key, $field, $val)
    {
        $arr = $this->getHash($key, $field);
        if (!is_null($arr)) {
            $str = reset($arr);
            $arr = explode(',', $str);
            foreach ($arr as $v) {
                if ($v == $val) {
                    return;
                }
            }
            $str .= ",{$val}";
            $this->setHash($key, array($field => $str));
        } else {
            $this->setHash($key, array($field => $val));
        }
    }

    // 在Hash的field内删除一个值
    public function fieldDelVal($key, $field, $val)
    {
        $arr = $this->getHash($key, $field);
        if (!is_null($arr)) {
            $arr    = explode(',', reset($arr));
            $tmpStr = '';
            foreach ($arr as $v) {
                if ($v != $val) {
                    $tmpStr .= ",{$v}";
                }
            }
            if ($tmpStr == '') {
                $this->delHash($key, $field);
            } else {
                $this->setHash($key, array($field => substr($tmpStr, 1)));
            }
        }
    }

    // 设置表格的一行数据
    public function setTableRow($table, $id, $arr, $expire = null)
    {
        $key = '' . $table . ':' . $id;
        $this->redis->hMset($key, $arr);
        if (!is_null($expire)) {
            $this->redis->setTimeout($key, $expire);
        }
    }

    // 获取表格的一行数据，$fields可为字符串或数组
    public function getTableRow($table, $id, $fields = null)
    {
        $key = '' . $table . ':' . $id;
        if (is_null($fields)) {
            $arr = $this->redis->hGetAll($key);
        } else {
            if (is_array($fields)) {
                $arr = $this->redis->hmGet($key, $fields);
                foreach ($arr as $key => $value) {
                    if ($value === false) {
                        unset($arr[$key]);
                    }
                }
            } else {
                $arr = $this->redis->hGet($key, $fields);
            }
        }
        return empty($arr) ? null : (is_array($arr) ? $arr : array($fields => $arr));
    }

    // 删除表格的一行数据
    public function delTableRow($table, $id)
    {
        $key = '' . $table . ':' . $id;
        $this->redis->del($key);
    }

    // 推送一条数据至列表，头部
    public function pushList($key, $arr)
    {
        $key = 'list:' . $key;
        $this->redis->lPush($key, json_encode($arr));
    }

    // 从列表拉取一条数据，尾部
    public function pullList($key, $timeout = 0)
    {
        $key = 'list:' . $key;
        if ($timeout > 0) {
            $val = $this->redis->brPop($key, $timeout); // 该函数返回的是一个数组, 0=key 1=value
        } else {
            $val = $this->redis->rPop($key);
        }
        $val = is_array($val) && isset($val[1]) ? $val[1] : $val;
        return empty($val) ? null : $this->objectToArray(json_decode($val));
    }

    // 取得列表的数据总条数
    public function getListSize($key)
    {
        $key = 'list:' . $key;
        return $this->redis->lSize($key);
    }

    // 删除列表
    public function delList($key)
    {
        $key = 'list:' . $key;
        $this->redis->del($key);
    }

    // 使用递归，将stdClass转为array
    protected function objectToArray($obj)
    {
        if (is_object($obj)) {
            $arr = (array) $obj;
        }
        if (is_array($obj)) {
            foreach ($obj as $key => $value) {
                $arr[$key] = $this->objectToArray($value);
            }
        }
        return !isset($arr) ? $obj : $arr;
    }

}
