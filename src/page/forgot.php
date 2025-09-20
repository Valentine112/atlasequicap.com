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
                    Recover password
                </div>
                <small style="color: grey;">Silly! Forgotten your again?</small>
            </div>
            <div id="message"></div>
            <div class="mt-3">
                <div>
                    <label for="email">Email Address</label>
                    <input type="text" class="form-control form-inp mt-2" placeholder="Email address" id="email">
                </div>

                <div class="mt-4">
                    <button class="btn btn-link form-control" onclick="log(this, 'forgot')">Recover</button>
                </div>
                <hr>
                <div class="mt-4">
                    <a href="login" class="btn btn-info form-control">Login now</a>
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