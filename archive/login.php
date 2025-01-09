<?php
session_start();
if ($_SESSION["failed"])
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Chess Game - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <!-- Nav Bar -->
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <div class="container-fluid justify-content-center">
            <span class="navbar-brand">Chess Game</span>
        </div>
    </nav>

    <!-- Login Form -->
    <div class="container d-flex align-items-center" style="max-height:600px; min-height:600px">
        <div class="container d-flex justify-content-center">
            <div class="border-5 border-top border-primary shadow-lg" style="width:50%;">
                <div class="p-5 border border-2 border-top-0 shadow-lg">
                    <div class="d-flex justify-content-center">
                        <h3>Login</h3>
                    </div>
                    <form action="includes/loginVerify.php" class="needs-validation" method="post">
                        <div class="form-floating mb-3 mt-3">
                            <input type="text" class="form-control" id="email" placeholder="Enter email" name="email" required>
                            <label for="email">Email</label>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please enter your username.</div>
                        </div>
                        <div class="form-floating mt-3 mb-3">
                            <input type="password" class="form-control" id="password" placeholder="Enter password" name="password" required>
                            <label for="pwd">Password</label>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please enter your password.</div>
                        </div>
                        <div class="d-flex">
                            <p class="small"><a class="text-primary" href="forgotPassword.php">Forgot password?</a></p>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-outline-dark" type="submit">Login</button>
                            <?php if ($_SESSION["failed"] == "true") echo '<span style = "color:red"><center><b> Unable to sign in, please try again </b></center></span style>' ?>

                            <div class="pt-3 d-flex justify-content-center">
                                <p class="mb-0  text-center">Don't have an account? <a href="signup.php" class="text-primary fw-bold">Sign Up</a></p>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>