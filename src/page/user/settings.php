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

            <section class="content p-4 p-md-5 pt-5">
                <?php include "src/template/head.php"; ?>
                <main>
                    <header>
                        <p class="title">Settings</p>
                    </header>
                    <section class="data-section">
                        <div class="data-header">
                            <div id="nav-tab" role="tablist">
                                <div class="wallet-sec active" id="stock-tab" data-bs-toggle="tab" data-bs-target="#stock" type="button" role="tab" aria-controls="stock" aria-selected="true">Profile</div>
                                &emsp;
                                <div class="wallet-sec" id="crypto-tab" data-bs-toggle="tab" data-bs-target="#crypto" type="button" role="tab" aria-controls="crypto" aria-selected="true">Security</div>
                                &emsp;
                                <div class="wallet-sec" id="crypto-tab" data-bs-toggle="tab" data-bs-target="#crypto" type="button" role="tab" aria-controls="crypto" aria-selected="true">Payment</div>
                            </div>
                        </div>
                        <div class="data-body">
                            <div class="table-responsive">
                                <table class="table table-borderless table-box border-0 bg-transparent">
                                    <thead>
                                        <tr>
                                            <th>Account Information</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Fullname</td>
                                            <td class="value"><?= $user['fullname']; ?></td>
                                            <td><button class="btn btn-info" onclick="edit(this)">Edit</button></td>
                                        </tr>
                                        <tr>
                                            <td>Email</td>
                                            <td class="value"><?= $user['email']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Phone</td>
                                            <td class="value"><?= $user['phone']; ?></td>
                                            <td><button class="btn btn-info" onclick="edit(this)">Edit</button></td>
                                        </tr>
                                        <tr>
                                            <td>Country</td>
                                            <td class="value"><?= $user['country']; ?></td>
                                            <td><button class="btn btn-info" onclick="edit(this)">Edit</button></td>
                                        </tr>
                                        <tr>
                                            <td>Account Status</td>
                                            <td class="value">
                                                <?php
                                                    if($user['status'] == 1):
                                                        echo "<span class=main-color>Verified</span>";
                                                    else:
                                                        echo "<span>Unverified</span>";
                                                    endif;
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Trading ID</td>
                                            <td>
                                                <span class=main-color>AE-<?= $user['tradeId']; ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Deposit Limit</td>
                                            <td>
                                                <span class=main-color>Unlimited</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </main>
            </section>
        </div>
    </div>
</body>
<script src="../src/assets/js/main.js"></script>
<script src="../src/assets/js/general.js"></script>
<script src="../src/assets/js/chart.js"></script>