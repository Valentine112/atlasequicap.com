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
                        <p class="title">Signals</p>
                    </header>
                    
                    <section class="payment-methods card-box col-lg-8 mx-lg-auto mt-4">
                        <div class="row justify-content-between data-body align-items-center">
                            <div class="col-4 assets-info">
                                <h2 class="mb-0">$0.00</h2>
                                <small>Wallet Balance</small>
                            </div>
                            <div class="col-8 row justify-content-end align-items-center text-end">
                                <div class="col-8">
                                    <button href="" class="btn btn-transparent form-inp" data-bs-toggle="modal" data-bs-target="#exampleModal">My Signals</button>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="signals mt-3">
                        <div class="row justify-content-around align-items-center">
                            <div class="signals-box card-box col-lg-5 my-2 py-5">
                                <h4>OVTLLL</h4>
                                <hr>
                                <div class="row justify-content-around align-items-center">
                                    <div class="col-6">
                                        <span>Signal Price</span>
                                    </div>
                                    <div class="col-6 text-end">
                                        <h5 class="">$100,000.00</h5>
                                    </div>
                                </div>
                                <div class="row justify-content-around align-items-center">
                                    <div class="col-6">
                                        <span>Signal Strength</span>
                                    </div>
                                    <div class="col-6 text-end">
                                        <h5 style="color: lightgreen;">99%</span>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-12">
                                    <div class="input-group mb-1">
                                        <input type="text" class="form-control form-inp" placeholder="Amount" aria-label="Amount" aria-describedby="basic-addon2">
                                        <span class="input-group-text" style="background: #121921; border: none; color: var(--white);" id="basic-addon2">USD</span>
                                    </div>
                                    <div class="col-12 row justify-content-between">
                                        <div class="col-6">
                                            <small>Current Balance</small>
                                        </div>
                                        <div class="col-6 text-end">
                                            <span>$0.00</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <button style="background-color: var(--main-color);" class="btn w-100 mt-4 py-2">Purchase Signal</button>
                                </div>
                            </div>
                        </div>
                    </section>
                </main>
            </section>

            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel" class="payment-head sub-head">My Signals</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="modal-details">
                                <p class="sub-sect py-5">You have no active signals. Purchase a signal to view it here.</p>

                                <div class="row col-12 justify-content-around mx-auto py-1">
                                    <div class="col-4">
                                        <h5 class="sub-sub-head mb-0">$0.00</h5>
                                        <small style="color: grey;">Amount</small>
                                    </div>
                                    <div class="col-4">
                                        <h5 class="sub-sub-head mb-0">OVLLTR</h5>
                                        <small style="color: grey;">Signal</small>
                                    </div>
                                    <div class="col-4">
                                        <h5 class="sub-sub-head mb-0">12/0/3</h5>
                                        <small style="color: grey;">Date</small>
                                    </div>

                                    <hr>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>