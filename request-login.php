<?php
session_start();
include('includes/dbconn.php');

if (isset($_POST['signin'])) {
    $uname = $_POST['username'];
    $password = md5($_POST['password']);

    $user = null;

    // Admin Check
    $sql = "SELECT id, AdminUserName as username, 'admin' as role FROM admin WHERE AdminUserName=:uname AND AdminPassword=:password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':uname', $uname, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $admin = $query->fetch(PDO::FETCH_OBJ);

    if ($admin) {
        $user = $admin;
    } else {
        // Employee Check
        $sql = "SELECT id, EmplUserName as username, 'employee' as role, Status FROM employees WHERE EmplUserName=:uname AND UserPassword=:password";
        $query = $dbh->prepare($sql);
        $query->bindParam(':uname', $uname, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->execute();
        $employee = $query->fetch(PDO::FETCH_OBJ);

        if ($employee) {
            $user = $employee;
        } else {
            // Technician Check
            $sql = "SELECT id, TechUserName as username, 'technician' as role, Status FROM technicians WHERE TechUserName=:uname AND TechPassword=:password";
            $query = $dbh->prepare($sql);
            $query->bindParam(':uname', $uname, PDO::PARAM_STR);
            $query->bindParam(':password', $password, PDO::PARAM_STR);
            $query->execute();
            $technician = $query->fetch(PDO::FETCH_OBJ);

            if ($technician) {
                $user = $technician;
            }
        }
    }

    if ($user) {
        // Check user status for non-admin roles
        if ($user->role == 'admin' || $user->Status == 1) {
            $_SESSION['login'] = $user->username;
            $_SESSION['role'] = $user->role;

            if ($user->role == 'admin') {
                $_SESSION['alogin'] = $user->id;
                echo "<script type='text/javascript'> document.location = 'admin/dashboard.php'; </script>";
            } elseif ($user->role == 'employee') {
                $_SESSION['emplogin'] = $user->id;
                echo "<script type='text/javascript'> document.location = 'employees/user-request.php'; </script>";
            } elseif ($user->role == 'technician') {
                $_SESSION['techlogin'] = $user->id;
                echo "<script type='text/javascript'> document.location = 'technician/tech-ongoing-request.php'; </script>";
            }
        } else {
            echo "<script>alert('In-Active Account. Please contact your administrator!');</script>";
        }
    } else {
        echo "<script>alert('Sorry, Invalid Details.');</script>";
    }
}
?>


<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Employee Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="assets/images/favicon.ico">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/themify-icons.css">
    <link rel="stylesheet" href="assets/css/metisMenu.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/slicknav.min.css">
    <link rel="stylesheet" href="assets/css/typography.css">
    <link rel="stylesheet" href="assets/css/default-css.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <script src="assets/js/vendor/modernizr-2.8.3.min.js"></script>
</head>

<body>
    <div id="preloader">
        <div class="loader"></div>
    </div>
    <section>
        <div class="container my-5 pt-2">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8 col-sm-10">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" name="signin">
                                <div class="login-form-head">
                                    <h4 class="text-center">Login</h4>
                                    <p class="text-center">Requesting Management System</p>
                                </div>
                                <div class="login-form-body">
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" autocomplete="off" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" autocomplete="off" required>
                                    </div>
                                    <br>
                                    <div class="submit-btn-area">
                                        <button id="form_submit" type="submit" name="signin">Submit <i class="ti-arrow-right"></i></button>
                                    </div>
                                    <br>
                                    <div class="text-center">
                                        <p>Don't have an account? <a href="employees/user-create-account.php">Create Account</a></p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer>
        <div class="footer-area">
            <p>© <?php echo date("Y"); ?> | Requesting Management System in PHP | Developed By <a href="#">EARIST Students</a></p>
        </div>
    </footer>
    <script src="assets/js/vendor/jquery-2.2.4.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/metisMenu.min.js"></script>
    <script src="assets/js/jquery.slimscroll.min.js"></script>
    <script src="assets/js/jquery.slicknav.min.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/scripts.js"></script>
</body>
</html>