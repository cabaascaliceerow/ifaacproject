<?php
session_start();
include('includes/config.php'); // muhiim: hore keen

// DELETE FUNCTION
if(isset($_GET['delid'])){
    $id = intval($_GET['delid']);

    $sql = "DELETE FROM complaints WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);

    if($query->execute()){
       
        echo "<script>window.location.href='complaints.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error deleting');</script>";
    }
}

// CHECK LOGIN
if(strlen($_SESSION['alogin'])=="") { 
    header("Location: index.php"); 
    exit();
}

// FETCH DATA
$stmt = $dbh->prepare("SELECT * FROM complaints ORDER BY CreatedAt DESC");
$stmt->execute();
$complaints = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Panel - Complaints</title>

<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/font-awesome.min.css">
<link rel="stylesheet" href="css/main.css">
<link rel="stylesheet" type="text/css" href="js/DataTables/datatables.min.css"/>

<style>
    @media (max-width:768px){

    .panel-body,
    .card-body{
        padding:10px !important;
        overflow-x:hidden !important;
    }

    /* hide table header */

    #complaintsTable thead,
    #complaintsTable tfoot{
        display:none !important;
    }

    /* mobile cards */

    #complaintsTable,
    #complaintsTable tbody,
    #complaintsTable tr,
    #complaintsTable td{
        display:block;
        width:100%;
    }

    #complaintsTable tbody tr{
        margin-bottom:12px;
        border:1px solid #ddd;
        border-radius:8px;
        background:#fff;
        padding:6px;
    }

    #complaintsTable tbody td{
        display:flex;
        align-items:flex-start;
        gap:10px;
        padding:10px 8px;
        font-size:12px;
        border-bottom:1px solid #eee !important;
        word-break:break-word;
        text-align:left !important;
    }

    #complaintsTable tbody td:last-child{
        border-bottom:none !important;
    }

    /* labels */

    #complaintsTable tbody td:nth-child(1):before{
        content:"#";
    }

    #complaintsTable tbody td:nth-child(2):before{
        content:"Student Name";
    }

    #complaintsTable tbody td:nth-child(3):before{
        content:"Roll ID";
    }

    #complaintsTable tbody td:nth-child(4):before{
        content:"Message";
    }

    #complaintsTable tbody td:nth-child(5):before{
        content:"Submitted";
    }

    #complaintsTable tbody td:nth-child(6):before{
        content:"Action";
    }

    #complaintsTable tbody td:before{
        font-weight:bold;
        color:#333;
        min-width:100px;
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

    /* delete button */

    .btn-delete{
        padding:4px 10px !important;
        font-size:12px !important;
    }

}
.card{
    box-shadow:0 0 15px rgba(0,0,0,0.1);
    border-radius:10px;
    margin-top:20px;
}
.card-header{
    background:#007bff;
    color:#fff;
    font-weight:bold;
    font-size:18px;
    border-radius:10px 10px 0 0;
}
.table th{
    background:#e9ecef;
    text-align:center;
}
.table td{
    text-align:center;
}
.btn-delete{
    background:#dc3545;
    color:#fff;
}
.table-responsive{
    overflow-x:auto;
}
.alert{
    margin-top:15px;
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
<h2 class="title">Manage Complaints</h2>
</div>
</div>

<div class="row breadcrumb-div">
<div class="col-md-6">
<ul class="breadcrumb">
<li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
<li class="active">Complaints</li>
</ul>
</div>
</div>

</div>

<section class="section">
<div class="container-fluid">
<div class="row">
<div class="col-md-12">

<div class="card">
<div class="card-body">

<?php if(count($complaints) > 0){ ?>
<div class="table-responsive">
<table id="complaintsTable" class="display table table-striped table-bordered" width="100%">
<thead>
<tr>
<th>#</th>
<th>Student Name</th>
<th>Roll ID</th>
<th>Message</th>
<th>Submitted At</th>
<th>Action</th>
</tr>
</thead>

<tbody>
<?php 
$cnt=1;
foreach($complaints as $c){ ?>
<tr>
<td><?php echo $cnt; ?></td>
<td><?php echo htmlentities($c->StudentName); ?></td>
<td><?php echo htmlentities($c->RollId); ?></td>
<td><?php echo htmlentities($c->Message); ?></td>
<td><?php echo $c->CreatedAt; ?></td>
<td>
<a href="complaints.php?delid=<?php echo $c->id; ?>" 
   class="btn btn-delete btn-sm"
   onclick="return confirm('Are you sure you want to delete this complaint?');">
   <i class="fa fa-trash"></i> Delete
</a>
</td>
</tr>
<?php $cnt++; } ?>
</tbody>

</table>
</div>

<?php } else { ?>
<div class="alert alert-info text-center">
<i class="fa fa-info-circle"></i> No complaints submitted yet.
</div>
<?php } ?>

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
$(document).ready(function() {

    $('#complaintsTable').DataTable({
        responsive:false,
        autoWidth:false
    });

});
</script>

</body>
</html>