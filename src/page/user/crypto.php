<?php //require "php/general.php"; ?>
<?php
    /*$symbol = "";
    if(isset($_GET['symbol'])):
        $symbol = Func::cleanData($_GET['symbol'], 'string');
    endif;*/

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
                        <p class="title">Crypto Trading</p>
                    </header>

                    <section>
                        <h3 class="sub-head">BTC</h3>
                    </section>
                </main>
            </section>
        </div>
    </div>
</body>
</html>