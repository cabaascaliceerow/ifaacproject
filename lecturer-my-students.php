<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['llogin']) == ""){
    header("Location: lecturer-login.php"); exit;
}

$lecturer_id = $_SESSION['lecturer_id'];

$info = $dbh->prepare("SELECT FacultyId, DepartmentId FROM tbllecturer WHERE id=:id");
$info->execute([':id' => $lecturer_id]);
$lec = $info->fetch(PDO::FETCH_OBJ);

$sq = $dbh->prepare("
    SELECT st.*, c.ClassName, f.FacultyName, d.DepartmentName
    FROM   tblstudents st
    JOIN   tblclasses   c ON c.id = st.ClassId
    JOIN   tblfaculty   f ON f.id = st.Faculty
    JOIN   tbldepartment d ON d.id = st.Department
    WHERE  st.Faculty     = :fid
      AND  st.Department  = :did
      AND  st.Status      = 1
    ORDER  BY st.StudentName
");
$sq->execute([':fid' => $lec->FacultyId, ':did' => $lec->DepartmentId]);
$students = $sq->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Students | Kownayn</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/animate-css/animate.min.css">
    <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/modernizr/modernizr.min.js"></script>
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <style> thead tr { background:#1F3864; color:#fff; } </style>
</head>
<body class="top-navbar-fixed">
<div class="main-wrapper">
<?php include('includes/topbar.php'); ?>
<div class="content-wrapper">
    <div class="content-container">
        <?php include('includes/lecturer-leftbar.php'); ?>
        <div class="main-page">
            <div class="container-fluid">
                <div class="row page-title-div">
                    <div class="col-md-6"><h2 class="title">Students in My Department</h2></div>
                </div>
                <div class="row breadcrumb-div">
                    <div class="col-md-6">
                        <ul class="breadcrumb">
                            <li><a href="lecturer-dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                            <li class="active">My Students</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel">
                            <div class="panel-body">
                                <?php if(count($students) == 0): ?>
                                <div class="alert alert-warning">
                                    <i class="fa fa-exclamation-triangle"></i>
                                    No students found in your department.
                                </div>
                                <?php else: ?>
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Student Name</th>
                                            <th>Roll ID</th>
                                            <th>Gender</th>
                                            <th>Semester</th>
                                            <th>Faculty</th>
                                            <th>Department</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i=1; foreach($students as $s): ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><strong><?php echo htmlentities($s->StudentName); ?></strong></td>
                                            <td><?php echo htmlentities($s->RollId); ?></td>
                                            <td><?php echo htmlentities($s->Gender); ?></td>
                                            <td><?php echo htmlentities($s->ClassName); ?></td>
                                            <td><?php echo htmlentities($s->FacultyName); ?></td>
                                            <td><?php echo htmlentities($s->DepartmentName); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php endif; ?>
                            </div>
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
<script src="js/main.js"></script>
</body>
</html>