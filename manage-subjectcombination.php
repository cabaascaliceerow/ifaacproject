<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])=="")
{   
    header("Location: index.php"); 
}
else{

// for Deactivate Subject
if(isset($_GET['did']))
{
    $did=intval($_GET['did']);
    $status=0;

    $sql="update tblsubjectcombination set status=:status where id=:did ";
    $query = $dbh->prepare($sql);

    $query->bindParam(':did',$did,PDO::PARAM_STR);
    $query->bindParam(':status',$status,PDO::PARAM_STR);

    $query->execute();

    $msg="Subject Deactivate successfully";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Admin Manage Subjects Combination</title>

    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen">
    <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen">
    <link rel="stylesheet" href="css/prism/prism.css" media="screen">
    <link rel="stylesheet" type="text/css" href="js/DataTables/datatables.min.css"/>
    <link rel="stylesheet" href="css/main.css" media="screen">

    <script src="js/modernizr/modernizr.min.js"></script>

<style>

@media (max-width:768px){

#example thead,
#example tfoot,
#example thead tr,
#example tfoot tr,
#example thead th,
#example tfoot th{
    display:none !important;
}

table.dataTable thead{
    height:0 !important;
    visibility:hidden !important;
}

#example,
#example tbody,
#example tr,
#example td{
    display:block;
    width:100%;
}

#example tbody tr{
    margin-bottom:10px;
    border:1px solid #ddd;
    border-radius:8px;
    background:#fff;
    padding:6px;
}

#example tbody td{
    display:flex;
    align-items:flex-start;
    padding:10px 8px;
    font-size:12px;
    border-bottom:1px solid #eee !important;
    white-space:normal !important;
    gap:10px;
    word-break:break-word;
}

#example tbody td:last-child{
    border-bottom:none !important;
}

#example tbody td:before{
    font-weight:bold;
    color:#333;
    min-width:90px;
    flex-shrink:0;
}

/* labels */

#example tbody td:nth-child(1):before{
    content:"#";
}

#example tbody td:nth-child(2):before{
    content:"Class";
}

#example tbody td:nth-child(3):before{
    content:"Subject";
}

#example tbody td:nth-child(4):before{
    content:"Faculty";
}

#example tbody td:nth-child(5):before{
    content:"Department";
}

#example tbody td:nth-child(6):before{
    content:"Status";
}

#example tbody td:nth-child(7):before{
    content:"Action";
}

/* search */

.dataTables_filter input{
    width:100% !important;
}

.dataTables_filter,
.dataTables_paginate,
.dataTables_info{
    text-align:center !important;
}

.fa-check{
    font-size:16px;
    color:green;
}

.fa-times{
    font-size:16px;
    color:red;
}

.panel-body{
    padding:10px !important;
    overflow-x:hidden !important;
}

}

.errorWrap{
    padding:10px;
    margin:0 0 20px 0;
    background:#fff;
    border-left:4px solid #dd3d36;
    box-shadow:0 1px 1px 0 rgba(0,0,0,.1);
}

.succWrap{
    padding:10px;
    margin:0 0 20px 0;
    background:#fff;
    border-left:4px solid #5cb85c;
    box-shadow:0 1px 1px 0 rgba(0,0,0,.1);
}

</style>

</head>

<body class="top-navbar-fixed">

<div class="main-wrapper">

<?php include('includes/topbar.php');?> 

<div class="content-wrapper">
<div class="content-container">

<?php include('includes/leftbar.php');?>  

<div class="main-page">

<div class="container-fluid">

<div class="row page-title-div">
<div class="col-md-6">
<h2 class="title">Manage Subjects Combination</h2>
</div>
</div>

<div class="row breadcrumb-div">
<div class="col-md-6">
<ul class="breadcrumb">
<li>
<a href="dashboard.php">
<i class="fa fa-home"></i> Home
</a>
</li>

<li>Subjects</li>

<li class="active">
Manage Subjects Combination
</li>

