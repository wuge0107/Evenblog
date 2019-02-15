<?php


namespace Helper;


class Byte 
{
	//字符串转成字节流
	public static function strToBin($str)
	{
	    $arr = preg_split('/(?<!^)(?!$)/u', $str);
	    foreach($arr as &$v){
	        $temp = unpack('H*', $v);
	        $v = base_convert($temp[1], 16, 2);
	        unset($temp);
	    }
	    return join(' ',$arr);
	}

	//字节流转成字符串
	public static function binToStr($str)
	{
		$arr = explode(' ', $str);
	    foreach($arr as &$v){
	        $v = pack("H".strlen(base_convert($v, 2, 16)), base_convert($v, 2, 16));
	    }
	    return join('', $arr);
	}

	// 约定转义 遇到0x7e 转成 0x7d 并在后跟一个 0x02
	// 遇到0x7d 后跟 0x01
	public static function binToTransformation($str)
	{
		$arr = explode(' ', $str);

		foreach ($arr as $key => $value) {
			if($value == '0x7e'){
				$arr[$key] = '0x7dreplace-1';
			}elseif($value == '0x7d'){
				$arr[$key] = '0x7dreplace-2';
			}
		}

		$str = implode(' ',$arr);
		$str = str_replace('replace-1', ' 0x02', $str);
		$str = str_replace('replace-2', ' 0x01', $str);
		return $str;
	}
	// 传输规则 
	// 字节 byte 转成字节流
	// 字 word 先高8位 再低8位
	// 双字 dword 高24 高16 高8 低8
	public static function rule($msg_type ,$msg_str)
	{
		$msg_type_arr = ['byte','word','dword'];
		if(empty($msg_str)){
			return null;
		}

		$msg_type = strtolower($msg_type);
		if(!in_array($msg_type,$msg_type_arr)){
			return null;
		}
		switch ($msg_type) {
			case 'byte':
				$msg_result = self::strToBin($msg_str);
				break;

			case 'word':
				$msg_result = ($msg_str >> 8) . ' ' . ($msg_str&0xFF);
				break;

			case 'dword':
				$msg_result = ($msg_str >> 24) .' '.($msg_str>>16).' '.($msg_str>>8).' '.($msg_str&0xFF);
				break;
			
			default:
				$msg_result = null;
				break;
		}
		return $msg_result;
	}
}