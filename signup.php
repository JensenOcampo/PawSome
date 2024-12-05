<?php
require 'config.php';

function sanitizeData($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$usernameErr = $emailErr = $passwordErr = $confirmPassErr = "";

$usernamePlaceholder = "Username";
$emailPlaceholder = "Email Address";
$passwordPlaceholder = "Password";
$confirmPassPlaceholder = "Confirm Password";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["username"])) {
        $usernameErr = "Username is required";
        $usernamePlaceholder = $usernameErr;
    } else {
        $username = sanitizeData($_POST["username"]);
        $usernamePlaceholder = "Username";
    }

    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
        $emailPlaceholder = $emailErr;
    } else {
        $email = sanitizeData($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
            $emailPlaceholder = $emailErr;
        } else {
            $emailPlaceholder = "Email Address";
        }
    }

    if (empty($_POST["passWord"])) {
        $passwordErr = "Password is required";
        $passwordPlaceholder = $passwordErr;
    } else {
        $password = sanitizeData($_POST["passWord"]);
        $passwordPlaceholder = "Password";
    }

    if (empty($_POST["confirmPass"])) {
        $confirmPassErr = "Confirm Password is required";
        $confirmPassPlaceholder = $confirmPassErr;
    } else {
        $confirmPass = sanitizeData($_POST["confirmPass"]);
        if ($password !== $confirmPass) {
            $confirmPassErr = "Passwords do not match";
            $confirmPassPlaceholder = $confirmPassErr;
        } else {
            $confirmPassPlaceholder = "Confirm Password";
        }
    }

    if (empty($usernameErr) && empty($emailErr) && empty($passwordErr) && empty($confirmPassErr)) {
        $insert = mysqli_query($db, "INSERT INTO login (username,email,password) VALUES ('$username', '$email', '$password')");

        if ($insert) {
            header("Location: login.php?success=1");
        } else {
            header("Location: signup.php?success=0");
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to PawSome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div></div>

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row border rounded-5 p-3 shadow box-area blur-container">

            <div class="col-md-6 rounded-4 d-flex align-items-center flex-column left-box" style="background-image:url('./assets/dogcat.jpg'); background-size: cover; background-repeat: no-repeat; background-position: center;">
                <img class="mt-2" src="./assets/logo1.png" style="background-size: cover; background-repeat: no-repeat; background-position: center; width: auto; height: 80px;">
            </div>


            <div class="col-md-6 right-box">
                <div class="row align-items-center">

                    <div class="header-text mb-4">
                        <h1>Welcome</h1>
                        <small>Log in to continue</small>
                    </div>

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="input-group mb-3">
                            <input type="text" name="username" class="form-control form-control-lg bg-light fs-6" placeholder="<?php echo $usernamePlaceholder; ?>" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                        </div>

                        <div class="input-group mb-3">
                            <input type="text" name="email" class="form-control form-control-lg bg-light fs-6" placeholder="<?php echo $emailPlaceholder; ?>" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        </div>

                        <div class="input-group mb-3">
                            <input type="password" name="passWord" class="form-control form-control-lg bg-light fs-6" placeholder="<?php echo $passwordPlaceholder; ?>">
                        </div>

                        <div class="input-group mb-3">
                            <input type="password" name="confirmPass" class="form-control form-control-lg bg-light fs-6" placeholder="<?php echo $confirmPassPlaceholder; ?>">
                        </div>

                        <div class="input-group mb-3">
                            <button class="btn btn-lg btn-success w-100 fs-6">Signup</button>
                        </div>

                    </form>

                    <div class="row">
                        <small class="text-center">
                            Already have account? <a href="login.php">Login</a>
                        </small>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>