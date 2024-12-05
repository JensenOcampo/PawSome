<?php
include 'config.php';

function sanitizeData($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$usernameErr = $passwordErr = $successMsg = $errorMsg = "";
$usernamePlaceholder = "Enter your username";
$passwordPlaceholder = "Enter your new password";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["username"])) {
        $usernameErr = "Username is required";
    } else {
        $username = sanitizeData($_POST["username"]);
    }

    if (empty($_POST["newPassword"])) {
        $passwordErr = "New password is required";
    } else {
        $newPassword = sanitizeData($_POST["newPassword"]);
    }

    if (empty($usernameErr) && empty($passwordErr)) {
        $query = "SELECT * FROM login WHERE username = '$username'";
        $result = mysqli_query($db, $query);

        if ($result && mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            error_log("Username found: " . $row['username']);

            $update = "UPDATE login SET password = '$newPassword' WHERE username = '$username'";

            if (mysqli_query($db, $update)) {
                $successMsg = "Password updated successfully. You can now log in.";
            } else {
                $errorMsg = "Error updating password: " . mysqli_error($db);
            }
        } else {
            $usernameErr = "Username not found.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row border rounded-5 p-3 shadow box-area blur-container">
            <!-------------- Left Box -------------->
            <div class="col-md-6 rounded-4 d-flex align-items-center flex-column left-box" style="background-image:url('./assets/dogcat.jpg'); background-size: cover; background-repeat: no-repeat; background-position: center;">
                <img class="mt-2" src="./assets/logo1.png" style="width: auto; height: 80px;">
            </div>

            <!-------------- Right Box -------------->
            <div class="col-md-6 right-box">
                <div class="row align-items-center">
                    <!-------------- Header -------------->
                    <div class="header-text mb-4">
                        <h1>Forgot Password</h1>
                        <small>Enter your username and new password to reset it</small>
                    </div>

                    <!-------------- Form -------------->
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <!-------------- Username Input -------------->
                        <div class="input-group mb-3">
                            <input type="text" name="username" class="form-control form-control-lg bg-light fs-6" placeholder="<?php echo $usernamePlaceholder; ?>" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                            <small class="text-danger"><?php echo $usernameErr; ?></small>
                        </div>

                        <!-------------- New Password Input -------------->
                        <div class="input-group mb-3">
                            <input type="password" name="newPassword" class="form-control form-control-lg bg-light fs-6" placeholder="<?php echo $passwordPlaceholder; ?>">
                            <small class="text-danger"><?php echo $passwordErr; ?></small>
                        </div>

                        <!-------------- Submit Button -------------->
                        <div class="input-group mb-3">
                            <button class="btn btn-lg btn-primary w-100 fs-6">Reset Password</button>
                        </div>
                    </form>

                    <!-------------- Success/Failure Messages -------------->
                    <?php if ($successMsg): ?>
                        <div class="alert alert-success"><?php echo $successMsg; ?></div>
                    <?php endif; ?>
                    <?php if ($errorMsg): ?>
                        <div class="alert alert-danger"><?php echo $errorMsg; ?></div>
                    <?php endif; ?>

                    <!-------------- Back to Login -------------->
                    <div class="row">
                        <small class="text-center">
                            Remembered your password? <a href="login.php">Login</a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>