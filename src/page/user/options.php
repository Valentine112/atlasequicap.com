<?php require "php/general.php"; ?>
<?php
    use Service\Func;
    $symbol = "";
    if(isset($_GET['options'])):
        $symbol = Func::cleanData($_GET['options'], 'string');
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../src/assets/css/general.css">
    <link rel="stylesheet" href="../assets/css/line-awesome.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
    <script src="//cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
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
                        <p class="title">Options</p>
                    </header>

                    <section class="options">
                        <div>
                            <div>
                                <h3 class="sub-head"><?= strtoupper($symbol); ?> | <small style="color: var(--main-color);">$395</small></h3>
                            </div>
                            <hr>
                            <div class="row data-section">
                                <div class="col-6 col-lg-2 data-header">
                                    <div id="nav-tab" role="tablist">
                                        <div class="wallet-sec active" id="buy-tab" data-bs-toggle="tab" data-bs-target="#buy" type="button" role="tab" aria-controls="buy" aria-selected="true">BUY</div>
                                        &emsp;
                                        <div class="wallet-sec" id="sell-tab" data-bs-toggle="tab" data-bs-target="#sell" type="button" role="tab" aria-controls="sell" aria-selected="true">SELL</div>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-2 data-header">
                                    <div id="nav-tab" role="tablist">
                                        <div class="wallet-sec active" id="put-tab" data-bs-toggle="tab" data-bs-target="#put" type="button" role="tab" aria-controls="put" aria-selected="true">PUT</div>
                                        &emsp;
                                        <div class="wallet-sec" id="call-tab" data-bs-toggle="tab" data-bs-target="#call" type="button" role="tab" aria-controls="call" aria-selected="true">CALL</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <hr>
                    <div class="data-body" id="parent">
                        <div class="tab-content" id="nav-tabContent">
                            <div class="table-responsive tab-pane show fade active" id="put" role="tabpanel" aria-labelledby="put-tab">
                                <table class="table table-borderless table-box border-0 bg-transparent" id="putTable">
                                    <thead>
                                        <tr>
                                            <th col="">Strike</th>
                                            <th col="">Expiration</th>
                                            <th col="">Last Price</th>
                                            <th col="">Bid</th>
                                            <th col="">Ask</th>
                                            <th col="">Volume</th>
                                            <th col="">Open Interest</th>
                                            <th>Price</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        <?php $options = $optionsInfo['options']['PUT'];
                                            foreach($options as $option): ?>
                                            <tr class="tab-pane fade show active" id="put" role="tabpanel" aria-labelledby="put-tab">
                                                <td>$<?= $option['strike']; ?>.00</td>
                                                <td><?= $option['expirationDate']; ?></td>
                                                <td><?= $option['lastPrice']; ?></td>
                                                <td><?= $option['bid']; ?></td>
                                                <td><?= $option['ask']; ?></td>
                                                <td><?= $option['volume']; ?></td>
                                                <td><?= $option['openInterest']; ?></td>
                                                <td>
                                                    <button class="btn btn-link" onclick="order(this, 'put', <?= $option['strike']; ?>, <?= $option['lastPrice']; ?>, '<?= $option['expirationDate']; ?>')">
                                                        <?= $option['type']; ?>  <?= $option['lastPrice']; ?>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <div id="pagination-container"></div>
                            </div>

                            <div class="table-responsive tab-pane fade" id="call" role="tabpanel" aria-labelledby="call-tab">
                                <table class="table table-borderless table-box border-0 bg-transparent" id="callTable">
                                    <thead>
                                        <tr>
                                            <th col="">Strike</th>
                                            <th col="">Expiration</th>
                                            <th col="">Last Price</th>
                                            <th col="">Bid</th>
                                            <th col="">Ask</th>
                                            <th col="">Volume</th>
                                            <th col="">Open Interest</th>
                                            <th>Price</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        <?php $options = $optionsInfo['options']['CALL'];
                                            foreach($options as $option): ?>
                                            <tr>
                                                <td>$<?= $option['strike']; ?>.00</td>
                                                <td><?= $option['expirationDate']; ?></td>
                                                <td><?= $option['lastPrice']; ?></td>
                                                <td><?= $option['bid']; ?></td>
                                                <td><?= $option['ask']; ?></td>
                                                <td><?= $option['volume']; ?></td>
                                                <td><?= $option['openInterest']; ?></td>
                                                <td>
                                                    <button class="btn btn-link" onclick="order(this, 'call', <?= $option['strike']; ?>, <?= $option['lastPrice']; ?>, '<?= $option['expirationDate']; ?>')">
                                                        <?= $option['type']; ?> <?= $option['lastPrice']; ?>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <div id="pagination-container"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card-box">
                        <h2 class="sub-sub-head"><?= strtoupper($symbol); ?> <span id="strike" style="color: var(--main-color);">$0.00</span> <span id="limitP"></span></h2>
                        <hr>

                        <div class="row justify-items-between align-items-center mt-2">
                            <div class="col-4">
                                <div class="mb-0">Contracts</div>
                                <small>100 Shares Each</small>
                            </div>
                            <div class="col-8">
                                <input type="number" name="amount" id="amount" class="form-control form-inp" placeholder="0" onkeyup="contract(this)">
                            </div>
                        </div>

                        <div class="row justify-items-between align-items-center mt-2">
                            <div class="col-4">
                                <div class="mb-0">Limit Price</div>
                            </div>
                            <div class="col-8">
                                <input type="number" name="contracts" id="contracts" class="form-control form-inp" placeholder="0.00" disabled>
                            </div>
                        </div>

                        <div class="row justify-items-between align-items-center mt-2">
                            <div class="col-4">
                                <div class="mb-0">Exp Date.</div>
                            </div>
                            <div class="col-8">
                                <select name="exp" id="exp" class="form-control form-inp">
                                    <option value="1">A Day</option>
                                    <option value="7">A Week</option>
                                    <option value="30">A month</option>
                                </select>
                            </div>
                        </div>

                        <hr>
                        <div class="row justify-items-between align-items-center mt-2">
                            <div class="col-4">
                                <div class="mb-0">Total Cost</div>
                            </div>
                            <div class="col-8 text-end">
                                $ <span id="totalCost">0</span>.00
                            </div>
                            </div>
                        <hr>
                        <?php if($user['crypto'] < 1): ?>
                            <div>
                                <a href="deposit" class="w-100 btn btn-info" style="padding: 10px 10px!important;">Deposit to Trade</a>
                            </div>
                        <?php else: ?>
                            <div>
                                <button class="form-control btn btn-link" onclick="placeOrder(this)">Order</button>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-12 text-center mt-3">
                        <?php print_r($options); ?>
                        <button class="btn btn-primary p-5"  data-bs-toggle="modal" data-bs-target="#optionsHistory">Options History</button>
                    </div>

                    <div class="modal" tabindex="-1" aria-labelledby="optionsHistory" id="optionsHistory" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel" class="payment-head sub-head">My Options</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="modal-details">
                                    <div class="table-responsive">
                                        <table class="table table-box border-0 bg-transparent">
                                            <thead>
                                                <tr>
                                                    <th scope="col">TRANSACTION ID</th>
                                                    <th scope="col">SYMBOL</th>
                                                    <th scope="col">TYPE</th>
                                                    <th scope="col">STRIKE</th>
                                                    <th scope="col">CONTRACT</th>
                                                    <th scope="col">ORDERTYPE</th>
                                                    <th scope="col">LIMIT PRICE</th>
                                                    <th scope="col">EXPIRATION</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(count($options) < 1): ?>
                                                    <tr>
                                                        <td colspan="6">
                                                            <div class="col-12 text-center py-5">
                                                                You have no options yet. Trade a stock now.
                                                            </div>
                                                        </td>
                                                        
                                                    </tr>
                                                <?php else: ?>
                                                    <?php 
                                                        foreach($options as $option): ?>
                                                        <tr>
                                                            <td>AE-<?= $option['tranx']; ?></td>
                                                            <td><?= $option['symbol']; ?></td>
                                                            <td><?= $option['type']; ?></td>
                                                            <td>$<?= $option['strike']; ?>.00</td>
                                                            <td><?= $option['contract']; ?></td>
                                                            <td><?= $option['market']; ?></td>
                                                            <td><?= $option['limitPrice']; ?></td>
                                                            <td><?= $option['exp']; ?></td>
                                                        </tr>
                                                <?php endforeach; endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
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
    let symbol = "<?= $_GET['options']; ?>";
    let stock = <?= $user['stock']; ?>;
    let table = new DataTable('#callTable');
    let table1 = new DataTable('#putTable');
    let data = {
        type: "error",
        status: 0,
        message: "fill",
        content: ""
    }

    function template(data) {
        console.log(data)
        
        data.forEach(item => {
            let elem = `<tr>
                <td${item.strike}</td>
                <td>${item.expirationDate}</td>
                <td>${item.lastPrice}</td>
                <td>${item.bid}</td>
                <td>${item.ask}</td>
                <td>${item.volume}</td>
                <td>${item.openInterest}</td>
            </tr>`;
            document.querySelector(".call").insertAdjacentHTML("beforeend", elem)
        })
    }

    const token = "d35dit1r01qhqkb1uurgd35dit1r01qhqkb1uus0"; // <-- replace with your finnhub.io API key
    const url = `https://finnhub.io/api/v1/stock/option-chain?symbol=${symbol}&token=${token}`;

    async function fetchOptions() {
        try {
            const res = await fetch(url);
            if (!res.ok) throw new Error("HTTP " + res.status);
            const data = await res.json();

            // If you want just calls & puts:
            console.log(data.data[0].options)
            if (data && data.data) {
                // return {
                //     "call": data.data[0].options.CALL,
                //     "put": data.data[0].options.PUT
                // }
                document.getElementById("myTable").insertAdjacentHTML("beforeend", template(data.data[0].options.CALL))
            }


        } catch (err) {
            console.error("Error fetching options:", err);
        }
    }

    function contract(self) {
        let parent = document.getElementById("parent")
        if(parent.getAttribute("data-ready") != null) {
            document.getElementById("totalCost").innerText = self.value * (parent.getAttribute("data-price") * 100)
        }else{
            data.type = "warning"
            data.content = "Select a contract first"
            new Func().notice_box(data)
        }
    }

    function order(self, type, strike, price,exp) {
        let parent = document.getElementById("parent")
        let attr = {
            'data-ready': 'true',
            'data-type': type,
            'data-strike': strike,
            'data-price': price,
            'data-exp': exp
        }
        for (const key in attr) {
            if (!Object.hasOwn(attr, key)) continue;
            parent.setAttribute(key, attr[key])
        }

        document.getElementById("strike").innerText = `$${strike}.00`
        document.getElementById("limitP").innerText = `PUT ${price}`
        document.getElementById("contracts").value = price

        document.querySelector(".card-box").scrollIntoView()
    }

    function placeOrder(self) {
        let market = document.getElementById("nav-tab").querySelector(".active").innerText
        let parent = document.getElementById("parent")
        let limitPrice = parent.getAttribute("data-price")
        if(parent.getAttribute("data-ready") != null) {
            let amount = document.getElementById("amount")
            let exp = document.getElementById("exp")
            console.log(amount.value * (limitPrice * 100))
            if(amount.value > 0){
                if(stock >= (amount.value * (limitPrice * 100))) {
                    let payload = {
                        part: "user",
                        action: "options",
                        val: {
                            symbol: symbol,
                            type: parent.getAttribute("data-type"),
                            strike: parent.getAttribute("data-strike"),
                            limitPrice: limitPrice,
                            contracts: amount.value,
                            market: market,
                            exp: parent.getAttribute("data-exp")
                        }
                    }

                    console.log(payload)
                    new Func().request("../request.php", JSON.stringify(payload), 'json')
                    .then(val => {
                        console.log(val)
                        new Func().notice_box(val)
                    })
                }else{
                    amount.focus()
                    data.type = "warning"
                    data.content = "Insufficient balance"
                    new Func().notice_box(data)
                }
            }else{
                amount.focus()
                data.type = "warning"
                data.content = "Please write a valid contract"
                new Func().notice_box(data)
            }
        }else{
            data.type = "warning"
            data.content = "Select a contract first"
            new Func().notice_box(data)
        }
    }
</script>
</html>