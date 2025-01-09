<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chess Game - Change Password</title>
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

    <div class="container d-flex align-items-center" style="max-height:600px; min-height:600px">
        <div class="container d-flex justify-content-center">
            <div class="border-5 border-top border-primary shadow-lg" style="width:50%;">
                <div class="p-5 border border-2 border-top-0 shadow-lg">
                    <div class="d-flex justify-content-center">
                        <h3>Change password</h3>
                    </div>
                    <form action="includes/updatePassword.php" class="needs-validation" method="post">
                        <!--Current pwd -->
                        <div class="form-floating mb-3 mt-3">
                            <input type="password" class="form-control" id="currentPwd" placeholder="Enter current password" name="currentPwd" required>
                            <label for="email">Current Password</label>
                        </div>
                        <!-- New password -->
                        <div class="form-floating mb-3 mt-3">
                            <input type="password" class="form-control" id="newPwd1" placeholder="Enter new password" name="newPwd1" required>
                            <label for="email">New Password</label>
                        </div>
                        <!-- New password repeated -->
                        <div class="form-floating mb-3 mt-3">
                            <input type="password" class="form-control" id="newPwd2" placeholder="reEnter new password" name="newPwd2" required>
                            <label for="email">New Password</label>
                        </div>
                        <!--Update button -->
                        <div class="d-grid">
                            <button class="btn btn-outline-dark" type="update">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>