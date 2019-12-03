<?php
session_start();
if (!isset($_SESSION['userid'])){
 
    header("location: index.php");
}
?>

<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<link rel="stylesheet" type="text/css" href="data.css">
<script src="jquery-3.4.1.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="data.js" > </script>
</head>
<!-- UPload Examdata File  -->
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
include('upload_students.php');
include('upload_students_away.php');
?>
<body id="body">
<div id="bar">
<div id="wlcomeadmin">
<a id="logout1"  href="logout.php">
  Logout</a> Welcome Admin
</div>
<label>

</label>
<label>
<a id="bar_img">
<img width="55%" src="images/Prince_Sattam_Bin_Abdulaziz_University.png"></a>
</label>
</div>
<div id="uploadfile">
<br>
<div id="outer-scontainer">
<h4 id="students-file"> Enter The Students Table File</h4>

<div id="uploaddiv">
<div id="response"><?php if (!empty($message)) {echo $message;}?></div>
    <div class="row">
            <form class="form-horizontal" action="" method="post"
            name="frmCSVImport" id="frmCSVImport" enctype="multipart/form-data">
                <div class="input-row">
                    <label>
                        <input type="file" name="file"id="file" class="file1"accept=".csv">
                        <img src="images/upload-2.png"id="file"><br>
                    </label>
                    <br>
                    <table id="tablebtn">
                    <tr> 
                    <td><label class="col-md-4 control-label">Choose CSV File to Upload </label></td>
                    <td><input  style="display:none"type='submit'id="import" name='import' class="import"value='Import'></td>
                    </tr>
                    <tr>
                    <td> <label> Delete The Current Data </label> </td>
                    <td> <input type="submit"value="Delete"name="delete" id="delete"></td>
                    </tr>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<!--End upload file-->

<!-- upload students_away file -->
<div id="uploadfile2">
<br>
<div id="outer-scontainer">
<h4 id="students-away-file"style="margin-top:0; margin-bottom:3px;"> Enter The Students Away From The University File</h4>

<div id="uploaddiv">
<div id="response"><?php if (!empty($message2)) {echo $message2;}?></div>
    <div class="row">
            <form class="form-horizontal" action="" method="post"
            name="frmCSVImport2" id="frmCSVImport2" enctype="multipart/form-data">
                <div class="input-row">
                    <label>
                        <input type="file" name="file"id="file" class="file1"accept=".csv">
                        <img src="images/upload-2.png"id="file"><br>
                    </label>
                    <br>
                    <table id="tablebtn">
                    <tr> 
                    <td><label class="col-md-4 control-label">Choose CSV File to Upload </label></td>
                    <td><input  style="display:none"type='submit'id="import" name='import2' class="import"value='Import'></td>
                    </tr>
                    <tr>
                    <td> <label> Delete The Current Data </label> </td>
                    <td> <input type="submit"value="Delete"name="delete2" id="delete"></td>
                    </tr>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<!-- end upload students_away file -->

<!-- SSD->student in same day and same time-->

<div id="ssd_div" >
<form method="POST">
<div id="search_div">
<h3 id="ssd_title"> Student Who Have Exames on  Same Day</h3>
<h3 id="sst_title" style="display:none;"> Student Who Have Exames at  Same Time</h3>

<div id="inpout">
<input type="text"id="search" name="search">
<input class="button button5" type="submit"class="hide" id="Search-btn"name="btn"value="Search">
</div>
</div>
</form> 
<table id="Mainـhead">
<thead>
<tr>
    <th >Class ID</th>
    <th>Subject_ID</th>
    <th>Subject_name</th>
    <th>lecturer_name</th>
    <th>exam_days</th>
    <th>exam_dates</th>
    <th>exam_dates</th>
</tr>
<thead>
</table>
<div id="ssd_scroll">
<div id="SSD_search" >
</div>
<div class="SSD-tables">
<?php
include('SSD_define.php');
?>
</div>
<div id="SSD-intime" style="display:none;">

<?php
include('SST_define.php')?>
</div>
</div>
<div id="nextprev">
<label id="nextlabel">
<a id="next" >
<img  width="50px" src="images/prev.png"></a>
</label>
<label>
<a id="prev">
    <img width="50px" src="images/next.png"></a>
