<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .navbar {
            background-color: #2aa9e0;
        }

        .navbar .navbar-brand img {
            width: 120px;
        }

        .navbar-nav .nav-link {
            color: white;
            font-weight: 500;
            margin-right: 20px;
            font-size: 20px;
            transition: color 0.3s ease;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: #0d6efd;
        }

        .navbar-toggler {
            border: none;
        }

        .navbar .icon {
            color: white;
            font-size: 1.2rem;
        }
    </style>
</head>

<body>
    <?php
    // Get the current script name
    $current_page = basename($_SERVER['PHP_SELF']);
    ?>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="./assets/logo1.png" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="icon"><i class="fa fa-bars"></i></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link <?= $current_page == 'index.php' ? 'active' : '' ?>" href="index.php"><i class="fa fa-home"></i> Home</a></li>
                    <li class="nav-item"><a class="nav-link <?= $current_page == 'appointment.php' ? 'active' : '' ?>" href="appointment.php"><i class="fa fa-calendar"></i> Appointment</a></li>
                    <li class="nav-item"><a class="nav-link <?= $current_page == 'services.php' ? 'active' : '' ?>" href="services.php"><i class="fa fa-clipboard"></i> Our Services</a></li>
                    <li class="nav-item"><a class="nav-link <?= $current_page == 'about_us.php' ? 'active' : '' ?>" href="about_us.php"><i class="fa fa-info-circle"></i> About Us</a></li>
                    <li class="nav-item"><a class="nav-link <?= $current_page == 'contact_us.php' ? 'active' : '' ?>" href="contact_us.php"><i class="fa fa-phone"></i> Contact Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php"><i class="fa fa-user-circle"></i> Admin Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>