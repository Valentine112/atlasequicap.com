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
        <div class="container-sb">
            <?php include "src/template/sb/sidebar.php"; ?>
            <section class="content">
                <?php include "src/template/head.php"; ?>
                <main>
                    <header>
                        <p class="title">Account</p>
                    </header>

                    <section class="data-section">
                        <div class="data-header">
                            <div>
                                <div class="wallet-sec active">Stocks Trading</div>
                                &emsp;&emsp;
                                <div class="wallet-sec">Crypto Trading</div>
                            </div>
                        </div>
                        <div class="data-body">
                            <div class="row justify-content-between align-items-center mb-4">
                                <div class="col-5">
                                    <p>TRADING ID</p>
                                    <p>ST-VT-4868680821</p>
                                </div>
                                <div class="col-5 text-end">
                                    <button class="btn btn-transparent" style="background-color: #303C51; color: #fff;">Deposit</button>
                                </div>
                            </div>
                            <div class="row col-11 mx-auto justify-content-between align-items-center mb-4" style="border: 1px solid grey; padding: 20px; border-radius: 10px;">
                                <div class="col-5">
                                    <h5 class="h4">$0.00</h5>
                                    <p><span id="walletType">Crypto</span> Balance</p>
                                </div>
                                <div class="col-5 text-end">
                                    <button class="btn btn-transparent" style="background-color: #303C51; color: #fff;"><i class="las la-wallet"></i>Transfer</button>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="chart">
                        <!-- TradingView Widget BEGIN -->
                        <div class="tradingview-widget-container">
                            <div class="tradingview-widget-container__widget"></div>
                            <div class="tradingview-widget-copyright"><a href="https://www.tradingview.com/" rel="noopener nofollow" target="_blank"><span class="blue-text">Apple quotes by TradingView</span></a></div>
                            <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-symbol-overview.js" async>
                                {
                                "lineWidth": 2,
                                "lineType": 0,
                                "chartType": "area",
                                "fontColor": "rgb(106, 109, 120)",
                                "gridLineColor": "rgba(242, 242, 242, 0.06)",
                                "volumeUpColor": "rgba(34, 171, 148, 0.5)",
                                "volumeDownColor": "rgba(247, 82, 95, 0.5)",
                                "backgroundColor": "#0F0F0F",
                                "widgetFontColor": "#DBDBDB",
                                "upColor": "#22ab94",
                                "downColor": "#f7525f",
                                "borderUpColor": "#22ab94",
                                "borderDownColor": "#f7525f",
                                "wickUpColor": "#22ab94",
                                "wickDownColor": "#f7525f",
                                "colorTheme": "dark",
                                "isTransparent": true,
                                "locale": "en",
                                "chartOnly": false,
                                "scalePosition": "right",
                                "scaleMode": "Normal",
                                "fontFamily": "-apple-system, BlinkMacSystemFont, Trebuchet MS, Roboto, Ubuntu, sans-serif",
                                "valuesTracking": "1",
                                "changeMode": "price-and-percent",
                                "symbols": [
                                    [
                                    "Apple",
                                    "AAPL|1D"
                                    ]
                                ],
                                "dateRanges": [
                                    "1d|1",
                                    "1m|30",
                                    "3m|60",
                                    "12m|1D",
                                    "60m|1W",
                                    "all|1M"
                                ],
                                "fontSize": "10",
                                "headerFontSize": "medium",
                                "autosize": true,
                                "width": "100%",
                                "height": "100%",
                                "noTimeScale": false,
                                "hideDateRanges": false,
                                "hideMarketStatus": false,
                                "hideSymbolLogo": false
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
                            <div class="row col-11 justify-content-between align-items-center mb-4">
                                <div class="col-sm-6 row justify-content-start align-items-center">
                                    <div class="col-2">
                                        <img src="../src/assets/logos/aapl.png" alt="">
                                    </div>
                                    <div class="col-5">
                                        <span><h5>Apple Inc.</h5>AAPL</span>
                                    </div> 
                                </div>
                                <div class="col-sm-4 text-end">
                                    <button class="btn btn-transparent" style="background-color: #303C51; color: #fff;">Trade</button>
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