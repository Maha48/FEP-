<html>
<head>
<link rel="stylesheet" type="text/css" href="data.css">
</head>
<table>
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
$SSD='CREATE TEMPORARY table search 
SELECT `Class_ID`,`Subject_ID`,Examdata.`Student_ID`,
`Subject_name`,`lecturer_name`,`exam_days`,
Examdata.`exam_dates`,`exam_times` 
from Examdata join (SELECT Student_ID, count(*), exam_dates
FROM Examdata
group by Student_ID, exam_dates having count(*) > 1) Examdata1
on Examdata.Student_ID=Examdata1.Student_ID 
and Examdata1.exam_dates=Examdata.exam_dates 
order by Examdata1.Student_ID,Examdata1.exam_dates';
$E_SSD=mysqli_query($connection,$SSD);
if(isset($_POST["search"])) {
    $S_value=$_POST["search"];

    $search="SELECT * FROM search  where
     Class_ID like  '%$S_value%'    or Subject_ID like   '%$S_value%'
     or Student_ID like  '%$S_value%'   or Subject_name like   '%$S_value%' 
     or lecturer_name like   '%$S_value%' or exam_days like   '%$S_value%'  
     or exam_dates like  '%$S_value%'  or exam_times like   '%$S_value%' ";
    $E_search=mysqli_query($connection,$search)or die("Error in query: $E_search. " . mysqli_error());
    $count=mysqli_num_rows($E_search);
    $out="";
    if($count == 0){
        $out="no";
    }
    else{
        while($row=mysqli_fetch_array($E_search)){
            $out .= "<tr>";
            $out .= '<td> Student ID: ' . $row["Student_ID"] . '</td></tr>';
            $out .= "<tr>";
            $out .= '<td class="myDIV">' . $row["Class_ID"] . '</td>';
            $out .= '<td class="myDIV">' . $row["Subject_ID"] . '</td>';
            $out .= '<td class="myDIV">' . $row["Subject_name"] .'</td>';
            $out .= '<td class="myDIV">' . $row["lecturer_name"] .'</td>';
            $out .= '<td class="myDIV">' . $row["exam_days"] . '</td>';
            $out .= '<td class="myDIV">' . $row["exam_dates"] . '</td>';
            $out .= '<td class="myDIV">' . $row["exam_times"] . '</td></tr>';
            $out .= "</tr>";

        }
    }
}
echo $out;
?>
</table>