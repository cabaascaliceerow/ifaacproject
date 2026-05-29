<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])=="")
{   
    header("Location: index.php"); 
}
else{

if(isset($_POST['Update']))
{

    $sid=intval($_GET['subjectid']);

    $subjectname=$_POST['subjectname'];
    $subjectcode=$_POST['subjectcode'];

    $facultyname=$_POST['facultyname'];
    $departmentname=$_POST['departmentname'];

    $sql="UPDATE tblsubjects 
          SET SubjectName=:subjectname,
              SubjectCode=:subjectcode,
              FacultyName=:facultyname,
              DepartmentName=:departmentname
          WHERE id=:sid";

    $query = $dbh->prepare($sql);

    $query->bindParam(':subjectname',$subjectname,PDO::PARAM_STR);
    $query->bindParam(':subjectcode',$subjectcode,PDO::PARAM_STR);

    $query->bindParam(':facultyname',$facultyname,PDO::PARAM_STR);
    $query->bindParam(':departmentname',$departmentname,PDO::PARAM_STR);

    $query->bindParam(':sid',$sid,PDO::PARAM_STR);

    $query->execute();

    $msg="Subject Info updated successfully";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>SMS Admin Update Subject</title>

<link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
<link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
<link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen">
<link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen">
<link rel="stylesheet" href="css/prism/prism.css" media="screen">
<link rel="stylesheet" href="css/select2/select2.min.css">
<link rel="stylesheet" href="css/main.css" media="screen">

<script src="js/modernizr/modernizr.min.js"></script>

</head>

<body class="top-navbar-fixed">

<div class="main-wrapper">

<!-- TOP NAVBAR -->
<?php include('includes/topbar.php');?> 

<div class="content-wrapper">
<div class="content-container">

<!-- LEFT SIDEBAR -->
<?php include('includes/leftbar.php');?>  

<div class="main-page">

<div class="container-fluid">

<div class="row page-title-div">
<div class="col-md-6">
<h2 class="title">Update Subject</h2>
</div>
</div>

<div class="row breadcrumb-div">
<div class="col-md-6">

<ul class="breadcrumb">

<li>
<a href="dashboard.php">
<i class="fa fa-home"></i> Home
</a>
</li>

<li>Subjects</li>

<li class="active">Update Subject</li>

</ul>

</div>
</div>

</div>

<div class="container-fluid">

<div class="row">

<div class="col-md-12">

<div class="panel">

<div class="panel-heading">
<div class="panel-title">
<h5>Update Subject</h5>
</div>
</div>

<div class="panel-body">

<?php if($msg){?>

<div class="alert alert-success left-icon-alert" role="alert">
<strong>Well done!</strong>
<?php echo htmlentities($msg); ?>
</div>

<?php } else if($error){?>

<div class="alert alert-danger left-icon-alert" role="alert">
<strong>Oh snap!</strong>
<?php echo htmlentities($error); ?>
</div>

<?php } ?>

<form class="form-horizontal" method="post">

<?php

$sid=intval($_GET['subjectid']);

$sql = "SELECT * FROM tblsubjects WHERE id=:sid";

$query = $dbh->prepare($sql);

$query->bindParam(':sid',$sid,PDO::PARAM_STR);

$query->execute();

$results=$query->fetchAll(PDO::FETCH_OBJ);

if($query->rowCount() > 0)
{
foreach($results as $result)
{

?>

<!-- SUBJECT NAME -->

<div class="form-group">

<label class="col-sm-2 control-label">
Subject Name
</label>

<div class="col-sm-10">

<input type="text"
name="subjectname"
class="form-control"
value="<?php echo htmlentities($result->SubjectName);?>"
placeholder="Subject Name"
required>

</div>
</div>

<!-- SUBJECT CODE -->

<div class="form-group">

<label class="col-sm-2 control-label">
Subject Code
</label>

<div class="col-sm-10">

<input type="text"
name="subjectcode"
class="form-control"
value="<?php echo htmlentities($result->SubjectCode);?>"
placeholder="Subject Code"
required>

</div>
</div>

<!-- FACULTY -->

<div class="form-group">

<label class="col-sm-2 control-label">
Faculty
</label>

<div class="col-sm-10">

<input type="text"
name="facultyname"
class="form-control"
value="<?php echo htmlentities($result->FacultyName);?>"
placeholder="Faculty Name"
required>

</div>
</div>

<!-- DEPARTMENT -->

<div class="form-group">

<label class="col-sm-2 control-label">
Department
</label>

<div class="col-sm-10">

<input type="text"
name="departmentname"
class="form-control"
value="<?php echo htmlentities($result->DepartmentName);?>"
placeholder="Department Name"
required>

</div>
</div>

<?php }} ?>

<div class="form-group">

<div class="col-sm-offset-2 col-sm-10">

<button type="submit"
name="Update"
class="btn btn-primary">

Update

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
</div>

<!-- JS FILES -->

<script src="js/jquery/jquery-2.2.4.min.js"></script>

<script src="js/bootstrap/bootstrap.min.js"></script>

<script src="js/pace/pace.min.js"></script>

<script src="js/lobipanel/lobipanel.min.js"></script>

<script src="js/iscroll/iscroll.js"></script>

<script src="js/prism/prism.js"></script>

<script src="js/select2/select2.min.js"></script>

<script src="js/main.js"></script>

<script>

$(function($) {

    $(".js-states").select2();

    $(".js-states-limit").select2({
        maximumSelectionLength: 2
    });

    $(".js-states-hide").select2({
        minimumResultsForSearch: Infinity
    });

});

</script>

</body>
</html>

<?php } ?>