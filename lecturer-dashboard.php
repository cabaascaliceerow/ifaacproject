<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['llogin']) == ""){
    header("Location: lecturer-login.php");
    exit;
}

$lecturer_id   = $_SESSION['lecturer_id'];
$lecturer_name = $_SESSION['lecturer_name'];

// ── Hel Faculty + Department Lecturer-ka ──
$info = $dbh->prepare("
    SELECT l.*, f.FacultyName, d.DepartmentName
    FROM   tbllecturer l
    LEFT   JOIN tblfaculty    f ON f.id = l.FacultyId
    LEFT   JOIN tbldepartment d ON d.id = l.DepartmentId
    WHERE  l.id = :id
");
$info->execute([':id' => $lecturer_id]);
$lec = $info->fetch(PDO::FETCH_OBJ);

// ── Tirada Subjects-ka loo xilsaaray ──
$s1 = $dbh->prepare("
    SELECT COUNT(*) FROM tblsubjectcombination
    WHERE LecturerId = :id AND status = 1
");
$s1->execute([':id' => $lecturer_id]);
$total_subjects = $s1->fetchColumn();

// ── Tirada Students-ka Faculty+Dept-kiisa ──
$s2 = $dbh->prepare("
    SELECT COUNT(*) FROM tblstudents
    WHERE Faculty = :fid AND Department = :did AND Status = 1
");
$s2->execute([':fid' => $lec->FacultyId, ':did' => $lec->DepartmentId]);
$total_students = $s2->fetchColumn();

// ── Tirada Results-ka Lecturer-ku galay ──
$s3 = $dbh->prepare("
    SELECT COUNT(DISTINCT r.id)
    FROM   tblresult r
    JOIN   tblsubjectcombination sc
           ON sc.SubjectId  = r.SubjectId
          AND sc.LecturerId = :id
");
$s3->execute([':id' => $lecturer_id]);
$total_results = $s3->fetchColumn();

// ── Subjects-ka loo xilsaaray (list) ──
$sq = $dbh->prepare("
    SELECT s.SubjectName, s.SubjectCode, c.ClassName
    FROM   tblsubjectcombination sc
    JOIN   tblsubjects s ON s.id = sc.SubjectId
    JOIN   tblclasses  c ON c.id = sc.ClassId
    WHERE  sc.LecturerId = :id AND sc.status = 1
    ORDER  BY c.id, s.SubjectName
");
$sq->execute([':id' => $lecturer_id]);
$subjects = $sq->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lecturer Dashboard | Kownayn</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/animate-css/animate.min.css">
    <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/modernizr/modernizr.min.js"></script>
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <style>
    .stat-card {
        border-radius: 10px;
        padding: 20px 24px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }
    .stat-card .num  { font-size: 38px; font-weight: 700; color: #fff; }
    .stat-card .lbl  { font-size: 13px; opacity: .95; color: #fff; }
    .stat-card .icon { font-size: 44px; opacity: .75; color: #fff; }

    .card-blue   { background: linear-gradient(135deg,#2980b9,#1a5276); }
    .card-green  { background: linear-gradient(135deg,#27ae60,#1e8449); }
    .card-orange { background: linear-gradient(135deg,#e67e22,#ca6f1e); }

    /* ✅ WELCOME BOX FIX */
    .welcome-box {
        background: linear-gradient(135deg, #1F3864, #2E75B6);
        color: #ffffff !important;
        border-radius: 10px;
        padding: 20px 28px;
        margin-bottom: 24px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .welcome-box h4 {
        margin: 0 0 6px;
        color: #ffffff !important;
        font-size: 20px;
        font-weight: bold;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
    }
    .welcome-box p {
        margin: 0;
        color: #ffffff !important;
        font-size: 13px;
        opacity: 1;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
    }
    .welcome-box i {
        color: #ffffff !important;
    }

    /* ✅ TABLE HEADER FIX */
    thead tr {
        background: #1F3864 !important;
        color: #ffffff !important;
    }
    thead tr th {
        color: #ffffff !important;
        font-weight: bold;
        font-size: 13px;
        border-color: #2E75B6 !important;
    }

    /* ✅ TABLE HOVER */
    .table-hover tbody tr:hover {
        background-color: #EEF4FF !important;
    }

    /* ✅ PANEL TITLE */
    .panel-title i {
        color: #1F3864;
    }
</style>
</head>
<body class="top-navbar-fixed">
<div class="main-wrapper">

<?php include('includes/topbar.php'); ?>

<div class="content-wrapper">
    <div class="content-container">
        <?php include('includes/lecturer-leftbar.php'); ?>

        <div class="main-page">

            <!-- PAGE TITLE -->
            <div class="container-fluid">
                <div class="row page-title-div">
                    <div class="col-md-6">
                        <h2 class="title">Dashboard</h2>
                    </div>
                </div>
                <div class="row breadcrumb-div">
                    <div class="col-md-6">
                        <ul class="breadcrumb">
                            <li><i class="fa fa-home"></i> Home</li>
                            <li class="active">Dashboard</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="container-fluid">

            <div class="welcome-box">
    <div style="display:flex; align-items:center; gap:15px;">
        <div style="font-size:48px; color:#ffffff; opacity:0.9;">
            <i class="fa fa-user-circle"></i>
        </div>
        <div>
            <h4 style="color:#ffffff; font-size:22px; font-weight:bold; margin:0 0 6px 0; text-shadow:1px 1px 3px rgba(0,0,0,0.4);">
                Welcome, <?php echo htmlentities($lecturer_name); ?>
            </h4>
            <p style="color:#ffffff; margin:0; font-size:13px; opacity:0.95;">
                <i class="fa fa-university"></i>
                <strong><?php echo htmlentities($lec->FacultyName ?? '-'); ?></strong>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <i class="fa fa-sitemap"></i>
                <strong><?php echo htmlentities($lec->DepartmentName ?? '-'); ?></strong>
            </p>
        </div>
    </div>
</div>

            <!-- Stat Cards -->
<div class="row">
    <div class="col-md-4">
        <a href="lecturer-my-subjects.php" style="text-decoration:none;">
        <div class="stat-card card-blue">
            <div>
                <div class="num"><?php echo $total_subjects; ?></div>
                <div class="lbl">Assigned Subjects</div>
            </div>
            <div class="icon"><i class="fa fa-book"></i></div>
        </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="lecturer-my-students.php" style="text-decoration:none;">
        <div class="stat-card card-green">
            <div>
                <div class="num"><?php echo $total_students; ?></div>
                <div class="lbl">Students in My Department</div>
            </div>
            <div class="icon"><i class="fa fa-users"></i></div>
        </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="lecturer-my-results.php" style="text-decoration:none;">
        <div class="stat-card card-orange">
            <div>
                <div class="num"><?php echo $total_results; ?></div>
                <div class="lbl">Results Entered</div>
            </div>
            <div class="icon"><i class="fa fa-check-square-o"></i></div>
        </div>
        </a>
    </div>
</div>
                <!-- Assigned Subjects Table -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <i class="fa fa-book"></i> My Assigned Subjects
                                </h3>
                            </div>
                            <div class="panel-body">

                                <?php if(count($subjects) == 0): ?>
                                <div class="alert alert-warning">
                                    <i class="fa fa-exclamation-triangle"></i>
                                    No subjects assigned yet. Contact the Administrator.
                                </div>
                                <?php else: ?>
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Subject Name</th>
                                            <th>Subject Code</th>
                                            <th>Semester</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i=1; foreach($subjects as $s): ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo htmlentities($s->SubjectName); ?></td>
                                            <td><?php echo htmlentities($s->SubjectCode); ?></td>
                                            <td><?php echo htmlentities($s->ClassName); ?></td>
                                            <td>
                                                <a href="lecturer-enter-marks.php"
                                                   class="btn btn-primary btn-xs">
                                                    <i class="fa fa-pencil"></i> Enter Marks
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Assigned Subjects -->

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