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
                        <p class="title">Withdrawal</p>
                    </header>

                    <section class="withdraw card-box mt-4">
                        <div class="sub-sub-head">
                            Select a method of withdrawal
                        </div>
                        <hr>
                        <div>
                            <label for="method">Choose withdrawal address</label>
                            <select class="form-select form-inp" id="inputGroupSelect01">
                                <option value="Btc">Bitcoin</option>
                                <option value="Eth">Ethereum</option>
                                <option value="Bnb">BNB</option>
                                <option value="Usdt">USDT</option>
                                <option value="Xrp">XRP</option>
                            </select>
                        </div>
                        <div class="mt-3">
                            <label for="amount">Amount (USD)</label>
                            <input type="text" class="form-control form-inp" id="amount" placeholder="Amount in USD">
                        </div>

                        <div class="row justify-content-end mt-4 text-end">
                            <div class="col-6 col-md-3">
                                <button class="btn btn-primary w-60">Confirm Withdrawal</button>
                            </div>
                        </div>
                    </section>

                    <div class="mt-4">
                        <header>
                            <p class="title">Latest Withdrawals</p>
                        </header>
                        <section class="deposit-history">
                            <div class="table-responsive">
                                <table class="table table-hover bg-dark table-box border-0 bg-transparent">
                                    <thead>
                                        <tr>
                                            <th scope="col">TRANSACTION ID #</th>
                                            <th scope="col">DATE</th>
                                            <th scope="col">MODE</th>
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
        </div>
    </div>
</body>
</head>