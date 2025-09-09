<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en">

<head>
    <?php 
        use Src\Config\Head; 
        Head::tags();
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QFSLedgerConnect - Storage foor your wallets</title>
    <link rel="icon" type="image/png" href="assets/images/favicon.png" sizes="16x16">
    <link rel="stylesheet" href="src/page/assets/css/main.css">
</head>

<body>
    <div class="container">
        <div class="body-cover col-11 col-lg-6 mx-auto">
            <header>
                <h2>QFSLedgerConnect</h2>
            </header>

            <div class="main mt-5">
                <div id="message"></div>
                <div>
                    <h4>Create account</h4>
                </div>
                <div class="form-group">
                    <label for="fullname">Fullname</label>
                    <input type="text" placeholder="Enter fullname" id="fullname" class="form-inp mt-1">
                </div>
                <div class="form-group mt-3">
                    <label for="username">Username</label>
                    <input type="text" placeholder="Enter username" id="username" class="form-inp mt-1">
                </div>
                <div class="form-group mt-3">
                    <label for="email">Email address</label>
                    <input type="email" placeholder="Enter email address" id="email" class="form-inp mt-1">
                </div>
                <div class="form-group mt-3">
                    <label for="password">Password</label>
                    <input type="password" placeholder="Enter password" id="password" class="form-inp mt-1">
                </div>
                <div class="form-group mt-3">
                    <button class="form-btn col-12" onclick="log(this, 'register')">Submit</button>
                </div>
                <div class="mt-2">
                    <a href="forgot" class="form-link">forgot password?</a>
                </div>
                <hr>
                <div class="form-group mt-3">
                    <a href="login">
                        <button class="form-btn-alt col-12">Login</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <?php include "src/template/quick-notice.php"; ?>

    <!-- page-wrapper end -->
    <!-- jQuery library -->
    <script src="assets/js/vendor/jquery-3.5.1.min.js"></script>
    <!-- bootstrap js -->
    <script src="assets/js/vendor/bootstrap.bundle.min.js"></script>
    <!-- slick slider js -->
    <script src="assets/js/vendor/slick.min.js"></script>
    <script src="assets/js/vendor/wow.min.js"></script>
    <script src="assets/js/contact.js"></script>
    <!-- dashboard custom js -->
    <script src="assets/js/app.js"></script>
    <script src="src/assets/js/main.js"></script>
</body>

</html>