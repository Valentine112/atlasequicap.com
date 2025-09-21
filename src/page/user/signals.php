<?php require "php/general.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
        use Src\Config\Head;
        use Service\Func;
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
                        <p class="title">Signals</p>
                    </header>
                    
                    <section class="payment-methods card-box col-lg-8 mx-lg-auto mt-4">
                        <div class="row justify-content-between data-body align-items-center">
                            <div class="col-4 assets-info">
                                <h2 class="mb-0">$<?= $user['wallet']; ?>.00</h2>
                                <small>Wallet Balance</small>
                            </div>
                            <div class="col-8 row justify-content-end align-items-center text-end">
                                <div class="col-8">
                                    <button href="" class="btn btn-transparent form-inp" data-bs-toggle="modal" data-bs-target="#exampleModal">My Signals</button>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="signals mt-3">
                        <div class="row justify-content-around align-items-center">
                            <?php foreach ($signals as $signal): ?>
                                <div class="signals-box card-box col-lg-5 my-2 py-5">
                                    <h4><?= $signal['name']; ?></h4>
                                    <hr>
                                    <div class="row justify-content-around align-items-center">
                                        <div class="col-6">
                                            <span>Signal Price</span>
                                        </div>
                                        <div class="col-6 text-end">
                                            <h5>$<?= $signal['price']; ?>.00</h5>
                                        </div>
                                    </div>
                                    <div class="row justify-content-around align-items-center">
                                        <div class="col-6">
                                            <span>Signal Strength</span>
                                        </div>
                                        <div class="col-6 text-end">
                                            <h5 style="color: lightgreen;"><?= $signal['strength']; ?>%</span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-12">
                                        <div class="input-group mb-1">
                                            <input type="text" class="form-control form-inp" placeholder="Amount" aria-label="Amount" aria-describedby="basic-addon2" id="amount">
                                            <span class="input-group-text" style="background: #121921; border: none; color: var(--white);">USD</span>
                                        </div>
                                        <div class="col-12 row justify-content-between">
                                            <div class="col-6">
                                                <small>Current Balance</small>
                                            </div>
                                            <div class="col-6 text-end">
                                                <span>$<?= $user['wallet']; ?>.00</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <button style="background-color: var(--main-color);" class="btn w-100 mt-4 py-2" onclick="purchase(this, '<?= $signal['id']; ?>', <?= $signal['price']; ?>)">Purchase Signal</button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                </main>
            </section>

            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel" class="payment-head sub-head">My Signals</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="modal-details">
                                <div class="table-responsive">
                                    <table class="table table-box border-0 bg-transparent">
                                        <thead>
                                            <tr>
                                                <th scope="col">TRANSACTION ID</th>
                                                <th scope="col">SIGNAL</th>
                                                <th scope="col">AMOUNT</th>
                                                <th scope="col">PROFIT</th>
                                                <th scope="col">STATUS</th>
                                                <th scope="col">DATE</th>
                                                <th scope="col">ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(count($signals) < 1): ?>
                                                <tr>
                                                    <td colspan="6">
                                                        <div class="col-12 text-center py-5">
                                                            You have no active signals. Purchase a signal to view it here.
                                                        </div>
                                                    </td>
                                                    
                                                </tr>
                                            <?php else: ?>
                                                <?php 
                                                    foreach($usersignals as $signal): 
                                                        if($signal['status'] == 0):
                                                            $stats = "<span class='text-warning'>Running</span>";
                                                        elseif ($signal['status'] == 1):
                                                            $stats = "<span class='text-danger'>Cancelled</span>";
                                                        else:
                                                            $stats = "<span class=text-success'>Ended</span>";
                                                        endif;

                                                        // Fetch signal name
                                                        $data = [
                                                            "id" => $signal['signalId'],
                                                            "1" => "1",
                                                            "needle" => "*",
                                                            "table" => "signals"
                                                        ];
                                                        $signalName = Func::searchDb($db, $data, "AND")['name'];
                                                ?>
                                                    <tr>
                                                        <td>AE-<?= $signal['tranx']; ?></td>
                                                        <td><?= strtoupper($signalName); ?></td>
                                                        <td>$<?= $signal['amount']; ?></td>
                                                        <td>$<?= $signal['profit']; ?>.00</td>
                                                        <td><?= $stats; ?></td>
                                                        <td><?= substr($signal['date'], 0, 10); ?></td>
                                                        <td>
                                                            <?php if($signal['status'] == 0): ?>
                                                                <button class="btn btn-danger" onclick="cancel(this, <?= $signal['signalId']; ?>)">Cancel</button>
                                                            <?php elseif($signal['status'] == 1): ?>
                                                                <span class="text-white">Cancelled</span>
                                                            <?php else: ?>
                                                                <span class="text-success">Ended</span>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                            <?php endforeach; endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
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
    let wallet = <?= $user['wallet']; ?>;

    function purchase(self, id, price) {
        let amount = self.closest('.signals-box').querySelector("#amount")
        //Check if amount is up to signal price
        if(amount.value < price) {
            data.type = "warning"
            data.content = "Amount not up to signal price"
            amount.focus()
            new Func().notice_box(data)
        }else{
            // Check if wallet is up to signal price
            if(wallet < price) {
                data.type = "error"
                data.content = "Your wallet balance is not sufficient enough for this transaction"
                amount.focus()
                new Func().notice_box(data)
            }else{
                let payload = {
                    part: "user",
                    action: "signal",
                    val: {
                        signalId: id,
                        amount: amount.value,
                        price: price
                    }
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
    }

    function cancel(self, id) {
        let payload = {
            part: "user",
            action: "signalCancel",
            val: {
                signalId: id
            }
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
</script>
</html>