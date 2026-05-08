<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])=="")
{   
header("Location: index.php"); 
}
else{

$stid=intval($_GET['stid']);

if(isset($_POST['submit']))
{
$studentname=$_POST['fullanme'];
$roolid=$_POST['rollid']; 
$studentemail=$_POST['emailid']; 
$gender=$_POST['gender']; 
$classid=$_POST['class']; 
$dob=$_POST['dob']; 
$status=$_POST['status'];
$faculty=$_POST['faculty'];
$department=$_POST['department'];

// ✅ Hubi RollId hore loo isticmaalay (student kale mooyee naftii)
$check = $dbh->prepare("SELECT * FROM tblstudents WHERE RollId=:rollid AND StudentId != :stid");
$check->bindParam(':rollid', $roolid, PDO::PARAM_STR);
$check->bindParam(':stid', $stid, PDO::PARAM_INT);
$check->execute();

if($check->rowCount() > 0){
    $error = "This Roll ID already exists! Please use a different Roll ID.";
} else {

$sql="update tblstudents set 
StudentName=:studentname,
RollId=:roolid,
StudentEmail=:studentemail,
Gender=:gender,
ClassId=:classid,
DOB=:dob,
Status=:status,
Faculty=:faculty,
Department=:department
where StudentId=:stid";

$query = $dbh->prepare($sql);
$query->bindParam(':studentname',$studentname,PDO::PARAM_STR);
$query->bindParam(':roolid',$roolid,PDO::PARAM_STR);
$query->bindParam(':studentemail',$studentemail,PDO::PARAM_STR);
$query->bindParam(':gender',$gender,PDO::PARAM_STR);
$query->bindParam(':classid',$classid,PDO::PARAM_STR);
$query->bindParam(':dob',$dob,PDO::PARAM_STR);
$query->bindParam(':status',$status,PDO::PARAM_STR);
$query->bindParam(':faculty',$faculty,PDO::PARAM_INT);
$query->bindParam(':department',$department,PDO::PARAM_INT);
$query->bindParam(':stid',$stid,PDO::PARAM_STR);
$query->execute();

$msg="Student info updated successfully";

} // end else
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>SMS Admin | Edit Student</title>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/font-awesome.min.css">
<link rel="stylesheet" href="css/animate-css/animate.min.css">
<link rel="stylesheet" href="css/lobipanel/lobipanel.min.css">
<link rel="stylesheet" href="css/prism/prism.css">
<link rel="stylesheet" href="css/select2/select2.min.css">
<link rel="stylesheet" href="css/main.css">
<script src="js/modernizr/modernizr.min.js"></script>
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
<h2 class="title">Edit Student</h2>
</div>
</div>
<div class="row breadcrumb-div">
<div class="col-md-6">
<ul class="breadcrumb">
<li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
<li class="active">Edit Student</li>
</ul>
</div>
</div>
</div>

<div class="container-fluid">
<div class="row">
<div class="col-md-12">
<div class="panel">
<div class="panel-heading">
<div class="panel-title">
<h5>Fill the Student info</h5>
</div>
</div>
<div class="panel-body">

<?php if($msg){ ?>
<div class="alert alert-success left-icon-alert">
<strong>Well done!</strong> <?php echo htmlentities($msg); ?>
</div>
<?php } if($error){ ?>
<div class="alert alert-danger left-icon-alert">
<strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
</div>
<?php } ?>

<form class="form-horizontal" method="post">

<?php 
$sql="SELECT s.*, f.FacultyName, d.DepartmentName 
      FROM tblstudents s
      LEFT JOIN tblfaculty f ON s.Faculty = f.id
      LEFT JOIN tbldepartment d ON s.Department = d.id
      WHERE s.StudentId=:stid";
$query = $dbh->prepare($sql);
$query->bindParam(':stid',$stid,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);

if($query->rowCount() > 0)
{
foreach($results as $result)
{
?>

<!-- Full Name -->
<div class="form-group">
<label class="col-sm-2 control-label">Full Name</label>
<div class="col-sm-10">
<input type="text" name="fullanme" class="form-control" value="<?php echo htmlentities($result->StudentName); ?>" required>
</div>
</div>

<!-- Roll Id -->
<div class="form-group">
<label class="col-sm-2 control-label">Roll Id</label>
<div class="col-sm-10">
<input type="text" name="rollid" class="form-control" value="<?php echo htmlentities($result->RollId); ?>" required>
</div>
</div>

<!-- Email -->
<div class="form-group">
<label class="col-sm-2 control-label">Email</label>
<div class="col-sm-10">
<input type="email" name="emailid" class="form-control" value="<?php echo htmlentities($result->StudentEmail); ?>" required>
</div>
</div>

<!-- Gender -->
<div class="form-group">
<label class="col-sm-2 control-label">Gender</label>
<div class="col-sm-10" style="padding-top:7px;">
<input type="radio" name="gender" value="Male" <?php if($result->Gender=="Male") echo "checked"; ?>> Male &nbsp;&nbsp;
<input type="radio" name="gender" value="Female" <?php if($result->Gender=="Female") echo "checked"; ?>> Female &nbsp;&nbsp;
<input type="radio" name="gender" value="Other" <?php if($result->Gender=="Other") echo "checked"; ?>> Other
</div>
</div>

<!-- Faculty -->
<div class="form-group">
<label class="col-sm-2 control-label">Faculty</label>
<div class="col-sm-10">
<select name="faculty" class="form-control" id="faculty-select" required>
<option value="">Select Faculty</option>
<?php
$sql2="SELECT * FROM tblfaculty ORDER BY FacultyName";
$query2=$dbh->prepare($sql2);
$query2->execute();
$faculties=$query2->fetchAll(PDO::FETCH_OBJ);
foreach($faculties as $fac){ ?>
<option value="<?php echo $fac->id; ?>" 
<?php if($fac->id == $result->Faculty) echo "selected"; ?>>
<?php echo htmlentities($fac->FacultyName); ?>
</option>
<?php } ?>
</select>
</div>
</div>

<!-- Department -->
<div class="form-group">
<label class="col-sm-2 control-label">Department</label>
<div class="col-sm-10">
<select name="department" class="form-control" id="department-select" required>
<option value="">Select Department</option>
<?php
$sql3="SELECT * FROM tbldepartment WHERE FacultyId=:fid ORDER BY DepartmentName";
$query3=$dbh->prepare($sql3);
$query3->bindParam(':fid', $result->Faculty, PDO::PARAM_INT);
$query3->execute();
$departments=$query3->fetchAll(PDO::FETCH_OBJ);
foreach($departments as $dep){ ?>
<option value="<?php echo $dep->id; ?>" 
<?php if($dep->id == $result->Department) echo "selected"; ?>>
<?php echo htmlentities($dep->DepartmentName); ?>
</option>
<?php } ?>
</select>
</div>
</div>

<!-- Class/Semester -->
<div class="form-group">
<label class="col-sm-2 control-label">Class</label>
<div class="col-sm-10">
<select name="class" class="form-control" required>
<option value="">Select Class</option>
<?php
$sql4="SELECT * from tblclasses";
$query4=$dbh->prepare($sql4);
$query4->execute();
$classes=$query4->fetchAll(PDO::FETCH_OBJ);
foreach($classes as $class){ ?>
<option value="<?php echo $class->id; ?>"
<?php if($class->id==$result->ClassId) echo "selected"; ?>>
<?php echo $class->ClassName; ?>
</option>
<?php } ?>
</select>
</div>
</div>

<!-- DOB -->
<div class="form-group">
<label class="col-sm-2 control-label">DOB</label>
<div class="col-sm-10">
<input type="date" name="dob" class="form-control" value="<?php echo htmlentities($result->DOB); ?>">
</div>
</div>

<!-- Status -->
<div class="form-group">
<label class="col-sm-2 control-label">Status</label>
<div class="col-sm-10" style="padding-top:7px;">
<input type="radio" name="status" value="1" <?php if($result->Status=="1") echo "checked"; ?>> Active &nbsp;&nbsp;
<input type="radio" name="status" value="0" <?php if($result->Status=="0") echo "checked"; ?>> Block
</div>
</div>

<?php }} ?>

<!-- Submit -->
<div class="form-group">
<div class="col-sm-offset-2 col-sm-10">
<button type="submit" name="submit" class="btn btn-primary">Update</button>
</div>
</div>

</form>
</div>
</div>
</div>
</div>
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
<script src="js/select2/select2.min.js"></script>
<script src="js/main.js"></script>
<script>
$(document).ready(function(){

    // ✅ Auto-hide alerts
    setTimeout(function(){
        $('.alert-success').fadeOut('slow');
        $('.alert-danger').fadeOut('slow');
    }, 3000);

    // Marka Faculty la beddelo — AJAX departments load
    $('#faculty-select').change(function(){
        var facultyId = $(this).val();
        $('#department-select').html('<option value="">Loading...</option>');

        if(facultyId == ""){
            $('#department-select').html('<option value="">-- Select Faculty First --</option>');
            return;
        }

        $.ajax({
            url: 'get-departments.php',
            type: 'GET',
            data: { faculty_id: facultyId },
            dataType: 'json',
            success: function(data){
                $('#department-select').html('<option value="">Select Department</option>');
                $.each(data, function(i, dept){
                    $('#department-select').append(
                        '<option value="'+dept.id+'">'+dept.DepartmentName+'</option>'
                    );
                });
            },
            error: function(){
                $('#department-select').html('<option value="">Error loading departments</option>');
            }
        });
    });
});
</script>
</body>
</html>

<?php } ?>