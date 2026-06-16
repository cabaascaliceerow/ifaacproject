<?php
session_start();

date_default_timezone_set('Africa/Mogadishu');

include('includes/config.php');
$msg="";
$error="";

if(isset($_POST['send']))
{
    $email=$_POST['email'];
    

    $sql="SELECT * FROM admin WHERE EmailId=:email";
    $query=$dbh->prepare($sql);
    $query->bindParam(':email',$email,PDO::PARAM_STR);
    $query->execute();

    if($query->rowCount()>0)
    {
        $otp=rand(100000,999999);

        $expiry=date("Y-m-d H:i:s",strtotime("+5 minutes"));

        $update="UPDATE admin
                 SET otp_code=:otp,
                     otp_expiry=:expiry
                 WHERE EmailId=:email";

        $up=$dbh->prepare($update);

        $up->bindParam(':otp',$otp,PDO::PARAM_STR);
        $up->bindParam(':expiry',$expiry,PDO::PARAM_STR);
        $up->bindParam(':email',$email,PDO::PARAM_STR);

        $up->execute();

        $_SESSION['reset_email']=$email;

        echo "<script>alert('OTP: $otp');</script>";

        header("refresh:1;url=verify-otp.php");
    }
    else
    {
        $error="Email not found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Forgot Password</title>
<link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

<div class="container">
<br><br>

<h3>Forgot Password</h3>

<?php if($error){ ?>
<div class="alert alert-danger">
<?php echo $error; ?>
</div>
<?php } ?>

<form method="post">

<input type="email"
name="email"
class="form-control"
placeholder="Enter Email"
required>

<br>

<button type="submit"
name="send"
class="btn btn-primary">
Send OTP
</button>

</form>

</div>

</body>
</html>