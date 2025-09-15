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
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel" class="payment-head sub-head">My Signals</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="modal-details">
                                <p class="sub-sect py-5">You have no active signals. Purchase a signal to view it here.</p>

                                <div class="row col-12 justify-content-around mx-auto py-1">
                                    <div class="col-4">
                                        <h5 class="sub-sub-head mb-0">$0.00</h5>
                                        <small style="color: grey;">Amount</small>
                                    </div>
                                    <div class="col-4">
                                        <h5 class="sub-sub-head mb-0">OVLLTR</h5>
                                        <small style="color: grey;">Signal</small>
                                    </div>
                                    <div class="col-4">
                                        <h5 class="sub-sub-head mb-0">12/0/3</h5>
                                        <small style="color: grey;">Date</small>
                                    </div>

                                    <hr>
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
            new Func().notice_box(data)
        }else{
            // Check if wallet is up to signal price
            if(wallet < price) {
                data.type = "error"
                data.content = "Your wallet balance is not sufficient enough for this transaction"
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
                    console.log(val)
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
</script>
</html>