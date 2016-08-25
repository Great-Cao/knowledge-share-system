<?php

namespace AppBundle\Common;

class Api
{
    public static function getTitle($url)
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($c);
        curl_close($c);
        if (!$data) {
            return '读取标题失败,请手动填写标题';
        }
        $code = mb_detect_encoding($data);
        if (!$code) {
            return '读取标题失败,请手动填写标题';
        }
        if ($code == 'GBK' || $code == 'UTF-8' ) {
            $data = mb_convert_encoding($data, "UTF-8", $code);
        } else {
            return '读取标题失败,请手动填写标题';
        }
        $postStart = strpos($data,'<title>')+7;
        $postEnd = strpos($data,'</title>');
        $length = $postEnd - $postStart;
        $title = substr($data ,$postStart, $length);

        return $title;
    }

    public static function getDailyOne()
    {
        $nowyear = date("Y");
        $nowmouth = date('m');
        $nowday = date('d');
        $date = mt_rand("2012",$nowyear)."-".mt_rand("1",$nowmouth)."-".mt_rand("1",$nowday);
        $content = file_get_contents('http://open.iciba.com/dsapi/?date='.$date);
        $content = json_decode($content);
        $time = time();
        return array('content' => $content, 'date' => $time);
    }
}