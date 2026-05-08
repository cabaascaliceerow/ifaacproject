 <?php
session_start();
error_reporting(0);
include('includes/config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kownayn University | Student Result System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

   

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/icheck/skins/flat/blue.css">
    <link rel="stylesheet" href="css/main.css">

    <style>
        html { scroll-behavior: smooth; }
        body { font-family: 'Segoe UI', sans-serif; padding-top: 80px; }


        /* ================= LOGIN FORM ===========…
[9:12 PM, 2/17/2026] Eng Abas Ali Eyrow: <?php
session_start();
error_reporting(0);
include('includes/config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kownayn University | Student Result System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/icheck/skins/flat/blue.css">
    <link rel="stylesheet" href="css/main.css">

    <style>
        html { scroll-behavior: smooth; }
        body { font-family: 'Segoe UI', sans-serif; padding-top: 80px; }

        /* ================= HERO ================= */
        .hero {
            height: 90px; /* Hero-ka qaado 60% screen height */
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            background-color: light;
            color: white;
        }

        /* ================= LOGIN FORM ================= */
        #login {
            padding-top: 50px; /* Space ka hore login form-ka */
            padding-bottom: 50px;
        }
        .login-box {
            /* Remove negative margin */
            margin-top: 0;
        }

        /* ================= FOOTER ================= */
      footer {
    background-color: #212529; /* dark */
    color: #ffffff;
}

footer h5,
footer h6 {
    color: #ffffff;
    font-weight: bold;
}

footer p {
    color: #dddddd;
}

footer a {
    color: #ffffff;
    text-decoration: none;
}

footer a:hover {
    color: #0d6efd; /* blue hover */
}
    </style>
</head>
<body>

<!-- ================= NAVBAR ================= -->
<nav class="navbar navbar-expand-lg navbar-dark shadow fixed-top" style="background: linear-gradient(90deg, #0f5132, #198754);">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="#home">
            <img src="images/logokowneyn.jpg" alt="Logo" style="height:45px; border-radius:5px;">
            <span style="font-weight:bold;">KOWNAYN UNIVERSITY</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#"> Result</a></li>
                 <li class="nav-item"><a class="nav-link" href="index.php">Admin</a></li>
                <li class="nav-item"><a class="nav-link" href="#about">About</a></li>

            </ul>
        </div>
    </div>
</nav>

<!-- ================= HERO ================= -->
<section id="home" class="hero">
    <div class="container">
        <h1 class="display-5">School Result Management System</h1>
    </div>
</section>

<!-- ================= LOGIN FORM ================= -->
<section id="login" class="bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="panel login-box shadow p-4 rounded-3">
                    <div class="panel-heading text-center mb-3">
                        <h4>Enter Your Details</h4>
                    </div>
                    <div class="panel-body">
                        <form action="result.php" method="post">
                            <div class="form-group mb-3">
                                <label for="rollid">Roll Id</label>
                                <input type="text" class="form-control" id="rollid" placeholder="Enter Your Roll Id" autocomplete="off" name="rollid">
                            </div>
                            <div class="form-group mb-3">
                                <label for="default">Class</label>
                                <select name="class" class="form-control" id="default" required>
                                    <option value="">Select Class</option>
                                    <?php 
                                    $sql = "SELECT * from tblclasses";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                                    if($query->rowCount() > 0){
                                        foreach($results as $result){ ?>
                                            <option value="<?php echo htmlentities($result->id); ?>">
                                                <?php echo htmlentities($result->ClassName); ?> 
                                            </option>
                                    <?php }} ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Search <i class="fa fa-check"></i></button>
                            <div class="mt-2 text-center"><a href="#home">Back to Home</a></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- FOOTER -->
<footer class="bg-dark text-light pt-4 pb-2">
    <div class="container">
        <div class="row">

            <div class="col-md-4">
                <h5>Kownayn University</h5>
                <p class="text-muted">Digital academic solutions.</p>
            </div>

            <div class="col-md-4">
                <h6>Links</h6>
                <a href="home.php" class="d-block text-light">Home</a>
                <a href="find-result.php" class="d-block text-light">Results</a>
                 <a href="index.php" class="d-block text-light">Admin</a>
                <a href="#about" class="d-block text-light">About</a>
            </div>

            <div class="col-md-4">
                <h6>Contact</h6>
                <p>Email: info@kownayn.edu</p>

                <div class="d-flex gap-3 mt-3">

                    <!-- WhatsApp -->
                    <a href="https://wa.me/252612139804" target="_blank" class="text-success fs-4">
                        <i class="bi bi-whatsapp"></i>
                    </a>

                    <!-- Facebook -->
                    <a href="https://facebook.com/yourpage" target="_blank" class="text-primary fs-4">
                        <i class="bi bi-facebook"></i>
                    </a>

                </div>
            </div>

        </div>

        <hr>

        <p class="text-center">© 2026 Kownayn University</p>
    </div>
</footer>

<!-- Bootstrap + JS -->
 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/jquery/jquery-2.2.4.min.js"></script>
<script src="js/icheck/icheck.min.js"></script>
<script>
    // Auto close mobile navbar after click
    const navLinks = document.querySelectorAll('.navbar-collapse .nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            const navbarCollapse = document.querySelector('.navbar-collapse');
            if(navbarCollapse.classList.contains('show')){
                new bootstrap.Collapse(navbarCollapse).hide();
            }
        });
    });

    // iCheck style init
    $(function(){
        $('input.flat-blue-style').iCheck({ checkboxClass: 'icheckbox_flat-blue' });
    });
</script>

</body>
</html>