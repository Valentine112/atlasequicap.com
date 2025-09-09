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
    <title>QFSLedgerConnect - Storage for your wallets</title>
</head>
<body class="data-light-body">
    <?php include "src/template/sb/index.html"; ?>
    
    <main class="deposit">
    <?php include "src/template/head.php"; ?>

        <section class="mt-3 deposit content">
            <div>
                <div class="referred referred-section mb-3">
                    <header class="text-center">
                        <h5>Buy Cryptocurrency</h5>
                        <p>We have provided you with the best and trusted platforms to buy cryptocurrency</p>
                    </header>

                    <div class="row justify-content-around align-items-center">
                        <div class="boxes col-6 col-md-5 col-lg-5 mt-2 mb-4 mx-auto text-center">
                            <a href="https://exchange.mercuryo.io">
                                <p>Mercuryo</p>

                                <img src="../src/assets/market/mercuryo.png" alt="">
                            </a>
                        </div>

                        <div class="boxes col-6 col-md-5 col-lg-5 mt-4 mb-4 mx-auto text-center">
                            <a href="https://moonpay.com/buy">
                                <p>Moonpay</p>

                                <img src="../src/assets/market/moonpay.png" alt="">
                            </a>
                        </div>

                        <div class="boxes col-6 col-md-5 col-lg-5 mt-4 mb-4 mx-auto text-center">
                            <a href="https://ramp.network.buy">
                                <p>Ramp</p>

                                <img src="../src/assets/market/ramp.png" alt="">
                            </a>
                        </div>

                        <div class="boxes col-6 col-md-5 col-lg-5 mt-4 mb-4 mx-auto text-center">
                            <a href="https://global.transak.com">
                                <p>Transak</p>

                                <img src="../src/assets/market/transak.png" alt="">
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
<script src="../src/assets/js/chart.js"></script>
</html>