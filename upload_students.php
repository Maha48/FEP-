<?php
require_once './vendor/autoload.php';
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();
// نستطيع الان قراءة المتغيرات من الملف
$db_host = getenv('DB_host'); //استدعاء البيانات من الملف المحتوي على البيانات الحساسه
$db_username = getenv('DB_username');
$db_password = getenv('DB_password');
$Database = getenv('DB');
$connection = mysqli_connect($db_host, $db_username, $db_password, $Database,'8889');
mysqli_set_charset( $connection, 'utf8' );
$queryempty = 'select * From Examdata';
$query = mysqli_query($connection, $queryempty) or die("Error in query: $query. " . mysqli_error($connection));
if (mysqli_num_rows($query) > 0) {
    $message = "There is data";
} else {
    if (isset($_POST["import"])) {
        // رفع محتويات الملف الى جدول الاختبارات 
        $fileName = $_FILES["file"]["tmp_name"];
        if ($_FILES["file"]["size"] > 0) {
            $file = fopen($fileName, "r");
            while (($column = fgetcsv($file, 110000, ",")) !== false) {
                $sqlInsert = "INSERT into Examdata (Class_ID,Subject_ID,Student_ID,Subject_name,lecturer_name,exam_days,exam_dates,exam_times)
                values ('" . $column[0] . "','" . $column[1] . "','" . $column[2] . "','" . $column[3] . "','" . $column[4] . "','" . $column[5] . "','" . $column[6] . "','" . $column[7] . "')";
                $result = mysqli_query($connection, $sqlInsert);}
                if (!empty($result)) {
                    $message = "Upload Done ";
                    // إضافة الاوقات من جدول الاختبارات 
                    $SELECTTIMES="INSERT INTO times
                    SELECT DISTINCT exam_times FROM Examdata order by exam_times ";
                    $E_SELECTTIMES=mysqli_query($connection, $SELECTTIMES);
                } else {
                    $message = "Problem In Upload Data";
                }
                //عملية تنظيف البيانات المتكررة 
            $dataclean="DELETE n1 FROM Examdata n1, Examdata n2 WHERE n1.id > n2.id AND n1.`Class_ID` = n2.`Class_ID` AND n1.`Subject_ID` = n2.`Subject_ID` AND n1.`Student_ID` = n2.`Student_ID` AND n1.`Subject_name` = n2.`Subject_name` AND n1.`lecturer_name` = n2.`lecturer_name`AND n1.`exam_days` = n2.`exam_days` AND n1.`exam_dates` = n2.`exam_dates` and n1.`exam_times` = n2.`exam_times`";
            $datacleaning=mysqli_query($connection,$dataclean);
        }
    }
}
if (isset($_POST['delete'])) {

    $Bedit=mysqli_query($connection,"UPDATE Process_State 
    SET PSSD=0
    WHERE PSSD=1");
    $E_Bedit = mysqli_query($connection, $Bedit);
    $D_SELECTEDTIMES="DELETE FROM times ";
    $E_D_SELECTEDTIME=mysqli_query($connection,$D_SELECTEDTIMES);
    $sqldelete = "delete from Examdata";
    $result1 = mysqli_query($connection, $sqldelete);
    if (!empty($result1)) {
        $message = "Deleted Done";
    }
}
?>