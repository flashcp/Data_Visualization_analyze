<?php
/**
 * Created by PhpStorm.
 * User: 鹏
 * Date: 2018/4/29
 * Time: 11:31
 */

include "public_utf.php";
include "simple_html_dom.php";


class ZhilianBussiness{
    private static $_instance;
    public static function instance(){
        if(isset(self::$_instance)){
            return self::$_instance;
        }
        $instance = new ZhilianBussiness();
        self::$_instance = $instance;
        return $instance;
    }


    public function nation_method($url){
        $job_name = [];//存放职位名称
        $job_company = [];//存放公司名称
        $job_company_type = [];//存放公司性质
        $job_address = [];//存放工作地点
        $job_salary = [];//存放职位月薪
        $job_edu = [];//存放学历
        $job_exp = [];//存放工作经验
        $json = [];
        $utf = new public_utf();
        $html = new simple_html_dom();
        try{        //捕捉单页请求报错
            $html->load_file($url);
//            print "bussiniss".$html."<br>";
        }catch(\Exception $e){
            print "bussiness error!"."<br>";
            return 0;
        }

        $i = 0;
        foreach ($html->find("#newlist_list_content_table .newlist") as $arr){     //抓取这一页里的文章标题、时间、文章链接
            $i++;
            $html_table =str_get_html($arr);
            $html_tr = $html_table->find("tr");
            if(count($html_tr) == 1){
                continue;
            }
            #第一个td
            $html_td_one =str_get_html($html_tr[0]);

            $name = $html_td_one->find("td");
            $job_name[] = $utf->myTrim($name[0]->plaintext);

            $company = $html_td_one->find("td");
            $job_company[] = $utf->convert_encoding_to_utf8($company[2]->plaintext);

            $salary =  $html_td_one->find("td");
            $job_salary[] = $utf->convert_encoding_to_utf8($salary[3]->plaintext);

            $address = $html_td_one->find("td");
            $job_address[] = $utf->convert_encoding_to_utf8($address[4]->plaintext);

            #第二个td
            $html_td_two =str_get_html($html_tr[1]);

            $span = $html_td_two->find(".newlist_deatil_two span");
            $job_company_type[] = $utf->convert_encoding_to_utf8(str_replace("公司性质：",'',$span[1]->plaintext));
            if (preg_match("/经验/", "$span[3]->plaintext") && preg_match("/学历/", "$span[4]->plaintext")) {
                $job_exp[] = $utf->convert_encoding_to_utf8(str_replace("经验：",'',$span[3]->plaintext));
                $job_edu[] = $utf->convert_encoding_to_utf8(str_replace("学历：",'',$span[4]->plaintext));
            }
            else{
                $job_exp[] = "不限";
                $job_edu[] = $utf->convert_encoding_to_utf8(str_replace("学历：",'',$span[3]->plaintext));
            }
        }
        print count($job_name);
        for ($i = 0; $i<count($job_name); $i++){
            $json[$i] = array(
                'job_name' => $job_name[$i],
                'job_company' => $job_company[$i],
                'job_salary' => $job_salary[$i],
                'job_address' => $job_address[$i],
                'job_company_type' => $job_company_type[$i],
                'job_edu' => $job_edu[$i],
                'job_exp' => $job_exp[$i],
                'source_url'=> $url
            );
        }
//        print_r($json,false);
        return $json;
    }

}