<?php

use Illuminate\Support\Facades\DB;
//格式化打印到文件
if (!function_exists('gg')) {

    function gg($debuginfo) {
        if (is_array($debuginfo)) {
            $debuginfo = print_r($debuginfo, true);
        }

        $string = "\n";
        $string .= $debuginfo;
        $string .= "\n\n";
        try {
            $file = storage_path()."/" . date("Y-m-d") . ".log";
            $fp = fopen($file, 'a+');
            fwrite($fp, $string);
            fclose($fp);
            @chmod($file, 0777);
        } catch(\Exception $e) {

        }

    }
}

//拼接显示字符串
if (!function_exists('joinArrayValue')) {
    function joinArrayValue($datas,$ids) {
        $string = [];
        foreach ($ids as $id) {
            $string[].=$datas[$id];
        }
        return implode(',',$string);
    }
}
if (!function_exists('isMobile')) {
    function isMobile()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return TRUE;
        }
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA'])) {
            return stristr($_SERVER['HTTP_VIA'], "wap") ? TRUE : FALSE;// 找不到为flase,否则为TRUE
        }
        // 判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array('mobile', 'nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap');
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return TRUE;
            }
        }
        if (isset ($_SERVER['HTTP_ACCEPT'])) { // 协议法，因为有可能不准确，放到最后判断
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== FALSE) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === FALSE || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return TRUE;
            }
        }
        return FALSE;
    }
}
if (!function_exists('getImage')) {
    function getImage($id) {
        return asset(DB::table('ovc_upload_image')->where('id',$id)->value('image_url'));
    }
}
