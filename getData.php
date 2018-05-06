<?php
/**
 * Created by PhpStorm.
 * User: 鹏
 * Date: 2018/4/30
 * Time: 21:06
 */



class getData{
    public static $_instacne = null;
    public static function instance(){
        if (self::$_instacne == null){
            return self::$_instacne = new self();
        }
        return self::$_instacne;
    }


    public function connect_mysql(){
        $con = mysqli_connect('localhost','root','pengzai');
        if (!$con)
        {
            die('Could not connect: ' . mysqli_error($con));
        }else{
            print "connect to mysql successful!";
            print "<br>";
        }
        return $con;
    }

    public function queryData($parameters, $con){
        // 选择数据库
        mysqli_select_db($con,"runoob");
        // 设置编码，防止中文乱码
        mysqli_set_charset($con, "utf8");

        if (is_array($parameters)){
            foreach ($parameters as $arr){
                //此地区的行业工资总金额以及公司数目
                $sum_salary = 0;
                $count_salary = 0;

                $sql="SELECT * FROM zhilian_die WHERE job_address like "."'%".$arr->name."%'";
                $result = mysqli_query($con,$sql);
                if (!$result) {
                    printf("Error: %s\n", mysqli_error($con));
                    exit();
                }

                while ($rows=mysqli_fetch_array($result)){
                    $count=count($rows);//不能在循环语句中，由于每次删除 row数组长度都减小
                    if ($count == 0){
                        break;
                    }
                    for($i=0;$i<$count;$i++){
                        if ((helper::instance()->checkStr($rows["job_salary"]) == 2) || (helper::instance()->checkStr($rows["job_salary"]) == 5)){
                            continue;
                        }
                        try{
//                            print $rows["job_salary"];
                            $salary_arr = explode("-", $rows["job_salary"]);
                            $single_salary = ((int)$salary_arr[0] + (int)$salary_arr[1]) / 2;
                            $sum_salary += $single_salary;
                            $count_salary++;
                        }catch (Exception $e){
                            print $rows["job_address"];
                            print"\n";
                            print $rows["job_salary"];
                        }

                    }
                }
                if ($count_salary == 0){
                    $arr->value = 0;
                }else{
                    $arr->value = (int)($sum_salary / $count_salary);
                }
            }
        }
//        print_r($parameters);
        return $parameters;
    }

}

#region读取data.json中城市的平均工资
function start(){
    $file_test = file_get_contents("data.json");
    $str_test = json_decode($file_test);
    $a_test = getData::instance();
    $con = $a_test->connect_mysql();
    $json_salary = $a_test->queryData($str_test,$con);
    $json=json_encode($json_salary);

    print_r($json);
    mysqli_close($con);
}
#endregion

start();
