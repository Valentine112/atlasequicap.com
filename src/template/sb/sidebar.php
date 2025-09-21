<!doctype html>
<html lang="en">

<head>
    <title>Sidebar 05</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="../src/template/sb/fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">

    <link rel="stylesheet" href="../src/template/sb/stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../src/template/sb/css/style.css">
    <meta name="robots" content="noindex, follow">
    <style>
        i{
            margin: auto;
            vertical-align: middle;
        }
        .sidebar-content{
            height: 100vh;
            overflow-y: auto;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .sidebar-content::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Edge, Opera */
        }
        #sidebar{
            height: unset;
            
        }
    </style>
</head>

<body>

    <!-- <div class="wrapper d-flex align-items-stretch"> -->
        <nav id="sidebar">
            <div class="custom-menu">
                <button type="button" id="sidebarCollapse" class="btn btn">
	            <i class="las la-bars"></i>
	            <span class="sr-only">Toggle Menu</span>
	        </button>
            </div>
            <div class="p-4 sidebar-content">
                <img src="../src/assets/images/logo.png" alt="" style="width: 150px; height: auto;">
                <ul class="list-unstyled components mb-5 mt-4">
                    <li class="home">
                        <a href="home"><i class="las la-home mr-3"></i></span> Home</a>
                    </li>
                    <li class="assets">
                        <a href="assets"><i class="las la-digital-tachograph mr-3"></i></span> Assets</a>
                    </li>
                    <li class="deposit">
                        <a href="deposit"><i class="las la-file-invoice mr-3"></i></span> Deposit</a>
                    </li>
                    <li class="withdraw">
                        <a href="withdraw"><i class="las la-hand-holding-usd mr-3"></i></span> Withdraw</a>
                    </li>
                    <li class="convert">
                        <a href="convert"><i class="las la-sync mr-3"></i></span> Convert</a>
                    </li>
                    <li class="signals">
                        <a href="signals"><i class="las la-signal mr-3"></i></span> Signal</a>
                    </li>
                    <li class="py-2" style="border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
                        <small style="color: grey;"> Trading</small>
                    </li>
                    <li class="dropdown py-1" onclick="toggleDrop(this)">
                        <span><i class="las la-image mr-3"></i> Trading <i class="las la-caret-down ml-5" style="font-size: 13px;"></i></span>

                        <ul class="px-3 ml-5 pl-5 d-none sub-menu">
                            <li><a href="stocks">Stocks Trading</a></li>
                            <li><a href="crypto?coin=btc">Crypto Futures</a></li>
                        </ul>
                    </li>
                    <li class="copytrade">
                        <a href="copytrade"><i class="las la-users mr-3"></i></span> Copy Trade</a>
                    </li>
                    <li class="dropdown py-1" onclick="toggleDrop(this)">
                        <span><i class="las la-image mr-3"></i> Live <i class="las la-caret-down ml-5" style="font-size: 13px;"></i></span>

                        <ul class="px-3 ml-5 pl-5 d-none sub-menu">
                            <li><a href="market">Live Trading</a></li>
                            <li><a href="certificate" class="certificate">My Certificate</a></li>
                        </ul>
                    </li>
                    <li class="mt-1 walletconnect">
                        <a href="walletconnect"><i class="las la-wallet mr-3"></i></span> Connect Wallet</a>
                    </li>
                    <li class="py-2" style="border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
                        <small style="color: grey;"> Account</small>
                    <li>
                    <li class="settings">
                        <a href="settings"><i class="las la-cog mr-3"></i></span> Settings</a>
                    </li>
                    <li class="referrals">
                        <a href="downlines"><i class="las la-comments mr-3"></i></span> Referrals</a>
                    </li>
                    <li>
                        <a href="mailto:support@atlasequicap.com"><i class="las la-phone-volume mr-3"></i></span> Contact Us</a>
                    </li>
                    <li>
                        <a href="../login"><i class="las la-door-open mr-3"></i></span> Logout</a>
                    </li>
                </ul>

                <!--<div class="mb-5">
                    <h3 class="h6 mb-3">Subscribe for newsletter</h3>
                    <form action="#" class="subscribe-form">
                        <div class="form-group d-flex">
                            <div class="icon"><span class="icon-paper-plane"></span></div>
                            <input type="text" class="form-control" placeholder="Enter Email Address">
                        </div>
                    </form>
                </div>-->
            </div>
        </nav>


    <script src="../src/template/sb/js/jquery.min.js"></script>
    <script src="../src/template/sb/js/popper.js"></script>
    <script src="../src/template/sb/js/bootstrap.min.js"></script>
    <script src="../src/template/sb/js/main.js"></script>
</body>
    <script>
        window.addEventListener("load", () => {
            let path = new Func().getPath()['main_path'] ?? null
            let elem = document.querySelector(`.${path}`) ?? null
            if(elem != null) {
                elem.classList.add("active")
            }
        })

        function toggleDrop(self) {
            self.querySelector(".sub-menu").classList.toggle("d-none")
        }
    </script>
</html>