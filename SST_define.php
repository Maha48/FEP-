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

//اكتشاف عن الطلاب الذين لديهم اكثر من اختبار بنفس اليوم وبنفس الفترة 
$SSD_sql = $connection->query("SELECT DISTINCT e1.*  from Examdata e1 , Examdata e2
WHERE e2.Student_ID=e1.Student_ID AND
e1.Subject_ID!=e2.Subject_ID AND
e1.exam_dates=e2.exam_dates  
AND e1.exam_times = e2.exam_times
ORDER BY e1.exam_dates,e1.Student_ID ;");
$student = "";
// طباعة بيانات كل طالب 
foreach ($SSD_sql as $sq) {
    if ($student != $sq["Student_ID"]) {
        $student = $sq["Student_ID"];
        echo '<table id="ssd_student"  class="panel"> 
        <tr>
        <td width="70px">
        <label>
            <input type="submit" class="' . $student . '" onclick="Function();" id="SSD-detiles">
            <img src="images/up-arrow.png"id="img_arrow">
            <br>
        </label>
        </td>';
        echo '<td> Student ID: ' . $sq["Student_ID"] . '</td></tr>';
    }
               // إظهار التفاصيل لكل طالب 
    echo '<tr id="ssd_data" class="'. $student . '" >';
    echo '<td class="myDIV">' . $sq["Class_ID"] . '</td>';
    echo '<td class="myDIV">' . $sq["Subject_ID"] . '</td>';
    echo '<td class="myDIV">' . $sq["Subject_name"] . '</td>';
    echo '<td class="myDIV">' . $sq["lecturer_name"] . '</td>';
    echo '<td class="myDIV">' . $sq["exam_days"] . '</td>';
    echo '<td class="myDIV">' . $sq["exam_dates"] . '</td>';
    echo '<td class="myDIV">' . $sq["exam_times"] . '</td></tr>';
}
?>
</table>