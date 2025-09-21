<?php require "php/general.php"; ?>
<?php
    use Service\Func;
    $symbol = "";
    if(isset($_GET['symbol'])):
        $symbol = Func::cleanData($_GET['symbol'], 'string');
    endif;
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
<body class="data-dark-body">
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
                                <a href="" rel="noopener nofollow" target="_blank" id="coinUrl"><span class="blue-text"></span></a>
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
                                <div class="sub-sub-head">
                                    <?= $totalShares; ?>
                                    <small><?= strtoupper($symbol); ?></small>
                                </div>
                            </div>
                            <div class="col-6 text-end">
                                <div style="color: silver">Total Profit</div>
                                <div>$<?= $totalProfit; ?></div>
                            </div>
                        </div>
                        <hr>
                        <div class="row mt-1 justify-content-between align-items-center">
                            <div class="col-6">
                                <div style="color: silver">Cost of Purchase</div>
                                <div class="sub-sub-head">$<?= $totalAmount; ?></div>
                            </div>
                            <div class="col-6 text-end">
                                <div style="color: silver">Stock</div>
                                <div><?= strtoupper($symbol); ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="card-box mt-3 symbol-box">
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
                                            <option value="Market Buy">Market Buy</option>
                                            <option value="Limit Buy">Limit Buy</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="limitOpts" class="d-none">
                                    <div class="row justify-content-between align-items-center mt-3">
                                        <div class="col-3">Buy Limit</div>
                                        <div class="col-9">
                                            <input type="text" placeholder="0" class="form-control form-inp" id="limitPrice" value="<?= $symbolInfo['regularMarketPrice'] ?? null; ?>">
                                        </div>
                                    </div>
                                    <div class="row justify-content-between align-items-center mt-3">
                                        <div class="col-3">Exp Date.</div>
                                        <div class="col-9">
                                            <select name="limitExp" id="limitExp" class="form-control form-inp">
                                                <option value="1">A Day</option>
                                                <option value="7">A Week</option>
                                                <option value="30">A Month</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-between align-items-center mt-3">
                                    <div class="col-3">Amount</div>
                                    <div class="col-9">
                                        <input type="number" placeholder="Amount (USD)" class="form-control form-inp" id="amount">
                                    </div>
                                </div>
                                <hr>
                                <div class="row justify-content-between align-items-center mt-3">
                                    <div class="col-3">Stock Balance</div>
                                    <div class="col-9 text-end">
                                        $<?= $user['stock']; ?>.00
                                    </div>
                                </div>
                                <hr>
                                <div>
                                    <button class="form-control btn" onclick="order(this, 'buy', 'stock', <?= $symbolInfo['regularMarketPrice'] ?? null; ?>, '<?= $symbol; ?>', <?= count($userStocks) > 0 ? $userStocks[0]['id'] : 0; ?>)">Buy</button>
                                </div>
                            </div>

                            <div class="tab-pane fade data-body" id="sell" role="tabpanel" aria-labelledby="sell-tab">
                            
                                <div class="table-responsive">
                                    <table class="table table-box border-0 bg-transparent">
                                        <thead>
                                            <tr>
                                                <th scope="col">SHARES</th>
                                                <th scope="col">ORDER</th>
                                                <th scope="col">AMOUNT</th>
                                                <th scope="col">LIMIT</th>
                                                <th scope="col">DATE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(count($userStocks) < 1): ?>
                                                <tr>
                                                    <td colspan="6">
                                                        <div class="col-12 text-center py-5">
                                                            You current do not own any <?= $symbol; ?> stock
                                                        </div>
                                                    </td>
                                                    
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach($userStocks as $stock): ?>
                                                    <tr>
                                                        <td><?= $stock['shares']; ?></td>
                                                        <td><?= $stock['orderType']; ?></td>
                                                        <td>$<?= $stock['amount']; ?></td>
                                                        <td><?= $stock['limitOrder']; ?></td>
                                                        <td><?= substr($stock['date'], 0, 10); ?></td>
                                                    </tr>
                                            <?php endforeach; endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <hr>
                                <div class="row justify-content-between align-items-center mt-3">
                                    <div class="col-3">Available Shares</div>
                                    <div class="col-9 text-end">
                                        <?= count($userStocks) > 0 ? $userStocks[0]['shares'] : 0; ?> <?= strtoupper($symbol); ?>
                                    </div>
                                </div>
                                <hr>
                                <div>
                                    <button class="form-control btn" onclick="order(this, 'sell', 'stock', <?= $symbolInfo['regularMarketPrice'] ?? null; ?>, '<?= $symbol; ?>', <?= count($userStocks) > 0 ? $userStocks[0]['id'] : 0; ?>)">Sell/Close All</button>
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
    let data = {
        type: "error",
        status: 0,
        message: "fill",
        content: ""
    }
    let stock = <?= $user['stock']; ?>;
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
                "${symbol.toUpperCase()}|1D"
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
        let limitOpts = self.closest(".data-body").querySelector("#limitOpts")
        if(self.value == "Limit Buy" || self.value == "Limit Sell"){
            limitOpts.classList.remove("d-none")
        }
        else{
            limitOpts.classList.add("d-none")
        }
    }

    function order(self, orderType, market, price, symbol, id) {
        let error = false
        let parent = self.closest(".data-body")
        let type = ""
        let limitPrice = ""
        let expDate = ""
        let amount = ""

        if(orderType == "buy"){
            amount = parent.querySelector("#amount").value
            type = parent.querySelector("#order").value
            limitPrice = parent.querySelector("#limitPrice").value
            expDate = parent.querySelector("#limitExp").value
        }

        // validations
        // Check if stock balance is sufficient
        if(orderType == "buy"){
            if(stock < amount) {
                data.type = "warning"
                data.content = "Please fund your stock wallet"
                new Func().notice_box(data)
                error = true
            }
            // Amount is in both, so i just targeted the closest one to this element
            if(amount < 1 || amount == "") {
                data.type = "warning"
                data.content = "Please enter a valid amount"
                new Func().notice_box(data)
                error = true
            }
        }

        // Check for the limit price next
        if(type == "Limit Buy") {
            if(limitPrice < 1 || limitPrice == "") {
                data.type = "warning"
                data.content = "Please enter a valid limit"
                new Func().notice_box(data)
                error = true
            }else{
                if(error) error = true
            }
        }else{
            limitPrice = ""
            expDate = ""
        }

        let dataObj = {orderType, market, price, symbol, amount, type, limitPrice, expDate, id}

        if(!error){
            let payload = {
                part: "user",
                action: "orderStock",
                val: dataObj
            }

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
        
    }
</script>
</html>