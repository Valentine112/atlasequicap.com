<?php require "php/general.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
        use Src\Config\Head; 
        Head::tags();
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/assets/css/landing.css">
    <link rel="stylesheet" href="../src/assets/css/general.css">
    <link rel="stylesheet" href="../assets/css/line-awesome.min.css">
    <title>QFSLedgerConnect - Storage for your wallets</title>
</head>
<body class="data-light-body">
    <main>
        <?php include "src/template/head.php"; ?>
        <section class="dashboard content">

            <header class="col-11 col-md-8 mx-auto">
                <h5>Dashboard</h5>
            </header>
            <div class="row boxCover justify-content-around col-12">
                <div class="boxes col-11 col-md-9 mt-2 mb-2 data-light">
                    <div>
                        <div>
                            <span>Portfolio &ensp; <i class="las la-sort-up" style="color: #bcf000;"></i></span>
                        </div>

                        <div>
                            <sup>$</sup>
                            <?= $user['btc'] + $user['eth'] + $user['xrp'] + $user['xlm'] + $user['qtum'] + $user['hbar'] + $user['xdc'] + $user['ada'] + $user['miota']; ?>.<span class="h6">00</span>
                        </div>

                        <div>
                            <span class="title-date"></span>
                        </div>
                    </div>

                    <div>
                        <i class="las la-money-bill"></i>
                    </div>
                </div>

                <div class="row col-12 col-lg-10 my-3 justify-content-around quick-links text-center data-light">
                    <div class="col-3">
                        <a href="deposit">
                            <i class="las la-dollar-sign"></i>
                            <br>
                            Buy
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="withdraw">
                            <i class="las la-piggy-bank"></i>
                            <br>
                            Withdraw
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="history">
                            <i class="las la-history"></i>
                            <br>
                            History
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="../login">
                            <i class="las la-sign-out-alt"></i>
                            <br>
                            Logout
                        </a>
                    </div>
                </div>

            </div>

            <div class="col-11 col-md-9 mx-auto">
                <p class="text-primary"><b>Assets</b></p>
                <div class="assets row justify-content-around align-items-center data-light-top">
                    <div class="col-12 col-lg-5 row sect py-3">
                        <div class="col-5">
                            <img src="../src/assets/crypto/btc.svg" alt="" class="mx-auto">
                            &ensp;
                            Bitcoin
                        </div>
                        
                        <div class="col-7 text-end">
                            <span><b>$<?= $user['btc']; ?></b></span>
                        </div>
                    </div>

                    <div class="col-12 col-lg-5 row sect py-3">
                        <div class="col-5">
                            <img src="../src/assets/crypto/eth.svg" alt="" class="mx-auto">
                            &ensp;
                            Ethereum
                        </div>
                        
                        <div class="col-7 text-end">
                            <span><b>$<?= $user['eth']; ?></b></span>
                        </div>
                    </div>
                    <div class="col-12 col-lg-5 row sect py-3">
                        <div class="col-5">
                            <img src="../src/assets/crypto/xrp.svg" alt="" class="mx-auto">
                            &ensp;
                            XRP
                        </div>
                        
                        <div class="col-7 text-end">
                            <span><b>$<?= $user['xrp']; ?></b></span>
                        </div>
                    </div>
                    <div class="col-12 col-lg-5 row sect py-3">
                        <div class="col-5">
                            <img src="../src/assets/crypto/xlm.svg" alt="" class="mx-auto">
                            &ensp;
                            XLM
                        </div>
                        
                        <div class="col-7 text-end">
                            <span><b>$<?= $user['xlm']; ?></b></span>
                        </div>
                    </div>
                    <div class="col-12 col-lg-5 row sect py-3">
                        <div class="col-5">
                            <img src="../src/assets/crypto/xdc.png" alt="" class="mx-auto">
                            &ensp;
                            XDC
                        </div>
                        
                        <div class="col-7 text-end">
                            <span><b>$<?= $user['xdc']; ?></b></span>
                        </div>
                    </div>
                    <div class="col-12 col-lg-5 row sect py-3">
                        <div class="col-5">
                            <img src="../src/assets/crypto/ada.svg" alt="" class="mx-auto">
                            &ensp;
                            ADA
                        </div>
                        
                        <div class="col-7 text-end">
                            <span><b>$<?= $user['ada']; ?></b></span>
                        </div>
                    </div>
                    <div class="col-12 col-lg-5 row sect py-3">
                        <div class="col-5">
                            <img src="../src/assets/crypto/qtum.svg" alt="" class="mx-auto">
                            &ensp;
                            QTUM
                        </div>
                        
                        <div class="col-7 text-end">
                            <span><b>$<?= $user['qtum']; ?></b></span>
                        </div>
                    </div>
                    <div class="col-12 col-lg-5 row sect py-3">
                        <div class="col-5">
                            <img src="../src/assets/crypto/miota.svg" alt="" class="mx-auto">
                            &ensp;
                            MIOTA
                        </div>
                        
                        <div class="col-7 text-end">
                            <span><b>$<?= $user['miota']; ?></b></span>
                        </div>
                    </div>
                    <div class="col-12 col-lg-5 row sect py-3">
                        <div class="col-5">
                            <img src="../src/assets/crypto/hbar.png" alt="" class="mx-auto">
                            &ensp;
                            HBAR
                        </div>
                        
                        <div class="col-7 text-end">
                            <span><b>$<?= $user['hbar']; ?></b></span>
                        </div>
                    </div>
                    <!--<div class="col-12 col-lg-5 row sect py-3">
                        <div class="col-5">
                            <img src="../src/assets/crypto/trump.jpeg" alt="" class="mx-auto">
                            &ensp;
                            HBAR
                        </div>
                        
                        <div class="col-7 text-end">
                            <span><b>$</b></span>
                        </div>
                    </div>-->
                </div>
            </div>

            <!-- TradingView Widget BEGIN -->
            <!-- TradingView Widget BEGIN -->
            <!--<div class="col-lg-12 mx-auto">
                <div class="tradingview-widget-container" style="height:100%;width:100%">
                    <div class="tradingview-widget-container__widget" style="height:calc(100% - 32px);width:100%"></div>
                    <div class="tradingview-widget-copyright"><a href="https://www.tradingview.com/" rel="noopener nofollow" target="_blank"><span class="blue-text">Track all markets on TradingView</span></a></div>
                    <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-advanced-chart.js" async>
                    {
                    "autosize": true,
                    "symbol": "NASDAQ:AAPL",
                    "interval": "D",
                    "timezone": "Etc/UTC",
                    "theme": "dark",
                    "style": "3",
                    "locale": "en",
                    "allow_symbol_change": true,
                    "calendar": false,
                    "support_host": "https://www.tradingview.com"
                    }
                    </script>
                </div>
            </div>-->
    <!-- TradingView Widget END -->
        </section>
    </main>

</body>
<script src="../src/assets/js/main.js"></script>
<script src="../src/assets/js/general.js"></script>
<script src="../src/assets/js/chart.js"></script>
<script>
    window.addEventListener("load", () => {
        var options = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'}
        
        document.querySelectorAll(".title-date").forEach(elem => {
            elem.innerText = new Date().toLocaleDateString('en-us', options)
        })
    })
    
    function myFunctions() {
        var copyText = document.getElementById("myInputs");
        copyText.select();
        document.execCommand("copy");
        alert("Referral link copied: " + copyText.value);
    }
</script>
    <script src="../assets/js/vendor/bootstrap.bundle.min.js"></script>
</html>