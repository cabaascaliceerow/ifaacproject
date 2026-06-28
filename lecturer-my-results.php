<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['llogin']) == ""){
    header("Location: lecturer-login.php"); exit;
}

$lecturer_id = $_SESSION['lecturer_id'];

$sq = $dbh->prepare("
    SELECT st.StudentName, st.RollId,
           sub.SubjectName, c.ClassName,
           r.ExamType, r.marks, r.AcademicYear, r.PostingDate
    FROM   tblresult r
    JOIN   tblstudents  st  ON st.StudentId = r.StudentId
    JOIN   tblsubjects  sub ON sub.id        = r.SubjectId
    JOIN   tblclasses   c   ON c.id          = r.ClassId
    JOIN   tblsubjectcombination sc
           ON sc.SubjectId = r.SubjectId
          AND sc.ClassId   = r.ClassId
          AND sc.LecturerId = :lid
    ORDER  BY r.PostingDate DESC
");
$sq->execute([':lid' => $lecturer_id]);
$results = $sq->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Results Entered | Kownayn</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/animate-css/animate.min.css">
    <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/modernizr/modernizr.min.js"></script>
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <style>
        thead tr { background:#1F3864; color:#fff; }
        .badge-A  { background:#2980b9; color:#fff; padding:3px 10px; border-radius:10px; }
        .badge-B  { background:#27ae60; color:#fff; padding:3px 10px; border-radius:10px; }
        .badge-C  { background:#f39c12; color:#fff; padding:3px 10px; border-radius:10px; }
        .badge-D  { background:#e67e22; color:#fff; padding:3px 10px; border-radius:10px; }
        .badge-F  { background:#e74c3c; color:#fff; padding:3px 10px; border-radius:10px; }
    </style>
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
                    <div class="col-md-6"><h2 class="title">Results I Entered</h2></div>
                </div>
                <div class="row breadcrumb-div">
                    <div class="col-md-6">
                        <ul class="breadcrumb">
                            <li><a href="lecturer-dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                            <li class="active">Results Entered</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel">
                            <div class="panel-body">
                                <?php if(count($results) == 0): ?>
                                <div class="alert alert-warning">
                                    <i class="fa fa-exclamation-triangle"></i>
                                    No results entered yet.
                                </div>
                                <?php else: ?>
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Student Name</th>
                                            <th>Roll ID</th>
                                            <th>Subject</th>
                                            <th>Semester</th>
                                            <th>Exam Type</th>
                                            <th>Marks</th>
                                            <th>Grade</th>
                                            <th>Academic Year</th>
                                            <th>Posted Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i=1; foreach($results as $r):
                                        $m = $r->marks;
                                        if($m >= 85)     { $grade="A"; $cls="badge-A"; }
                                        elseif($m >= 75) { $grade="B"; $cls="badge-B"; }
                                        elseif($m >= 65) { $grade="C"; $cls="badge-C"; }
                                        elseif($m >= 50) { $grade="D"; $cls="badge-D"; }
                                        else             { $grade="F"; $cls="badge-F"; }
                                    ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><strong><?php echo htmlentities($r->StudentName); ?></strong></td>
                                            <td><?php echo htmlentities($r->RollId); ?></td>
                                            <td><?php echo htmlentities($r->SubjectName); ?></td>
                                            <td><?php echo htmlentities($r->ClassName); ?></td>
                                            <td><?php echo htmlentities($r->ExamType); ?></td>
                                            <td><strong><?php echo $r->marks; ?></strong></td>
                                            <td><span class="<?php echo $cls; ?>"><?php echo $grade; ?></span></td>
                                            <td><?php echo htmlentities($r->AcademicYear); ?></td>
                                            <td><?php echo date('d-m-Y', strtotime($r->PostingDate)); ?></td>
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