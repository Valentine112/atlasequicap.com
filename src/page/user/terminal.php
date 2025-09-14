<?php //require "php/general.php"; ?>
<?php
    /*$symbol = "";
    if(isset($_GET['symbol'])):
        $symbol = Func::cleanData($_GET['symbol'], 'string');
    endif;*/
?>
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

                    <section class="chart">
                        <!-- TradingView Widget BEGIN -->
                        <div class="tradingview-widget-container" id="tradingContainer">
                            <div class="tradingview-widget-container__widget"></div>
                            <div class="tradingview-widget-copyright">
                                <a href="https://www.tradingview.com/" rel="noopener nofollow" target="_blank" id="chartlink"><span class="blue-text">MSFT chart by TradingView</span></a>
                            </div>
                            
                        </div>
                        <!-- TradingView Widget END -->
                    </section>

                    <div class="card-box mt-3 col-lg-7 statistics">
                        <h5 class="sub-sub-head">Key Statistics</h5>
                        <hr>
                        <div class="row mt-1 justify-content-between align-items-center">
                            <div class="col-6">
                                <div style="color: silver">Total Shares</div>
                                <div class="sub-sub-head">0.00 <small>TSLA</small></div>
                            </div>
                            <div class="col-6 text-end">
                                <div style="color: silver">Total Profit</div>
                                <div>$0.00</div>
                            </div>
                        </div>
                        <hr>
                        <div class="row mt-1 justify-content-between align-items-center">
                            <div class="col-6">
                                <div style="color: silver">Cost of Purchase</div>
                                <div class="sub-sub-head">$0.00</div>
                            </div>
                            <div class="col-6 text-end">
                                <div style="color: silver">Today's Profit</div>
                                <div>$0.00</div>
                            </div>
                        </div>
                    </div>

                    <div class="card-box mt-3">
                        <div class="data-header">
                            <div id="nav-tab" role="tablist">
                                <div class="wallet-sec active" id="buy-tab" data-bs-toggle="tab" data-bs-target="#buy" type="button" role="tab" aria-controls="buy" aria-selected="true">Buy</div>
                                &emsp;
                                <div class="wallet-sec" id="sell-tab" data-bs-toggle="tab" data-bs-target="#sell" type="button" role="tab" aria-controls="sell" aria-selected="false">Sell</div>
                                
                            </div>
                        </div>
                        <div class="assets-info tab-content" id="nav-tabContent">
                            <div class="tab-pane show fade active data-body" id="buy" role="tabpanel" aria-labelledby="buy-tab">
                                <div class="row justify-content-between align-items-center">
                                    <div class="col-3">
                                        Order Type
                                    </div>
                                    <div class="col-9">
                                        <select name="order" id="order" class="form-control form-inp" onchange="orderType(this)">
                                            <option value="market">Market Buy</option>
                                            <option value="limit">Limit Buy</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="limitOpts" class="d-none">
                                    <div class="row justify-content-between align-items-center mt-3">
                                        <div class="col-3">Buy Limit</div>
                                        <div class="col-9">
                                            <input type="text" placeholder="0" class="form-control form-inp">
                                        </div>
                                    </div>
                                    <div class="row justify-content-between align-items-center mt-3">
                                        <div class="col-3">Exp Date.</div>
                                        <div class="col-9">
                                            <select name="limitExp" id="limitExp" class="form-control form-inp">
                                                <option value="day">A Day</option>
                                                <option value="week">A Week</option>
                                                <option value="month">A Month</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-between align-items-center mt-3">
                                    <div class="col-3">Amount</div>
                                    <div class="col-9">
                                        <input type="text" placeholder="Amount (USD)" class="form-control form-inp">
                                    </div>
                                </div>
                                <hr>
                                <div class="row justify-content-between align-items-center mt-3">
                                    <div class="col-3">Wallet Balance</div>
                                    <div class="col-9 text-end">
                                        $0.00
                                    </div>
                                </div>
                                <hr>
                                <div>
                                    <button class="form-control btn" onclick="order(this, 'buy')">Buy</button>
                                </div>
                            </div>

                            <div class="tab-pane fade data-body" id="sell" role="tabpanel" aria-labelledby="sell-tab">
                            <div class="row justify-content-between align-items-center">
                                <div class="col-3">
                                    Order Type
                                </div>
                                <div class="col-9">
                                    <select name="order" id="order" class="form-control form-inp" onchange="orderType(this)">
                                        <option value="market">Market Sell</option>
                                        <option value="limit">Limit Sell</option>
                                    </select>
                                </div>
                            </div>
                            <div id="limitOpts" class="d-none">
                                <div class="row justify-content-between align-items-center mt-3">
                                    <div class="col-3">Sell Limit</div>
                                    <div class="col-9">
                                        <input type="text" placeholder="0" class="form-control form-inp">
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-between align-items-center mt-3">
                                <div class="col-3">Amount</div>
                                <div class="col-9">
                                    <input type="text" placeholder="Amount (USD)" class="form-control form-inp">
                                </div>
                            </div>
                            <hr>
                            <div class="row justify-content-between align-items-center mt-3">
                                <div class="col-3">Available Shares</div>
                                <div class="col-9 text-end">
                                    0.00
                                </div>
                            </div>
                            <hr>
                            <div>
                                <button class="form-control btn" onclick="order(this, 'sell')">Sell</button>
                            </div>
                            </div>
                        </div>
                    </div>
                </main>
            </section>
        </div>
    </div>
</body>
<script>
    window.addEventListener("load", () =>  {
        // Fetch parameter from URL
        let param = new Func().getPath()['parameter'] ?? null
        let symbol = param.symbol
        
        const script = document.createElement("script");
        script.src = "https://s3.tradingview.com/external-embedding/embed-widget-symbol-overview.js";
        script.type = "text/javascript";
        script.async = true;
        script.innerHTML = `
        {
            "lineWidth": 2,
            "lineType": 0,
            "chartType": "area",
            "fontColor": "rgb(106, 109, 120)",
            "gridLineColor": "rgba(46, 46, 46, 0.06)",
            "volumeUpColor": "rgba(34, 171, 148, 0.5)",
            "volumeDownColor": "rgba(247, 82, 95, 0.5)",
            "backgroundColor": "#ffffff",
            "widgetFontColor": "#0F0F0F",
            "upColor": "#22ab94",
            "downColor": "#f7525f",
            "borderUpColor": "#22ab94",
            "borderDownColor": "#f7525f",
            "wickUpColor": "#22ab94",
            "wickDownColor": "#f7525f",
            "colorTheme": "light",
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
                "Microsoft",
                "MSFT|1D"
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
        }`

        document.getElementById("tradingContainer").insertAdjacentElement('beforeend', script)
    })

    function orderType(self) {
        console.log(self.value)
        let limitOpts = self.closest(".data-body").querySelector("#limitOpts")
        if(self.value == "limit"){
            limitOpts.classList.remove("d-none")
        }
        else{
            limitOpts.classList.add("d-none")
        }
    }

    function order(self, type) {
        
    }
</script>
</html>