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
                        <p class="title">Deposit</p>
                    </header>

                    <section class="payment-methods card-box mt-4">
                        <div class="sub-head">
                            Payment Method
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12 row justify-content-between align-items-center py-1 my-2 payment-method">
                                <div class="col-5 col-lg-4">
                                    <div class="row col-lg-6 payment-methods align-items-center">
                                        <div class="col-5">
                                            <img src="../src/assets/logos/btc.svg" alt="">
                                        </div>
                                        <div class="col-5">
                                            <span class="">
                                                <h6 class="mb-0 sub-sub-head">BITCOIN</h6>
                                                <small>SEGWIT</small>
                                            </span>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="d-none d-md-block col-lg-4">
                                    <small class="mb-0">Processing Time: Instant - 30 minutes</small>
                                    <br>
                                    <small>Fee: 0%</small>
                                </div>

                                <div class="col-7 col-lg-4 text-end">
                                    <button href="" class="btn btn-transparent btn-link btn-link" data-bs-toggle="modal" data-bs-target="#exampleModal">Deposit now</button>
                                </div>
                            </div>

                            <div class="col-12 row justify-content-between align-items-center py-1 my-2 payment-method">
                                <div class="col-5 col-lg-4">
                                    <div class="row col-lg-6 payment-methods align-items-center">
                                        <div class="col-5">
                                            <img src="../src/assets/logos/eth.svg" alt="">
                                        </div>
                                        <div class="col-5">
                                            <span class="">
                                                <h6 class="mb-0 sub-sub-head">ETHEREUM</h6>
                                                <small>ERC20</small>
                                            </span>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="d-none d-md-block col-lg-4">
                                    <small class="mb-0">Processing Time: Instant - 30 minutes</small>
                                    <br>
                                    <small>Fee: 0%</small>
                                </div>

                                <div class="col-6 col-lg-4 text-end">
                                    <button href="" class="btn btn-transparent btn-link btn-link" data-bs-toggle="modal" data-bs-target="#exampleModal">Deposit now</button>
                                </div>
                            </div>

                            <div class="col-12 row justify-content-between align-items-center py-1 my-2 payment-method">
                                <div class="col-5 col-lg-4">
                                    <div class="row col-lg-6 payment-methods align-items-center">
                                        <div class="col-5">
                                            <img src="../src/assets/logos/xrp.svg" alt="">
                                        </div>
                                        <div class="col-5">
                                            <span class="">
                                                <h6 class="mb-0 sub-sub-head">RIPPLE</h6>
                                                <small>XRP</small>
                                            </span>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="d-none d-md-block col-lg-4">
                                    <small class="mb-0">Processing Time: Instant - 30 minutes</small>
                                    <br>
                                    <small>Fee: 0%</small>
                                </div>

                                <div class="col-6 col-lg-4 text-end">
                                    <button href="" class="btn btn-transparent btn-link btn-link" data-bs-toggle="modal" data-bs-target="#exampleModal">Deposit now</button>
                                </div>
                            </div>

                            <div class="col-12 row justify-content-between align-items-center py-1 my-2 payment-method">
                                <div class="col-5 col-lg-4">
                                    <div class="row col-lg-6 payment-methods align-items-center">
                                        <div class="col-5">
                                            <img src="../src/assets/logos/usdt.svg" alt="">
                                        </div>
                                        <div class="col-5">
                                            <span class="">
                                                <h6 class="mb-0 sub-sub-head">TETHER</h6>
                                                <small>ERC</small>
                                            </span>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="d-none d-md-block col-lg-4">
                                    <small class="mb-0">Processing Time: Instant - 30 minutes</small>
                                    <br>
                                    <small>Fee: 0%</small>
                                </div>

                                <div class="col-6 col-lg-4 text-end">
                                    <button href="" class="btn btn-transparent btn-link btn-link" data-bs-toggle="modal" data-bs-target="#exampleModal">Deposit now</button>
                                </div>
                            </div>
                        </div>
                    </section>


                    <div class="mt-4">
                        <header>
                            <p class="title">Latest Deposits</p>
                        </header>
                        <section class="deposit-history">
                            <div class="table-responsive">
                                <table class="table table-hover table-box border-0 bg-transparent">
                                    <thead>
                                        <tr>
                                            <th scope="col">TRANSACTION ID #</th>
                                            <th scope="col">DATE</th>
                                            <th scope="col">PAYMENT METHOD</th>
                                            <th scope="col">AMOUNT</th>
                                            <th scope="col">STATUS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td>Mark</td>
                                            <td>Otto</td>
                                            <td>@mdo</td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </div>
                </main>
            </section>

            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel" class="payment-head sub-head">Deposit Bitcoin</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="modal-details">
                               <div class="mb-3">
                                    <label for="formGroupExampleInput" class="form-label">Amount (USD)</label>
                                    <input type="text" class="form-control form-inp" id="formGroupExampleInput" placeholder="Amount in USD">
                                </div>
                                <div>
                                    <label for="amount">Copy Address</label>
                                    <br>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control form-inp" placeholder="Wallet address" aria-label="Example text with button addon" aria-describedby="button-addon1" id="address">
                                        <button class="btn btn-outline-secondary" type="button" id="button-addon1">Copy</button>
                                    </div>
                                </div>
                            
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-link" id="pay-btn">Pay now</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>