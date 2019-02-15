<?php


namespace Core;

class Json
{
    public static function json_encode_ex($val)
    {
        // 不转义中文、斜杠
        return json_encode($val, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }


	/**
	 * jsonp编码
	 * ------------------------------------------
	 * 判断是否为jsonp请求，并返回jsonp格式，如果非jsonp请求则返回json格式
	 */
    public static function jsonp_encode_ex($arr)
    {
        $json = self::json_encode_ex($arr);
        if (!empty($_GET['callbak'])) {
            return $_GET['callbak'] . '(' . $json . ')'; // jsonp
        }
        return $json; // json
    }
}