<?php
/**
 * Created by PhpStorm.
 * User: 鹏
 * Date: 2018/4/29
 * Time: 19:52
 */

class ZhilianEntity{

    #region 单例设计模式
    private static $_instance = null;
    public static function instance(){
        if (is_null(self::$_instance)){
            return self::$_instance=new self();
        }
        return self::$_instance;
    }
    #endregion


    public function addNotice($parameter){
        $servername = "localhost";
        $username = "root";
        $password = "******";

        // 创建连接
        $conn = new mysqli($servername, $username, $password);
        // 检测连接
        if ($conn->connect_error) {
            die("连接失败: " . $conn->connect_error);
        }
        mysqli_query($conn,"set names 'utf8'");

        mysqli_select_db($conn,"runoob");

        $val_id = "";
        $val = "";
        $i = 0;
        foreach ($parameter as $k => $v) {
            if ($i<10){
                $val_id = $val_id.$k.",";
                $val = $val."'".$v."'".",";
            }else{
                $val_id = $val_id.$k;
                $val = $val."'".$v."'";
            }
            $i++;
        }
        $sql = "INSERT INTO zhilian_die ($val_id)
 VALUES ($val)";
        if ($conn->query($sql) === TRUE) {
            echo "新记录插入成功"."<br>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
        return 1;
    }

}