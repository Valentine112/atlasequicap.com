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
                        <p class="title">Downlines</p>
                        <span class="sub-sub-text mt-0">Invite friends and earn 10% in commission for every deposit they make</span>
                    </header>

                    <section>
                        <div class="row justify-content-around align-items-center mt-4">
                            <div class="col-12 col-lg-5 card-box px-4 py-5">
                                <div class="sub-head">
                                    <?php
                                        $bonus = 0;
                                        foreach($referrals[0] as $referred):
                                            $data = [
                                                "user" => $referred['id'],
                                                "1" => "1",
                                                "needle" => "COUNT(amount)",
                                                "table" => "deposit"
                                            ];
                                            $totalBonus = Func::searchDb($db, $data, "AND");
                                            if(!empty($totalBonus)):
                                                $bonus = (($totalBonus['COUNT(amount)'] * 10)/100);
                                            endif;
                                        endforeach;
                                    ?>
                                    Referral Bonus - $<?= $bonus; ?>
                                </div>
                                <div>
                                    Total Referrals - <?= $referrals[1]; ?>
                                </div>
                                <div class="mt-5">
                                    <label for="refcode">My Referral Code</label>
                                    <input type="text" value="<?= $user['refcode']; ?>" class="form-control form-inp" disabled>
                                </div>
                                <div class="mt-3">
                                    <label for="reflink">Referral Link</label>
                                    <div class="input-group">
                                        <input type="text" value="https://www.atlasequicap.com/signup?referred=<?= $user['refcode']; ?>" aria-describedby="basic-addon2" class="form-control form-inp" id="refLink" disabled>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" id="basic-addon2" onclick="copyRef(this)">Copy</span>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="col-12 col-lg-5 card-box">
                                <div class="sub-head">
                                    Referral History
                                </div>
                                <hr>
                                <div class="table-responsive">
                                    <table class="table table-hover table-box border-0 bg-transparent">
                                        <thead>
                                            <tr>
                                                <th scope="col">User</th>
                                                <th scope="col">Date Joined</th>
                                                <th scope="col">STATUS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if($referrals[1] < 1): ?>
                                                <tr>
                                                    <td colspan="6">
                                                        <div class="col-12 text-center py-5">
                                                            No Referrals Yet...
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach($referrals[0] as $referred): ?>
                                                    <tr>
                                                        <td><?= $referred['fullname']; ?></td>
                                                        <td><?= $referred['date']; ?></td>
                                                        <td><?= $referred['status'] == 1 ? 'Verified' : 'Unverified'; ?></td>
                                                    </tr>
                                            <?php endforeach; endif; ?>
                                        </tbody>
                                    </table>
                                </div>
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
<script>
    let data = {
        type: "error",
        status: 0,
        message: "fill",
        content: ""
    }
    function copyRef(self) {
        data.type = "success"
        data.content = "Referral link copied to clipboard"
        if(navigator.clipboard.writeText(document.getElementById("refLink").value)){
            new Func().notice_box(data)
        }
    }
</script>