<?php
/**
 * Created by PhpStorm.
 * User: jiuzheyang
 * Date: 2017/8/9
 * Time: 13:26
 */



class public_utf
{
    public function convert_encoding_to_utf8($content){
        if(!empty($content) && !mb_check_encoding($content, 'utf-8')) {
            $content = mb_convert_encoding($content,'UTF-8','gbk');
        }
        return $content;
    }

    public function myTrim($str)
    {
        $search = array(" ","　","\n","\r","\t");
        $replace = array("","","","","");
        return trim(str_replace($search, $replace, $str));
    }

    public function xiufuHtml($html){
            return str_replace('&#39;',"'",str_replace('&nbsp;','',str_replace('&trade;','™',str_replace('&copy;','©',str_replace('&lt;','<',str_replace('&gt;','>',str_replace('&amp;','&',str_replace('&quot;','“',str_replace('&reg;','®',str_replace('&lt;','<',$html))))))))));
    }

    public function getRegtext($text){
        return $this->myTrim($this->xiufuHtml($this->convert_encoding_to_utf8($text)));
    }

}
