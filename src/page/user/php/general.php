<?php
    use Config\Database;
    use Query\Select;
    use Query\Update;
    use Service\Func;

    $db = new Database;
    $selecting = new Select($db);
    // Check if user is logged in
    if(empty($_COOKIE['auth_token'])) header("location: ../login");

    $admin = "admin@qfsledgerconnect.org";
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
        $userId = $user['id'];
        $email = $user['email'];

        //if($result): header("location: login?action=logout");

        $wallet_balance = $user['walletbalance'] ?? 0;
        $duration = $user['duration'] ?? 0;
        

        $withdrawn = 0;
        $wstatus = 'completed';
        $data = [
            "email" => $email,
            "status" => 'completed',
            'needle' => 'SUM(moni)',
            'table' => 'wbtc'
        ];
        $withdrawn = Func::searchDb($db, $data, "AND");

        /*if($active == 1) {
            $percentage = ($increase/100) * $usd;

            $ptime = strtotime($pdate);
            $ctime = strtotime(date('Y-m-d H:i:s'));

            $daysCount = (60 * 60) * 24;

            $daysCount = (($ctime - $ptime)/$daysCount);

            $daysCount = floor($daysCount);

            $zero = 0;
            $empty = "";

            // First check if there is a running package
            if($active === 1):

                // Check if the due date has been reached
                if($daysCount >= $duration):
                    $totalProfit = 0;
                    if($pkgName == "MINING"):
                        // MINING PACKAGE
                        $maxDays = floor($duration / 3);
                        $totalProfit = $maxDays * $percentage;

                    else:
                        // REGULAR PACKAGES
                        $totalProfit = $percentage * $duration;

                    endif;
                    // Reset
                    // Due date has been reached
                    // Reset everything regarding the package

                    $updating = new Update($db, "SET activate = ?, walletbalance = walletbalance + ?, profit = ?, increase = ?, pname = ?, pdate = ?, counting = ?, duration = ?, froms = ? WHERE email = ?#, $zero# $totalProfit# $zero# $zero# $empty# $empty# $zero# $zero# $zero# $email");
                    $updating->mutate('iiiissiiis', 'user');

                else:
                    if($pkgName == "MINING"):
                        // MINING PACKAGE

                        // Check if a 3 day interval has been reached
                        $remainder = $daysCount % 3;
                        $interval = ($daysCount - $remainder) / 3;

                        $totalProfit = $percentage * $interval;

                    else:
                        // REGULAR PACKAGES
                        $totalProfit = $percentage * $daysCount;

                    endif;

                    // Due date hasn't been reached yet
                    // Still update the profit

                    $updating = new Update($db, "SET profit = ? WHERE email = ?# $totalProfit# $email");
                    $updating->mutate('is', 'user');

                endif;
            endif;
        }else{
            $daily = "";
            $percentage ="0";
        }

        // Calculating trade
        $profit = Func::calcTrade($userId, $db, "general");*/

        // Fetching transaction activities
        $arr = [
            'type' => 'topup',
            'filled' => 'empty'
        ];

        $arr1 = [
            'type' => 'withdraw',
            'filled' => 'empty'
        ];

        $withdraw = [];
        $pay = [];

        $selecting->more_details("WHERE email = ?# $email");
        $action = $selecting->action("status, date", "btc");
        if($action != null) return $action;
        $selecting->reset();

        $values = $selecting->pull();
        if($values[1] > 0){
            $data = $values[0];
            foreach($data as $info) {
                $arr['status'] = $info['status'];
                $arr['date'] = $info['date'];
                $arr['time'] = strtotime($info['date']);
                $arr['filled'] = "filled";

                array_push($pay, $arr);
            }
        }
        
        $selecting = new Select($db);
        $selecting->more_details("WHERE email = ?# $email");
        $action = $selecting->action("moni, status, date", "wbtc");
        $selecting->reset();
        $values = $selecting->pull();
        if($values[1] > 0){
            $data = $values[0];
            foreach($data as $info) {
                $arr1['amount'] = $info['moni'];
                $arr1['status'] = $info['status'];
                $arr1['date'] = $info['date'];
                $arr1['time'] = strtotime($info['date']);
                $arr1['filled'] = "filled";

                array_push($withdraw, $arr1);
            }
        }

        $activities = array_merge($pay, $withdraw);

        usort($activities, function($a, $b) {
            $t1 = $a['time'];
            $t2 = $b['time'];
            return $t2 - $t1;
        });

        $activities = array_splice($activities, 0, 10);

        $selecting = new Select($db);
        $selecting->more_details("WHERE id = ? LIMIT 1# $userId");
        $action = $selecting->action("duration, refcode, walletbalance, initial_balance", "users");
        if($action != null) return $action;
        $value = $selecting->pull();
        $selecting->reset();
        if($value[1] > 0) {
            $row = $value[0][0];

            $duration = $row['duration'];
            $refcode = $row['refcode'];
            $wallet_balance = $row['walletbalance'];
            $initial_balance = $row['initial_balance'];
    
            if($wallet_balance == null) $wallet_balance = 0;
            if($duration == null) $duration = 0;
        
        }

        
        $selecting->more_details("WHERE referred = ?# $refcode");
        $action = $selecting->action("username, amount, date", "referred");
        if($action != null) return $action;
        $referral = $selecting->pull();
        $selecting->reset();
        
        $selecting->more_details("WHERE referred = ? AND verify = ?# $refcode# $zero");
        $action = $selecting->action("username, email, verify", "users");
        if($action != null) return $action;
        $referralBonus = $selecting->pull();
        $selecting->reset();

        $selecting->more_details("WHERE email = ?# $email");
        $action = $selecting->action('mode, status, date', 'btc');
        if($action != null) return $action;
        $selecting->reset();
        $value = $selecting->pull();
    
        $deposits = $value;

        $selecting->more_details("WHERE email = ?# $email");
        $value = $selecting->action('moni, mode, date, status', 'wbtc');
        if($action != null) return $action;
        $selecting->reset();
        $value = $selecting->pull();

        $withdrawals = $value;

        $selecting->more_details("");
        $action = $selecting->action("pname, bonus, duration, froms, tos", "package1");
        if($action != null) return $action;
        $selecting->reset();
        $packages = $selecting->pull();
        
    endif;

    if(in_array($_SERVER['REQUEST_URI'], ["/user/pay?method=bitcoin", "/user/pay?method=ethereum", "/user/pay?method=bnb", "/user/pay?method=usdt"])):
        $error_mssg = NULL;

        $token = $_GET['method'];
        $_SESSION['crypto_name'] = $token;

        $result1 = Func::cleanData($token, 'string');

        (string) $crypto = "";
        (string) $wallet = "";
        switch ($result1) {
            case 'bitcoin':
                $crypto = "Bitcoin";
                $wallet = "bwallet";
                break;
            case 'ethereum':
                $crypto = "Ethereum (ERC20)";
                $wallet = "ewallet";
                break;
            case 'usdt':
                $crypto = "USDT (TRC20)";
                $wallet = "usdt";
                break;
            case 'bnb':
                $crypto = "BNB (BEP20)";
                $wallet = "pm";
                break;
            default:
                $crypto = "Unknown";
                break;
        }
        
 
        $selecting->more_details("WHERE email = ?# $admin");
        $action = $selecting->action("$wallet", 'admin');
        if($action != null) return $action;
        $selecting->reset();
        $walletAddress = $selecting->pull()[0][0][$wallet];
    endif;


?>