<?php
require_once './vendor/autoload.php';
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();
// نستطيع الان قراءة المتغيرات من الملف
$db_host = getenv('DB_host'); //استدعاء البيانات من الملف المحتوي على البيانات الحساسه
$db_username = getenv('DB_username');
$db_password = getenv('DB_password');
$Database = getenv('DB');
$connection = mysqli_connect($db_host, $db_username, $db_password, $Database);
try {
    
     $conn=new pdo ("mysql:host=$db_host;port='8889';dbname=$Database" , $db_username,$db_password);
     $conn->exec("set names utf8");
//البحث عن الطلاب الذين لديهم اختبارات متتالية في أيام متتابعة 
    $CEDSA="SELECT e1.Subject_ID as  first_Subject_ID, e1.Student_ID,
    e1.subject_name as  first_subject_name,
    e1.lecturer_name as  first_lecturer_name,
    e1.exam_days as first_exam_day, 
    e1.exam_dates as First_Exam_Date,
    e2.subject_name as next_subject_name ,
    e2.Subject_ID as next_Subject_ID,
    e2.lecturer_name as next_lecturer_name ,
    e2.exam_days as next_exam_day,e2.exam_dates as next_Exam_Date,
    e1.Student_ID in (select B.student_ID from students_away B) as away
    FROM Examdata as e1, Examdata as e2
    WHERE e1.Student_ID = e2.Student_ID 
    AND DATEDIFF(e2.exam_dates,e1.exam_dates) = 1
    ORDER BY Student_ID,First_Exam_Date";
    $CED_sql = $conn->prepare($CEDSA);
    // تنفيذ الإستعلام
$CED_sql->execute();
$student_CED = "";
// طباعة سطر بيانات الطالب من دون تكرار طباعة الاي-دي الخاص به
foreach ($CED_sql as $cq) {
    if ($student_CED != $cq["Student_ID"]) {
        $student_CED = $cq["Student_ID"];
        echo '<table id="CED_student"  class="panel"> 
        <tr>
        <td width="70px">
        <label>
            <input type="submit" class="' . $student_CED . '"  id="CED-detiles">
            <img src="images/up-arrow.png"id="img_arrow">
            <br>
        </label>
        </td>';
        echo '<td> Student ID: ' . $cq["Student_ID"] . '</td></tr>';
    }
// أختبار إذا كان الطالب بعيد عن الجامعة وتميز سطر بياناته باللون الأصفر
    if ($cq['away']==0){

    echo '<tr id="CED_data" class="'. $student_CED . '" >';
    echo '<td class="myDIV">' . $cq["first_Subject_ID"] . '</td>';
    echo '<td class="myDIV">' . $cq["first_subject_name"] . '</td>';
    echo '<td class="myDIV">' . $cq["first_lecturer_name"] . '</td>';
    echo '<td class="myDIV">' . $cq["first_exam_day"] . '</td>';
    echo '<td class="myDIV">' . $cq["First_Exam_Date"] . '</td>';
    
    echo '<td class="myDIV">' . $cq["next_Subject_ID"] . '</td>';
    echo '<td class="myDIV">' . $cq["next_subject_name"] . '</td>';
    echo '<td class="myDIV">' . $cq["next_lecturer_name"] . '</td>';
    echo '<td class="myDIV">' . $cq["next_exam_day"] . '</td>';
    echo '<td class="myDIV">' . $cq["next_Exam_Date"] . '</td></tr>';
}
else{
    echo '<tr id="CED_data" class="'. $student_CED . '" >';
    echo '<td class="SA">' . $cq["first_Subject_ID"] . '</td>';
    echo '<td class="SA">' . $cq["first_subject_name"] . '</td>';
    echo '<td class="SA">' . $cq["first_lecturer_name"] . '</td>';
    echo '<td class="SA">' . $cq["first_exam_day"] . '</td>';
    echo '<td class="SA">' . $cq["First_Exam_Date"] . '</td>';
    
    echo '<td class="SA">' . $cq["next_Subject_ID"] . '</td>';
    echo '<td class="SA">' . $cq["next_subject_name"] . '</td>';
    echo '<td class="SA">' . $cq["next_lecturer_name"] . '</td>';
    echo '<td class="SA">' . $cq["next_exam_day"] . '</td>';
    echo '<td class="SA">' . $cq["next_Exam_Date"] . '</td></tr>';
}
}
}


catch(PDOException $catch){

    echo "Sorry no connection : " .$catch->getMessage();
}

?>
</table>