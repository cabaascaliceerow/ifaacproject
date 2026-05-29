<?php
/**
 * add-subject-combination.php  (SAXAN - DHAMEYSTIRAN)
 * ---------------------------------------------------------------
 * Hadda waxaa ku jira Faculty + Department
 * Maadada Faculty + Department + Semester loogu gaar yahay
 * ---------------------------------------------------------------
 */
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
    exit;
}

$msg   = "";
$error = "";

if (isset($_POST['submit'])) {

    $class      = intval($_POST['class']);
    $subject    = intval($_POST['subject']);
    $faculty_id = intval($_POST['faculty']);
    $dept_id    = intval($_POST['department']);
    $status     = 1;

    if (!$class || !$subject || !$faculty_id || !$dept_id) {
        $error = "Fadlan dhammaan fields buuxi!";
    } else {
        /* -- Hubi combination horeba ma jirto? -- */
        $check = $dbh->prepare("
            SELECT id FROM tblsubjectcombination
            WHERE  ClassId      = :class
              AND  SubjectId    = :subject
              AND  FacultyId    = :fid
              AND  DepartmentId = :did
        ");
        $check->execute([
            ':class'   => $class,
            ':subject' => $subject,
            ':fid'     => $faculty_id,
            ':did'     => $dept_id,
        ]);

        if ($check->rowCount() > 0) {
            $error = "Combination-kan horeba waa la galay! (Semester + Subject + Faculty + Department isku mid ah)";
        } else {
            $sql = "INSERT INTO tblsubjectcombination
                        (ClassId, SubjectId, FacultyId, DepartmentId, status)
                    VALUES
                        (:class, :subject, :fid, :did, :status)";
            $query = $dbh->prepare($sql);
            $query->execute([
                ':class'   => $class,
                ':subject' => $subject,
                ':fid'     => $faculty_id,
                ':did'     => $dept_id,
                ':status'  => $status,
            ]);

            if ($dbh->lastInsertId()) {
                $msg = "Subject Combination si guul leh ayaa loo keydsaday!";
            } else {
                $error = "Wax khalad ah ayaa dhacay. Mar kale isku day.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMS Admin | Subject Combination</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/animate-css/animate.min.css">
    <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css">
    <link rel="stylesheet" href="css/prism/prism.css">
    <link rel="stylesheet" href="css/select2/select2.min.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/modernizr/modernizr.min.js"></script>
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
                            <h2 class="title">Add Subject Combination</h2>
                        </div>
                    </div>
                    <div class="row breadcrumb-div">
                        <div class="col-md-6">
                            <ul class="breadcrumb">
                                <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                <li>Subjects</li>
                                <li class="active">Add Subject Combination</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- FORM PANEL -->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="panel">
                                <div class="panel-heading">
                                    <div class="panel-title">
                                        <h5><i class="fa fa-link"></i>&nbsp; Add Subject Combination</h5>
                                    </div>
                                </div>
                                <div class="panel-body">

                                    <!-- ALERTS -->
                                    <?php if ($msg) : ?>
                                    <div class="alert alert-success alert-dismissible">
                                        <button class="close" data-dismiss="alert">&times;</button>
                                        <strong><i class="fa fa-check-circle"></i> Guul!</strong>
                                        <?php echo htmlentities($msg); ?>
                                    </div>
                                    <?php endif; ?>
                                    <?php if ($error) : ?>
                                    <div class="alert alert-danger alert-dismissible">
                                        <button class="close" data-dismiss="alert">&times;</button>
                                        <strong><i class="fa fa-exclamation-circle"></i> Khalad!</strong>
                                        <?php echo htmlentities($error); ?>
                                    </div>
                                    <?php endif; ?>

                                    <form class="form-horizontal" method="post">

                                        <!-- Faculty -->
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">
                                                <i class="fa fa-university"></i> Faculty
                                            </label>
                                            <div class="col-sm-9">
                                                <select name="faculty" id="sel-faculty"
                                                        class="form-control" required>
                                                    <option value="">-- Faculty dooro --</option>
                                                    <?php
                                                    $q = $dbh->query("SELECT * FROM tblfaculty ORDER BY FacultyName");
                                                    foreach ($q->fetchAll(PDO::FETCH_OBJ) as $f) {
                                                        echo '<option value="' . (int)$f->id . '">'
                                                            . htmlentities($f->FacultyName) . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Department (AJAX) -->
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">
                                                <i class="fa fa-building-o"></i> Department
                                            </label>
                                            <div class="col-sm-9">
                                                <select name="department" id="sel-dept"
                                                        class="form-control" required>
                                                    <option value="">-- Marka hore Faculty dooro --</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Semester -->
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">
                                                <i class="fa fa-list-ol"></i> Semester
                                            </label>
                                            <div class="col-sm-9">
                                                <select name="class" class="form-control" required>
                                                    <option value="">-- Semester dooro --</option>
                                                    <?php
                                                    $q = $dbh->query("SELECT * FROM tblclasses ORDER BY id");
                                                    foreach ($q->fetchAll(PDO::FETCH_OBJ) as $c) {
                                                        echo '<option value="' . (int)$c->id . '">'
                                                            . htmlentities($c->ClassName) . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Subject -->
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">
                                                <i class="fa fa-book"></i> Subject
                                            </label>
                                            <div class="col-sm-9">
                                              <select name="subject" id="sel-subject" class="form-control" required>
    <option value="">
        -- Marka hore Faculty & Department dooro --
    </option>
</select>
                                            </div>
                                        </div>

                                        <!-- Submit -->
                                        <div class="form-group">
                                            <div class="col-sm-offset-3 col-sm-9">
                                                <button type="submit" name="submit"
                                                        class="btn btn-primary">
                                                    <i class="fa fa-plus"></i>&nbsp; Ku dar Combination
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
$(function () {

    /* Auto-hide alerts */
    setTimeout(function () {
        $('.alert').fadeOut('slow');
    }, 4000);


    /* Faculty => load Departments */
    $('#sel-faculty').on('change', function () {

        var fid = $(this).val();
        var dst = $('#sel-dept');

        dst.html('<option value="">Loading...</option>');

        if (!fid) {
            dst.html('<option value="">-- Marka hore Faculty dooro --</option>');
            return;
        }

        $.ajax({
            url: 'get-departments.php',
            type: 'GET',
            data: { faculty_id: fid },
            dataType: 'json',

            success: function (data) {

                var opts = '<option value="">-- Department dooro --</option>';

                if (data.length > 0) {

                    $.each(data, function (i, d) {

                        opts += '<option value="' + d.id + '">'
                            + d.DepartmentName +
                            '</option>';

                    });

                } else {

                    opts = '<option value="">Department lama helin</option>';
                }

                dst.html(opts);
            },

            error: function () {

                dst.html('<option value="">Khalad ayaa dhacay</option>');
            }
        });

    });


    /* Department => load Subjects */
    $('#sel-dept').on('change', function () {

        var facultyText = $('#sel-faculty option:selected').text();
        var deptText    = $('#sel-dept option:selected').text();

        var subjectBox = $('#sel-subject');

        subjectBox.html('<option value="">Loading...</option>');

        if (!facultyText || !deptText) {

            subjectBox.html('<option value="">-- Subject lama helin --</option>');
            return;
        }

        $.ajax({

            url: 'get-subjects.php',
            type: 'GET',

            data: {
                faculty: facultyText,
                department: deptText
            },

            dataType: 'json',

            success: function (data) {

                var options = '<option value="">-- Subject dooro --</option>';

                if (data.length > 0) {

                    $.each(data, function (i, s) {

                        options += '<option value="' + s.id + '">'
                            + s.SubjectName + ' (' + s.SubjectCode + ')'
                            + '</option>';

                    });

                } else {

                    options = '<option value="">Subject lama helin</option>';
                }

                subjectBox.html(options);
            },

            error: function () {

                subjectBox.html('<option value="">Khalad ayaa dhacay</option>');
            }

        });

    });

});
</script>

</body>
</html>
 ?>
