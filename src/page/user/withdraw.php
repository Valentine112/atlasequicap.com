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
                        <p class="title">Withdrawal</p>
                    </header>

                    <section class="withdraw card-box mt-4">
                        <div class="sub-sub-head">
                            Select a method of withdrawal
                        </div>
                        <hr>
                        <div>
                            <label for="method">Choose withdrawal address</label>
                            <select class="form-select form-inp" id="inputGroupSelect01" onchange="withdrawalMode(this)">
                                <option value="Btc" data-addr="<?= $user['btc']; ?>">Bitcoin</option>
                                <option value="Eth" data-addr="<?= $user['eth']; ?>">Ethereum</option>
                                <option value="Bnb" data-addr="<?= $user['bnb']; ?>">BNB</option>
                                <option value="Usdt" data-addr="<?= $user['usdt']; ?>">USDT</option>
                                <option value="Xrp" data-addr="<?= $user['xrp']; ?>">XRP</option>
                            </select>
                        </div>
                        <div class="mt-3">
                            <label for="amount">Amount (USD)</label>
                            <input type="text" class="form-control form-inp" id="amount" placeholder="Amount in USD">
                        </div>

                        <div class="row justify-content-end mt-4 text-end">
                            <div class="col-8 col-lg-6 col-md-3">
                                <button class="btn btn-primary w-60" onclick="withdraw(this)" id="withdrawBtn" data-addr="<?= $user['btc']; ?>" data-mode="Btc" data-ready=1>Confirm Withdrawal</button>
                            </div>
                        </div>
                    </section>

                    <div class="mt-4">
                        <header>
                            <p class="title">Latest Withdrawals</p>
                        </header>
                        <section class="deposit-history">
                            <div class="table-responsive">
                                <table class="table table-hover bg-dark table-box border-0 bg-transparent">
                                    <thead>
                                        <tr>
                                            <th scope="col">TRANSACTION ID #</th>
                                            <th scope="col">MODE</th>
                                            <th scope="col">AMOUNT</th>
                                            <th scope="col">STATUS</th>
                                            <th scope="col">DATE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(count($deposits) < 1): ?>
                                            <tr>
                                                <td colspan="6">
                                                    <div class="col-12 text-center py-5">
                                                        No transaction yet...
                                                    </div>
                                                </td>
                                                
                                            </tr>
                                        <?php else: ?>
                                            <?php 
                                                foreach($withdrawals as $withdrawal): 
                                                    if($withdrawal['status'] == 0):
                                                        $stats = "<span class='text-warning'>Pending</span>";
                                                    elseif ($withdrawal['status'] == 1):
                                                        $stats = "<span class='text-danger'>Cancelled</span>";
                                                    else:
                                                        $stats = "<span class=text-success'>Success</span>";
                                                    endif;
                                            ?>
                                                <tr>
                                                    <td>AE-<?= $withdrawal['tranx']; ?></td>
                                                    <td><?= strtoupper($withdrawal['mode']); ?></td>
                                                    <td><?= $withdrawal['amount']; ?></td>
                                                    <td><?= $stats; ?></td>
                                                    <td><?= substr($withdrawal['date'], 0, 10); ?></td>
                                                </tr>
                                        <?php endforeach; endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </section>
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
    function withdrawalMode(self) {
        data.type = "error"
        data.content = "Please go to your settings page and add a valid address for this mode"
        let optionInd = self.selectedIndex
        let option = self.options[optionInd]
        // Check if there is an address for the value
        let addr = option.getAttribute("data-addr")
        let withdrawBtn = document.getElementById("withdrawBtn")
        if(addr.length < 1) {
            withdrawBtn.setAttribute("data-ready", 0)
            new Func().notice_box(data)
        }else{
            withdrawBtn.setAttribute("data-addr", addr)
            withdrawBtn.setAttribute("data-mode", self.value)
            withdrawBtn.setAttribute("data-ready", 1)
        }
    }

    function withdraw(self){
        data.type = "error"
        data.content = "Please go to your settings page and add a valid address for this mode"
        let amount = document.getElementById("amount")
        if(self.getAttribute("data-ready") == 0) {
            new Func().notice_box(data)
        }else{
            // Check if amount is valid
            if(amount.value.length < 1) {
                data.type = "warning"
                data.content = "Please add a valid amount"
                new Func().notice_box(data)
                amount.focus()
            }else{
                // Withdraw ready
                let payload = {
                    part: "user",
                    action: "withdraw",
                    val: {
                        amount: amount.value,
                        mode: self.getAttribute("data-mode"),
                        address: self.getAttribute("data-addr")
                    }
                }

                new Func().request("../request.php", JSON.stringify(payload), 'json')
                .then(val => {
                    console.log(val)
                    new Func().notice_box(val)
                })
            }
        }
    }
</script>
</head>