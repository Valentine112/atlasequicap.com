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

        <section class="mt-5 content" data-bs-theme="dark" data-parent="body">
            <h3>Edit Profile</h3>
            <p>Edit and update your profile to the latest changes</p>
            <div>
                Toggle Darkmode <input type="checkbox" name="" id="colorMode" onclick="toggleMode(this)">
            </div>
            <div class="row form-body justify-content-around align-items-center mt-3 profile-form data-light">
                <div class="col-11 col-md-6">
                    <label for="fullname">Fullname</label>
                    <input type="text" placeholder="Fullname" class="form-inp" id="fullname" value="<?= $user['fname'].' '.$user['lname']; ?>">
                </div>

                <div class="col-11 col-md-6">
                    <label for="number">Phone number</label>
                    <input type="text" placeholder="Phone number" class="form-inp" id="number" value="<?= $user['phone']; ?>">
                </div>

                <div class="col-11 col-md-6">
                    <label for="number">Country</label>
                    <input type="text" placeholder="Country" class="form-inp" id="country" value="<?= $user['country']; ?>">
                </div>

                <div class="col-11 col-md-6">
                    <label for="password">Date Joined</label>
                    <input type="text" placeholder="Date joined" class="form-inp" id="date" value="<?= $user['date']; ?>" disabled>
                </div>

                <div class="col-11 col-md-6">
                    <label for="password">Confirm password</label>
                    <input type="text" placeholder="Confirm password" class="form-inp" id="password">
                </div>
            </div>
            <br>
            <div class="col-11 col-md-6 mx-auto">
                <button class="form-control form-btn" onclick="submit(this, 'profile')">Submit</button>
            </div>
        </section>

        <!-- Include the notice box here -->
        <?php include "src/template/quick-notice.php"; ?>

    </main>
</body>
<script src="../src/assets/js/main.js"></script>
<script src="../src/assets/js/general.js"></script>
</html>