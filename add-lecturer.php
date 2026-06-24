<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin']) == ""){
    header("Location: index.php");
    exit;
}

$msg = ""; $error = "";

if(isset($_POST['submit']))
{
    $name    = trim($_POST['lecturername']);
    $uname   = trim($_POST['username']);
    $pass    = md5(trim($_POST['password']));
    $email   = trim($_POST['email']);
    $faculty = intval($_POST['faculty']);
    $dept    = intval($_POST['department']);

    // Check duplicate username
    $chk = $dbh->prepare("SELECT id FROM tbllecturer WHERE UserName=:u");
    $chk->execute([':u' => $uname]);

    if($chk->rowCount() > 0){
        $error = "Username already exists. Please choose another.";
    } else {
        $ins = $dbh->prepare("
            INSERT INTO tbllecturer
              (LecturerName, UserName, Password, EmailId, FacultyId, DepartmentId, Status)
            VALUES
              (:name, :uname, :pass, :email, :fid, :did, 1)
        ");
        $ins->execute([
            ':name'  => $name,
            ':uname' => $uname,
            ':pass'  => $pass,
            ':email' => $email,
            ':fid'   => $faculty,
            ':did'   => $dept,
        ]);
        $msg = "Lecturer added successfully!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Lecturer | Kownayn</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/animate-css/animate.min.css">
    <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/modernizr/modernizr.min.js"></script>
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
</head>
<body class="top-navbar-fixed">
<div class="main-wrapper">

<?php include('includes/topbar.php'); ?>

<div class="content-wrapper">
    <div class="content-container">
        <?php include('includes/leftbar.php'); ?>

        <div class="main-page">

            <!-- PAGE TITLE -->
            <div class="container-fluid">
                <div class="row page-title-div">
                    <div class="col-md-6">
                        <h2 class="title">Add Lecturer</h2>
                    </div>
                </div>
                <div class="row breadcrumb-div">
                    <div class="col-md-6">
                        <ul class="breadcrumb">
                            <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                            <li class="active">Add Lecturer</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- FORM -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="panel">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <i class="fa fa-plus"></i> Add New Lecturer
                                </h3>
                            </div>
                            <div class="panel-body">

                                <?php if($msg): ?>
                                <div class="alert alert-success alert-dismissible">
                                    <button class="close" data-dismiss="alert">&times;</button>
                                    <strong>Success!</strong> <?php echo htmlentities($msg); ?>
                                </div>
                                <?php endif; ?>
                                <?php if($error): ?>
                                <div class="alert alert-danger alert-dismissible">
                                    <button class="close" data-dismiss="alert">&times;</button>
                                    <strong>Error!</strong> <?php echo htmlentities($error); ?>
                                </div>
                                <?php endif; ?>

                                <form method="post" class="form-horizontal">

                                    <!-- Full Name -->
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">
                                            Full Name <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-sm-7">
                                            <input type="text" name="lecturername"
                                                   class="form-control"
                                                   placeholder="Enter Lecturer Full Name"
                                                   required>
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">
                                            Email <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-sm-7">
                                            <input type="email" name="email"
                                                   class="form-control"
                                                   placeholder="Enter Email Address"
                                                   required>
                                        </div>
                                    </div>

                                    <!-- Faculty -->
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">
                                            Faculty <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-sm-7">
                                            <select name="faculty" id="sel-faculty"
                                                    class="form-control" required>
                                                <option value="">-- Select Faculty --</option>
                                                <?php
                                                $q = $dbh->query("SELECT * FROM tblfaculty ORDER BY FacultyName");
                                                foreach($q->fetchAll(PDO::FETCH_OBJ) as $f){
                                                    echo '<option value="'.(int)$f->id.'">'.htmlentities($f->FacultyName).'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Department -->
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">
                                            Department <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-sm-7">
                                            <select name="department" id="sel-dept"
                                                    class="form-control" required>
                                                <option value="">-- Select Faculty First --</option>
                                            </select>
                                        </div>
                                    </div>

                                    <hr>

                                    <!-- Username -->
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">
                                            Username <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-sm-7">
                                            <input type="text" name="username"
                                                   class="form-control"
                                                   placeholder="Enter Login Username"
                                                   required>
                                            <small class="text-muted">
                                                This is what the lecturer will use to login.
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Password -->
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">
                                            Password <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-sm-7">
                                            <input type="text" name="password"
                                                   class="form-control"
                                                   placeholder="Enter Login Password"
                                                   required>
                                            <small class="text-muted">
                                                Share this password with the lecturer after creation.
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Submit -->
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-7">
                                            <button type="submit" name="submit"
                                                    class="btn btn-primary">
                                                <i class="fa fa-plus"></i> Add Lecturer
                                            </button>
                                            <a href="manage-lecturers.php"
                                               class="btn btn-default">
                                                <i class="fa fa-list"></i> View All
                                            </a>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /FORM -->

        </div>
    </div>
</div>
</div>

<script src="js/bootstrap/bootstrap.min.js"></script>
<script src="js/pace/pace.min.js"></script>
<script src="js/lobipanel/lobipanel.min.js"></script>
<script src="js/iscroll/iscroll.js"></script>
<script src="js/main.js"></script>
<script>
// Faculty → Department AJAX
$('#sel-faculty').on('change', function(){
    var fid = $(this).val();
    $('#sel-dept').html('<option value="">Loading...</option>');
    if(!fid){
        $('#sel-dept').html('<option value="">-- Select Faculty First --</option>');
        return;
    }
    $.ajax({
        url: 'get-departments.php',
        type: 'GET',
        data: { faculty_id: fid },
        dataType: 'json',
        success: function(data){
            var opts = '<option value="">-- Select Department --</option>';
            $.each(data, function(i, d){
                opts += '<option value="'+d.id+'">'+d.DepartmentName+'</option>';
            });
            $('#sel-dept').html(opts);
        }
    });
});

setTimeout(function(){ $('.alert').fadeOut('slow'); }, 4000);
</script>
</body>
</html>