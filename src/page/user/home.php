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
                        <p class="title">Account</p>
                    </header>

                    <section class="data-section">
                        <div class="data-header">
                            <div id="nav-tab" role="tablist">
                                <div class="wallet-sec active" id="stock-tab" data-bs-toggle="tab" data-bs-target="#stock" type="button" role="tab" aria-controls="stock" aria-selected="true">Stocks Trading</div>
                                &emsp;
                                <div class="wallet-sec" id="crypto-tab" data-bs-toggle="tab" data-bs-target="#crypto" type="button" role="tab" aria-controls="crypto" aria-selected="true">Cryptocurrency Trading</div>
                            </div>
                        </div>
                        <div class="data-body">
                            <div class="row justify-content-between align-items-center mb-4">
                                <div class="col-5">
                                    <div class="sub-sect">TRADING ID</div>
                                    <p class="trade-id sub-head">VT-<?= $user['tradeId']; ?></p>
                                </div>
                                <div class="col-5 text-end">
                                    <a href="deposit" class="btn btn-transparent">Deposit</a>
                                </div>
                            </div>

                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane show fade active" id="stock" role="tabpanel" aria-labelledby="stock-tab">
                                    <div class="row col-11 mx-auto justify-content-between align-items-center mb-4" style="border: 1px solid #303f50; padding: 20px; border-radius: 10px;">
                                        <div class="col-5">
                                            <div class="balance sub-head">$<?= $user['stock']; ?>.00</div>
                                            <span>StockWallet Balance</span>
                                        </div>
                                        <div class="col-7 text-end align-items-center">
                                            <a href="convert" class="btn btn-transparent"><i class="las la-wallet"></i>&ensp;Transfer</a>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="tab-pane fade" id="crypto" role="tabpanel" aria-labelledby="crypto-tab">
                                    <div class="row col-11 mx-auto justify-content-between align-items-center mb-4" style="border: 1px solid #303f50; padding: 20px; border-radius: 10px;">
                                        <div class="col-5">
                                            <div class="balance sub-head">$<?= $user['crypto']; ?>.00</div>
                                            <span>CryptoWallet Balance</span>
                                        </div>
                                        <div class="col-7 text-end align-items-center">
                                            <a href="convert" class="btn btn-transparent"><i class="las la-wallet"></i>&ensp;Transfer</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="chart">
                        <!-- TradingView Widget BEGIN -->
                        <div class="tradingview-widget-container">
                            <div class="tradingview-widget-container__widget"></div>
                            <div class="tradingview-widget-copyright">
                                <a href="https://www.tradingview.com/symbols/NASDAQ-MSFT/?exchange=NASDAQ" rel="noopener nofollow" target="_blank"><span class="blue-text">MSFT chart by TradingView</span></a>
                            </div>
                            <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-mini-symbol-overview.js" async>
                                {
                                "symbol": "NASDAQ:MSFT",
                                "chartOnly": false,
                                "dateRange": "12M",
                                "noTimeScale": false,
                                "colorTheme": "light",
                                "isTransparent": true,
                                "locale": "en",
                                "width": "100%",
                                "autosize": true,
                                "height": "100%"
                                }
                            </script>
                        </div>
                        <!-- TradingView Widget END -->
                    </section>

                    <section class="positions mt-3">
                        <header>
                            <p class="title">Popular Positions</p>
                        </header>
                        <div class="positions-body">
                            <div class="row col-12 justify-content-between align-items-center mb-4">
                                <div class="col-8 row justify-content-start align-items-center">
                                    <div class="col-2">
                                        <img src="../src/assets/logos/aapl.png" alt="">
                                    </div>
                                    <div class="col-8">
                                        <span><div class="stock-name">Apple Inc.</div>AAPL</span>
                                    </div> 
                                </div>
                                <div class="col-4 text-end">
                                    <a href="stocks/terminal?symbol=aapl" class="btn btn-transparent" style="background-color: #303C51;">Trade</a>
                                </div>
                            </div>

                            <div class="row col-12 justify-content-between align-items-center mb-4">
                                <div class="col-8 row justify-content-start align-items-center">
                                    <div class="col-2">
                                        <img src="../src/assets/logos/msft.png" alt="">
                                    </div>
                                    <div class="col-8">
                                        <span><div class="stock-name">Mircosoft</div>MSFT</span>
                                    </div> 
                                </div>
                                <div class="col-4 text-end">
                                    <a href="stocks/terminal?symbol=MSFT" class="btn btn-transparent" style="background-color: #303C51;">Trade</a>
                                </div>
                            </div>

                            <div class="row col-12 justify-content-between align-items-center mb-4">
                                <div class="col-8 row justify-content-start align-items-center">
                                    <div class="col-2">
                                        <img src="../src/assets/logos/nflx.png" alt="">
                                    </div>
                                    <div class="col-8">
                                        <span><div class="stock-name">Netflix Inc.</div>NFLX</span>
                                    </div> 
                                </div>
                                <div class="col-4 text-end">
                                    <a href="stocks/terminal?symbol=nflx" class="btn btn-transparent" style="background-color: #303C51;">Trade</a>
                                </div>
                            </div>

                            <div class="row col-12 justify-content-between align-items-center mb-4">
                                <div class="col-8 row justify-content-start align-items-center">
                                    <div class="col-2">
                                        <img src="../src/assets/logos/tsla.png" alt="">
                                    </div>
                                    <div class="col-8">
                                        <span><div class="stock-name">Tesla Inc.</div>TSLA</span>
                                    </div> 
                                </div>
                                <div class="col-4 text-end">
                                    <a href="stocks/terminal?symbol=tsla" class="btn btn-transparent" style="background-color: #303C51;">Trade</a>
                                </div>
                            </div>

                            <div class="row col-12 justify-content-between align-items-center mb-4">
                                <div class="col-8 row justify-content-start align-items-center">
                                    <div class="col-2">
                                        <img src="../src/assets/logos/amzn.png" alt="">
                                    </div>
                                    <div class="col-8">
                                        <span><div class="stock-name">Amazon Inc.</div>AMZN</span>
                                    </div> 
                                </div>
                                <div class="col-4 text-end">
                                    <a href="stocks/terminal?symbol=amzn" class="btn btn-transparent" style="background-color: #303C51;">Trade</a>
                                </div>
                            </div>

                            <div class="row col-12 justify-content-between align-items-center mb-4">
                                <div class="col-8 row justify-content-start align-items-center">
                                    <div class="col-2">
                                        <img src="../src/assets/logos/btc.svg" alt="">
                                    </div>
                                    <div class="col-8">
                                        <span><div class="stock-name">Bitcoin</div>BTC</span>
                                    </div> 
                                </div>
                                <div class="col-4 text-end">
                                    <a href="crypto/terminal?symbol=btc" class="btn btn-transparent" style="background-color: #303C51;">Trade</a>
                                </div>
                            </div>

                            <div class="row col-12 justify-content-between align-items-center mb-4">
                                <div class="col-8 row justify-content-start align-items-center">
                                    <div class="col-2">
                                        <img src="../src/assets/logos/eth.svg" alt="">
                                    </div>
                                    <div class="col-8">
                                        <span><div class="stock-name">Ethereum</div>ETH</span>
                                    </div> 
                                </div>
                                <div class="col-4 text-end">
                                    <a href="crypto/terminal?symbol=eth" class="btn btn-transparent" style="background-color: #303C51;">Trade</a>
                                </div>
                            </div>

                            <div class="row col-12 justify-content-between align-items-center mb-4">
                                <div class="col-8 row justify-content-start align-items-center">
                                    <div class="col-2">
                                        <img src="../src/assets/logos/sol.svg" alt="">
                                    </div>
                                    <div class="col-8">
                                        <span><div class="stock-name">Solana</div>SOL</span>
                                    </div> 
                                </div>
                                <div class="col-4 text-end">
                                    <a href="crypto/terminal?symbol=sol" class="btn btn-transparent" style="background-color: #303C51;">Trade</a>
                                </div>
                            </div>

                            <div class="row col-12 justify-content-between align-items-center mb-4">
                                <div class="col-8 row justify-content-start align-items-center">
                                    <div class="col-2">
                                        <img src="../src/assets/logos/ton.svg" alt="">
                                    </div>
                                    <div class="col-8">
                                        <span><div class="stock-name">TON</div>TON</span>
                                    </div> 
                                </div>
                                <div class="col-4 text-end">
                                    <a href="crypto/terminal?symbol=ton" class="btn btn-transparent" style="background-color: #303C51;">Trade</a>
                                </div>
                            </div>

                            <div class="row col-12 justify-content-between align-items-center mb-4">
                                <div class="col-8 row justify-content-start align-items-center">
                                    <div class="col-2">
                                        <img src="../src/assets/logos/xrp.svg" alt="">
                                    </div>
                                    <div class="col-8">
                                        <span><div class="stock-name">Ripple</div>XRP</span>
                                    </div> 
                                </div>
                                <div class="col-4 text-end">
                                    <a href="crypto/terminal?symbol=xrp" class="btn btn-transparent" style="background-color: #303C51;">Trade</a>
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