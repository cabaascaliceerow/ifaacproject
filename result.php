<?php 
session_start();
error_reporting(0);
include('includes/config.php');

/* ================= MESSAGE ================= */
$msg = "";
$error = "";

/* ================= COMPLAINT INSERT ================= */
if(isset($_POST['submit_complaint'])){
    try{
        $name = $_POST['cname'];
        $roll = $_POST['croll'];
        $message = htmlspecialchars($_POST['message']);

        $sql = "INSERT INTO complaints(StudentName, RollId, Message) 
                VALUES(:name, :roll, :message)";
        $query = $dbh->prepare($sql);

        $query->bindParam(':name',$name,PDO::PARAM_STR);
        $query->bindParam(':roll',$roll,PDO::PARAM_STR);
        $query->bindParam(':message',$message,PDO::PARAM_STR);

        if($query->execute()){
            $msg = "Complaint submitted successfully";
        } else {
            $error = "Error! Try again";
        }

    } catch(PDOException $e){
        $error = "Database error";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">


<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/font-awesome.min.css">

<style>
body{ background:#f5f5f5; }

.result-card{
    background:#fff;
    padding:20px;
    margin-top:20px;
    box-shadow:0 0 10px rgba(0,0,0,0.1);
}

.table th{ background:#f2f2f2; text-align:center; }
.table td{ text-align:center; }

.info-box p{ margin:3px 0; font-size:15px; }

.overall-box{
    margin-top:15px;
    padding:10px;
    background:#e9ecef;
    font-weight:bold;
    text-align:center;
}

/* HEADER */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:15px;
    text-align:center;
}

.header-left, .header-center, .header-right{ flex:1; }

.header-center img{
    height:80px;
    width:80px;
    border-radius:50%;
    object-fit:cover;
    border:2px solid #000;
}


/* COMPLAINT DESIGN */
.complaint-box{
    margin-top:20px;
    padding:15px;
    background:#fff;
    border-left:5px solid #dc3545;
}

.complaint-box h4{
    margin-bottom:15px;
    color:#dc3545;
}

/* PRINT */
@media print{
 body{ background:#fff; }
 .result-card{ box-shadow:none; margin-top:0; }
 .btn{ display:none !important; }
 .complaint-box{ display:none; }
}
</style>
</head>

<body>
<div class="container">

<?php
$rollid=$_POST['rollid'];
$classid=$_POST['class'];

$stmt = $dbh->prepare("SELECT s.StudentName, s.RollId, s.StudentId, 
       f.FacultyName, d.DepartmentName
FROM tblstudents s
LEFT JOIN tblfaculty f ON s.Faculty = f.id
LEFT JOIN tbldepartment d ON s.Department = d.id
WHERE s.RollId=:rollid");

$stmt->bindParam(':rollid',$rollid,PDO::PARAM_STR);
$stmt->execute();
$student = $stmt->fetch(PDO::FETCH_OBJ);

if($stmt->rowCount() > 0){

$studentid=$student->StudentId;
?>

<div class="result-card">

<!-- ✅ MESSAGE (SIDA ADMIN) -->
<?php if($msg){ ?>
<div class="alert alert-success">
    <?php echo htmlentities($msg); ?>
</div>
<?php } ?>

<?php if($error){ ?>
<div class="alert alert-danger">
    <?php echo htmlentities($error); ?>
</div>
<?php } ?>

<div class="header">
<div class="header-left">
<h4 style="margin:0;font-weight:bold;">KOWNAYN UNIVERSITY</h4>
</div>

<div class="header-center">
<img src="images/logokowneyn.jpg">
</div>

<div class="header-right">
<h4 style="margin:0;font-weight:bold;">جامعة كونين</h4>
</div>
</div>

<hr>

<?php
$res_stmt = $dbh->prepare("SELECT tblresult.AcademicYear, tblsubjects.SubjectName, tblresult.ExamType, tblresult.marks 
FROM tblresult 
JOIN tblsubjects ON tblsubjects.id=tblresult.SubjectId 
WHERE tblresult.StudentId=:studentid AND tblresult.ClassId=:classid 
ORDER BY tblsubjects.SubjectName ASC");

$res_stmt->bindParam(':studentid',$studentid,PDO::PARAM_STR);
$res_stmt->bindParam(':classid',$classid,PDO::PARAM_STR);
$res_stmt->execute();
$results = $res_stmt->fetchAll(PDO::FETCH_OBJ);

if($res_stmt->rowCount() > 0){

$subjects=[];
$academicYear="";

foreach($results as $res){
    $subjects[$res->SubjectName][$res->ExamType]=$res->marks;
    $academicYear=$res->AcademicYear;
}
?>

<div class="info-box">
<p><strong>Name:</strong> <?php echo $student->StudentName; ?></p>
<p><strong>Roll ID:</strong> <?php echo $student->RollId; ?></p>
<p><strong>Faculty:</strong> <?php echo $student->FacultyName ? htmlentities($student->FacultyName) : 'N/A'; ?></p>
<p><strong>Department:</strong> <?php echo $student->DepartmentName ? htmlentities($student->DepartmentName) : 'N/A'; ?></p>
<p><strong>Semester:</strong> <?php echo $classid; ?></p>
<p><strong>Academic Year:</strong> <?php echo $academicYear; ?></p>
</div>

<br>

<table class="table table-bordered">
<thead>
<tr>
<th>#</th>
<th>Subject</th>
<th>Midterm (40)</th>
<th>Final (60)</th>
<th>Total (100)</th>
</tr>
</thead>

<tbody>
<?php
$cnt=1; 
$grandTotal=0; 
$subjectCount=0;

foreach($subjects as $subject=>$marks){
    $mid=$marks['Midterm'] ?? 0;
    $final=$marks['Final'] ?? 0;
    $total=$mid+$final;

    $grandTotal+=$total;
    $subjectCount++;
?>
<tr>
<td><?php echo $cnt; ?></td>
<td><?php echo $subject; ?></td>
<td><?php echo $mid;?> </td>
<td><?php echo $final;?> </td>
<td><?php echo $total;?> </td>
</tr>
<?php $cnt++; } ?>

<?php
$maxTotal=$subjectCount*100;
$overallPercent=round(($grandTotal/$maxTotal)*100,2);

function getGrade($p){
 if($p>=90) return 'A';
 elseif($p>=80) return 'B';
 elseif($p>=70) return 'C';
 elseif($p>=60) return 'D';
 else return 'F';
}

$grade=getGrade($overallPercent);
?>

<tr>
<th colspan="4">Overall Total</th>
<th><?php echo $grandTotal;?> / <?php echo $maxTotal;?></th>
</tr>

<tr>
<th colspan="4">Overall Percentage</th>
<th><?php echo $overallPercent;?> %</th>
</tr>

<tr>
<th colspan="4">Overall Grade</th>
<th><?php echo $grade;?></th>
</tr>

</tbody>
</table>

<div class="overall-box">
Overall Result = <?php echo $overallPercent;?> % (Grade: <?php echo $grade;?>)
</div>

<!-- ================= COMPLAINT ================= -->
<div class="complaint-box">
<h4>Submit Complaint</h4>

<form method="post">

<input type="hidden" name="rollid" value="<?php echo $rollid; ?>">
<input type="hidden" name="class" value="<?php echo $classid; ?>">

<input type="hidden" name="cname" value="<?php echo $student->StudentName; ?>">
<input type="hidden" name="croll" value="<?php echo $student->RollId; ?>">

<div class="form-group">
<label>Your Message</label>
<textarea name="message" class="form-control" required></textarea>
</div>

<br>
<button type="submit" name="submit_complaint" class="btn btn-danger">
<i class="fa fa-send"></i> Submit Complaint
</button>
</form>
</div>

<?php } else { ?>
<div class="alert alert-warning">Result not declared yet</div>
<?php } ?>

</div>

<div class="text-center" style="margin-top:15px;">
<button onclick="window.print()" class="btn btn-success">
<i class="fa fa-print"></i> Print
</button>

<a href="find-result.php" class="btn btn-primary">
<i class="fa fa-home"></i> Back
</a>
</div>

<?php } else { ?>
<div class="alert alert-danger">Invalid Roll ID</div>
<?php } ?>

</div>
<script>
// Marka document-ka uu load noqdo
document.addEventListener("DOMContentLoaded", function() {
    // Hel alert-ka success
    var successAlert = document.querySelector(".alert-success");
    if(successAlert){
        // Kadib 3 ilbiriqsi (3000ms) qarso
        setTimeout(function(){
            successAlert.style.display = "none";
        }, 3000); // 3000ms = 3 ilbiriqsi
    }

    // Haddii aad rabto in error message-ka sidoo kale qarso, ku dar sidan
    var errorAlert = document.querySelector(".alert-danger");
    if(errorAlert){
        setTimeout(function(){
            errorAlert.style.display = "none";
        }, 5000); // 5 ilbiriqsi
    }
});
</script>
</body>
</html>