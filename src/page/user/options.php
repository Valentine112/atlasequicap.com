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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../src/assets/css/general.css">
    <link rel="stylesheet" href="../assets/css/line-awesome.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
    <script src="//cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
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
                        <p class="title">Options</p>
                        <?php //print_r($optionsInfo); ?>
                    </header>

                    <section class="options">
                        <div>
                            <div>
                                <h3 class="sub-head"><?= strtoupper($symbol); ?> | <small style="color: var(--main-color);">$395</small></h3>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6 col-lg-2">
                                    <div id="nav-tab" role="tablist">
                                        <div class="wallet-sec" id="buy-tab" data-bs-toggle="tab" data-bs-target="#buy" type="button" role="tab" aria-controls="buy" aria-selected="true">BUY</div>
                                        &emsp;
                                        <div class="wallet-sec" id="sell-tab" data-bs-toggle="tab" data-bs-target="#sell" type="button" role="tab" aria-controls="sell" aria-selected="true">SELL</div>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-2">
                                    <div id="nav-tab" role="tablist">
                                        <div class="wallet-sec" id="buy-tab" data-bs-toggle="tab" data-bs-target="#buy" type="button" role="tab" aria-controls="buy" aria-selected="true">BUY</div>
                                        &emsp;
                                        <div class="wallet-sec" id="sell-tab" data-bs-toggle="tab" data-bs-target="#sell" type="button" role="tab" aria-controls="sell" aria-selected="true">SELL</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <hr>
                    <div class="data-body">
                        <div class="table-responsive">
                            <table class="table table-borderless table-box border-0 bg-transparent" id="myTable">
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
                                <tbody class="call">
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
                                            <td><button class="btn btn-link"><?= $option['type']; ?> <?= $option['lastPrice']; ?></button></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <div id="pagination-container"></div>
                        </div>
                    </div>

                    <div class="card-box">
                        <h2 class="sub-sub-head">TSLA <span id="putPrice" style="color: var(--main-color);">$100</span> PUT 100</h2>
                        <hr>

                        <div class="row justify-items-between align-items-center mt-2">
                            <div class="col-4">
                                <div class="mb-0">Contracts</div>
                                <small>100 Shares Each</small>
                            </div>
                            <div class="col-8">
                                <input type="number" name="contracts" id="contracts" class="form-control form-inp" placeholder="0">
                            </div>
                        </div>

                        <div class="row justify-items-between align-items-center mt-2">
                            <div class="col-4">
                                <div class="mb-0">Limit Price</div>
                            </div>
                            <div class="col-8">
                                <input type="number" name="contracts" id="contracts" class="form-control form-inp" placeholder="0.02">
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
                                $ <span>4</span>.00
                            </div>
                            </div>
                        <hr>
                        <!-- <div>
                            <a href="deposit" class="w-100 btn btn-info" style="padding: 5px 10px!important;">Deposit to Trade</a>
                        </div> -->
                        <div>
                            <button class="form-control btn btn-link">Put</button>
                        </div>
                    </div>
                </main>
            </section>
        </div>
    </div>
</body>
<script>
    let table = new DataTable('#myTable');

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

    const symbol = "AAPL";
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

    //console.log(fetchOptions())
</script>
</html>