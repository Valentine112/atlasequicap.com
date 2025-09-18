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
<body class="data-light-body">
    <?php include "src/template/quick-notice.php"; ?>
    <div class="section">
        <div class="wrapper d-flex align-items-stretch">
            <?php include "src/template/sb/sidebar.php"; ?>

            <section class="content p-4 p-md-5 pt-5">
                <?php include "src/template/head.php"; ?>
                <main>
                    <header>
                        <p class="title">Crypto Trading</p>
                    </header>

                    <section class="crypto-box">
                        <div class="row justify-content-start">
                            <div class="col-3 text-right">
                                <select name="coin" id="coin" class="form-inp" onchange="location = this.value;">
                                    
                                </select>
                            </div>
                            <div class="col-3 text-left">
                                <span id="price" class="sub-sub-text text-success">$<?= $coins['price']; ?></span>
                            </div>
                        </div>
                        <div class="row mt-3 justify-content-around mx-auto">
                            <div class="col-12 col-lg-8">
                                <!-- TradingView Widget BEGIN -->
                                <div class="tradingview-widget-container" style="height:100%;width:100%" id="tradingContainer">
                                    <div class="tradingview-widget-container__widget" style="height:calc(100% - 32px);width:100%"></div>
                                    <div class="tradingview-widget-copyright"><a href="" rel="noopener nofollow" target="_blank" id="coinUrl"><span class="blue-text"></span></a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-lg-4 justify-content-around card-box">
                                <div class="data-header mx-auto">
                                    <div id="nav-tab" role="tablist" class="justify-content-around mx-auto orderSec">
                                        <div class="wallet-sec active" id="market-tab" data-bs-toggle="tab" data-bs-target="#stock" type="button" role="tab" aria-controls="stock" aria-selected="true" onclick="orderType(this, 'market')">Market</div>
                                      
                                        <div class="wallet-sec" id="limit-tab" data-bs-toggle="tab" data-bs-target="#crypto" type="button" role="tab" aria-controls="exec" aria-selected="true" onclick="orderType(this, 'limit')">Limit</div>

                                        <div class="wallet-sec" id="stop-tab" data-bs-toggle="tab" data-bs-target="#crypto" type="button" role="tab" aria-controls="exec" aria-selected="true" onclick="orderType(this, 'stop')">Stop</div>
                                    </div>
                                </div>

                                <div class="cryptoOrder" style="font-size: 15px!important;">
                                    <div class="mt-3">
                                        <label for="amount">Amount</label>
                                        <input type="number" class="form-control form-inp" placeholder="0.00" id="amount" onkeyup="amount(this, <?= $coins['price']; ?>)">
                                    </div>
                                    <div class="mt-0 row">
                                        <div class="col-4">
                                            <small>Crypto Wallet</small>
                                        </div>
                                        <div class="col-8 text-end">
                                            <small class="text-success">$<?= $user['crypto']; ?></small>
                                        </div>
                                    </div>

                                    <div class="mt-3 row">
                                        <div class="col-2 text-info">
                                            Side
                                        </div>
                                        <div class="col-10 text-end side">
                                            <button class="btn btn-primary" onclick="side(this, 'buy')">Buy</button>
                                            <button class="btn btn-danger" style="opacity: 0.2" onclick="side(this, 'sell')">Sell</button>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="mt-2 exec-box" style="display: none;">
                                        <label for="amount">Execution Price</label>
                                        <input type="number" class="form-control form-inp" placeholder="0.00" id="execution">
                                    </div>
                                    <div class="mt-2">
                                        <label for="amount">Take Profit</label>
                                        <input type="number" class="form-control form-inp" placeholder="0.00" id="profit">
                                    </div>
                                    <div class="mt-2">
                                        <label for="amount">Stop Loss</label>
                                        <input type="number" class="form-control form-inp" placeholder="0.00" id="loss">
                                    </div>
                                    <div class="mt-3 row">
                                        <div class="col-6">
                                            Duration
                                        </div>
                                        <div class="col-6 text-end">
                                            Leverage
                                        </div>
                                    </div>
                                    <div class="mt-0 row">
                                        <div class="col-9">
                                            <select name="" id="duration" class="form-control form-inp">
                                                <option value="1">1 Day</option>
                                                <option value="7">1 Week</option>
                                                <option value="14">2 Weeks</option>
                                            </select>
                                        </div>
                                        <div class="col-3">
                                            <select name="" id="leverage" class="form-control form-inp">
                                                <option value="1">1x</option>
                                                <option value="3">3x</option>
                                                <option value="5">5x</option>
                                                <option value="10">10x</option>
                                                <option value="15">15x</option>
                                                <option value="25">25x</option>
                                                <option value="30">30x</option>
                                                <option value="40">40x</option>
                                                <option value="50">50x</option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="mt-0 row">
                                        <div class="col-6">
                                            <small>Free Margin.</small>
                                        </div>
                                        <div class="col-6 text-end">
                                            <small>0.00 USDT</small>
                                        </div>
                                    </div>
                                    <div class="mt-0 row">
                                        <div class="col-6">
                                            <small>Order Quantity</small>
                                        </div>
                                        <div class="col-6 text-end">
                                            <small id="orderValue">0.00 BTC</small> 
                                        </div>
                                    </div>  
                                    <div class="mt-0 row">
                                        <div class="col-6">
                                            <small>Margin Impact</small>
                                        </div>
                                        <div class="col-6 text-end">
                                            <small id="orderValue">0.00 USDT</small>
                                        </div>
                                    </div>  
                                    <div class="mt-0 row">
                                        <div class="col-6">
                                            <small>New Free Margin</small>
                                        </div>
                                        <div class="col-6 text-end">
                                            <small id="orderValue">-0.00 USDT</small>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="mt-2">
                                        <?php if($user['stock'] > 0): ?>
                                            <button class="btn col-12 btn-success" onclick="trade(this, <?= $coins['price']; ?>)" id="trade" data-side="buy">Trade</button>
                                        <?php else: ?>
                                            <a href="deposit" class="btn form-control btn-info">Deposit wallet to trade</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 card-box mt-3 historyBox">
                                <div class="row px-2">
                                    <div class="col-4 col-lg-2 active text-center" onclick="history(this, 'all')">
                                        All Positions
                                    </div>
                                    <!-- <div class="col-4 col-lg-2 text-center" onclick="history(this, 'fill')">
                                        Filled Orders
                                    </div>
                                    <div class="col-4 col-lg-2 text-center" onclick="history(this, 'open')">
                                        Open Orders
                                    </div>
                                    <div class="col-4 col-lg-2 text-center" onclick="history(this, 'closed')">
                                        Closed Orders
                                    </div> -->
                                </div>

                                <div class="table-responsive mt-2">
                                    <table class="table table-box border-0 bg-transparent">
                                        <thead>
                                            <tr>
                                                <th scope="col">ASSET</th>
                                                <th scope="col">TYPE</th>
                                                <th scope="col">POSITION SIZE</th>
                                                <th scope="col">ENTRY PRICE</th>
                                                <th scope="col">TP/SL</th>
                                                <th scope="col">PNL</th>
                                                <th scope="col">DURATION</th>
                                                <th scope="col" class="historyStats">STATUS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(count($userCrypto) < 1): ?>
                                                <tr>
                                                    <td colspan="8" class="table-span">
                                                        <div class="col-12 text-center py-5">
                                                            No transaction yet...
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php else: foreach($userCrypto as $crypt): ?>
                                                <tr>
                                                    <td>
                                                        <?= $crypt['asset']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $crypt['orderType']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $crypt['size']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $crypt['entry']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $crypt['tp']."/".$crypt['sl']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $crypt['profit']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $crypt['duration']."Day(s)"; ?>
                                                    </td>
                                                    <td>
                                                        <?= $crypt['status'] == 0 ? "Opened" :  ($crypt['status'] == 1 ? "Filled" : "Closed"); ?>
                                                    </td>
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
<script>
    function orderType(self, type) {
        let execBox = document.querySelector(".exec-box")
        if(type == "limit" || type == "stop") {
            execBox.style.display = "block"
        }else{
            execBox.style.display = "none"
        }
    }
