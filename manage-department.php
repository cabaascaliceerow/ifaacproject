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

// ===== ADD DEPARTMENT =====
if(isset($_POST['add_department'])){
    $deptName = trim($_POST['dept_name']);
    $facultyId = intval($_POST['faculty_id']);

    $check = $dbh->prepare("SELECT * FROM tbldepartment WHERE DepartmentName=:name AND FacultyId=:fid");
    $check->bindParam(':name', $deptName, PDO::PARAM_STR);
    $check->bindParam(':fid', $facultyId, PDO::PARAM_INT);
    $check->execute();

    if($check->rowCount() > 0){
        $error = "Department already exists in this Faculty!";
    } else {
        $sql = "INSERT INTO tbldepartment(DepartmentName, FacultyId) VALUES(:name, :fid)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':name', $deptName, PDO::PARAM_STR);
        $query->bindParam(':fid', $facultyId, PDO::PARAM_INT);
        if($query->execute()){
            $msg = "Department added successfully!";
        } else {
            $error = "Something went wrong!";
        }
    }
}

// ===== UPDATE DEPARTMENT =====
if(isset($_POST['update_department'])){
    $id = intval($_POST['dept_id']);
    $deptName = trim($_POST['dept_name_edit']);
    $facultyId = intval($_POST['faculty_id_edit']);

    $sql = "UPDATE tbldepartment SET DepartmentName=:name, FacultyId=:fid WHERE id=:id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':name', $deptName, PDO::PARAM_STR);
    $query->bindParam(':fid', $facultyId, PDO::PARAM_INT);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    if($query->execute()){
        $msg = "Department updated successfully!";
    } else {
        $error = "Something went wrong!";
    }
}

// ===== DELETE DEPARTMENT =====
if(isset($_GET['delete_dept'])){
    $id = intval($_GET['delete_dept']);
    $sql = "DELETE FROM tbldepartment WHERE id=:id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    if($query->execute()){
        $msg = "Department deleted successfully!";
    } else {
        $error = "Something went wrong!";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Department</title>
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
            <h2 class="title">Manage Department</h2>
        </div>
    </div>
    <div class="row breadcrumb-div">
        <div class="col-md-6">
            <ul class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                <li class="active">Manage Department</li>
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

    <!-- ADD DEPARTMENT -->
    <div class="col-md-4">
        <div class="panel">
            <div class="panel-heading">
                <div class="panel-title"><h5>Add New Department</h5></div>
            </div>
            <div class="panel-body">
                <form method="post">
                    <div class="form-group">
                        <label>Faculty</label>
                        <select name="faculty_id" class="form-control" required>
                            <option value="">Select Faculty</option>
                            <?php
                            $sql = "SELECT * FROM tblfaculty ORDER BY FacultyName";
                            $query = $dbh->prepare($sql);
                            $query->execute();
                            $faculties = $query->fetchAll(PDO::FETCH_OBJ);
                            foreach($faculties as $fac){ ?>
                            <option value="<?php echo $fac->id; ?>">
                                <?php echo htmlentities($fac->FacultyName); ?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Department Name</label>
                        <input type="text" name="dept_name" class="form-control" required placeholder="e.g. Computer Science">
                    </div>
                    <button type="submit" name="add_department" class="btn btn-primary btn-block">
                        <i class="fa fa-plus"></i> Add Department
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- DEPARTMENT LIST -->
    <div class="col-md-8">
        <div class="panel">
            <div class="panel-heading">
                <div class="panel-title"><h5>All Departments</h5></div>
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Department Name</th>
                            <th>Faculty</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sql = "SELECT d.*, f.FacultyName 
                            FROM tbldepartment d 
                            LEFT JOIN tblfaculty f ON d.FacultyId = f.id 
                            ORDER BY f.FacultyName, d.DepartmentName";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $depts = $query->fetchAll(PDO::FETCH_OBJ);
                    $cnt = 1;
                    foreach($depts as $dept){ ?>
                    <tr>
                        <td><?php echo $cnt++; ?></td>
                        <td><?php echo htmlentities($dept->DepartmentName); ?></td>
                        <td><?php echo htmlentities($dept->FacultyName); ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm"
                                onclick="editDept(<?php echo $dept->id; ?>, 
                                '<?php echo addslashes($dept->DepartmentName); ?>', 
                                <?php echo $dept->FacultyId; ?>)">
                                <i class="fa fa-edit"></i> Edit
                            </button>
                            <a href="?delete_dept=<?php echo $dept->id; ?>" 
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Delete this department?')">
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
<div class="modal fade" id="editDeptModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Department</h4>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="dept_id" id="edit_dept_id">
                    <div class="form-group">
                        <label>Faculty</label>
                        <select name="faculty_id_edit" id="edit_faculty_id" class="form-control" required>
                            <option value="">Select Faculty</option>
                            <?php
                            foreach($faculties as $fac){ ?>
                            <option value="<?php echo $fac->id; ?>">
                                <?php echo htmlentities($fac->FacultyName); ?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Department Name</label>
                        <input type="text" name="dept_name_edit" id="edit_dept_name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="update_department" class="btn btn-primary">Update</button>
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
function editDept(id, name, facultyId){
    $('#edit_dept_id').val(id);
    $('#edit_dept_name').val(name);
    $('#edit_faculty_id').val(facultyId);
    $('#editDeptModal').modal('show');
}
</script>
</body>
</html>
<?php } ?>