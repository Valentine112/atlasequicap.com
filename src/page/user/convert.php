<?php //require "php/general.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
        use Src\Config\Head; 
        Head::tags();
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../src/assets/css/general.css">
    <link rel="stylesheet" href="../assets/css/line-awesome.min.css">
    <title>QFSLedgerConnect - Storage for your wallets</title>
</head>
<body class="data-light-body">
    <?php include "src/template/quick-notice.php"; ?>
    <div class="section">
        <div class="wrapper d-flex align-items-stretch">
            <?php include "src/template/sb/sidebar.php"; ?>

            <section class="content p-4 p-md-5 pt-5">
                <?php include "src/template/head.php"; ?>
                <main>
                    <header>
                        <p class="title">Convert</p>
                    </header>

                    <section class="payment-methods card-box col-lg-8 mx-auto mt-4 convert">
                        <div class="sub-head">
                            Transfer Between Accounts
                        </div>
                        <hr>
                        <div class="px-3" id="from">
                            <p style="color: grey;">From</p>
                            <div class="row justify-content-between">
                                <div class="col-3 assets-info">
                                    <h2 class="mb-0">$0.00</h2>
                                    <small>Balance</small>
                                </div>
                                <div class="col-9 row">
                                    <div class="col-6">
                                        <select class="form-control form-inp">
                                            <option value="main">Main Wallet</option>
                                            <option>Stock Wallet</option>
                                            <option>Crypto Wallet</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <input type="number" class="form-control form-inp" placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="px-3" id="to">
                            <p style="color: grey;">To</p>
                            <div class="row justify-content-between">
                                <div class="col-3 assets-info">
                                    <h2 class="mb-0">$0.00</h2>
                                    <small>Balance</small>
                                </div>
                                <div class="col-9 row justify-content-end">
                                    <div class="col-8">
                                        <select class="form-control form-inp">
                                            <option value="main" class="main">Main Wallet</option>
                                            <option class="stock">Stock Wallet</option>
                                            <option class="crypto">Crypto Wallet</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div>
                            <button class="btn w-100 mt-4 py-3" style="background-color: var(--main-color); padding: 8px 10px!important;">Convert</button>
                        </div>
                    </section>
                </main>
            </section>
        </div>
    </div>
</body>
</html>