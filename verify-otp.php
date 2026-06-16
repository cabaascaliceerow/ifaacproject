<?php
session_start();
include('includes/config.php');

$error="";

if(isset($_POST['verify']))
{
    $email=$_SESSION['reset_email'];

    $otp=$_POST['otp'];

    $sql="SELECT * FROM admin
          WHERE EmailId=:email
          AND otp_code=:otp
          AND otp_expiry > NOW()";

    $query=$dbh->prepare($sql);

    $query->bindParam(':email',$email,PDO::PARAM_STR);
    $query->bindParam(':otp',$otp,PDO::PARAM_STR);

    $query->execute();

    if($query->rowCount()>0)
    {
        header("Location:new-password.php");
    }
    else
    {
        $error="Invalid OTP";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Verify OTP</title>
<link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

<div class="container">

<br><br>

<h3>Verify OTP</h3>

<?php if($error){ ?>
<div class="alert alert-danger">
<?php echo $error; ?>
</div>
<?php } ?>

<form method="post">

<input type="text"
name="otp"
class="form-control"
placeholder="Enter OTP"
required>

<br>

<button type="submit"
name="verify"
class="btn btn-success">
Verify OTP
</button>

</form>

</div>

</body>
</html>