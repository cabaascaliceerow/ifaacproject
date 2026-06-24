<?php
session_start();
date_default_timezone_set('Africa/Mogadishu');
include('includes/config.php');

$msg = ""; $error = "";

if(isset($_POST['send']))
{
    $email = $_POST['email'];

    $sql = "SELECT * FROM tbllecturer WHERE EmailId=:email AND Status=1";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();

    if($query->rowCount() > 0)
    {
        $otp    = rand(100000, 999999);
        $expiry = date("Y-m-d H:i:s", strtotime("+5 minutes"));

        $update = "UPDATE tbllecturer
                   SET otp_code=:otp, otp_expiry=:expiry
                   WHERE EmailId=:email";
        $up = $dbh->prepare($update);
        $up->bindParam(':otp',    $otp,    PDO::PARAM_STR);
        $up->bindParam(':expiry', $expiry, PDO::PARAM_STR);
        $up->bindParam(':email',  $email,  PDO::PARAM_STR);
        $up->execute();

        $_SESSION['reset_email_lecturer'] = $email;

        echo "<script>alert('OTP: $otp');</script>";
        header("refresh:1;url=lecturer-verify-otp.php");
    }
    else
    {
        $error = "Email not found. Please check and try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lecturer Forgot Password | Kownayn</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; padding-top: 80px; }
        .navbar-custom { background: linear-gradient(90deg, #0f5132, #198754); }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark shadow fixed-top navbar-custom">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="home.php">
            <img src="images/logokowneyn.jpg" alt="Logo"
                 style="height:45px; border-radius:5px;">
            <span style="font-weight:bold;">KOWNAYN UNIVERSITY</span>
        </a>
    </div>
</nav>

<!-- FORM -->
<section class="bg-light py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="shadow p-4 rounded-3 bg-white">
                    <div class="text-center mb-4">
                        <i class="fa fa-lock fa-3x text-success"></i>
                        <h4 class="mt-2">Forgot Password</h4>
                        <p class="text-muted">
                            Enter your email — OTP code will appear on screen
                        </p>
                    </div>

                    <?php if($error): ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                    <?php endif; ?>

                    <form method="post">
                        <div class="mb-3">
                            <label>Email Address</label>
                            <input type="email" name="email"
                                   class="form-control"
                                   placeholder="Enter your registered email"
                                   required>
                        </div>
                        <button type="submit" name="send"
                                class="btn btn-success w-100">
                            <i class="fa fa-paper-plane"></i> Send OTP
                        </button>
                        <div class="text-center mt-3">
                            <a href="lecturer-login.php">
                                <i class="fa fa-arrow-left"></i> Back to Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>