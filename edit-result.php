<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])=="") {
    header("Location: index.php");
    exit();
}

$msg = "";
$error = "";

if(!isset($_GET['stid']) || !isset($_GET['classid'])){
    echo "Invalid Access";
    exit();
}

$stid = intval($_GET['stid']);
$classid = intval($_GET['classid']);

// UPDATE
if(isset($_POST['submit'])) {
    $rowid = $_POST['id'];
    $marks = $_POST['marks'];

    foreach($rowid as $count => $id){
        $mrks = $marks[$count];
        $iid = $rowid[$count];

        $sql = "UPDATE tblresult SET marks=:mrks WHERE id=:iid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':mrks',$mrks,PDO::PARAM_STR);
        $query->bindParam(':iid',$iid,PDO::PARAM_STR);
        $query->execute();
    }
    $msg = "Result info updated successfully";
}

// FETCH (include student info)
$sql = "SELECT tblresult.id as resultid,
               tblsubjects.SubjectName,
               tblresult.marks,
               tblclasses.ClassName,
               tblresult.AcademicYear,
               tblresult.ExamType,
               tblstudents.StudentName,
               tblstudents.RollId
        FROM tblresult
        JOIN tblsubjects ON tblsubjects.id=tblresult.SubjectId
        JOIN tblclasses ON tblclasses.id=tblresult.ClassId
        JOIN tblstudents ON tblstudents.StudentId=tblresult.StudentId
        WHERE tblresult.StudentId=:stid AND tblresult.ClassId=:classid
        ORDER BY tblsubjects.SubjectName ASC";

$query = $dbh->prepare($sql);
$query->bindParam(':stid',$stid,PDO::PARAM_STR);
$query->bindParam(':classid',$classid,PDO::PARAM_STR);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);

// GROUP Midterm & Final
$subjects = [];
foreach($results as $row){
    $subjects[$row->SubjectName][$row->ExamType] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>SMS Admin | Update Result</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/font-awesome.min.css">
<link rel="stylesheet" href="css/animate-css/animate.min.css">
<link rel="stylesheet" href="css/lobipanel/lobipanel.min.css">
<link rel="stylesheet" href="css/prism/prism.css">
<link rel="stylesheet" href="css/select2/select2.min.css">
<link rel="stylesheet" href="css/main.css">

<style>
.table th, .table td {
    text-align: center;
    vertical-align: middle;
}
.table-responsive {
    width: 100%;
    overflow-x: auto;
}

@media (max-width: 768px) {
    .table th, .table td {
        font-size: 12px;
        padding: 6px;
    }
    input.form-control {
        padding: 4px;
        font-size: 12px;
    }
}
</style>
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
        <h2 class="title">Update Student Result</h2>
    </div>
</div>

</div>

<div class="container-fluid">
<div class="row">
<div class="col-md-12">

<div class="panel">
<div class="panel-heading">
<div class="panel-title">
<h5>Edit Result (Midterm & Final)</h5>
</div>
</div>

<div class="panel-body">

<?php if($msg){ ?>
<div class="alert alert-success"><?php echo htmlentities($msg); ?></div>
<?php } ?>

<form method="post">

<?php if(count($results)>0){ ?>

<p><strong>Name:</strong> <?php echo htmlentities($results[0]->StudentName); ?></p>
<p><strong>Roll ID:</strong> <?php echo htmlentities($results[0]->RollId); ?></p>

<p><strong>Academic Year:</strong> <?php echo htmlentities($results[0]->AcademicYear); ?></p>
<p><strong>Semester:</strong> <?php echo htmlentities($results[0]->ClassName); ?></p>

<div class="table-responsive">
<table class="table table-bordered">
<thead>
<tr>
    <th>#</th>
    <th>Subject</th>
    <th>Midterm (40)</th>
    <th>Final (60)</th>
</tr>
</thead>
<tbody>

<?php $i=1; foreach($subjects as $subjectName => $types){ ?>
<tr>
    <td><?php echo $i++; ?></td>
    <td><?php echo htmlentities($subjectName); ?></td>

    <td>
        <input type="hidden" name="id[]" value="<?php echo $types['Midterm']->resultid ?? ''; ?>">
        <input type="number" name="marks[]" class="form-control"
               value="<?php echo $types['Midterm']->marks ?? ''; ?>" max="40">
    </td>

    <td>
        <input type="hidden" name="id[]" value="<?php echo $types['Final']->resultid ?? ''; ?>">
        <input type="number" name="marks[]" class="form-control"
               value="<?php echo $types['Final']->marks ?? ''; ?>" max="60">
    </td>
</tr>
<?php } ?>

</tbody>
</table>
</div>

<?php } ?>

<div class="form-group">
    <button type="submit" name="submit" class="btn btn-primary">Update Result</button>
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

<script src="js/jquery/jquery-2.2.4.min.js"></script>
<script src="js/bootstrap/bootstrap.min.js"></script>
<script src="js/pace/pace.min.js"></script>
<script src="js/lobipanel/lobipanel.min.js"></script>
<script src="js/iscroll/iscroll.js"></script>
<script src="js/prism/prism.js"></script>
<script src="js/select2/select2.min.js"></script>
<script src="js/main.js"></script>

</body>
</html>
