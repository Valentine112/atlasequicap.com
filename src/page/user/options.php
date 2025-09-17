<?php require "php/general.php"; ?>
<?php
    use Service\Func;
    $symbol = "";
    if(isset($_GET['symbol'])):
        $symbol = Func::cleanData($_GET['symbol'], 'string');
    endif;

    function getStockOptions($symbol) {
        // Yahoo Finance API endpoint
        $url = "https://query2.finance.yahoo.com/v7/finance/options/" . urlencode($symbol);

        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo "cURL Error: " . curl_error($ch);
            return null;
        }
        curl_close($ch);

        $data = json_decode($response, true);

        if (isset($data["optionChain"]["result"][0]["options"][0])) {
            return $data["optionChain"]["result"][0]["options"][0];
        }

        return null;
    }
    print_r(getStockOptions("AAPL"));
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
                        <p class="title">Options</p>
                        <?php print_r($optionsInfo); ?>
                    </header>

                    <section>
                        <h3 class="sub-head">TSLA | <small style="color: var(--main-color);">$395</small></h3>
                    </section>
                    <hr>
                    <div class="data-body">
                        <div class="table-responsive">
                            <table class="table table-borderless table-box border-0 bg-transparent">
                                <thead>
                                    <tr>
                                        <th col="">Type</th>
                                        <th col="">Strike</th>
                                        <th col="">Expiration</th>
                                        <th col="">Last Price</th>
                                        <th col="">Bid</th>
                                        <th col="">Ask</th>
                                        <th col="">Volume</th>
                                        <th col="">Open Interest</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    foreach ($optionsInfo['data'] as $contract):
                                        $contractSymbol = $contract['contractSymbol'];
                                        $type = (str_contains($contractSymbol, "C")) ? "CALL" : "PUT";
                                        $strike = $contract['strike'];
                                        $expiry = $contract['expirationDate'];
                                        $last = $contract['lastPrice'] ?? "-";
                                        $bid = $contract['bid'] ?? "-";
                                        $ask = $contract['ask'] ?? "-";
                                        $volume = $contract['volume'] ?? "-";
                                        $oi = $contract['openInterest'] ?? "-";
                                ?>
                                    <tr>
                                        <td><?= $type; ?></td>
                                        <td><?= $strike; ?></td>
                                        <td><?= $expiry; ?></td>
                                        <td><?= $last; ?></td>
                                        <td><?= $bid; ?></td>
                                        <td><?= $ask; ?></td>
                                        <td><?= $volume; ?></td>
                                        <td><?= $oi; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
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
                        <div>
                            <a href="deposit" class="w-100 btn btn-info" style="padding: 5px 10px!important;">Deposit to Trade</a>
                        </div>
                        <div>
                            <button class="form-control btn btn-link">Put</button>
                        </div>
                    </div>
                </main>
            </section>
        </div>
    </div>
</body>
</html>