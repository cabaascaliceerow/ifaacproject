<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
    $subjectname=$_POST['subjectname'];
    $subjectcode=$_POST['subjectcode'];
    $facultyname=$_POST['facultyname'];
    $departmentname=$_POST['departmentname'];

    $sql="INSERT INTO tblsubjects
    (SubjectName,SubjectCode,FacultyName,DepartmentName)
    VALUES
    (:subjectname,:subjectcode,:facultyname,:departmentname)";

    $query = $dbh->prepare($sql);
    $query->bindParam(':subjectname',$subjectname,PDO::PARAM_STR);
    $query->bindParam(':subjectcode',$subjectcode,PDO::PARAM_STR);
    $query->bindParam(':facultyname',$facultyname,PDO::PARAM_STR);
    $query->bindParam(':departmentname',$departmentname,PDO::PARAM_STR);
    $query->execute();

    $lastInsertId = $dbh->lastInsertId();

    if($lastInsertId)
    {
        $msg="Subject Created successfully";
    }
    else
    {
        $error="Something went wrong. Please try again";
    }
}

$sql = "SELECT * FROM tblfaculty";
$query = $dbh->prepare($sql);
$query->execute();
$faculties = $query->fetchAll(PDO::FETCH_OBJ);

// Department data diyaari
$sql2 = "SELECT tbldepartment.DepartmentName,
                tblfaculty.FacultyName
         FROM tbldepartment
         JOIN tblfaculty
         ON tbldepartment.FacultyId = tblfaculty.id";

$query2 = $dbh->prepare($sql2);
$query2->execute();
$results = $query2->fetchAll(PDO::FETCH_OBJ);

$deptData = [];
foreach($results as $row){
    $deptData[$row->FacultyName][] = $row->DepartmentName;
}

$deptJson = json_encode($deptData);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMS Admin Subject Creation</title>
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

<?php include('includes/topbar.php');?>

<div class="content-wrapper">
<div class="content-container">

<?php include('includes/leftbar.php');?>

<div class="main-page">
<div class="container-fluid">

<div class="row page-title-div">
<div class="col-md-6">
<h2 class="title">Subject Creation</h2>
</div>
</div>

<div class="row breadcrumb-div">
<div class="col-md-6">
<ul class="breadcrumb">
<li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
<li>Subjects</li>
<li class="active">Create Subject</li>
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
<h5>Create Subject</h5>
</div>
</div>

<div class="panel-body">

<?php if($msg != ""){ ?>
<div class="alert alert-success left-icon-alert" role="alert">
<strong>Well done!</strong>
<?php echo htmlentities($msg); ?>
</div>
<?php } ?>

<?php if($error != ""){ ?>
<div class="alert alert-danger left-icon-alert" role="alert">
<strong>Oh snap!</strong>
<?php echo htmlentities($error); ?>
</div>
<?php } ?>

<form class="form-horizontal" method="post">

<div class="form-group">
<label class="col-sm-2 control-label">Subject Name</label>
<div class="col-sm-10">
<input type="text"
name="subjectname"
class="form-control"
placeholder="Subject Name"
required>
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label">Subject Code</label>
<div class="col-sm-10">
<input type="text"
name="subjectcode"
class="form-control"
placeholder="Subject Code"
required>
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label">Faculty</label>
<div class="col-sm-10">
<select name="facultyname"
id="faculty"
class="form-control"
required>

<option value="">Select Faculty</option>

<?php foreach($faculties as $faculty){ ?>
<option value="<?php echo htmlentities($faculty->FacultyName); ?>">
<?php echo htmlentities($faculty->FacultyName); ?>
</option>
<?php } ?>

</select>
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label">Department</label>
<div class="col-sm-10">
<select name="departmentname"
id="department"
class="form-control"
required>
<option value="">Select Department</option>
</select>
</div>
</div>

<div class="form-group">
<div class="col-sm-offset-2 col-sm-10">
<button type="submit"
name="submit"
class="btn btn-primary">
Submit
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

<script src="js/jquery/jquery-2.2.4.min.js"></script>
<script src="js/bootstrap/bootstrap.min.js"></script>
<script src="js/pace/pace.min.js"></script>
<script src="js/lobipanel/lobipanel.min.js"></script>
<script src="js/iscroll/iscroll.js"></script>
<script src="js/prism/prism.js"></script>
<script src="js/select2/select2.min.js"></script>
<script src="js/main.js"></script>

<script>

    var departments = <?php echo $deptJson; ?>;

    document.getElementById("faculty").addEventListener("change", function () {

        var selectedFaculty = this.value;

        var departmentSelect = document.getElementById("department");

        departmentSelect.innerHTML = '<option value="">Select Department</option>';

        if(departments[selectedFaculty]){

            departments[selectedFaculty].forEach(function(dep){

                departmentSelect.innerHTML +=
                '<option value="' + dep + '">' + dep + '</option>';

            });

        }

    });

</script>

</body>
</html>

<?php } ?>