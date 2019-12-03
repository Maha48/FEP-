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
if(isset($_POST['processingg'])){
    // $SSSD="SELECT distinct Class_ID,Subject_ID ,exam_days,exam_dates,
    //       Subject_name,lecturer_name FROM SSD";
    // $ESSSD=mysqli_query($connection,$SSSD)or die( mysqli_error($connection)); 
   //تحديد الطلاب الذين لديهم المشكله بشرط ان تكون مادتين مختلفتين وعليها يتم تحديد المواد التي تاثرت
    $SSSD="SELECT distinct Class_ID,Subject_ID ,exam_days,exam_dates,Subject_name,lecturer_name FROM 
    (SELECT DISTINCT e1.*  from Examdata e1 , Examdata e2
        WHERE e2.Student_ID=e1.Student_ID AND
        e1.Subject_ID!=e2.Subject_ID AND
        e1.exam_dates=e2.exam_dates  
        ORDER BY e1.exam_dates,`e1`.Student_ID) as ssd";
    $ESSSD=mysqli_query($connection,$SSSD)or die( mysqli_error($connection)); 
// اختبار اذا كان يوجد طلاب لديهم المشكله من الاساس قبل الشروع بالمعالجة  
if(!$ESSSD){
    echo '<div class="alert"><span class="closebtn">&times;</span>  
    <strong>Error message!</strong><br> no data to processing.
  </div>
  <script>
  var close = document.getElementsByClassName("closebtn");
  var i;
  for (i = 0; i < close.length; i++) {
    close[i].onclick = function(){
      var div = this.parentElement;
      div.style.opacity = "0";
      setTimeout(function(){ div.style.display = "none"; }, 600);
    }
  }
  </script>';
 }
else{ 
    //تغير حالة زر المعالجة 
    $Bcheck=mysqli_query($connection,"UPDATE Process_State 
    SET PSSD=1
    WHERE PSSD=0")or die( mysqli_error($connection));
$TSTUDENTSOFSSSD="CREATE TEMPORARY TABLE TSSSSD 
(Student_ID INT(11));";
$ETSTUDENTSOFSSSD=mysqli_query($connection,$TSTUDENTSOFSSSD)or die( mysqli_error($connection));
echo "<div class='sel sel--black-panther'>";
?>
</div>
<div style="float:right;margin:0; margin-top:40px;" id="sssd_div4">
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
        <th> Previous exam day</th>
        <th> Previous exam date</th>
        <th> Previous student Count</th>
        <th> Updated exam day</th>
        <th> Updated exam date</th>
        <th> Updated student Count</th>
    </tr>
</thead>
<?php
// معالجة مادة مادة في محاولة لتقليل عدد الطلاب 
foreach($ESSSD as $s1){
    $Subject_ID=$s1['Subject_ID'];
    $Previous_examdate=$s1['exam_dates'];
    $STUDENTSOFSSSD="INSERT INTO TSSSSD SELECT DISTINCT Student_ID From Examdata where `Subject_ID`='$Subject_ID'";
    $ESTUDENTSOFSSSD=mysqli_query($connection,$STUDENTSOFSSSD)or die( mysqli_error($connection));
    //  تحديد عدد الطلاب الذين كان لديهم المشكله في اليوم السابق (قبل المعالجه)
$Previous_count="SELECT COUNT(DISTINCT Student_ID)AS PCstudents
                    FROM Examdata 
                    WHERE Student_ID in (SELECT DISTINCT Student_ID 
                    FROM TSSSSD ) AND
                    Subject_ID!='$Subject_ID'
                    AND exam_dates='$Previous_examdate'";
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
    echo '<td>'.$F_Previous_count['PCstudents'].'</td>';
    //  اختبار عدد الطلاب الذين سيصبح لديهم نفس المشكله لجميع الايام المحدده مسبقا  مع اختيار اقل حاله لذلك
    $dataupdated= "SELECT COUNT(DISTINCT
                    CASE
                    WHEN d.Student_ID IN ( SELECT Student_ID FROM TSSSSD  ) 
                    AND d.Subject_ID != '$Subject_ID'
                    THEN d.Student_ID
                    ELSE NULL
                    END
                ) AS UCStudents ,d.exam_dates ,d.exam_days
                FROM Examdata d 
                GROUP BY d.exam_dates  ,d.exam_days
                ORDER BY `UCStudents` , d.exam_dates ASC";
    $E_dataupdated=mysqli_query($connection,$dataupdated)or die( mysqli_error($connection));
    // احضار البيانات المحدثه التي سيتيم تغير موعد اختبار المادة بناء عليها  
    $F_dataupdated=mysqli_fetch_array($E_dataupdated);
        $updated_count=$F_dataupdated['UCStudents'];
        $updated_day=$F_dataupdated['exam_days'];
        $updated_date=$F_dataupdated['exam_dates'];
        echo"<td>".$updated_day.'</td>';
        echo"<td>".$updated_date.'</td>';
        echo "<td>".$updated_count.'</td>';
        echo'</tr>';
        If($Previous_examdate!=$updated_date){
        // تحديث البيانات 
        $E_update="UPDATE Examdata
        SET `exam_dates`='$updated_date' , exam_days='$updated_day' 
        WHERE Subject_ID='$Subject_ID' ";
    $EE_update=mysqli_query($connection,$E_update)or die( mysqli_error($connection));}
 }
// تفريغ جدول طلاب المشكله عند الماده الحالية حتى يتسنى اضافة طلاب المادة التي تليها 
$DELETE="DELETE FROM TSSSSD";
$DELETEE=mysqli_query($connection,$DELETE);
}
}
}
?>
</table>
</div>