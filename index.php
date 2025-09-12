<?php
    require "config/config.php";

    use Router\Router;
    use Config\Database;

    // Calling it this way because it's the whole class being return, instead of having to store the whole class as a variable and using it to access the methods

    // Note in the get/post methods also, the main class is also being returned

    (new Router(new Database))

    // Pages accessible without session
    ->get('/', function() {
        require "src/page/landing.php";
    })

    ->get('/home', function() {
        require "src/page/landing.php";
    })
    
    ->get('/login', function() {
        require "src/page/login.php";
    })

    ->get('/signup', function() {
        require "src/page/register.php";
    })

    ->get('/verify', function() {
        require "src/page/verify.php";
    })

    ->get('/2fa', function() {
        require "src/page/2fa.php";
    })

    ->get('/forgot', function() {
        require "src/page/forgot.php";
    })

    ->get('/password', function() {
        require "src/page/new-password.php";
    })

    ->get('/track', function() {
        require "src/page/track.php";
    })

    // Pages accessible after the landing, this are the content pages

    ->get('/user/home', function() {
        require "src/page/user/home.php";
    })

    ->get('/user/traders', function() {
        require "src/page/user/traders.php";
    })

    ->get('/user/wallets', function() {
        require "src/page/user/wallets.php";
    })

    ->get('/user/assets', function() {
        require "src/page/user/assets.php";
    })

    ->get('/user/convert', function() {
        require "src/page/user/convert.php";
    })

    ->get('/user/history', function() {
        require "src/page/user/history.php";
    })

    ->get('/user/profile', function() {
        require "src/page/user/profile.php";
    })

    ->get('/user/wallet', function() {
        require "src/page/user/wallet.php";
    })

    ->get('/user/market', function() {
        require "src/page/user/market.php";
    })

    ->get('/user/downlines', function() {
        require "src/page/user/downlines.php";
    })

    ->get('/user/investment-statement', function() {
        require "src/page/user/investments.php";
    })

    ->get('/user/deposit-statement', function() {
        require "src/page/user/deposits.php";
    })

    ->get('/user/withdrawal-statement', function() {
        require "src/page/user/withdrawals.php";
    })

    ->get('/user/packages', function() {
        require "src/page/user/packages.php";
    })

    ->get('/user/deposit', function() {
        require "src/page/user/deposit.php";
    })

    ->get('/user/pay', function() {
        require "src/page/user/pay.php";
    })

    ->get('/user/activate', function() {
        require "src/page/user/activate.php";
    })

    ->get('/user/withdraw', function() {
        require "src/page/user/withdraw.php";
    })

    ->get('/admin', function() {
        require "src/admin/admin.php";
    })

    ->get('/admin/home', function() {
        require "src/admin/home.php";
    })
    
    ->get('/admin/create', function() {
        require "src/admin/create.php";
    })

    ->get('/admin/edit', function() {
        require "src/admin/edit.php";
    })

    ->get('/admin/package-edit', function() {
        require "src/admin/package-edit.php";
    })

    ->get('/admin/home', function() {
        require "src/admin/home.php";
    })


    // Post request
    ->post('/admin', function() {
        require "src/admin/admin.php";
    })

    ->post('/admin/create', function() {
        require "src/admin/create.php";
    })

    ->post('/admin/edit', function() {
        require "src/admin/edit.php";
    })

    ->post('/admin/package-edit', function() {
        require "src/admin/package-edit.php";
    })

    ->listen();

    // Routing ends


?>