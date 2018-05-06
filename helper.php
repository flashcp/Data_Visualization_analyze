<?php
/**
 * Created by PhpStorm.
 * User: 鹏
 * Date: 2018/5/6
 * Time: 3:51
 */

class helper{
    public static $_instance = null;
    public static function instance(){
        if (self::$_instance  == null){
            return self::$_instance = new self();
        }
        return self::$_instance;
    }

    #region 检测字符是否在字符串中
    public function  char_in_str($str, $char){
        $a = strstr($str, $char);
        if ($a == false){
            return 0;       //不存在
        }else{
            return 1;       //存在
        }
    }
    #endregion

    #region 检测字符串中是否有汉字
    public function checkStr($str)
    {
        $output='';
        $a=preg_match('/['.chr(0xa1).'-'.chr(0xff).']/', $str);
        $b=preg_match('/[0-9]/', $str);
        $c=preg_match('/[a-zA-Z]/', $str);
        if($a && $b && $c)
            $output=1;  //汉字数字英文的混合字符串
        elseif($a && $b && !$c)
            $output=2;    //汉字数字的混合字符串
        elseif($a && !$b && $c)
            $output=3;       //汉字英文的混合字符串
        elseif(!$a && $b && $c)
            $output=4;    //数字英文的混合字符串
        elseif($a && !$b && !$c)
            $output=5;     //纯汉字
        elseif(!$a && $b && !$c)
            $output=6;    //纯数字
        elseif(!$a && !$b && $c)
            $output=7;    //纯英文
        return $output;
    }
}