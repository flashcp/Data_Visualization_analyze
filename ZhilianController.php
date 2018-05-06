<?php
/**
 * Created by PhpStorm.
 * User: 鹏
 * Date: 2018/4/29
 * Time: 11:31
 */

include "ZhilianBussiness.php";
include "ZhilianEntity.php";
require_once ("public_utf.php");


class ProvinveClass{
    public $field_id;
    public $province;
    public $create_time;
};

class SpiderClass{      //抓取总入口
    #region 进行每一页抓取
    public function StartPage(){
        ignore_user_abort(true);        //忽略抓取时间
        set_time_limit(0);

        $len = 0;
        for ($i = 1; $i <= 43; $i++) {      //总页数43
            $url = "http://sou.zhaopin.com/jobs/searchresult.ashx?jl=%E5%85%A8%E5%9B%BD&kw=%E6%A8%A1%E5%85%B7%E5%B7%A5%E7%A8%8B%E5%B8%88&sm=0&isfilter=0&fl=489&isadv=0&sg=333e0944a2c34b53b0d3b4b9df7196bf&p=";
            $parameters = ZhilianBussiness::instance()->nation_method($url.$i);     //调用爬取方法
            if ($parameters == 0){
                print "<br>"."this page get fail!"."<br>";
                continue;
            }
            $len = $len+$this->InsertData($parameters);     //调用调整数据方法
        }
        return $len;
    }

    public function InsertData($parameters){        //调整插入数据，进行插入
        $iCount = 0;
        $utf = new public_utf();
        foreach ($parameters as $item) {
            if ($item["job_name"] == '') {
                $iCount++;
            } else {
                $die_engineer = $this->FieldSort($item["job_address"]);     //调用地区分类方法
                print "die_engineer";
                print_r($die_engineer);
                $parameter = [
                    'field_id' => $die_engineer->field_id ,
                    'province' =>  $utf->convert_encoding_to_utf8($die_engineer->province),
                    'job_name' =>$item["job_name"],
                    'job_company' => $item["job_company"],
                    'job_company_type' => $item["job_company_type"],
                    'job_address' => $item["job_address"],
                    'job_salary' => $item["job_salary"],
                    'job_edu' => $item["job_edu"],
                    'job_exp' => $item["job_exp"],
                    'create_time'=>$utf->convert_encoding_to_utf8($die_engineer->create_time),
                    'source_url'=>$item["source_url"]
                ];
                ZhilianEntity::instance()->addNotice($parameter);       //插入数据
                $iCount++;
            }
        }


        return $iCount;
    }

    public function FieldSort($address){        //将信息进行区域分类
        $filename = "./ProvincesAndCity.json";      //打开json文件，转换为数组
        $json_string = file_get_contents($filename);
        $province_arr=json_decode($json_string,true);

        $die_engineer = new ProvinveClass();        //新建一个模具工程师对象
        $date=date_create();
        $die_engineer->create_time = date_timestamp_get($date);

        $len = count($province_arr["provinces"]);
        $address = strtok($address, "-");       //将传入的地址分割，用于地区的匹配
        for ($i=0;$i<$len;$i++){
            $provinceName = $province_arr["provinces"][$i]["provinceName"];
            $city = $province_arr["provinces"][$i]["citys"];
            if(in_array($address,$city)){
                print "进入数组";
                print "i:".$i."<br>";
                if ($i>=0&&$i<=4){
                    $die_engineer->field_id = 0;
                }
                if ($i>=5&&$i<=11){
                    $die_engineer->field_id = 1;
                }
                if ($i>=12&&$i<=17){
                    $die_engineer->field_id = 2;
                }
                if ($i>=18&&$i<=22){
                    $die_engineer->field_id = 3;
                }
                if ($i>=23&&$i<=27){
                    $die_engineer->field_id = 4;
                }
                if ($i>=28&&$i<=30){
                    $die_engineer->field_id = 5;
                }
                $die_engineer->province = $provinceName;
                break;
            }
        }
        return $die_engineer;
    }
}


$test = new SpiderClass();      //调用总入口
try{
    $test->StartPage();
}catch (\Exception $e){
    print "<br>"."出现错误！"."<br>";
    return 0;
}



