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
    <title>ITradeMarket -  Investment HTML template</title>
    <link rel="icon" type="image/png" href="assets/images/favicon.png" sizes="16x16">
    <link rel="stylesheet" href="src/page/assets/css/main.css">
</head>

<body>
    <!-- scroll-to-top start -->
    <div class="scroll-to-top">
        <span class="scroll-icon">
        <i class="fa fa-rocket" aria-hidden="true"></i>
      </span>
    </div>
    <!-- scroll-to-top end -->

    <div class="full-wh">
        <!-- STAR ANIMATION -->
        <div class="bg-animation">
            <div id='stars'></div>
            <div id='stars2'></div>
            <div id='stars3'></div>
            <div id='stars4'></div>
        </div>
        <!-- / STAR ANIMATION -->
    </div>
    <div class="page-wrapper">

        <!-- account section start -->
        <div class="account-section bg_img" data-background="assets/images/bg/bg-5.jpg">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-5 col-lg-7">
                        <div class="account-card">
                            <div class="account-card__header bg_img overlay--one" data-background="assets/images/bg/bg-6.jpg">
                                <h2 class="section-title"><span class="base--color">ITradeMarket</span></h2>
                            </div>
                            <div class="account-card__body">
                                <h3 class="text-center">Two-Factor Authentication</h2>
                                <div id="message">
                    
                                </div>
                                <form class="mt-4"  action="" onsubmit="event.preventDefault()">
                                    <div class="form-group">
                                        <label>Code from email</label>
                                        <input type="text" class="form-control" placeholder="Enter the code" id="code">
                                    </div>
                                    <div class="mt-3">
                                        <button class="cmn-btn col-12" onclick="log(this, 'auth')">Confirm</button>
                                    </div>
                                    <div class="form-row my-3">
                                        <div class="col-sm-6 text-sm-right">
                                            <p class="f-size-14"><a href="login" class="base--color btn">Login again</a></p>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- account section end -->
        <!-- Include the notice box here -->
        <?php include "src/template/quick-notice.php"; ?>

    </div>
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