<?php
include('includes/config.php');

if(isset($_GET['faculty']) && isset($_GET['department']))
{
    $faculty   = $_GET['faculty'];
    $department = $_GET['department'];

    $sql = "SELECT * FROM tblsubjects
            WHERE FacultyName = :faculty
            AND DepartmentName = :department
            ORDER BY SubjectName";

    $query = $dbh->prepare($sql);

    $query->bindParam(':faculty', $faculty, PDO::PARAM_STR);
    $query->bindParam(':department', $department, PDO::PARAM_STR);

    $query->execute();

    $subjects = $query->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($subjects);
}
?>