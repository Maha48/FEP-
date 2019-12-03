<?php
$queryempty2 = 'select * From students_away';
$query2 = mysqli_query($connection, $queryempty2) or die("Error in query: $query2. " . mysqli_error());
if (mysqli_num_rows($query2) > 0) {
    $message2 = "There is data";
} else {
    if (isset($_POST["import2"])) {
         // رفع محتويات الملف الى جدول طلاب البعد 
        $fileName = $_FILES["file"]["tmp_name"];
        if ($_FILES["file"]["size"] > 0) {
            $file = fopen($fileName, "r");
            while (($column = fgetcsv($file, 110000, ";")) !== false) {
                $sqlInsert1 = "INSERT into students_away (Student_ID,town,distance,department)
                values ('" . $column[0] . "','" . $column[1] . "','" . $column[2] . "','" . $column[3] . "')";
                $resultw = mysqli_query($connection, $sqlInsert1);
                if (!empty($resultw)) {
                    $message2 = "Upload Done ";
                } else {
                    $message2 = "Problem In Upload Data";
                }
            }
                  //عملية تنظيف البيانات المتكررة 
            $dataclean2="DELETE n1 FROM students_away n1, students_away n2 WHERE n1.id > n2.id AND n1.`Student_ID` = n2.`Student_ID`";
            $datacleaning2=mysqli_query($connection,$dataclean2);
        }
    }
}
if (isset($_POST['delete2'])) {


    $sqldelete2 = "delete from students_away";
    $result2 = mysqli_query($connection, $sqldelete2);
    if (!empty($result2)) {
        $message2 = "Deleted Done";
    }
}
?>