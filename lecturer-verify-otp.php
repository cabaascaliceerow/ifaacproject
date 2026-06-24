<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(!isset($_SESSION['reset_email_lecturer'])){
    header("Location: lecturer-forgot-password.php");
    exit;
}

$msg = ""; $error = "";
$email = $_SESSION['reset_email_lecturer'];
$step  = isset($_SESSION['otp_verified_lecturer']) ? 2 : 1;

// STEP 1: Verify OTP
if(isset($_POST['verify_otp'])){
    $otp = trim($_POST['otp']);
    $now = date('Y-m-d H:i:s');

    $chk = $dbh->prepare("
        SELECT id FROM tbllecturer
        WHERE EmailId=:email AND otp_code=:otp AND otp_expiry >= :now
    ");
    $chk->execute([':email'=>$email, ':otp'=>$otp, ':now'=>$now]);

    if($chk->rowCount() == 0){
        $error = "Invalid or expired OTP. Please try again.";
    } else {
        $_SESSION['otp_verified_lecturer'] = true;
        $step = 2;
    }
}

// STEP 2: Reset Password
if(isset($_POST['reset_password'])){
    $newpass  = trim($_POST['newpassword']);
    $confpass = trim($_POST['confirmpassword']);

    if($newpass !== $confpass){
        $error = "Passwords do not match!";
        $step  = 2;
    } elseif(strlen($newpass) < 4){
        $error = "Password must be at least 4 characters!";
        $step  = 2;
    } else {
        $hashed = md5($newpass);
        $dbh->prepare("
            UPDATE tbllecturer
            SET Password=:pass, otp_code=NULL, otp_expiry=NULL
            WHERE EmailId=:email
        ")->execute([':pass'=>$hashed, ':email'=>$email]);

        unset($_SESSION['reset_email_lecturer']);
        unset($_SESSION['otp_verified_lecturer']);

        echo "<script>
            alert('Password reset successfully! Please login.');
            document.location='lecturer-login.php';
        </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kownayn University | Reset Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; padding-top: 80px; }
        .navbar-custom { background: linear-gradient(90deg, #0f5132, #198754); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark shadow fixed-top navbar-custom">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="home.php">
            <img src="images/logokowneyn.jpg" alt="Logo" style="height:45px; border-radius:5px;">
            <span style="font-weight:bold;">KOWNAYN UNIVERSITY</span>
        </a>
    </div>
</nav>

<section class="bg-light py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="shadow p-4 rounded-3 bg-white">

                    <?php if($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if($msg): ?>
                    <div class="alert alert-success"><?php echo $msg; ?></div>
                    <?php endif; ?>

                    <?php if($step == 1): ?>
                    <!-- STEP 1: OTP -->
                    <div class="text-center mb-4">
                        <i class="fa fa-envelope fa-3x text-success"></i>
                        <h4 class="mt-2">Enter OTP Code</h4>
                        <p class="text-muted">
                            OTP code was sent to:<br>
                            <strong><?php echo htmlentities($email); ?></strong>
                        </p>
                    </div>
                    <form method="post">
                        <div class="mb-3">
                            <label>OTP Code</label>
                            <input type="text" name="otp"
                                   class="form-control text-center"
                                   placeholder="Enter 6-digit OTP"
                                   maxlength="6" required>
                        </div>
                        <button type="submit" name="verify_otp"
                                class="btn btn-success w-100">
                            <i class="fa fa-check"></i> Verify OTP
                        </button>
                        <div class="text-center mt-3">
                            <a href="lecturer-forgot-password.php">
                                <i class="fa fa-refresh"></i> Resend OTP
                            </a>
                        </div>
                    </form>

                    <?php else: ?>
                    <!-- STEP 2: New Password -->
                    <div class="text-center mb-4">
                        <i class="fa fa-key fa-3x text-success"></i>
                        <h4 class="mt-2">Set New Password</h4>
                    </div>
                    <form method="post">
                        <div class="mb-3">
                            <label>New Password</label>
                            <input type="password" name="newpassword"
                                   class="form-control"
                                   placeholder="Enter new password"
                                   required>
                        </div>
                        <div class="mb-3">
                            <label>Confirm Password</label>
                            <input type="password" name="confirmpassword"
                                   class="form-control"
                                   placeholder="Confirm new password"
                                   required>
                        </div>
                        <button type="submit" name="reset_password"
                                class="btn btn-success w-100">
                            <i class="fa fa-save"></i> Reset Password
                        </button>
                    </form>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>