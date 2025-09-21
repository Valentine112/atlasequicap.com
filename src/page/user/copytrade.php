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
                        <p class="title">Copy Professionals</p>
                    </header>

                    <section class="dashboard content traders profile">
                        <div class="col-12 col-lg-11 mx-auto head-box p-4">
                            <div class="col-12 col-lg-9 mx-auto text-center">
                                <h2>
                                    Discover World Best Expert Traders
                                </h2>
                                <p>Follow and copy top traders on Sterling Capital and actively enjoy seamless trading</p>
                            </div>
                            
                        </div>
                        <div class="row boxCover justify-content-around col-12 mx-auto">
                            <div class="mt-3 col-12 col-lg-9 row justify-content-around align-content-start mb-3 text-center detail-box">
                                <div class="col-4">
                                    <small>Copytrading</small>
                                    <h5 class="main-color"><?= $cTrader > 0 ? $cData['name'] : "None"; ?></h5>
                                </div>
                                <div class="col-4">
                                    <small>Ending</small>
                                    <h5 class="main-color"><?= $cTrader > 0 ? (5 - $cCount) : "None"; ?></h5>
                                </div>
                                <div class="col-4">
                                    <small>Invested</small>
                                    <h5 class="main-color"><?= $cTrader > 0 ? "$".$cAmount : "None"; ?></h5>
                                </div>
                                <div class="col-4 justify-content-start">
                                    <small>Profit</small>
                                    <h5 class="main-color"><?= $cTrader > 0 ? "$".$cProfit : "None"; ?></h5>
                                </div>
                                <?php if($cTrader > 0): ?>
                                    <div class="col-9">
                                        <button class="btn btn-danger" onclick="submit(this, 'endtrade')">Cancel Copytrading</button>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php foreach ($traders[0] as $key => $trader): ?>
                                <div class="col-12 col-md-5 my-4 traders-box" data-parent="body">
                                    <div class="col-12 row justify-content-between align-items-center">
                                        <div class="col-8 row align-items-center">
                                            <div class="col-4">
                                                <i class="las la-user-circle pic"></i>
                                            </div>
                                            <div class="col-8 text-center">
                                                <span><?= $trader['name']; ?></span>
                                                <br>
                                                <small><i class="las la-users ml-0"></i> - <?= $trader['followers']; ?></small>
                                            </div>
                                        </div>
                                        <div class="col-4 text-right">
                                            <button class="btn btn-warning px-5" onclick="copyTrade(this)"><i class="las la-copy ml-0"></i></button>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-12 row justify-content-around align-items-center">
                                        <div class="col-12">
                                            <small class="faint">Statistics for 7d</small>
                                        </div>
                                        <div class="col-4">
                                            <small class="faint">Winrate</small>
                                            <h5 class="text-white"><?php
                                            $total = ($trader['win'] + $trader['loss'] + $trader['even']);
                                                echo floor(
                                                ($trader['win'] * 100)/
                                                ($total == 0 ? 1 : $total)
                                                ); ?>%</h5>
                                        </div>
                                        <div class="col-4">
                                            <small class="faint">ROI</small>
                                            <h5 class="text-white"><?= $trader['roi']; ?>%</h5>
                                        </div>
                                        <div class="col-4">
                                            <small class="faint">PnL</small>
                                            <h5 class="text-white"><?= $trader['pnl'] < 0 ? "-" : "+"; ?><?= $trader['pnl']; ?></h5>
                                        </div>
                                    </div>
                                    <div class="col-12 align-items-center">
                                        <canvas id="myChart<?= $key; ?>" style="width: 100%;" class="pi"></canvas>
                                    </div>

                                    <div class="proceed mt-3 align-items-center row justify-content-center" data-bs-theme="dark">
                                        <div class="col-8">
                                            <input type="number" id="amount" class="form-control form-inp" placeholder="amount">
                                        </div>
                                        <div class="col-3 text-center">
                                            <button class="btn btn-success" onclick="submit(this, 'trader')">Go</button>
                                        </div>
                                    </div>

                                    <input type="hidden" id="stats" data-id="<?= $trader['id']; ?>" data-win="<?= $trader['win']; ?>" data-loss="<?= $trader['loss']; ?>" data-even="<?= $trader['even']; ?>">
                                    <hr>
                                    <div>
                                        <small class="beware">We are not responsible for any losses encountered from copying tarders on our platform. Make sure to always do your analysis first.</small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
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
    window.addEventListener("load", () => {
        let xValues = ["Win", "Loss", "Even"]
        let barColors = ["lightgreen", "red", "white"]
        let tradersBox = document.querySelectorAll(".traders-box")
        Array.from(tradersBox).forEach((elem, ind) => {
            let stats = elem.querySelector("#stats")
            let yValues = [stats.getAttribute("data-win"), stats.getAttribute("data-loss"), stats.getAttribute("data-even")]
            //get canvas id
            new Chart(`myChart${ind}`, {
                type: "doughnut",
                data:{
                    labels: xValues,
                    datasets: [{
                        backgroundColor: barColors,
                        data: yValues
                    }]
                }, options:{
                    title: {
                        display: true,
                        text: "Analytical preview of trade record"
                    }
                }
            })
        })
    })

    function copyTrade(self) {
        // First hide all the proceed boxes
        document.querySelectorAll(".proceed").forEach(elem => elem.style.display = "none")

        let parent = self.closest(".traders-box")
        parent.querySelector(".proceed").style.display = "flex"
        parent.querySelector("#amount").focus()
    }
</script>
</html>