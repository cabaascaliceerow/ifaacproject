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

// Ardayda kaliya Faculty+Dept+Semester Lecturer-ka
$q = $dbh->prepare("
    SELECT StudentId, StudentName, RollId
    FROM   tblstudents
    WHERE  ClassId     = :cid
      AND  Faculty     = :fid
      AND  Department  = :did
      AND  Status      = 1
    ORDER  BY StudentName
");
$q->execute([
    ':cid' => $classid,
    ':fid' => $lec->FacultyId,
    ':did' => $lec->DepartmentId,
]);
$students = $q->fetchAll(PDO::FETCH_OBJ);

$html = '<option value="">-- Select Student --</option>';
if(count($students) == 0){
    $html = '<option value="">No students found for this semester</option>';
} else {
    foreach($students as $s){
        $html .= '<option value="'.(int)$s->StudentId.'">'.
                 htmlentities($s->StudentName).' ('.htmlentities($s->RollId).')</option>';
    }
}
echo $html;
?>