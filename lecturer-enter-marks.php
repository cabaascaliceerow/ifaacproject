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

$msg = ""; $error = "";

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

/* ================================================================
   FORM SUBMIT: Natiijada keydi
================================================================ */
if(isset($_POST['submit']))
{
    $class        = intval($_POST['class']);
    $studentid    = intval($_POST['studentid']);
    $academicyear = $_POST['academicyear'];
    $examtype     = $_POST['examtype'];
    $marks        = isset($_POST['marks']) ? $_POST['marks'] : [];

    // Xaqiiji in subject-ka uu yahay kan Lecturer-ka loo xilsaaray
    $faculty_id = $lec->FacultyId;
    $dept_id    = $lec->DepartmentId;

    if(!$class || !$studentid || !$academicyear || !$examtype){
        $error = "Please fill all required fields!";
    } else {

        // Hel subjects-ka Lecturer-kan loo xilsaaray
        $stmt = $dbh->prepare("
            SELECT s.id
            FROM   tblsubjectcombination sc
            JOIN   tblsubjects s ON s.id = sc.SubjectId
            WHERE  sc.ClassId      = :cid
              AND  sc.FacultyId    = :fid
              AND  sc.DepartmentId = :did
              AND  sc.LecturerId   = :lid
              AND  sc.status       = 1
            ORDER  BY s.SubjectName
        ");
        $stmt->execute([
            ':cid' => $class,
            ':fid' => $faculty_id,
            ':did' => $dept_id,
            ':lid' => $lecturer_id,
        ]);
        $subjects = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if(count($subjects) == 0){
            $error = "No subjects assigned to you for this semester!";
        } elseif(count($marks) != count($subjects)){
            $error = "Marks count does not match subjects count!";
        } else {

            // Hubi duplicate
            $check = $dbh->prepare("
                SELECT id FROM tblresult
                WHERE StudentId=:sid AND ClassId=:cid
                  AND AcademicYear=:ay AND ExamType=:exam
                  AND SubjectId=:subj
            ");
            $duplicate = false;
            foreach($subjects as $subj_id){
                $check->execute([
                    ':sid'  => $studentid,
                    ':cid'  => $class,
                    ':ay'   => $academicyear,
                    ':exam' => $examtype,
                    ':subj' => $subj_id,
                ]);
                if($check->rowCount() > 0){ $duplicate = true; break; }
            }

            if($duplicate){
                $error = "Marks already entered for this student - Semester + ExamType + Year!";
            } else {
                $insert = $dbh->prepare("
                    INSERT INTO tblresult
                      (StudentId, ClassId, AcademicYear, ExamType, SubjectId, marks)
                    VALUES
                      (:studentid, :class, :ay, :exam, :sid, :marks)
                ");
                for($i = 0; $i < count($subjects); $i++){
                    $insert->execute([
                        ':studentid' => $studentid,
                        ':class'     => $class,
                        ':ay'        => $academicyear,
                        ':exam'      => $examtype,
                        ':sid'       => $subjects[$i],
                        ':marks'     => intval($marks[$i]),
                    ]);
                }
                $msg = "Result saved successfully!";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Enter Marks | Kownayn</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/animate-css/animate.min.css">
    <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/modernizr/modernizr.min.js"></script>
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <style>
        .grade-badge {
            display: inline-block;
            min-width: 38px; height: 28px; line-height: 28px;
            text-align: center; border-radius: 14px;
            font-size: 11px; font-weight: 700;
            padding: 0 8px; background: #ddd; color: #555;
            transition: background .2s, color .2s;
        }
        .badge-A { background:#2980b9; color:#fff; }
        .badge-B { background:#27ae60; color:#fff; }
        .badge-C { background:#f39c12; color:#fff; }
        .badge-D { background:#e67e22; color:#fff; }
        .badge-F { background:#e74c3c; color:#fff; }

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
        .marks-input { width:90px !important; text-align:center; font-weight:bold; }

        .ajax-spin { display:none; color:#888; font-size:13px; padding:6px 0; }

        #marks-summary {
            display:none;
            background:#eaf4ff; border:1px solid #3498db;
            border-radius:6px; padding:12px 16px; margin-top:10px;
            font-size:13px;
        }
        .sum-row {
            display:flex; justify-content:space-between;
            padding:3px 0; border-bottom:1px dashed #c8e0f4;
        }
        .sum-row:last-child { border-bottom:none; }
        .sum-total { font-weight:bold; color:#2471a3; font-size:14px; padding-top:6px; }

        /* Info banner */
        .info-banner {
            background: linear-gradient(135deg,#1F3864,#2E75B6);
            color:#fff; border-radius:8px;
            padding:12px 20px; margin-bottom:20px;
            font-size:13px;
        }
        .info-banner span { margin-right:20px; }
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
                        <h2 class="title">Enter Student Marks</h2>
                    </div>
                </div>
                <div class="row breadcrumb-div">
                    <div class="col-md-6">
                        <ul class="breadcrumb">
                            <li>
                                <a href="lecturer-dashboard.php">
                                    <i class="fa fa-home"></i> Home
                                </a>
                            </li>
                            <li class="active">Enter Marks</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel">
                            <div class="panel-body">

                                <!-- Lecturer Info Banner -->
                                <div class="info-banner">
                                    <i class="fa fa-user-circle-o"></i>
                                    <span><strong><?php echo htmlentities($lecturer_name); ?></strong></span>
                                    <span>
                                        <i class="fa fa-university"></i>
                                        <?php echo htmlentities($lec->FacultyName ?? '-'); ?>
                                    </span>
                                    <span>
                                        <i class="fa fa-sitemap"></i>
                                        <?php echo htmlentities($lec->DepartmentName ?? '-'); ?>
                                    </span>
                                </div>

                                <!-- ALERTS -->
                                <?php if($msg): ?>
                                <div class="alert alert-success alert-dismissible">
                                    <button class="close" data-dismiss="alert">&times;</button>
                                    <strong>Success!</strong> <?php echo htmlentities($msg); ?>
                                </div>
                                <?php endif; ?>
                                <?php if($error): ?>
                                <div class="alert alert-danger alert-dismissible">
                                    <button class="close" data-dismiss="alert">&times;</button>
                                    <strong>Error!</strong> <?php echo htmlentities($error); ?>
                                </div>
                                <?php endif; ?>

                                <!-- FORM -->
                                <form method="post" class="form-horizontal" id="marks-form">

                                    <!-- Academic Year -->
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

                                    <!-- Exam Type -->
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

                                    <!-- Semester -->
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Semester</label>
                                        <div class="col-sm-4">
                                            <select name="class" id="sel-class"
                                                    class="form-control" required>
                                                <option value="">Select Semester</option>
                                                <?php
                                                // Kaliya semesters-ka Lecturer-ku subjects ku leeyahay
                                                $csem = $dbh->prepare("
                                                    SELECT DISTINCT c.id, c.ClassName
                                                    FROM   tblsubjectcombination sc
                                                    JOIN   tblclasses c ON c.id = sc.ClassId
                                                    WHERE  sc.LecturerId   = :lid
                                                      AND  sc.FacultyId    = :fid
                                                      AND  sc.DepartmentId = :did
                                                      AND  sc.status       = 1
                                                    ORDER  BY c.id
                                                ");
                                                $csem->execute([
                                                    ':lid' => $lecturer_id,
                                                    ':fid' => $lec->FacultyId,
                                                    ':did' => $lec->DepartmentId,
                                                ]);
                                                foreach($csem->fetchAll(PDO::FETCH_OBJ) as $c){
                                                    echo '<option value="'.(int)$c->id.'">'.htmlentities($c->ClassName).'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Student -->
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Student</label>
                                        <div class="col-sm-6">
                                            <select name="studentid" id="sel-student"
                                                    class="form-control" required>
                                                <option value="">-- Select Semester First --</option>
                                            </select>
                                            <div class="ajax-spin" id="spin-student">
                                                <i class="fa fa-spinner fa-spin"></i>
                                                Loading students...
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Subjects & Marks -->
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Subjects</label>
                                        <div class="col-sm-10">
                                            <div class="ajax-spin" id="spin-subject">
                                                <i class="fa fa-spinner fa-spin"></i>
                                                Loading subjects...
                                            </div>
                                            <div id="subject-list"></div>
                                            <div id="marks-summary"></div>
                                        </div>
                                    </div>

                                    <!-- Submit -->
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" name="submit"
                                                    id="btn-submit"
                                                    class="btn btn-primary"
                                                    disabled>
                                                <i class="fa fa-save"></i> Save Marks
                                            </button>
                                        </div>
                                    </div>

                                </form>
                                <!-- /FORM -->

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

<script>
$(function(){

    // Auto-hide alerts
    setTimeout(function(){ $('.alert').fadeOut('slow'); }, 4000);

    /* ══════════════════════════════════
       Semester → Load Students + Subjects
    ══════════════════════════════════ */
    $('#sel-class').on('change', function(){
        var cid = $(this).val();
        resetAll();
        if(!cid) return;
        loadStudents(cid);
        loadSubjects(cid);
    });

    /* ── Load Students ── */
    function loadStudents(cid){
        $('#spin-student').show();
        $('#sel-student').html('<option value="">Loading...</option>');

        $.ajax({
            url:  'lecturer-get-students.php',
            type: 'POST',
            data: { classid: cid },
            success: function(html){
                $('#spin-student').hide();
                $('#sel-student').html(html);
            },
            error: function(){
                $('#spin-student').hide();
                $('#sel-student').html('<option value="">Error loading students</option>');
            }
        });
    }

    /* ── Load Subjects ── */
    function loadSubjects(cid){
        $('#spin-subject').show();
        $('#subject-list').html('');
        $('#marks-summary').hide();
        $('#btn-submit').prop('disabled', true);

        $.ajax({
            url:  'lecturer-get-subjects.php',
            type: 'POST',
            data: { classid: cid },
            success: function(html){
                $('#spin-subject').hide();
                $('#subject-list').html(html);
                if($('#subject-list .subject-row').length > 0){
                    $('#btn-submit').prop('disabled', false);
                }
            },
            error: function(){
                $('#spin-subject').hide();
                $('#subject-list').html('<p class="text-danger">Error loading subjects.</p>');
            }
        });
    }

    /* ── Grade badge (live) ── */
    $(document).on('input', '.marks-input', function(){
        var val   = parseInt($(this).val(), 10);
        var badge = $(this).siblings('.grade-badge');
        badge.removeClass('badge-A badge-B badge-C badge-D badge-F')
             .text('-').css({background:'#ddd', color:'#555'});
        if(!isNaN(val) && val >= 0){
            if     (val >= 85) badge.addClass('badge-A').text('A');
            else if(val >= 75) badge.addClass('badge-B').text('B');
            else if(val >= 65) badge.addClass('badge-C').text('C');
            else if(val >= 50) badge.addClass('badge-D').text('D');
            else               badge.addClass('badge-F').text('FAIL');
        }
        updateSummary();
    });

    /* ── Marks summary ── */
    function updateSummary(){
        var total=0, count=0, html='';
        $('#subject-list .subject-row').each(function(){
            var name = $(this).find('strong').first().text();
            var val  = parseInt($(this).find('.marks-input').val(), 10);
            if(!isNaN(val) && val >= 0){
                total += val; count++;
                html += '<div class="sum-row"><span>'+name+'</span><span>'+val+'</span></div>';
            }
        });
        if(count > 0){
            var avg = (total/count).toFixed(1);
            html += '<div class="sum-row sum-total"><span>Total</span><span>'+total+'</span></div>';
            html += '<div class="sum-row sum-total"><span>Average</span><span>'+avg+'%</span></div>';
            $('#marks-summary').html(html).show();
        } else {
            $('#marks-summary').hide();
        }
    }

    /* ── Reset ── */
    function resetAll(){
        $('#sel-student').html('<option value="">-- Select Semester First --</option>');
        $('#subject-list').html('');
        $('#marks-summary').hide();
        $('#btn-submit').prop('disabled', true);
    }

    /* ── Client validation ── */
    $('#marks-form').on('submit', function(e){
        if(!$('#sel-student').val()){
            e.preventDefault();
            alert('Please select a student!');
            return;
        }
        var ok = true;
        $('.marks-input').each(function(){
            var v = parseInt($(this).val(), 10);
            if(isNaN(v) || v < 0 || v > 100){ ok = false; }
        });
        if(!ok){
            e.preventDefault();
            alert('Please enter valid marks between 0 and 100!');
        }
    });

});
</script>
</body>
</html>