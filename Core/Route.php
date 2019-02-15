<?php

namespace Core;


/**
 * 路由控制器
 */
class Route
{
    /**
     * 获取路由
     */
    public static function init()
    {
         if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/') {
            //得到用户自定义的文件夹名
            $str = implode('', array_slice(explode('/', $_SERVER['SCRIPT_FILENAME']), -2, 1));
            //url参数转换成数组
            $urlParam = substr(substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'],'?')),1);
            $patharr = explode('/', ltrim($_SERVER['REQUEST_URI'], '/'));
            $key     = array_search("index.php", $patharr);
            if ($key !== false) {
                unset($patharr[$key]);
                $patharr = array_merge($patharr);
            }
            if ($str == $patharr[0]) {
                //删除这个元素
                unset($patharr[0]);
                //删除数组其中的一个元素后 数组进行重新排序 array_merge()
                $patharr = array_merge($patharr);
            }
            if (isset($patharr[0]) && $patharr[0] !== '') {
                $controller = $patharr[0];
            } else {
                $controller = "index";
            }
            unset($patharr[0]);
            if (!empty($patharr[1])) {
                //判断是否有参数
                if(strpos($patharr[1], '?') !== false){
                    $action = substr($patharr[1],0,strrpos($patharr[1],'?'));
                }else{
                    $action = $patharr[1];
                }
                unset($patharr[1]);
            } else {
                $action = "index";
            }

            $data = explode('&', $urlParam);
            if(!empty($data)){
                //有get参数
                foreach ($data as $key => $value) {
                    $v = explode('=', $value);
                    if(count($v) == 1){
                        $_GET[$v[0]] = NULL;
                        continue;
                    }
                    if(count($v) == 2){
                        $_GET[$v[0]] = $v[1];
                        continue;
                    }
                    //多余的等号数据还原
                    $length = count($v);
                    $getkey = $v[0];
                    unset($v[0]);
                    $v1 = implode('=', $v);
                    $_GET[$getkey] = $v1;
                }
            }
        } else {
            $controller = 'index';
            $action = 'index';
        }
        return (object)['controller'=>$controller,'action'=>$action];
    }
}