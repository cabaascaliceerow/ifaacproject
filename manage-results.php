<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])=="") {   
    header("Location: index.php"); 
    exit();
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin Manage Students</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" >
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
    <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen" >
    <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen" >
    <link rel="stylesheet" href="css/prism/prism.css" media="screen" >
    <link rel="stylesheet" type="text/css" href="js/DataTables/datatables.min.css"/>
    <link rel="stylesheet" href="css/main.css" media="screen" >
    <script src="js/modernizr/modernizr.min.js"></script>
    <style>
        @media (max-width:768px){

    .panel-body{
        padding:10px !important;
        overflow-x:hidden !important;
    }

    /* table header hide */

    #example thead,
    #example tfoot{
        display:none !important;
    }

    /* mobile card style */

    #example,
    #example tbody,
    #example tr,
    #example td{
        display:block;
        width:100%;
    }

    #example tbody tr{
        margin-bottom:12px;
        border:1px solid #ddd;
        border-radius:8px;
        background:#fff;
        padding:6px;
    }

    #example tbody td{
        display:flex;
        align-items:flex-start;
        gap:10px;
        padding:10px 8px;
        font-size:12px;
        border-bottom:1px solid #eee !important;
        word-break:break-word;
    }

    #example tbody td:last-child{
        border-bottom:none !important;
    }

    /* labels */

    #example tbody td:nth-child(1):before{
        content:"#";
    }

    #example tbody td:nth-child(2):before{
        content:"Student Name";
    }

    #example tbody td:nth-child(3):before{
        content:"Roll Id";
    }

    #example tbody td:nth-child(4):before{
        content:"Semester";
    }

    #example tbody td:nth-child(5):before{
        content:"Reg Date";
    }

    #example tbody td:nth-child(6):before{
        content:"Status";
    }

    #example tbody td:nth-child(7):before{
        content:"Action";
    }

    #example tbody td:before{
        font-weight:bold;
        color:#333;
        min-width:110px;
        flex-shrink:0;
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

    /* edit icon */

    .fa-edit{
        font-size:16px;
        color:#007bff;
    }

}
    .errorWrap { padding:10px; margin:0 0 20px 0; background:#fff; border-left:4px solid #dd3d36; box-shadow:0 1px 1px 0 rgba(0,0,0,.1);}
    .succWrap { padding:10px; margin:0 0 20px 0; background:#fff; border-left:4px solid #5cb85c; box-shadow:0 1px 1px 0 rgba(0,0,0,.1);}
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
            <h2 class="title">Manage Students</h2>
        </div>
    </div>
    <div class="row breadcrumb-div">
        <div class="col-md-6">
            <ul class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                <li> Students</li>
                <li class="active">Manage Students</li>
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
        <h5>View Students Info</h5>
    </div>
</div>

<div class="panel-body p-20">
<table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
<thead>
<tr>
    <th>#</th>
    <th>Student Name</th>
    <th>Roll Id</th>
    <th>Semester</th>
    <th>Reg Date</th>
    <th>Status</th>
    <th>Action</th>
</tr>
</thead>
<tfoot>
<tr>
    <th>#</th>
    <th>Student Name</th>
    <th>Roll Id</th>
    <th>Semester</th>
    <th>Reg Date</th>
    <th>Status</th>
    <th>Action</th>
</tr>
</tfoot>
<tbody>
<?php
$sql = "SELECT DISTINCT tblstudents.StudentName, tblstudents.RollId, tblstudents.RegDate, tblstudents.StudentId, tblstudents.Status, 
               tblclasses.ClassName, tblclasses.id as ClassId, tblresult.AcademicYear
        FROM tblresult
        JOIN tblstudents ON tblstudents.StudentId=tblresult.StudentId
        JOIN tblclasses ON tblclasses.id=tblresult.ClassId";
$query = $dbh->prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0) {
    foreach($results as $result) { ?>
<tr>
<td><?php echo htmlentities($cnt);?></td>
<td><?php echo htmlentities($result->StudentName);?></td>
<td><?php echo htmlentities($result->RollId);?></td>
<td><?php echo htmlentities($result->ClassName);?> (<?php echo htmlentities($result->AcademicYear);?>)</td>
<td><?php echo htmlentities($result->RegDate);?></td>
<td><?php echo $result->Status==1 ? 'Active' : 'Blocked';?></td>
<td>
<a href="edit-result.php?stid=<?php echo htmlentities($result->StudentId);?>&classid=<?php echo htmlentities($result->ClassId);?>">
<i class="fa fa-edit" title="Edit Record"></i> </a>
</td>
</tr>
<?php $cnt++; } } ?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
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
        autoWidth:false
    });

});
</script>
</body>
</html>