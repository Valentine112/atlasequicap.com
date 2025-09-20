<!DOCTYPE html>
<html lang="en">

<head>
    <?php 
        use Src\Config\Head; 
        Head::tags();
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atlasequicap Broker</title>
    <link rel="stylesheet" href="src/assets/css/login.css">
</head>

<body>
    <div class="login">
        <div class="card-box col-lg-5">
            <div class="head-sect text-center">
                <img src="src/assets/images/logo.png" alt="">
                <h1 class="head">AtlasEquicap</h1>
            </div>
            <hr>
            <div class="">
                <div class="sub-head">
                    Login
                </div>
                <small style="color: grey;">Welcome back, Access you account by logging in now </small>
            </div>
            <div id="message"></div>
            <div class="mt-3">
                <div>
                    <label for="email">Email Address</label>
                    <input type="text" class="form-control form-inp mt-2" placeholder="Email address" id="email">
                </div>

                <div class="mt-3">
                    <label for="password">Password</label>
                    <input type="password" class="form-control form-inp mt-2" placeholder="Password" id="password">
                </div>
                <div class="mt-4">
                    <button class="btn btn-link form-control" onclick="log(this, 'login')">Submit</button>
                </div>
                <div class="mt-4 col-12">
                    <a href="forgot" style="color: grey;">Forgot Password?</a>
                </div>
                <hr>
                <div class="mt-4">
                    <a href="signup" class="btn btn-info form-control">Register now</a>
                </div>
                <div class="mt-4 text-center footer">
                    <small>By using AtlasEquicap Trading Platform you agree to our <a href="terms">Terms & Conditions</a></small>
                </div>
            </div>
        </div>
    </div>
    <?php include "src/template/quick-notice.php"; ?>
    <script src="src/assets/js/main.js"></script>
</body>

</html>