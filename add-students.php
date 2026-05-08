<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])=="")
    {   
    header("Location: index.php"); 
    }
    else{
if(isset($_POST['submit']))
{
$studentname=$_POST['fullanme'];
$roolid=$_POST['rollid']; 
$studentemail=$_POST['emailid']; 
$gender=$_POST['gender']; 
$classid=$_POST['class']; 
$dob=$_POST['dob'];
$faculty=$_POST['faculty'];
$department=$_POST['department'];
$status=1;

// ✅ Hubi RollId hore loo isticmaalay
$check = $dbh->prepare("SELECT * FROM tblstudents WHERE RollId=:rollid");
$check->bindParam(':rollid', $roolid, PDO::PARAM_STR);
$check->execute();

if($check->rowCount() > 0){
    $error = "This Roll ID already exists! Please use a different Roll ID.";
} else {

$sql="INSERT INTO tblstudents(StudentName,RollId,StudentEmail,Gender,ClassId,DOB,Faculty,Department,Status) 
      VALUES(:studentname,:roolid,:studentemail,:gender,:classid,:dob,:faculty,:department,:status)";

$query = $dbh->prepare($sql);
$query->bindParam(':studentname',$studentname,PDO::PARAM_STR);
$query->bindParam(':roolid',$roolid,PDO::PARAM_STR);
$query->bindParam(':studentemail',$studentemail,PDO::PARAM_STR);
$query->bindParam(':gender',$gender,PDO::PARAM_STR);
$query->bindParam(':classid',$classid,PDO::PARAM_STR);
$query->bindParam(':dob',$dob,PDO::PARAM_STR);
$query->bindParam(':faculty',$faculty,PDO::PARAM_INT);
$query->bindParam(':department',$department,PDO::PARAM_INT);
$query->bindParam(':status',$status,PDO::PARAM_STR);
$query->execute();
$lastInsertId = $dbh->lastInsertId();
if($lastInsertId)
{
$msg="Student info added successfully";
}
else 
{
$error="Something went wrong. Please try again";
}

} // end else
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMS Admin | Student Admission</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen">
    <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen">
    <link rel="stylesheet" href="css/prism/prism.css" media="screen">
    <link rel="stylesheet" href="css/select2/select2.min.css">
    <link rel="stylesheet" href="css/main.css" media="screen">
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
                            <h2 class="title">Student Admission</h2>
                        </div>
                    </div>
                    <div class="row breadcrumb-div">
                        <div class="col-md-6">
                            <ul class="breadcrumb">
                                <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                <li class="active">Student Admission</li>
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

                                <?php if($msg){?>
                                <div class="alert alert-success left-icon-alert" role="alert">
                                    <strong>Well done!</strong> <?php echo htmlentities($msg); ?>
                                </div>
                                <?php } else if($error){?>
                                <div class="alert alert-danger left-icon-alert" role="alert">
                                    <strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
                                </div>
                                <?php } ?>

                                <form class="form-horizontal" method="post">

                                <!-- Full Name -->
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Full Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="fullanme" class="form-control" required autocomplete="off">
                                    </div>
                                </div>

                                <!-- Roll Id -->
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Roll Id</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="rollid" class="form-control" maxlength="5" required autocomplete="off">
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Email Id</label>
                                    <div class="col-sm-10">
                                        <input type="email" name="emailid" class="form-control" required autocomplete="off">
                                    </div>
                                </div>

                                <!-- Gender -->
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Gender</label>
                                    <div class="col-sm-10" style="padding-top:7px;">
                                        <input type="radio" name="gender" value="Male" required checked> Male &nbsp;&nbsp;
                                        <input type="radio" name="gender" value="Female" required> Female
                                    </div>
                                </div>

                                <!-- Faculty -->
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Faculty</label>
                                    <div class="col-sm-10">
                                        <select name="faculty" class="form-control" id="faculty-select" required>
                                            <option value="">Select Faculty</option>
                                            <?php 
                                            $sql = "SELECT * FROM tblfaculty ORDER BY FacultyName";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                                            foreach($results as $result){ ?>
                                            <option value="<?php echo $result->id; ?>">
                                                <?php echo htmlentities($result->FacultyName); ?>
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
                                            <option value="">-- Marka hore Faculty dooro --</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Semester -->
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Semester</label>
                                    <div class="col-sm-10">
                                        <select name="class" class="form-control" required>
                                            <option value="">Select Semester</option>
                                            <?php 
                                            $sql = "SELECT * FROM tblclasses";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                                            foreach($results as $result){ ?>
                                            <option value="<?php echo htmlentities($result->id); ?>">
                                                <?php echo htmlentities($result->ClassName); ?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- DOB -->
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">DOB</label>
                                    <div class="col-sm-10">
                                        <input type="date" name="dob" class="form-control">
                                    </div>
                                </div>

                                <!-- Submit -->
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" name="submit" class="btn btn-primary">Add Student</button>
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
    $('#faculty-select').change(function(){
        var facultyId = $(this).val();
        var deptSelect = $('#department-select');

        deptSelect.html('<option value="">Loading...</option>');

        if(facultyId == ""){
            deptSelect.html('<option value="">-- Select Faculty First --</option>');
            return;
        }

        $.ajax({
            url: 'get-departments.php',
            type: 'GET',
            data: { faculty_id: facultyId },
            dataType: 'json',
            success: function(data){
                deptSelect.html('<option value="">Select Department</option>');
                if(data.length > 0){
                    $.each(data, function(i, dept){
                        deptSelect.append(
                            '<option value="'+dept.id+'">'+dept.DepartmentName+'</option>'
                        );
                    });
                } else {
                    deptSelect.html('<option value="">No departments found</option>');
                }
            },
            error: function(){
                deptSelect.html('<option value="">Error loading departments</option>');
            }
        });
    });
});
</script>
<script>
$(document).ready(function(){

    // ✅ Auto-hide alerts
    setTimeout(function(){
        $('.alert-danger').fadeOut('slow');
        $('.alert-success').fadeOut('slow');
    }, 3000); // 3 ilbiriqsi kadib ayuu qarsoomaa

    $('#faculty-select').change(function(){
        var facultyId = $(this).val();
        var deptSelect = $('#department-select');

        deptSelect.html('<option value="">Loading...</option>');

        if(facultyId == ""){
            deptSelect.html('<option value="">-- Select Faculty First --</option>');
            return;
        }

        $.ajax({
            url: 'get-departments.php',
            type: 'GET',
            data: { faculty_id: facultyId },
            dataType: 'json',
            success: function(data){
                deptSelect.html('<option value="">Select Department</option>');
                if(data.length > 0){
                    $.each(data, function(i, dept){
                        deptSelect.append(
                            '<option value="'+dept.id+'">'+dept.DepartmentName+'</option>'
                        );
                    });
                } else {
                    deptSelect.html('<option value="">No departments found</option>');
                }
            },
            error: function(){
                deptSelect.html('<option value="">Error loading departments</option>');
            }
        });
    });
});
</script>
</body>
</html>
<?php } ?>