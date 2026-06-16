<?php
session_start();
include('includes/config.php');

$msg="";

if(isset($_POST['change']))
{
    $email=$_SESSION['reset_email'];

    $password=md5($_POST['password']);

    $sql="UPDATE admin
          SET Password=:password,
              otp_code=NULL,
              otp_expiry=NULL
          WHERE EmailId=:email";

    $query=$dbh->prepare($sql);

    $query->bindParam(':password',$password,PDO::PARAM_STR);
    $query->bindParam(':email',$email,PDO::PARAM_STR);

    $query->execute();

    $msg="Password Changed Successfully";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>New Password</title>
<link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

<div class="container">

<br><br>

<h3>Create New Password</h3>

<?php if($msg){ ?>
<div class="alert alert-success">
<?php echo $msg; ?>
</div>
<?php } ?>

<form method="post">

<input type="password"
name="password"
class="form-control"
placeholder="New Password"
required>

<br>

<button type="submit"
name="change"
class="btn btn-primary">
Change Password
</button>

</form>

</div>

</body>
</html>