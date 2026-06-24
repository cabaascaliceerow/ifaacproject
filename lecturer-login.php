<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(isset($_SESSION['llogin']) && $_SESSION['llogin'] != ''){
    header("Location: lecturer-dashboard.php");
    exit;
}

if(isset($_POST['login']))
{
    $uname    = $_POST['username'];
    $password = md5($_POST['password']);

    $sql = "SELECT id, LecturerName, UserName, Status 
            FROM tbllecturer 
            WHERE UserName=:uname AND Password=:password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':uname',    $uname,    PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if($query->rowCount() > 0){
        if($results[0]->Status == 1){
            $_SESSION['llogin']        = $results[0]->UserName;
            $_SESSION['lecturer_id']   = $results[0]->id;
            $_SESSION['lecturer_name'] = $results[0]->LecturerName;
            echo "<script>document.location='lecturer-dashboard.php';</script>";
        } else {
            echo "<script>alert('Account disabled. Contact Administrator.');</script>";
        }
    } else {
        echo "<script>alert('Invalid Username or Password.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kownayn University | Lecturer Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/main.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; padding-top: 80px; }
        .navbar-custom {
            background: linear-gradient(90deg, #0f5132, #198754);
        }
        .hero {
            height: 90px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa;
        }
        footer { background-color: #212529; color: #fff; }
        footer a { color: #fff; text-decoration: none; }
        footer a:hover { color: #0d6efd; }
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
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="find-result.php">Result</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php">Admin</a></li>
                <li class="nav-item">
                    <a class="nav-link active" href="lecturer-login.php">Lecturer</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="container text-center">
        <h1 class="display-6">Lecturer Login — Kownayn Result Management System</h1>
    </div>
</section>

<!-- LOGIN FORM -->
<section class="bg-light py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="shadow p-4 rounded-3 bg-white">
                    <div class="text-center mb-4">
                        <i class="fa fa-user-circle-o fa-3x text-success"></i>
                        <h4 class="mt-2">Lecturer Login</h4>
                    </div>
                    <form action="" method="post">
                        <div class="mb-3">
                            <label>Username</label>
                            <input type="text" name="username"
                                   class="form-control"
                                   placeholder="Enter Username" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password"
                                   class="form-control"
                                   placeholder="Enter Password" required>
                        </div>
                        <button type="submit" name="login"
                                class="btn btn-success w-100">
                            <i class="fa fa-sign-in"></i> Sign In
                        </button>
                    </form>
                    <div class="text-center mt-3">
                        <small class="text-muted">
                           <div class="text-center mt-3">
    <a href="lecturer-forgot-password.php">Forgot Password?</a>
</div>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="pt-4 pb-2">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>Kownayn University</h5>
                <p class="text-muted">Digital academic solutions.</p>
            </div>
            <div class="col-md-4">
                <h6>Links</h6>
                <a href="home.php"           class="d-block">Home</a>
                <a href="find-result.php"    class="d-block">Results</a>
                <a href="index.php"          class="d-block">Admin</a>
                <a href="lecturer-login.php" class="d-block">Lecturer</a>
            </div>
            <div class="col-md-4">
                <h6>Contact</h6>
                <p>Email: info@kownayn.edu</p>
                <div class="d-flex gap-3 mt-2">
                    <a href="https://wa.me/252612139804"
                       target="_blank" class="text-success fs-4">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                    <a href="https://facebook.com/yourpage"
                       target="_blank" class="text-primary fs-4">
                        <i class="bi bi-facebook"></i>
                    </a>
                </div>
            </div>
        </div>
        <hr>
        <p class="text-center mb-0">&copy; 2026 Kownayn University</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>