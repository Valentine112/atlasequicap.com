<?php
    use Config\Database;
    use Query\{
        Select,
        Update,
        Insert
    };
    use Service\Func;

    $db = new Database;
    $selecting = new Select($db);
    // Check if user is logged in
    if(empty($_COOKIE['auth_token'])) header("location: ../login");
    // Check if session is already created
    $user = null;
    if(!empty($_COOKIE['auth_token'])):
        (int) $zero = 0;
        // ------- FETCH USER INFO --------- //
        $data = [
            "token" => $_COOKIE['auth_token'],
            "1" => "1",
            "needle" => "*",
            "table" => "users"
        ];
        $user = Func::searchDb($db, $data, "AND");
        if(empty($user)) header("location: ../login?action=logout");
        $userId = $user['id'];
        $cTrader = $user['ctrader'];
        $cProfit = $user['cprofit'];
        $cDate = $user['cdate'];
        $cAmount = $user['camount'];
        $cCount = $user['ccount'];
        $cData = [];
        $refcode = $user['refcode'];

        if($cTrader > 0):
            $data = [
                'id' => $userId,
                '1' => '1',
                'needle' => '*',
                'table' => 'traders'
            ];
            $cData = Func::searchDb($db, $data, "AND");
        endif;

        // Fetch admin details
        $data = [
            "2" => "2",
            "1" => "1",
            "needle" => "*",
            "table" => "admin"
        ];
        $admin = Func::searchDb($db, $data, "AND");
        [$btc, $eth, $xrp, $tether] = [$admin['btc'], $admin['eth'], $admin['xrp'], $admin['tether']];

        $selecting = new Select($db);

        // Fetch user stocks
        $selecting->more_details("WHERE user = ?# $userId");
        $action = $selecting->action("*", "stocks");
        if($action != null) return $action;
        $stocks = $selecting->pull()[0];
        $selecting->reset();

        // Fetch user crypto
        $selecting->more_details("WHERE user = ?# $userId");
        $action = $selecting->action("*", "crypto");
        if($action != null) return $action;
        $crypto = $selecting->pull()[0];
        $selecting->reset();

        // Fetch user deposit transactions, LIMIT 10
        $selecting->more_details("WHERE user = ? LIMIT 10# $userId");
        $action = $selecting->action("*", "deposit");
        if($action != null) return $action;
        $deposits = $selecting->pull()[0];
        $selecting->reset();

        // Fetch user withdrawal transactions, LIMIT 10
        $selecting->more_details("WHERE user = ? LIMIT 10# $userId");
        $action = $selecting->action("*", "withdrawals");
        if($action != null) return $action;
        $withdrawals = $selecting->pull()[0];
        $selecting->reset();

        // Fetch all signals
        $selecting->more_details("");
        $action = $selecting->action("*", "signals");
        if($action != null) return $action;
        $signals = $selecting->pull()[0];
        $selecting->reset();

        // Fetch user signals
        $selecting->more_details("WHERE user = ? ORDER BY id DESC# $userId");
        $action = $selecting->action("*", "usersignals");
        if($action != null) return $action;
        $usersignals = $selecting->pull()[0];
        $selecting->reset();

        // Fetch user crypto
        $selecting->more_details("WHERE user = ? ORDER BY id DESC# $userId");
        $action = $selecting->action("*", "crypto");
        if($action != null) return $action;
        $userCrypto = $selecting->pull()[0];
        $selecting->reset();

        // Fetch traders
        $selecting->more_details("ORDER BY pnl DESC");
        $action = $selecting->action("*", "traders");
        if($action != null) return $action;
        $traders = $selecting->pull();
        $selecting->reset();

        // Fetch referred
        $selecting->more_details("WHERE referred = ?# $refcode");
        $action = $selecting->action("id, fullname, date, status", "users");
        if($action != null) return $action;
        $referrals = $selecting->pull();
        $selecting->reset();

        // Code to insert demo signals, at least 10
        /*$subject = ['name', 'price', 'strength', 'date'];
        $items = [
            ['WOLFX', '50000', '70', Func::dateFormat()],
            ['ZULU', '70000', '80', Func::dateFormat()],
            ['COIN', '100000', '88', Func::dateFormat()],
            ['SSIGNAL', '80000', '85', Func::dateFormat()],
            ['MOON', '115000', '90', Func::dateFormat()],
            ['NVDSIGNALS', '121000', '92', Func::dateFormat()],
            ['PHANTOM', '75000', '79', Func::dateFormat()],
            ['FXTRADER', '90000', '86', Func::dateFormat()],
            ['ALGOTRADER', '65000', '91', Func::dateFormat()],
            ['XPN', '75000', '91', Func::dateFormat()]
        ];
        foreach ($items as $item) {
            $inserting = new Insert($db, "signals", $subject, "");
            $action = $inserting->action($item, "siis");
            if(!$action) return $action;
            $inserting->reset();
        }*/
        if(isset($_GET['symbol'])):
            $symbolInfo = "";
            if($_GET['type'] == "stock"):
                $symbolInfo = Func::fetchStock($_GET['symbol']);
                // fetch user stocks
                $symbol = strtoupper($_GET['symbol']);
                $selecting->more_details("WHERE user = ? AND symbol = ? AND triggered < 2 ORDER BY id DESC# $userId# $symbol");
                $action = $selecting->action("*", "stocks");
                if($action != null) return $action;
                $userStocks = $selecting->pull()[0];
                $selecting->reset();

                // Fetch total history on the stock
                $symbol = strtoupper($_GET['symbol']);
                $selecting->more_details("WHERE user = ? AND symbol = ? AND triggered > 0 ORDER BY id DESC# $userId# $symbol");
                $action = $selecting->action("SUM(shares), SUM(profit), SUM(amount)", "stocks");
                if($action != null) return $action;
                $totalStocks = $selecting->pull()[0];
                $totalShares = 0;
                $totalProfit = 0.00;
                $totalAmount = 0.00;
                if(count($totalStocks) > 0):
                    $totalShares = round($totalStocks[0]['SUM(shares)'], 2);
                    $totalProfit = $totalStocks[0]['SUM(profit)'];
                    $totalAmount = $totalStocks[0]['SUM(amount)'];
                endif;
                $selecting->reset();
            endif;
        endif;

        if(isset($_GET['options'])):
            $optionsInfo = "";
            if($_GET['type'] == "stock"):
                $optionsInfo = Func::getStockOptions($_GET['options']);
            endif;
        endif;

        if(isset($_GET['coin'])):
            $coins = Func::fetchCoin($_GET['coin']);
        endif;
    endif;
?>