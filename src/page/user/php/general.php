<?php
    use Config\Database;
    use Query\Select;
    use Query\Update;
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
    endif;
?>