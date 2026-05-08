<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kownayn University | Student Result System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        html { scroll-behavior: smooth; }

        body {
            font-family: 'Segoe UI', sans-serif;
            padding-top: 80px;
        }
        a i {
    transition: 0.3s;
}
a i:hover {
    transform: scale(1.3);
}

        .hero {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
            url('kownayn_university_cover.jpg');
            background-size: cover;
            background-position: center;
            height: 85vh;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .card {
            background-color:#ececec ;
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark shadow fixed-top"
     style="background: linear-gradient(90deg, #0f5132, #198754);">

    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="#home">
            <img src="images/logokowneyn.jpg" style="height:45px;">
            <b>KOWNAYN UNIVERSITY</b>
        </a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="find-result.php">Results</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php">Admin</a></li>
                <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- HERO -->
<section id="home" class="hero">
    <div class="container">
        <h1>Student Result Management System</h1>
        <p>Official Academic Result Portal – Kownayn University</p>
        <a href="find-result.php" class="btn btn-success btn-lg">Check Your Result</a>
    </div>
</section>

<!-- FEATURES -->
<section class="py-5 text-center">
    <div class="container">
        <h2 class="fw-bold">System Features</h2>
        <p class="text-muted mb-5">Modern tools for managing academic results</p>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 rounded-4">
                    <img src="OIP (1).webp" style="width:80px;">
                    <h5 class="mt-3">Result Management</h5>
                    <p class="text-muted">Manage and publish student results easily.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 rounded-4">
                    <img src="OIP.webp" style="width:80px;">
                    <h5 class="mt-3">Secure Database</h5>
                    <p class="text-muted">All data is safely stored and protected.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 rounded-4">
                    <img src="download.webp" style="width:80px;">
                    <h5 class="mt-3">Responsive Design</h5>
                    <p class="text-muted">Works on mobile, tablet and desktop.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ABOUT -->
<section id="about" class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-md-6">
                <h2 class="fw-bold">About the System</h2>
                <p class="text-muted">
                    This system helps Kownayn University manage results digitally.
                    It improves speed, accuracy, and accessibility.
                </p>
                <a href="find-result.php" class="btn btn-success">Check Results</a>
            </div>

            <div class="col-md-6 text-center">
                <img src="OIP (1).webp" class="img-fluid rounded shadow">
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>