</script>
<script>
    let param = new Func().getPath()['parameter'] ?? null
    let symbol = param.coin.toUpperCase()
    let data = {
        type: "success",
        status: 1,
        message: "fill",
        content: ""
    }
    let cryptoWallet = <?= $user['crypto']; ?>;
    function fetchCoins() {
        let ind = 0
        let coin = document.getElementById("coin")
        let selectedOption = ""
        fetch("https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&per_page=50&page=1")
        .then(res => res.json())
        .then(data => {
            data.forEach((c, i) => {
                if(symbol == c.symbol) ind = i 
                coin.insertAdjacentHTML('beforeEnd', `<option value="crypto?coin=${c.symbol}" data-price=${c.current_price}>${c.symbol.toUpperCase()}</option>`)
            });
        })
        .catch(err => console.log(err));
        document.getElementById("price").innerText = document.getElementById("coin").value
        coin.value = `crypto?coin=${symbol}`
    }

    fetchCoins()

    function history(self, type) {
        document.querySelector(".historyBox .active").classList.remove("active")
        self.classList.add("active")
        let historyStats = document.querySelector(".historyStats")
        let tableSpan = document.querySelector(".table-span")
        if(type == "all") {
            historyStats.style.display = "none"
        }else{
            historyStats.style.display = "block"
        }
    }

    function trade(self, price) {
        let error = false
        let order = document.querySelector(".orderSec .active").innerText
        let amount = document.getElementById("amount").value
        let side = self.getAttribute("data-side")
        let execution = document.getElementById("execution").value
        let profit = document.getElementById("profit").value
        let loss = document.getElementById("loss").value
        let duration = document.getElementById("duration").value
        let leverage = document.getElementById("leverage").value

        if(order == "Market") execution = price
       
        if(amount == "" || profit == "" || loss == "" || execution == ""){
            data.type = "warning"
            data.content = "Please fill all forms"
            new Func().notice_box(data)
            error = true
        }

        if(amount > cryptoWallet) {
            data.type = "warning"
            data.content = "Please fund your crypto wallet"
            new Func().notice_box(data)
            error = true
        }
        if(!error) {
            let dataObj = {order, symbol, amount, side, execution, profit, loss, duration, leverage}

             let payload = {
                part: "user",
                action: "orderCrypto",
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


    function side(self, type) {
        self.style.opacity = "1"
        if(type == "buy"){
            self.nextElementSibling.style.opacity = "0.2"
        }else{
            self.previousElementSibling.style.opacity = "0.2"
        }
        document.getELementById("trade").setAttribute("data-side", type)
    }

    function amount(self, price) {
        document.getElementById("orderValue").innerText = (self.value / price).toFixed(2) + " BTC"
    }
</script>

<script>
    window.addEventListener("load", () =>  {
        // Fetch parameter from URL
        let param = new Func().getPath()['parameter'] ?? null
        let symbol = param.coin.toUpperCase()
        
        const script = document.createElement("script");
        script.src = "https://s3.tradingview.com/external-embedding/embed-widget-advanced-chart.js";
        script.type = "text/javascript";
        script.async = true;
        script.innerHTML = `
        {
            "allow_symbol_change": false,
            "calendar": false,
            "details": false,
            "hide_side_toolbar": true,
            "hide_top_toolbar": false,
            "hide_legend": false,
            "hide_volume": false,
            "hotlist": false,
            "interval": "D",
            "locale": "en",
            "save_image": true,
            "style": "1",
            "symbol": "OANDA:${symbol}USD",
            "theme": "dark",
            "timezone": "Etc/UTC",
            "backgroundColor": "#0F0F0F",
            "gridColor": "rgba(242, 242, 242, 0.06)",
            "watchlist": [],
            "withdateranges": false,
            "compareSymbols": [],
            "studies": [],
            "autosize": true
        }`

        document.getElementById("tradingContainer").insertAdjacentElement('beforeend', script)
    })
</script>
</html>