</ul>
</div>
</div>

</div>

<section class="section">

<div class="container-fluid">

<div class="row">

<div class="col-md-12">

<div class="panel">

<div class="panel-heading">
<div class="panel-title">
<h5>View Subjects Combination Info</h5>
</div>
</div>

<?php if($msg){ ?>

<div class="alert alert-success left-icon-alert" role="alert">
<strong>Well done!</strong>
<?php echo htmlentities($msg); ?>
</div>

<?php } else if($error){ ?>

<div class="alert alert-danger left-icon-alert" role="alert">
<strong>Oh snap!</strong>
<?php echo htmlentities($error); ?>
</div>

<?php } ?>

<div class="panel-body p-20">

<table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">

<thead>
<tr>
<th>#</th>
<th>Class and Section</th>
<th>Subject</th>
<th>Faculty</th>
<th>Department</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>

<tfoot>
<tr>
<th>#</th>
<th>Class and Section</th>
<th>Subject</th>
<th>Faculty</th>
<th>Department</th>
<th>Status</th>
<th>Action</th>
</tr>
</tfoot>

<tbody>

<?php

$sql = "SELECT 
tblclasses.ClassName,
tblsubjects.SubjectName,
tblfaculty.FacultyName,
tbldepartment.DepartmentName,
tblsubjectcombination.id as scid,
tblsubjectcombination.status

FROM tblsubjectcombination

JOIN tblclasses 
ON tblclasses.id = tblsubjectcombination.ClassId

JOIN tblsubjects 
ON tblsubjects.id = tblsubjectcombination.SubjectId

JOIN tblfaculty
ON tblfaculty.id = tblsubjectcombination.FacultyId

JOIN tbldepartment
ON tbldepartment.id = tblsubjectcombination.DepartmentId";

$query = $dbh->prepare($sql);

$query->execute();

$results=$query->fetchAll(PDO::FETCH_OBJ);

$cnt=1;

if($query->rowCount() > 0)
{
foreach($results as $result)
{
?>

<tr>

<td><?php echo htmlentities($cnt);?></td>

<td>
<?php echo htmlentities($result->ClassName);?>
</td>

<td>
<?php echo htmlentities($result->SubjectName);?>
</td>

<td>
<?php echo htmlentities($result->FacultyName);?>
</td>

<td>
<?php echo htmlentities($result->DepartmentName);?>
</td>

<td>

<?php

$stts=$result->status;

if($stts=='0')
{
    echo htmlentities('Inactive');
}
else
{
    echo htmlentities('Active');
}

?>

</td>

<td>

<?php if($stts=='0')
{ ?>

<a href="manage-subjectcombination.php?acid=<?php echo htmlentities($result->scid);?>"
onclick="confirm('do you really want to activate this subject');">

<i class="fa fa-check" title="Activate Record"></i>

</a>

<?php } else { ?>

<a href="manage-subjectcombination.php?did=<?php echo htmlentities($result->scid);?>"
onclick="confirm('do you really want to deactivate this subject');">

<i class="fa fa-times" title="Deactivate Record"></i>

</a>

<?php } ?>

</td>

</tr>

<?php
$cnt=$cnt+1;
}}

?>

</tbody>

</table>

</div>
</div>
</div>
</div>
</div>
</section>

</div>
</div>
</div>

</div>

<script src="js/jquery/jquery-2.2.4.min.js"></script>
<script src="js/bootstrap/bootstrap.min.js"></script>
<script src="js/pace/pace.min.js"></script>
<script src="js/lobipanel/lobipanel.min.js"></script>
<script src="js/iscroll/iscroll.js"></script>
<script src="js/prism/prism.js"></script>
<script src="js/DataTables/datatables.min.js"></script>
<script src="js/main.js"></script>

<script>

$(function(){

    $('#example').DataTable({
        responsive:false,
        autoWidth:false,
        searching:true,
        paging:true,
        info:true
    });

});

</script>

</body>
</html>

<?php } ?>