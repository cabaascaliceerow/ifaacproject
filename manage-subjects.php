<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])=="")
{   
    header("Location: index.php"); 
}
else{

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Admin Manage Subjects</title>

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
    #example tfoot{
        display:none !important;
    }

    .panel-body{
        padding:10px !important;
        overflow-x:hidden !important;
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

    /* Labels */

    #example tbody td:nth-child(1):before{
        content:"#";
    }

    #example tbody td:nth-child(2):before{
        content:"Subject Name";
    }

    #example tbody td:nth-child(3):before{
        content:"Subject Code";
    }

    #example tbody td:nth-child(4):before{
        content:"Faculty";
    }

    #example tbody td:nth-child(5):before{
        content:"Department";
    }

    #example tbody td:nth-child(6):before{
        content:"Creation Date";
    }

    #example tbody td:nth-child(7):before{
        content:"Updation Date";
    }

    #example tbody td:nth-child(8):before{
        content:"Action";
    }

    #example tbody td:before{
        font-weight:bold;
        color:#333;
        min-width:150px;
        flex-shrink:0;
    }

    .dataTables_filter input{
        width:100% !important;
    }

    .dataTables_filter,
    .dataTables_paginate,
    .dataTables_info{
        text-align:center !important;
    }

    .fa-edit{
        font-size:16px;
        color:#007bff;
    }

}

.errorWrap {
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #dd3d36;
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}

.succWrap{
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #5cb85c;
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}

</style>

</head>

<body class="top-navbar-fixed">

<div class="main-wrapper">

    <!-- TOP NAVBAR -->
    <?php include('includes/topbar.php');?> 

    <div class="content-wrapper">
        <div class="content-container">

            <!-- LEFT SIDEBAR -->
            <?php include('includes/leftbar.php');?>  

            <div class="main-page">

                <div class="container-fluid">

                    <div class="row page-title-div">
                        <div class="col-md-6">
                            <h2 class="title">Manage Subjects</h2>
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

                                <li class="active">Manage Subjects</li>
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
                                            <h5>View Subjects Info</h5>
                                        </div>
                                    </div>

<?php if($msg){ ?>

<div class="alert alert-success left-icon-alert" role="alert">
    <strong>Well done! </strong>
    <?php echo htmlentities($msg); ?>
</div>

<?php } else if($error){ ?>

<div class="alert alert-danger left-icon-alert" role="alert">
    <strong>Oh snap! </strong>
    <?php echo htmlentities($error); ?>
</div>

<?php } ?>

<div class="panel-body p-20">

<table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">

    <thead>
        <tr>
            <th>#</th>
            <th>Subject Name</th>
            <th>Subject Code</th>
            <th>Faculty</th>
            <th>Department</th>
            <th>Creation Date</th>
            <th>Updation Date</th>
            <th>Action</th>
        </tr>
    </thead>

    <tfoot>
        <tr>
            <th>#</th>
            <th>Subject Name</th>
            <th>Subject Code</th>
            <th>Faculty</th>
            <th>Department</th>
            <th>Creation Date</th>
            <th>Updation Date</th>
            <th>Action</th>
        </tr>
    </tfoot>

<tbody>

<?php 

$sql = "SELECT * FROM tblsubjects";
$query = $dbh->prepare($sql);
$query->execute();

$results = $query->fetchAll(PDO::FETCH_OBJ);

$cnt = 1;

if($query->rowCount() > 0)
{
    foreach($results as $result)
    {   
?>

<tr>

    <td><?php echo htmlentities($cnt);?></td>

    <td><?php echo htmlentities($result->SubjectName);?></td>

    <td><?php echo htmlentities($result->SubjectCode);?></td>

    <td><?php echo htmlentities($result->FacultyName);?></td>

    <td><?php echo htmlentities($result->DepartmentName);?></td>

    <td><?php echo htmlentities($result->Creationdate);?></td>

    <td><?php echo htmlentities($result->UpdationDate);?></td>

    <td>
        <a href="edit-subject.php?subjectid=<?php echo htmlentities($result->id);?>">
            <i class="fa fa-edit" title="Edit Record"></i>
        </a>
    </td>

</tr>

<?php 

$cnt = $cnt + 1;

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

<!-- JS FILES -->

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

<?php } ?>