</label>
</div>
<input class="button button5" value="Student Same Time" type="button" id="same_time" >
</div>
<!-- end SSD->student in same day and same time-->

<!-- CED ->consecutive exams in consecutive days-->

<div id="CED_box">
<div id="search_div">
<h3 id="CED_title"> Student Who Have Consecutive Exames on Consecutive Day</h3>
<BR>
<form method="POST">
<div id="inpout">
<input type="text" name="tosearch1" id="textbox-CED" > 
<input class="button button5" type="submit" value ="search" name ="search" id="search-CED"class="hide-CED" >

</div>
</div>
<br>
</form> 
<table id="Mainـhead">
<thead>
<tr ID="HE">
    <th width="12.5%">Subject ID</th>
    <th width="12.5%">First Subject Name</th>
    <th width="12.5%">Lecturer    name</th>
    <th width="12.5%">First Exam    Day</th>
    <th width="12.5%">First Exam    Date</th>
    <th width="12.5%">Next  Subject Name</th>
    <th width="12.5%">lecturer    name</th>
    <th width="12.5%">Next  Exam    Day</th>
    <th width="12.5%">Next  Exam    Date</th>
</tr>
<thead>
</table>
<div id="CED_scroll">
<div id="CED_search" >
</div>
<div class="CED-tables">

<?php
include ('CED_define.php');
?>
</div>
</div>
<img src="images/i-important (1).png" id="SAN" >
<h3 id="note">
    Yellow row for the student with distance away from the university
</h3>

<div id="nextprev">
<label id="nextlabel">
    <a id="next-CED" >
    <img  width="50px" src="images/prev.png" ></a>
</label>
<label>
<a id="prev-CED">
    <img width="50px" src="images/next.png"></a>
</label>
</div>
</div> 
<!--  end  CED -->

<!--suggest new day of SSD -->

<div  id="sssd_div">
<div id="SuggestNewDay">
<p class="StyleFont" id="processing_title">Processing The Problem Of Students Who Have More Than One Exam on  Same Day</p>
<?php
$Bcheck=mysqli_query($connection,"SELECT * from Process_State ");
$EBcheck=mysqli_fetch_array($Bcheck);
if($EBcheck['PSSD']==1){
    echo'<div class="warning-msg">
    <img src="images/i-important (1).png">
  <i class="fa fa-warning"></i>
  <sup>hed been processed data before ..</sup>
</div>';
}
?>
<!--هنا جزء الرساله المنبثقه التابعه ل بوتون الانفرميشن-->
<button id="myBtn" class="button button5">More Information</button>
<div id="myModal" class="modal">
  <div class="modal-content">
    <div class="modal-body">
<h2 id="h2">
<center>
    Process of students problem policy <br>-those who have more than one exam  on the same day-
</center>
</h2>
<p id="p">
<font color=" #ff0e0e"><B> Note:</B></font> If you press on “ processing ” the data will change which means the data will never be as what it was. 
When you press “processing” for the first time all the data will be processed after that a list of choices 
will appear contains the subject ID, in case you want to process any of those subjects again, all you have to do is selecting 
all the subjects you want then press “processing selected subjects” beside the selected subject.  
However, pressing “processing” button only the subjects of the students who have this issue will be processed.
</p>
    </div>
    <span class="close">Close</span></h3>
    </div>
</div>
<!--نهاية بوتون الانفرميشن-->
<!-- processing start -->
<form method="POST" id="proform">
<input class="button button5" type="submit" name="processing" id="processing" value="processing" onclick="myFunction()">
</form>
<form method="POST" id="sub-pro-form">
<input class="button button5" type="submit" name="sub-pro" id="sub-pro" value="subjects processing" onclick="myFunction4()">
</form>
<form  method="post" action="download.php">
<input class="button button5" type="submit" value="Download" name="Download" id="Download">
</form>
<img src="images\loading.gif" style="margin-left:450px;margin-top:50px;display:none;" id="load-img" >
<div id="PSSD_INFO"></div>
</div> 
</div>
<!-- END processing start -->
<!-- end suggest new day SSD --> 

<!--  suggest new time SST -->
<div id="sametimeprocessing">
<p class="StyleFont" id="processing_title">Processing The Problem Of Students Who Have More Than One Exam on  Same time</p>
<?php
include ('sst.php');
?>
<div id="stt">
</div>
</div>
<!-- end suggest new time SST -->
