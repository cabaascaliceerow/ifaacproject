<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['llogin']) == "") exit;

$lecturer_id = $_SESSION['lecturer_id'];
$classid     = intval($_POST['classid']);

// Hel Faculty+Dept Lecturer-ka
$info = $dbh->prepare("SELECT FacultyId, DepartmentId FROM tbllecturer WHERE id=:id");
$info->execute([':id' => $lecturer_id]);
$lec = $info->fetch(PDO::FETCH_OBJ);

// Subjects kaliya Lecturer-kan loo xilsaaray
$q = $dbh->prepare("
    SELECT s.id, s.SubjectName, s.SubjectCode
    FROM   tblsubjectcombination sc
    JOIN   tblsubjects s ON s.id = sc.SubjectId
    WHERE  sc.ClassId      = :cid
      AND  sc.FacultyId    = :fid
      AND  sc.DepartmentId = :did
      AND  sc.LecturerId   = :lid
      AND  sc.status       = 1
    ORDER  BY s.SubjectName
");
$q->execute([
    ':cid' => $classid,
    ':fid' => $lec->FacultyId,
    ':did' => $lec->DepartmentId,
    ':lid' => $lecturer_id,
]);
$subjects = $q->fetchAll(PDO::FETCH_OBJ);

if(count($subjects) == 0){
    echo '<div class="alert alert-warning">
            <i class="fa fa-exclamation-triangle"></i>
            No subjects assigned to you for this semester.
          </div>';
} else {
    foreach($subjects as $s){
        echo '
        <div class="subject-row">
            <div>
                <strong>'.htmlentities($s->SubjectName).'</strong>
                <small class="text-muted"> — '.htmlentities($s->SubjectCode).'</small>
            </div>
            <div class="d-flex align-items-center gap-2">
                <input type="number"
                       name="marks[]"
                       class="form-control marks-input"
                       placeholder="0-100"
                       min="0" max="100" required>
                <span class="grade-badge">-</span>
            </div>
        </div>';
    }
}
?>