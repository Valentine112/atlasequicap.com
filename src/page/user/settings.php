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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <title>QFSLedgerConnect - Storage for your wallets</title>
    <style>
        .table td {
            padding: 1rem!important;
        }
    </style>
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
                        <p class="title">Settings</p>
                    </header>
                    <section class="data-section">
                        <div class="data-header">
                            <div id="nav-tab" role="tablist">
                                <div class="wallet-sec active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true">Profile</div>
                                &emsp;
                                <div class="wallet-sec" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab" aria-controls="security" aria-selected="true">Security</div>
                                &emsp;
                                <div class="wallet-sec" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button" role="tab" aria-controls="payment" aria-selected="true">Payment</div>
                            </div>
                        </div>
                        <div class="data-body tab-content" id="nav-tabContent">
                            <div data-type="profile" class="profile-info table-responsive tab-pane show fade profile active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <table class="table table-borderless table-box border-0 bg-transparent">
                                    <thead>
                                        <tr>
                                            <th>Account Information<br>
                                                <small>Always keep your profile settings up to date</small>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Fullname</td>
                                            <td class="value" data-type="fullname"><?= $user['fullname']; ?></td>
                                            <td><button class="btn btn-info" onclick="edit(this)">Edit</button></td>
                                        </tr>
                                        <tr>
                                            <td>Email</td>
                                            <td><?= $user['email']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Phone</td>
                                            <td class="value" data-type="phone"><?= $user['phone']; ?></td>
                                            <td><button class="btn btn-info" onclick="edit(this)">Edit</button></td>
                                        </tr>
                                        <tr>
                                            <td>Country</td>
                                            <td class="value" data-type="country"><?= $user['country']; ?></td>
                                            <td><button class="btn btn-info" onclick="edit(this)">Edit</button></td>
                                        </tr>
                                        <tr>
                                            <td>Account Status</td>
                                            <td>
                                                <?php
                                                    if($user['status'] == 1):
                                                        echo "<span class=main-color>Verified</span>";
                                                    else:
                                                        echo "<span>Unverified</span>";
                                                    endif;
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Trading ID</td>
                                            <td>
                                                <span class=main-color>AE-<?= $user['tradeId']; ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Deposit Limit</td>
                                            <td>
                                                <span class=main-color>Unlimited</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><button class="btn btn-link" onclick="update(this)">Update Information</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <divv data-type="security" class="profile-info table-responsive tab-pane fade security" id="security" role="tabpanel" aria-labelledby="security-tab">
                                <table class="table table-borderless table-box border-0 bg-transparent">
                                    <thead>
                                        <tr>
                                            <th>Account Security<br>
                                                <small>Update your password and never lose access to your account</small>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Email Address</td>
                                            <td><?= $user['email']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Password</td>
                                            <td class="value" data-type="password">********</td>
                                            <td><button class="btn btn-info" onclick="edit(this)">Change</button></td>
                                        </tr>
                                        <tr>
                                            <td><button class="btn btn-link" onclick="update(this)">Update Information</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <divv data-type="payment" class="profile-info table-responsive tab-pane fade payment" id="payment" role="tabpanel" aria-labelledby="payment-tab">
                                <table class="table table-borderless table-box border-0 bg-transparent">
                                    <thead>
                                        <tr>
                                            <th>Withdrawal Addresses<br>
                                                <small>Update your addresses to be able to withdraw</small>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Bitcoin Address (BTC)</td>
                                            <td data-type="btc" class="value"><?= !empty($user['btc']) ? $user['btc'] : "Not Added"; ?></td>
                                            <td><button class="btn btn-info" onclick="edit(this)">Set</button></td>
                                        </tr>
                                        <tr>
                                            <td>Ethereum Address (ERC-20)</td>
                                            <td class="value" data-type="eth"><?= !empty($user['eth']) ? $user['eth'] : "Not Added"; ?></td>
                                            <td><button class="btn btn-info" onclick="edit(this)">Set</button></td>
                                        </tr>
                                        <tr>
                                            <td>USDT Address (TRC-20)</td>
                                            <td class="value" data-type="usdt"><?= !empty($user['usdt']) ? $user['usdt'] : "Not Added"; ?></td>
                                            <td><button class="btn btn-info" onclick="edit(this)">Set</button></td>
                                        </tr>
                                        <tr>
                                            <td><button class="btn btn-link" onclick="update(this)">Update Information</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </main>
            </section>
        </div>
    </div>
</body>
<script src="../src/assets/js/main.js"></script>
<script src="../src/assets/js/general.js"></script>
<script src="../src/assets/js/chart.js"></script>
<script>
    let data = {
        type: "error",
        status: 0,
        message: "fill",
        content: ""
    }
    function edit(self) {
        let parent = self.closest("tr")
        let elem = parent.querySelector(".value")
        elem.setAttribute("contenteditable", true)
        elem.focus()
    }

    function update(self) {
        let error = false
        let parent = self.closest(".profile-info")
        let parentType = parent.getAttribute("data-type")
        let dataObj = {}
        // Fetch all the values
        parent.querySelectorAll(".value").forEach(elem => {
            // Get the data-type
            let type = elem.getAttribute("data-type")
            if(parentType == "profile") {
                // Make sure the values are not empty
                // They all have to be filled
                if(new Func().stripSpace(elem.innerText).length < 1) {
                    data.type = "warning"
                    data.content = "You cannot leave this forms blank"
                    new Func().notice_box(data)

                    error = true
                }
            }
            
            if(parentType == "security") {
                if(elem.innerText.length <= 7 || elem.innerText == "********") {
                    data.type = "warning"
                    data.content = "Password should be greater than 7 characters"
                    new Func().notice_box(data)

                    error = true
                }
            }

            if(parentType == "payment") {
                if(elem.innerText == "Not Added") elem.innerText = ""
            }
            dataObj[type] = elem.innerText
            dataObj.type = parentType
        })
        console.log(dataObj)
        if(!error) {
            // Send to server
            let payload = {
                part: "user",
                action: "profile",
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