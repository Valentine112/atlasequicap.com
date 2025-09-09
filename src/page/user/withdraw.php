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
    <link rel="stylesheet" href="../src/assets/css/landing.css">
    <link rel="stylesheet" href="../src/assets/css/general.css">
    <title>QFSLedgerConnect - Storage for your wallets</title>
</head>
<body class="data-light-body">
    <main class="profile">
        <?php include "src/template/head.php"; ?>

        <section class="mt-3 content profile container withdraw" data-bs-theme="dark">
            <div>
                <header class="text-lg-center">
                    <h5>Withdraw</h5>
                    <p>Request for a withdraw and get approved in no time</p>
                </header>
                <div class="row justify-content-around align-items-center forms">

                    <div onsubmit="event.preventDefault(); event.stopImmediatePropagation();" data-parent="body" class="data-light">
                        <div class="row justify-content-around col-12">
                            <div class="col-md-6 mb-3 mt-3">
                                <div class="form-group">
                                    <label>Send or Withdraw money to an address</label>
                                    <input name="usd" type="number" class="form-inp" id="amount" placeholder="Amount (Integers only)" value="">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3 mt-3">
                                <div class="form-group">
                                    <label>Preferred withdrawal mode</label>
                                    <select name="mode" class="form-inp" id="wallet" onchange="selectAddress(this)">

                                        <option value="">select an account type</option>

                                        <option value="BITCOIN" data-value="">Bitcoin</option>

                                        <option value="ETHEREUM" data-value="">Ethereum</option>

                                        <option value="BNB" data-value="">BNB</option>

                                        <option value="USDT" data-value="">USDT</option>

                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-around col-12">
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label>Account details</label>
                                    <input type="text" name="wallet" class="form-inp"
                                    id="address" placeholder="Enter correct details"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6 mt-4">
                                <div class="form-group">
                                    <label>Enter your password to confirm your withdrawal</label>
                                    <input name="password" type="password" class="form-inp" id="password" placeholder="Enter password">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row col-md-6 mx-auto">
                            <button type="submit" name="submit" class="form-btn mb-3" onclick="submit(this, 'withdraw')">Withdraw funds</button>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!-- Include the notice box here -->
    <?php include "src/template/quick-notice.php"; ?>
</body>
<script src="../src/assets/js/main.js"></script>
<script src="../src/assets/js/general.js"></script>
<script>
    function selectAddress(self) {
        var val = self.options[self.selectedIndex].getAttribute("data-value")
        document.getElementById("address").value = val
    }
</script>
</html>