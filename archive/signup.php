<?php
session_start();
$_SESSION["logged_in"] = "false";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="styling/styling.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Chess Game - Create Account</title>
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

    <!-- Sign Up Form -->
    <div class="container d-flex align-items-center" style="max-height:800px; min-height:800px">
        <div class="container d-flex justify-content-center">
            <div class="border-5 border-top border-primary shadow-lg" style="width:50%;">
                <div class="p-5 border border-2 border-top-0 shadow-lg">
                    <div class="d-flex justify-content-center">
                        <h3>Create Account</h3>
                    </div>
                    <form action="includes/createAccount.php" class="was-validated" method="post">
                        <div class="mb-3 mt-3">
                            <label for="username" class="form-label">Username:</label>
                            <input type="text" class="form-control" id="username" placeholder="Enter username" name="username" required>
                            <div class="invalid-feedback">Please enter a username</div>
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" required>
                            <div class="invalid-feedback">Please enter an email.</div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" class="form-control" id="password" placeholder="Enter password" name="password" required>
                            <div class="invalid-feedback">Please enter a password.</div>
                        </div>
                        <div class="mb-3">
                            <label for="passwordConfirm" class="form-label">Confirm Password:</label>
                            <input type="password" class="form-control" id="passwordConfirm" placeholder="Re-enter password" name="passwordConfirm" required>
                            <div class="invalid-feedback">Please re-enter your password.</div>
                        </div>
                        <div class="d-grid pt-2">
                            <button class="btn btn-outline-dark" type="submit">Sign Up</button>
                        </div>
                        <div class="pt-3 d-flex justify-content-center">
                            <p class="mb-0  text-center">Have an account? <a href="login.php" class="text-primary fw-bold">Log In</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>