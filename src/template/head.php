<head>
    <!-- bootstrap 4  -->
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <!-- fontawesome 5  -->
    <link rel="stylesheet" href="assets/css/all.min.css">
    <!-- line-awesome webfont -->
    <link rel="stylesheet" href="../assets/css/line-awesome.min.css">
    <style>
        .menu-nav{
            background: #fff;
            padding: 1%;
            box-shadow: 1px 1px 1px 2px ##e1e1e1;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 2;
        }
        .nav-options{
            text-align: center;
        }
        .nav-options i{
            color: #c1c1c1;
            font-size: 20px;
        }
        .menu-nav a{
            text-decoration: none;
        }
        .menu-nav span{
            color: #444;
            font-size: 14px;
        }
        .menu-nav .nav-options .active{
            color: #bcf000;
            font-weight: 600;
        }
        @media screen and (max-width: 767px) {
            .menu-nav{
                top: unset;
                bottom: 0;
                padding: 2%;
                border-radius: 5px;
            }
            .nav-options{
                width: 18%;
                margin: 0 1%;
                display: inline-block;
            }
        }
    </style>
</head>
<nav class="col-12 col-lg-6 mx-auto row justfiy-content-center justify-content-lg-center menu-nav data-light">
    <div class="col-lg-2 nav-options">
        <a href="home">
            <i class="las la-home"></i>
            <br>
            <span class="active home">Home</span>
        </a>
    </div>
    <div class="col-lg-2 nav-options">
        <a href="market">
            <i class="las la-chart-line"></i>
            <br>
            <span class="market">Market</span>
        </a>
    </div>
    <div class="col-lg-2 nav-options">
        <a href="wallets">
            <i class="las la-wallet"></i>
            <br>
            <span class="wallets">Wallets</span>
        </a>
    </div>
    <div class="col-lg-2 nav-options">
        <a href="history">
            <i class="las la-history"></i>
            <br>
            <span class="history">History</span>
        </a>
    </div>
    <div class="col-lg-2 nav-options">
        <a href="profile">
            <i class="las la-user"></i>
            <br>
            <span class="profile">Profile</span>
        </a>
    </div>
</nav>

<?php //include "kyc.php"; ?>

<script>
    let menuNav = document.querySelector(".menu-nav")
    let path = new Func().getPath()['main_path']

    let allowedPaths = ["home", "market", "wallets", "history", "profile"]

    if(path !== "" && (allowedPaths.includes(path))) {
        menuNav.querySelector(".active").classList.remove("active")
        menuNav.querySelector(`.${path}`).classList = "active"
    }else{
        menuNav.querySelector(".active").classList.remove("active")
    }
</script>