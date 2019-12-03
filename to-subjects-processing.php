<form method="post" id="PSSFORM" >
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
$SSSD="SELECT distinct Class_ID,Subject_ID ,exam_days,exam_dates,
       Subject_name,lecturer_name FROM 
       (SELECT DISTINCT e1.*  from Examdata e1 , Examdata e2
        WHERE e2.Student_ID=e1.Student_ID AND
        e1.Subject_ID!=e2.Subject_ID AND
        e1.exam_dates=e2.exam_dates  
        ORDER BY e1.exam_dates,`e1`.Student_ID) as ssd";
$ESSSD=mysqli_query($connection,$SSSD); 
echo " <select  name='SUB[]' multiple id='PSSSS'>";
foreach ($ESSSD as $sub){
$selectedsubject=$sub['Subject_ID'];
echo '<option value='.$selectedsubject.'>'.$selectedsubject.'</option>'; 
}
?>
</select>
<input class="button button5" type="submit" name='sb_se' ID="sb_se" value='processing selected subjects'onclick="myfunction2()">
</form>
<div id="sssd_div2" >
<!-- إنشاء جدول لعرض نتيجة المعالجة  -->
<div id="ssd_scroll" class="soa"class="t4">
<table id="subject_head" class="t6" >
</div>
<thead>
    <tr>
    <th> Class ID</th>
        <th> Subject ID</th>
        <th> Subject name</th>
        <th> lecturer name</th>
        <th> Present exam day</th>
        <th> Present exam date</th>
        <th> Present student Count</th>
    </tr>
    </thead>
<?php
$TSTUDENTSOFSSSD="CREATE TEMPORARY TABLE TSSSSD 
(Student_ID INT(11));";
$ETSTUDENTSOFSSSD=mysqli_query($connection,$TSTUDENTSOFSSSD);
foreach($ESSSD as $s1){
    $Subject_ID=$s1['Subject_ID'];
    $Previous_examdate=$s1['exam_dates'];
    $STUDENTSOFSSSD="INSERT INTO TSSSSD SELECT DISTINCT Student_ID 
    From Examdata where `Subject_ID`='$Subject_ID'";
    $ESTUDENTSOFSSSD=mysqli_query($connection,$STUDENTSOFSSSD);
    //  تحديد عدد الطلاب الذين كان لديهم المشكله في اليوم السابق (قبل المعالجه)
$Previous_count="SELECT COUNT(DISTINCT Student_ID)AS PCstudents
                 FROM Examdata 
                 WHERE Student_ID in (SELECT DISTINCT Student_ID FROM TSSSSD ) AND
                 Subject_ID!='$Subject_ID'
                 AND exam_dates='$Previous_examdate'";
$E_Previous_count=mysqli_query($connection,$Previous_count) or die( mysqli_error($connection));

$F_Previous_count=mysqli_fetch_array($E_Previous_count);
echo'<tr>';
    echo '<td>'.$s1['Class_ID'].'</td>';
    echo '<td>'.$Subject_ID.'</td>';
    echo '<td>'.$s1['Subject_name'].'</td>';
    echo '<td>'.$s1['lecturer_name'].'</td>';
    echo '<td>'.$s1['exam_days'].'</td>';
    echo '<td>'.$Previous_examdate.'</td>';
    echo '<td>'.$F_Previous_count['PCstudents'].'</td></tr>';
    $DELETE="DELETE FROM TSSSSD";
    $DELETEE=mysqli_query($connection,$DELETE) or die("Error in query: $DELETEE. " . mysqli_error());
}
?>
<table>
