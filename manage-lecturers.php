<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin']) == ""){
    header("Location: index.php");
    exit;
}

$msg = ""; $error = "";

// ── Enable / Disable / Delete ──
if(isset($_GET['action']) && isset($_GET['id'])){
    $lid = intval($_GET['id']);
    if($_GET['action'] == 'enable'){
        $dbh->prepare("UPDATE tbllecturer SET Status=1 WHERE id=:id")
            ->execute([':id' => $lid]);
        $msg = "Lecturer enabled successfully.";
    }
    elseif($_GET['action'] == 'disable'){
        $dbh->prepare("UPDATE tbllecturer SET Status=0 WHERE id=:id")
            ->execute([':id' => $lid]);
        $msg = "Lecturer disabled successfully.";
    }
    elseif($_GET['action'] == 'delete'){
        $dbh->prepare("DELETE FROM tbllecturer WHERE id=:id")
            ->execute([':id' => $lid]);
        $msg = "Lecturer deleted successfully.";
    }
}

// ── Fetch all lecturers ──
$lecturers = $dbh->query("
    SELECT l.*, f.FacultyName, d.DepartmentName
    FROM   tbllecturer l
    LEFT   JOIN tblfaculty    f ON f.id = l.FacultyId
    LEFT   JOIN tbldepartment d ON d.id = l.DepartmentId
    ORDER  BY l.id DESC
")->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Lecturers | Kownayn</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/animate-css/animate.min.css">
    <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/modernizr/modernizr.min.js"></script>
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <style>
        .badge-active {
            background: #27ae60; color: #fff;
            padding: 3px 12px; border-radius: 10px; font-size: 12px;
        }
        .badge-inactive {
            background: #e74c3c; color: #fff;
            padding: 3px 12px; border-radius: 10px; font-size: 12px;
        }
        thead tr { background: #1F3864; color: #fff; }
    </style>
</head>
<body class="top-navbar-fixed">
<div class="main-wrapper">

<?php include('includes/topbar.php'); ?>

<div class="content-wrapper">
    <div class="content-container">
        <?php include('includes/leftbar.php'); ?>

        <div class="main-page">

            <!-- PAGE TITLE -->
            <div class="container-fluid">
                <div class="row page-title-div">
                    <div class="col-md-6">
                        <h2 class="title">Manage Lecturers</h2>
                    </div>
                    <div class="col-md-6 text-right" style="padding-top:10px;">
                        <a href="add-lecturer.php" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Add New Lecturer
                        </a>
                    </div>
                </div>
                <div class="row breadcrumb-div">
                    <div class="col-md-6">
                        <ul class="breadcrumb">
                            <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                            <li class="active">Manage Lecturers</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- TABLE -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel">
                            <div class="panel-body">

                                <?php if($msg): ?>
                                <div class="alert alert-success alert-dismissible">
                                    <button class="close" data-dismiss="alert">&times;</button>
                                    <strong>Success!</strong> <?php echo htmlentities($msg); ?>
                                </div>
                                <?php endif; ?>

                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Full Name</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Faculty</th>
                                            <th>Department</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(count($lecturers) == 0): ?>
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">
                                                No lecturers found.
                                                <a href="add-lecturer.php">Add one now</a>.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php $i = 1; foreach($lecturers as $l): ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo htmlentities($l->LecturerName); ?></td>
                                            <td>
                                                <strong>
                                                    <?php echo htmlentities($l->UserName); ?>
                                                </strong>
                                            </td>
                                            <td><?php echo htmlentities($l->EmailId); ?></td>
                                            <td><?php echo htmlentities($l->FacultyName  ?? '-'); ?></td>
                                            <td><?php echo htmlentities($l->DepartmentName ?? '-'); ?></td>
                                            <td>
                                                <?php if($l->Status == 1): ?>
                                                    <span class="badge-active">Active</span>
                                                <?php else: ?>
                                                    <span class="badge-inactive">Disabled</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($l->Status == 1): ?>
                                                <a href="manage-lecturers.php?action=disable&id=<?php echo $l->id; ?>"
                                                   class="btn btn-warning btn-xs"
                                                   onclick="return confirm('Disable this lecturer?')">
                                                    <i class="fa fa-ban"></i> Disable
                                                </a>
                                                <?php else: ?>
                                                <a href="manage-lecturers.php?action=enable&id=<?php echo $l->id; ?>"
                                                   class="btn btn-success btn-xs">
                                                    <i class="fa fa-check"></i> Enable
                                                </a>
                                                <?php endif; ?>

                                                <a href="manage-lecturers.php?action=delete&id=<?php echo $l->id; ?>"
                                                   class="btn btn-danger btn-xs"
                                                   onclick="return confirm('Delete this lecturer permanently?')">
                                                    <i class="fa fa-trash"></i> Delete
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /TABLE -->

        </div>
    </div>
</div>
</div>

<script src="js/bootstrap/bootstrap.min.js"></script>
<script src="js/pace/pace.min.js"></script>
<script src="js/lobipanel/lobipanel.min.js"></script>
<script src="js/iscroll/iscroll.js"></script>
<script src="js/main.js"></script>
<script>
setTimeout(function(){ $('.alert').fadeOut('slow'); }, 4000);
</script>
</body>
</html>