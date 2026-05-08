<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])=="")
{   
    header("Location: index.php"); 
}
else{

$msg = "";
$error = "";

// ===== ADD FACULTY =====
if(isset($_POST['add_faculty'])){
    $facultyName = trim($_POST['faculty_name']);
    $check = $dbh->prepare("SELECT * FROM tblfaculty WHERE FacultyName=:name");
    $check->bindParam(':name', $facultyName, PDO::PARAM_STR);
    $check->execute();
    
    if($check->rowCount() > 0){
        $error = "Faculty already exists!";
    } else {
        $sql = "INSERT INTO tblfaculty(FacultyName) VALUES(:name)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':name', $facultyName, PDO::PARAM_STR);
        if($query->execute()){
            $msg = "Faculty added successfully!";
        } else {
            $error = "Something went wrong!";
        }
    }
}

// ===== UPDATE FACULTY =====
if(isset($_POST['update_faculty'])){
    $id = intval($_POST['faculty_id']);
    $facultyName = trim($_POST['faculty_name_edit']);
    $sql = "UPDATE tblfaculty SET FacultyName=:name WHERE id=:id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':name', $facultyName, PDO::PARAM_STR);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    if($query->execute()){
        $msg = "Faculty updated successfully!";
    } else {
        $error = "Something went wrong!";
    }
}

// ===== DELETE FACULTY =====
if(isset($_GET['delete_faculty'])){
    $id = intval($_GET['delete_faculty']);
    $sql = "DELETE FROM tblfaculty WHERE id=:id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    if($query->execute()){
        $msg = "Faculty deleted successfully!";
    } else {
        $error = "Cannot delete — departments still linked!";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Faculty</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
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
            <h2 class="title">Manage Faculty</h2>
        </div>
    </div>
    <div class="row breadcrumb-div">
        <div class="col-md-6">
            <ul class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                <li class="active">Manage Faculty</li>
            </ul>
        </div>
    </div>
</div>

<div class="container-fluid">

<?php if($msg){ ?>
<div class="alert alert-success"><strong>Success!</strong> <?php echo htmlentities($msg); ?></div>
<?php } if($error){ ?>
<div class="alert alert-danger"><strong>Error!</strong> <?php echo htmlentities($error); ?></div>
<?php } ?>

<div class="row">

    <!-- ADD FACULTY -->
    <div class="col-md-4">
        <div class="panel">
            <div class="panel-heading">
                <div class="panel-title"><h5>Add New Faculty</h5></div>
            </div>
            <div class="panel-body">
                <form method="post">
                    <div class="form-group">
                        <label>Faculty Name</label>
                        <input type="text" name="faculty_name" class="form-control" required placeholder="e.g. Faculty of Engineering">
                    </div>
                    <button type="submit" name="add_faculty" class="btn btn-primary btn-block">
                        <i class="fa fa-plus"></i> Add Faculty
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- FACULTY LIST -->
    <div class="col-md-8">
        <div class="panel">
            <div class="panel-heading">
                <div class="panel-title"><h5>All Faculties</h5></div>
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Faculty Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sql = "SELECT * FROM tblfaculty ORDER BY FacultyName";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $faculties = $query->fetchAll(PDO::FETCH_OBJ);
                    $cnt = 1;
                    foreach($faculties as $fac){ ?>
                    <tr>
                        <td><?php echo $cnt++; ?></td>
                        <td><?php echo htmlentities($fac->FacultyName); ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" 
                                onclick="editFaculty(<?php echo $fac->id; ?>, '<?php echo addslashes($fac->FacultyName); ?>')">
                                <i class="fa fa-edit"></i> Edit
                            </button>
                            <a href="?delete_faculty=<?php echo $fac->id; ?>" 
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Delete this faculty?')">
                                <i class="fa fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
</div>
</div>
</div>
</div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editFacultyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Faculty</h4>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="faculty_id" id="edit_faculty_id">
                    <div class="form-group">
                        <label>Faculty Name</label>
                        <input type="text" name="faculty_name_edit" id="edit_faculty_name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="update_faculty" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="js/jquery/jquery-2.2.4.min.js"></script>
<script src="js/bootstrap/bootstrap.min.js"></script>
<script src="js/pace/pace.min.js"></script>
<script src="js/lobipanel/lobipanel.min.js"></script>
<script src="js/main.js"></script>
<script>
function editFaculty(id, name){
    $('#edit_faculty_id').val(id);
    $('#edit_faculty_name').val(name);
    $('#editFacultyModal').modal('show');
}
</script>
</body>
</html>
<?php } ?>