<?php 
//include 'connection_database.php';
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
// تحديد الطلاب الذين لديهم المشكله بشرط ان تكون مادتين مختلفتين وعليها يتم تحديد المواد التي تاثرت
    $SSSD="SELECT distinct Class_ID,Subject_ID ,exam_days,
            exam_dates,exam_times,Subject_name,lecturer_name FROM 
            (SELECT DISTINCT e1.*  from Examdata e1 , Examdata e2
             WHERE e2.Student_ID=e1.Student_ID AND
             e1.Subject_ID!=e2.Subject_ID AND
             e1.exam_dates=e2.exam_dates  AND
             e1.exam_times=e2.exam_times
             ORDER BY e1.exam_dates,`e1`.Student_ID) as ssd";
    $ESSSD=mysqli_query($connection,$SSSD); 
$TSTUDENTSOFSSSD="CREATE TEMPORARY TABLE TSSSSD 
(Student_ID INT(11));";
$ETSTUDENTSOFSSSD=mysqli_query($connection,$TSTUDENTSOFSSSD);
?>
<div style="float:right;"id="ssssd_div" >
<div id="ssd_scroll" class="soa"class="t4">
<table class="t6" >
</div >
<!-- إنشاء جدول لعرض نتيجة المعالجة  -->
<thead>
    <tr>
    <th> Class ID</th>
        <th> Subject ID</th>
        <th> Subject name</th>
        <th> lecturer name</th>
        <th> exam day</th>
        <th width="100px">  exam date</th>
        <th> Previous exam time</th>
        <th> Previous student Count</th>
        <th> Updated exam time & Count</th>
    </tr>
</thead>
<?php
// معالجة مادة مادة في محاولة لتقليل عدد الطلاب 
foreach($ESSSD as $s1){
    $Subject_ID=$s1['Subject_ID'];
    $Previous_examdate=$s1['exam_dates'];
    $Previous_time=$s1['exam_times'];
    $STUDENTSOFSSSD="INSERT INTO TSSSSD SELECT DISTINCT Student_ID
                     From Examdata where `Subject_ID`='$Subject_ID'";
    $ESTUDENTSOFSSSD=mysqli_query($connection,$STUDENTSOFSSSD);
    $Previous_count="SELECT COUNT(DISTINCT Student_ID)AS PCstudents
                     FROM Examdata 
                     WHERE Student_ID in (SELECT DISTINCT Student_ID FROM TSSSSD ) AND
                     Subject_ID!='$Subject_ID'
                     AND exam_dates='$Previous_examdate'
                     and exam_times='$Previous_time'";
    $E_Previous_count=mysqli_query($connection,$Previous_count)or die( mysqli_error($connection));
    $F_Previous_count=mysqli_fetch_array($E_Previous_count);
if ($F_Previous_count['PCstudents']!=0){
echo'<tr>';
echo '<td>'.$s1['Class_ID'].'</td>';
    echo '<td>'.$Subject_ID.'</td>';
    echo '<td>'.$s1['Subject_name'].'</td>';
    echo '<td>'.$s1['lecturer_name'].'</td>';
    echo '<td>'.$s1['exam_days'].'</td>';
    echo '<td>'.$Previous_examdate.'</td>';
    echo '<td>'.$Previous_time.'</td>';
    echo '<td>'.$F_Previous_count['PCstudents'].'</td>';
        //  اختبار عدد الطلاب الذين سيصبح لديهم نفس المشكله لجميع الايام المحدده مسبقا  مع اختيار اقل حاله لذلك
        $dataupdated= "  SELECT t.exam_times,
                        (SELECT COUNT(DISTINCT CASE WHEN d.Student_ID IN 
                        ( SELECT Student_ID FROM  TSSSSD )
                        AND d.Subject_ID != '$Subject_ID' 
                        THEN d.Student_ID ELSE NULL END ) 
                        AS countss FROM Examdata d  
                        where d.exam_dates='$Previous_examdate'  and d.exam_times=t.exam_times) as UCStudents 
                        From times t
                        ORDER by UCStudents, t.exam_times";
$E_dataupdated=mysqli_query($connection,$dataupdated);
        echo '<td>';
        while($l_dataupdated=mysqli_fetch_array($E_dataupdated)){
            echo '&#160;'.$l_dataupdated['exam_times'].'&#160;'.$l_dataupdated['UCStudents'].'&#160;';
        }
        echo '</td></tr>';
     }
$DELETE="DELETE FROM TSSSSD";
$DELETEE=mysqli_query($connection,$DELETE);
}
 ?>
</table>
</div>
<form method="post" id="Psametime-sub" >
<select id="sameT-subject"> 
<?PHP
foreach ($ESSSD as $s2){
 echo '<option value='.$s2['Subject_ID'].'>'.$s2['Subject_ID'].'</option>';}
?>
</select >
<select id="sameT-times">
<?php 
$fortimes="SELECT DISTINCT exam_times FROM times order by exam_times ";
$E_fortimes=mysqli_query($connection,$fortimes);
foreach($E_fortimes as $s3){
    echo '<option value='.$s3['exam_times'].'>'.$s3['exam_times'].'</option>';  
}
?>
</select>
<input type="submit" id="U_times" class="button button5" value="Update" > 
</form> 