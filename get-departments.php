<?php
include('includes/config.php');
$facultyId = intval($_GET['faculty_id']);

$sql = "SELECT * FROM tbldepartment WHERE FacultyId = :facultyId ORDER BY DepartmentName";
$query = $dbh->prepare($sql);
$query->bindParam(':facultyId', $facultyId, PDO::PARAM_INT);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);

header('Content-Type: application/json');
echo json_encode($results);
?>