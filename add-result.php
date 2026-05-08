<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])=="")
{   
    header("Location: index.php"); 
}
else{
$msg="";
$error="";

if(isset($_POST['submit']))
{
    $class=$_POST['class']; // Semester
    $studentid=$_POST['studentid']; 
    $academicyear=$_POST['academicyear'];
    $examtype=$_POST['examtype'];
    $mark=$_POST['marks'];

    // Subjects for the semester
    $stmt = $dbh->prepare("SELECT tblsubjects.id 
        FROM tblsubjectcombination 
        JOIN tblsubjects ON tblsubjects.id=tblsubjectcombination.SubjectId 
        WHERE tblsubjectcombination.ClassId=:cid 
        ORDER BY tblsubjects.SubjectName");
    $stmt->execute(array(':cid' => $class));

    $subjects=array();
    while($row=$stmt->fetch(PDO::FETCH_ASSOC))
    {
        array_push($subjects,$row['id']);
    }

    $all_success=true;

    for($i=0;$i<count($mark);$i++)
    {
        $mar=$mark[$i];
        $sid=$subjects[$i];

        // Check if the record for this exam already exists
        $check = $dbh->prepare("SELECT id FROM tblresult 
            WHERE StudentId=:studentid 
            AND AcademicYear=:ay 
            
            AND ExamType=:exam 
            AND SubjectId=:sid");
        $check->bindParam(':studentid',$studentid);
        $check->bindParam(':ay',$academicyear);
       
        $check->bindParam(':exam',$examtype);
        $check->bindParam(':sid',$sid);
        $check->execute();

        if($check->rowCount() > 0){
            $error="Marks for this Exam Type already entered for this student in this Semester / Academic Year.";
            $all_success=false;
            break; // stop further inserts
        } else {
            // Insert marks
            $sql="INSERT INTO tblresult
            (StudentId,ClassId,AcademicYear,ExamType,SubjectId,marks)
            VALUES(:studentid,:class,:ay,:exam,:sid,:marks)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':studentid',$studentid,PDO::PARAM_STR);
            $query->bindParam(':class',$class,PDO::PARAM_STR);
            $query->bindParam(':ay',$academicyear,PDO::PARAM_STR);
           
            $query->bindParam(':exam',$examtype,PDO::PARAM_STR);
            $query->bindParam(':sid',$sid,PDO::PARAM_STR);
            $query->bindParam(':marks',$mar,PDO::PARAM_STR);
            $query->execute();
        }
    }

    if($all_success){
        $msg="Result info added successfully";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>SMS Admin | Declare Result</title>

<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/font-awesome.min.css">
<link rel="stylesheet" href="css/animate-css/animate.min.css">
<link rel="stylesheet" href="css/lobipanel/lobipanel.min.css">
<link rel="stylesheet" href="css/prism/prism.css">
<link rel="stylesheet" href="css/select2/select2.min.css">
<link rel="stylesheet" href="css/main.css">

<script src="js/modernizr/modernizr.min.js"></script>
<script src="js/jquery/jquery-2.2.4.min.js"></script>

<script>
function getStudent(val) {
    $.ajax({
        type: "POST",
        url: "get_student.php",
        data:'classid='+val,
        success: function(data){
            $("#studentid").html(data);
        }
    });
    $.ajax({
        type: "POST",
        url: "get_student.php",
        data:'classid1='+val,
        success: function(data){
            $("#subject").html(data);
        }
    });
}
</script>
</head>

<body class="top-navbar-fixed">
<div class="main-wrapper">
<?php include('includes/topbar.php');?> 

<div class="content-wrapper">
<div class="content-container">
<?php include('includes/leftbar.php');?>  

<div class="main-page">
<div class="container-fluid">
<div class="row page-title-div">
<div class="col-md-6">
<h2 class="title">Declare Result</h2>
</div>
</div>
</div>

<div class="container-fluid">
<div class="row">
<div class="col-md-12">
<div class="panel">
<div class="panel-body">

<?php if($msg){ ?>
<div class="alert alert-success">
<strong>Success!</strong> <?php echo htmlentities($msg); ?>
</div>
<?php } ?>

<?php if($error){ ?>
<div class="alert alert-danger">
<strong>Error!</strong> <?php echo htmlentities($error); ?>
</div>
<?php } ?>

<form method="post" class="form-horizontal">

<!-- Academic Year -->
<div class="form-group">
<label class="col-sm-2 control-label">Academic Year</label>
<div class="col-sm-10">
<select name="academicyear" class="form-control" required>
<option value="">Select Year</option>
<option value="2025-2026">2025-2026</option>
<option value="2026-2027">2026-2027</option>
</select>
</div>
</div>

<!-- Exam Type -->
<div class="form-group">
<label class="col-sm-2 control-label">Exam Type</label>
<div class="col-sm-10">
<select name="examtype" class="form-control" required>
<option value="">Select Exam</option>
<option value="Midterm">Midterm</option>
<option value="Final">Final</option>
</select>
</div>
</div>

<!-- Semester -->
<div class="form-group">
<label class="col-sm-2 control-label">Semester</label>
<div class="col-sm-10">
<select name="class" class="form-control" onChange="getStudent(this.value);" required>
<option value="">Select Semester</option>
<?php 
$sql = "SELECT * from tblclasses";
$query = $dbh->prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
foreach($results as $result){ ?>
<option value="<?php echo htmlentities($result->id); ?>">
<?php echo htmlentities($result->ClassName); ?>
</option>
<?php } ?>
</select>
</div>
</div>

<!-- Student -->
<div class="form-group">
<label class="col-sm-2 control-label">Student Name</label>
<div class="col-sm-10">
<select name="studentid" id="studentid" class="form-control" required></select>
</div>
</div>

<!-- Subjects -->
<div class="form-group">
<label class="col-sm-2 control-label">Subjects</label>
<div class="col-sm-10">
<div id="subject"></div>
</div>
</div>

<div class="form-group">
<div class="col-sm-offset-2 col-sm-10">
<button type="submit" name="submit" class="btn btn-primary">
Declare Result
</button>
</div>
</div>

</form>

</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

<script src="js/bootstrap/bootstrap.min.js"></script>
<script src="js/pace/pace.min.js"></script>
<script src="js/lobipanel/lobipanel.min.js"></script>
<script src="js/iscroll/iscroll.js"></script>
<script src="js/prism/prism.js"></script>
<script src="js/select2/select2.min.js"></script>
<script src="js/main.js"></script>

</body>
</html>
<?php } ?>