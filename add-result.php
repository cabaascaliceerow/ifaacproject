<?php
/**
 * addresult.php - SAXAN OO DHAMEYSTIRAN
 *
 * XALKA DHIBAATADA:
 *   Hore: Semester doortid => ardayda oo dhan + maadooyinka oo dhan soo muuqan jireen
 *   Hadda: Faculty + Department + Semester waa in la doortaa =>
 *          oo keliya ardayda department kaas + maadooyinka department kaas ayaa soo muuqda
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

/* ================================================================
   FORM SUBMIT: Natiijada keydi
================================================================ */
if (isset($_POST['submit'])) {

    $class        = intval($_POST['class']);       // Semester ID
    $studentid    = intval($_POST['studentid']);
    $academicyear = $_POST['academicyear'];
    $examtype     = $_POST['examtype'];
    $faculty_id   = intval($_POST['faculty']);
    $dept_id      = intval($_POST['department']);
    $marks        = isset($_POST['marks']) ? $_POST['marks'] : [];

    // --- Xaqiiji fields ---
    if (!$class || !$studentid || !$academicyear || !$examtype || !$faculty_id || !$dept_id) {
        $error = "Fadlan dhammaan meelaha buuxi!";

    } else {

        // --- Hel maadooyinka: Faculty + Department + Semester ---
        $stmt = $dbh->prepare("
            SELECT s.id
            FROM   tblsubjectcombination sc
            JOIN   tblsubjects s ON s.id = sc.SubjectId
            WHERE  sc.ClassId      = :cid
              AND  sc.FacultyId    = :fid
              AND  sc.DepartmentId = :did
              AND  sc.status       = 1
            ORDER  BY s.SubjectName
        ");
        $stmt->execute([':cid' => $class, ':fid' => $faculty_id, ':did' => $dept_id]);
        $subjects = $stmt->fetchAll(PDO::FETCH_COLUMN); // array of subject IDs

        if (count($subjects) == 0) {
            $error = "Maado lama helin semester + faculty + department kaas!";

        } elseif (count($marks) != count($subjects)) {
            $error = "Tirada marks iyo maadooyinka ma is-waafaqsana!";

        } else {

            // --- Hubi marks horeba la gelin ---
            $check = $dbh->prepare("
                SELECT id FROM tblresult
                WHERE  StudentId    = :sid
                  AND  ClassId      = :cid
                  AND  AcademicYear = :ay
                  AND  ExamType     = :exam
                  AND  SubjectId    = :subj
            ");

            $duplicate = false;
            foreach ($subjects as $subj_id) {
                $check->execute([
                    ':sid'  => $studentid,
                    ':cid'  => $class,
                    ':ay'   => $academicyear,
                    ':exam' => $examtype,
                    ':subj' => $subj_id,
                ]);
                if ($check->rowCount() > 0) {
                    $duplicate = true;
                    break;
                }
            }

            if ($duplicate) {
                $error = "Marks horeba waa la garay arday kaas - ExamType + Semester + AcademicYear isku mid ah!";

            } else {
                // --- Geli marks ---
                $insert = $dbh->prepare("
                    INSERT INTO tblresult
                        (StudentId, ClassId, AcademicYear, ExamType, SubjectId, marks)
                    VALUES
                        (:studentid, :class, :ay, :exam, :sid, :marks)
                ");

                for ($i = 0; $i < count($subjects); $i++) {
                    $insert->execute([
                        ':studentid' => $studentid,
                        ':class'     => $class,
                        ':ay'        => $academicyear,
                        ':exam'      => $examtype,
                        ':sid'       => $subjects[$i],
                        ':marks'     => intval($marks[$i]),
                    ]);
                }
                $msg = "Natiijadu si guul leh ayaa loo keydsaday!";
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

    <style>
        /* ---- Grade badge ---- */
        .grade-badge {
            display: inline-block;
            min-width: 38px; height: 28px; line-height: 28px;
            text-align: center; border-radius: 14px;
            font-size: 11px; font-weight: 700;
            padding: 0 8px; background: #ddd; color: #555;
            transition: background .2s, color .2s;
        }
        .badge-A  { background: #2980b9; color: #fff; }
        .badge-B  { background: #27ae60; color: #fff; }
        .badge-C  { background: #f39c12; color: #fff; }
        .badge-D  { background: #e67e22; color: #fff; }
        .badge-F  { background: #e74c3c; color: #fff; }

        /* ---- Subject row ---- */
        .subject-row {
            display: flex; align-items: center;
            justify-content: space-between;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 10px 14px;
            margin-bottom: 8px;
        }
        .subject-row:hover { background: #eef4ff; }
        .subject-row .marks-input {
            width: 90px !important;
            text-align: center;
            font-weight: bold;
        }

        /* ---- Spinner ---- */
        .ajax-spin { display:none; color:#888; font-size:13px; padding:6px 0; }

        /* ---- Marks summary ---- */
        #marks-summary {
            display: none;
            background: #eaf4ff; border: 1px solid #3498db;
            border-radius: 6px; padding: 12px 16px; margin-top: 10px;
            font-size: 13px;
        }
        .sum-row {
            display: flex; justify-content: space-between;
            padding: 3px 0; border-bottom: 1px dashed #c8e0f4;
        }
        .sum-row:last-child { border-bottom: none; }
        .sum-total { font-weight: bold; color: #2471a3; font-size: 14px; padding-top: 6px; }
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
                            <h2 class="title">Declare Result</h2>
                        </div>
                    </div>
                    <div class="row breadcrumb-div">
                        <div class="col-md-6">
                            <ul class="breadcrumb">
                                <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                <li class="active">Declare Result</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- PANEL -->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel">
                                <div class="panel-body">

                                    <!-- ALERTS -->
                                    <?php if ($msg) : ?>
                                    <div class="alert alert-success alert-dismissible">
                                        <button class="close" data-dismiss="alert">&times;</button>
                                        <strong>Success!</strong> <?php echo htmlentities($msg); ?>
                                    </div>
                                    <?php endif; ?>
                                    <?php if ($error) : ?>
                                    <div class="alert alert-danger alert-dismissible">
                                        <button class="close" data-dismiss="alert">&times;</button>
                                        <strong>Error!</strong> <?php echo htmlentities($error); ?>
                                    </div>
                                    <?php endif; ?>

                                    <!-- ===================== FORM ===================== -->
                                    <form method="post" class="form-horizontal" id="result-form">

                                        <!-- 1. Academic Year -->
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Academic Year</label>
                                            <div class="col-sm-4">
                                                <select name="academicyear" class="form-control" required>
                                                    <option value="">Select Year</option>
                                                    <option value="2025-2026">2025-2026</option>
                                                    <option value="2026-2027">2026-2027</option>
                                                    <option value="2027-2028">2027-2028</option>
                                                    <option value="2028-2029">2028-2029</option>
                                                    <option value="2029-2030">2029-2030</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- 2. Exam Type -->
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Exam Type</label>
                                            <div class="col-sm-4">
                                                <select name="examtype" class="form-control" required>
                                                    <option value="">Select Exam</option>
                                                    <option value="Midterm">Midterm</option>
                                                    <option value="Final">Final</option>
                                                </select>
                                            </div>
                                        </div>

                                        <hr>

                                        <!-- 3. Faculty -->
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Faculty</label>
                                            <div class="col-sm-6">
                                                <select name="faculty" id="sel-faculty"
                                                        class="form-control" required>
                                                    <option value="">-- Select Faculty --</option>
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

                                        <!-- 4. Department (dynamic - changes with Faculty) -->
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Department</label>
                                            <div class="col-sm-6">
                                                <select name="department" id="sel-dept"
                                                        class="form-control" required>
                                                    <option value="">-- Select Faculty First --</option>
                                                </select>
                                            </div>
                                        </div>

                                        <hr>

                                        <!-- 5. Semester -->
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Semester</label>
                                            <div class="col-sm-4">
                                                <select name="class" id="sel-class"
                                                        class="form-control" required>
                                                    <option value="">Select Semester</option>
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

                                        <!-- 6. Student Name (dynamic - loads after Faculty+Dept+Semester) -->
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Student Name</label>
                                            <div class="col-sm-6">
                                                <select name="studentid" id="sel-student"
                                                        class="form-control" required>
                                                    <option value="">-- Select Faculty + Dept + Semester first --</option>
                                                </select>
                                                <div class="ajax-spin" id="spin-student">
                                                    <i class="fa fa-spinner fa-spin"></i> Loading students...
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 7. Subjects & Marks (dynamic) -->
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Subjects</label>
                                            <div class="col-sm-10">
                                                <div class="ajax-spin" id="spin-subject">
                                                    <i class="fa fa-spinner fa-spin"></i> Loading subjects...
                                                </div>
                                                <div id="subject"></div>
                                                <div id="marks-summary"></div>
                                            </div>
                                        </div>

                                        <!-- 8. Submit -->
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" name="submit"
                                                        id="btn-submit"
                                                        class="btn btn-primary"
                                                        disabled>
                                                    Declare Result
                                                </button>
                                            </div>
                                        </div>

                                    </form>
                                    <!-- =================== END FORM =================== -->

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- JS -->
<script src="js/bootstrap/bootstrap.min.js"></script>
<script src="js/pace/pace.min.js"></script>
<script src="js/lobipanel/lobipanel.min.js"></script>
<script src="js/iscroll/iscroll.js"></script>
<script src="js/prism/prism.js"></script>
<script src="js/select2/select2.min.js"></script>
<script src="js/main.js"></script>

<script>
$(function () {

    /* ── Auto-hide alerts after 4s ── */
    setTimeout(function () { $('.alert').fadeOut('slow'); }, 4000);

    /* ══════════════════════════════════════════════════
       1.  FACULTY  ➜  Load Departments
    ══════════════════════════════════════════════════ */
    $('#sel-faculty').on('change', function () {
        var fid = $(this).val();

        // Reset downstream selects
        $('#sel-dept').html('<option value="">Loading...</option>');
        resetStudentAndSubjects();

        if (!fid) {
            $('#sel-dept').html('<option value="">-- Select Faculty First --</option>');
            return;
        }

        $.ajax({
            url: 'get-departments.php',
            type: 'GET',
            data: { faculty_id: fid },
            dataType: 'json',
            success: function (data) {
                var opts = '<option value="">-- Select Department --</option>';
                $.each(data, function (i, d) {
                    opts += '<option value="' + d.id + '">' + d.DepartmentName + '</option>';
                });
                if (data.length === 0) {
                    opts = '<option value="">No departments found</option>';
                }
                $('#sel-dept').html(opts);
            },
            error: function () {
                $('#sel-dept').html('<option value="">Error loading departments</option>');
            }
        });
    });

    /* ══════════════════════════════════════════════════
       2.  DEPARTMENT or SEMESTER  ➜  Load Students + Subjects
       (both must be selected, plus Faculty)
    ══════════════════════════════════════════════════ */
    $('#sel-dept, #sel-class').on('change', function () {
        var fid = $('#sel-faculty').val();
        var did = $('#sel-dept').val();
        var cid = $('#sel-class').val();

        resetStudentAndSubjects();

        // Need all three before loading
        if (!fid || !did || !cid) return;

        loadStudents(cid, fid, did);
        loadSubjects(cid, fid, did);
    });

    /* ══════════════════════════════════════════════════
       3.  Load Students via AJAX
    ══════════════════════════════════════════════════ */
    function loadStudents(cid, fid, did) {
        $('#spin-student').show();
        $('#sel-student').html('<option value="">Loading...</option>');

        $.ajax({
            url: 'get_student.php',
            type: 'POST',
            data: { classid: cid, faculty_id: fid, dept_id: did },
            success: function (html) {
                $('#spin-student').hide();
                $('#sel-student').html(html);
            },
            error: function () {
                $('#spin-student').hide();
                $('#sel-student').html('<option value="">Error loading students</option>');
            }
        });
    }

    /* ══════════════════════════════════════════════════
       4.  Load Subjects via AJAX
    ══════════════════════════════════════════════════ */
    function loadSubjects(cid, fid, did) {
        $('#spin-subject').show();
        $('#subject').html('');
        $('#marks-summary').hide();
        $('#btn-submit').prop('disabled', true);

        $.ajax({
            url: 'get_student.php',
            type: 'POST',
            data: { classid1: cid, faculty_id: fid, dept_id: did },
            success: function (html) {
                $('#spin-subject').hide();
                $('#subject').html(html);

                if ($('#subject .subject-row').length > 0) {
                    $('#btn-submit').prop('disabled', false);
                }
            },
            error: function () {
                $('#spin-subject').hide();
                $('#subject').html('<p style="color:red;">Error loading subjects.</p>');
            }
        });
    }

    /* ══════════════════════════════════════════════════
       5.  Grade badge  (live, runs after AJAX via delegation)
    ══════════════════════════════════════════════════ */
    $(document).on('input', '.marks-input', function () {
        var val   = parseInt($(this).val(), 10);
        var badge = $(this).siblings('.grade-badge');

        badge.removeClass('badge-A badge-B badge-C badge-D badge-F')
             .text('-').css('background', '#ddd').css('color', '#555');

        if (!isNaN(val) && val >= 0) {
            if      (val >= 85) badge.addClass('badge-A').text('A');
            else if (val >= 75) badge.addClass('badge-B').text('B');
            else if (val >= 65) badge.addClass('badge-C').text('C');
            else if (val >= 50) badge.addClass('badge-D').text('D');
            else                badge.addClass('badge-F').text('FAIL');
        }

        updateSummary();
    });

    /* ══════════════════════════════════════════════════
       6.  Marks summary box
    ══════════════════════════════════════════════════ */
    function updateSummary() {
        var rows  = $('#subject .subject-row');
        var total = 0, count = 0, html = '';

        rows.each(function () {
            var name = $(this).find('strong').first().text();
            var val  = parseInt($(this).find('.marks-input').val(), 10);
            if (!isNaN(val) && val >= 0) {
                total += val; count++;
                html += '<div class="sum-row"><span>' + name + '</span><span>' + val + '</span></div>';
            }
        });

        if (count > 0) {
            var avg = (total / count).toFixed(1);
            html += '<div class="sum-row sum-total"><span>Total</span><span>' + total + '</span></div>';
            html += '<div class="sum-row sum-total"><span>Average</span><span>' + avg + '%</span></div>';
            $('#marks-summary').html(html).show();
        } else {
            $('#marks-summary').hide();
        }
    }

    /* ══════════════════════════════════════════════════
       7.  Reset helper
    ══════════════════════════════════════════════════ */
    function resetStudentAndSubjects() {
        $('#sel-student').html('<option value="">-- Select Faculty + Dept + Semester first --</option>');
        $('#subject').html('');
        $('#marks-summary').hide();
        $('#btn-submit').prop('disabled', true);
    }

    /* ══════════════════════════════════════════════════
       8.  Client-side form validation before submit
    ══════════════════════════════════════════════════ */
    $('#result-form').on('submit', function (e) {
        if (!$('#sel-student').val()) {
            e.preventDefault();
            alert('Please select a student!');
            return;
        }
        var ok = true;
        $('.marks-input').each(function () {
            var v = parseInt($(this).val(), 10);
            if (isNaN(v) || v < 0 || v > 100) { ok = false; }
        });
        if (!ok) {
            e.preventDefault();
            alert('Please enter valid marks (0 - 100) for all subjects!');
        }
    });

});
</script>

</body>
</html>
 ?>
