<?php
    namespace Service;

    use DateTime;
    use mysqli;
    use Query\Select;
    use Query\Update;

    class Func {

        public static function cleanData($data, string $type) {
            
            switch ($type) {
                case 'string':
                    $data = htmlspecialchars(trim(stripcslashes($data)), ENT_NOQUOTES);
                    break;
    
                case 'integer':
                    (filter_var($data, FILTER_VALIDATE_INT)) ? (int) $data = $data : $data = "error";
    
                    break;
    
                case 'email':
                    $data = filter_var($data, FILTER_SANITIZE_EMAIL);
                    (filter_var($data, FILTER_VALIDATE_EMAIL)) ? $data = $data : $data = "error";
    
                    break;
                default:
                    return "error";
                    break;
            }
    
            return $data;
        }

        public static function tokenGenerator() : string {
            return bin2hex(random_bytes(10)).time();
        }
    
        public static function deviceInfo() : array {
            $server = $_SERVER['HTTP_USER_AGENT'];
    
            $server1 = strpos($server, "(") + 1;
            $server2 = strpos($server, ")") - $server1;
        
            $server3 = substr($server, $server1, $server2);
    
            return [$server3, $_SERVER['REMOTE_ADDR']];
        }

        public static function dateFormat() : string {
            $date = new DateTime('now');
            $date = $date->format('Y-m-d\TH:i:sp');

            return $date;
        }
        public static function dateParts(string $date) : array {
            $year = substr($date, 0, 4);
            $month = substr($date, 5, 2);
            $day = substr($date, 8, 2);

            return [$year, $month, $day];
        }
        public static function sortAsc($a, $b){
            $t1 = $a['time'];
            $t2 = $b['time'];
            return $t2 - $t1;
        }

        public static function email_config() : array {
            return [
                'id' => 'admin@qfsledgerconnect.org',
                'pass' => 'qfsledger1',
                'name' => 'QFSLedgerConnect',
                'host' => 'mail.qfsledgerconnect.org'
            ];

        }

        public static function searchObject(array $data, string|int $needle, string $key) : array {
            /**
             * Search for needly in object
             * If 1 exist, then the item is found
             * Else, Item does not exist in object
             */
            $exist = [];
            if(!empty($data)):
                foreach($data as $val):
                    if($val[$key] == $needle):
                        array_push($exist, 1);
                    else:
                        array_push($exist, 0);
                    endif;

                endforeach;
            endif;

            return $exist;
        }

        public static function searchDb(mysqli $db, array $data, string $expression) {
            $keys = array_keys($data);
            $values = array_values($data);

            $key = $keys[0];
            $key1 = $keys[1];
            $val = $values[0];
            $val1 = $values[1];
            $needle = $data['needle'];
            $table = $data['table'];

            $selecting = new Select($db);
            $selecting->more_details("WHERE $key = ? $expression $key1 = ? ORDER BY id ASC# $val# $val1");
            $action = $selecting->action($needle, $table);

            if($action != null) return $action;
            $value = $selecting->pull();
            if($value[1] > 0):
                if($needle === "*"):
                    return $value[0][0];
                else:
                    if(count(explode(",", $needle)) === 1):
                        return $value[0][0][$needle];
                    else:
                        return $value[0];
                    endif;
                endif;
            else:
                return [];
            endif;
        }

        public static function mention(mysqli $db, string $content, array $data) {
            $status = 1;
            $key = array_keys($data)[0];
            $val = array_values($data)[0];

            $selecting = new Select($db);
            $selecting->more_details("WHERE $key = ?# $val");
            $action = $selecting->action("other", "mentions");
            $selecting->reset();

            if($action != null) return $action;

            $value = $selecting->pull()[0];

            foreach($value as $other):
                $mentioned = $other['other'];
                // Get the username of person
                $selecting->more_details("WHERE id = ?# $mentioned");
                $selecting->action("username", "user");
                $selecting->reset();

                if($action != null) return $action;
    
                $username = $selecting->pull()[0][0]['username'];

                $mention = "@$username";
                $format = "<a href='profile?token=$mentioned' style='color: #ff465b; text-decoration: none;'>$mention</a>";
                $content = str_replace($mention, $format, $content);

            endforeach;

            return $content;
        }

        public static function blockedUsers(mysqli $db, int $user) : array {
            $selecting = new Select($db);

            // Get all the users that has been blocked by this user first
            $selecting->more_details("WHERE user = ?# $user");
            $action = $selecting->action("other", "blocked_users");
            $selecting->reset();

            if($action != null):
                return $action;
            endif;

            $blocked_users = $selecting->pull();
            $blocked_result = [];
            $blocked_query = "";

            // Get all the blocked users and add them to an array
            if($blocked_users[1] > 0):
                foreach($blocked_users[0] as $blocked):
                    array_push($blocked_result, $blocked['other']);
                endforeach;

                // Join all the blocked result with the hash as a divider
                $blocked_result = implode("#", $blocked_result);

                // Create a question mark parameter for the query
                $param = array_fill(1, $blocked_users[1], "?");
                $param = implode(",", $param);
                $blocked_query = "AND user NOT IN ($param)";
            else:
                
                $blocked_query = "AND user NOT IN (?)";
                $blocked_result = " ";
            endif;


            return [$blocked_query, $blocked_result];
        }

        public static function array_column_recursive(array $haystack, $needle) : array {
            $found = [];
            array_walk_recursive($haystack, function($value, $key) use (&$found, $needle) {
                if($key == $needle) $found[] = $value;
            });

            return $found;
        }

        public static function sort_on_time($a, $b){
            $t1 = $a['time'];
            $t2 = $b['time'];
            return $t2 - $t1;
        }

        public static function calcTrade(int $user, mysqli $db, string $type) : int|float {
            $prob = [];
            $probResult = 0;
            $profit = 0;

            $data = [
                'id' => $user,
                '1' => '1',
                'needle' => 'ctrader, camount, cdate, cprofit, ccount',
                'table' => 'user'
            ];
            $trade = Func::searchDb($db, $data, "AND")[0];

            function calcRate(int $res, int $total) : int {
                $result = round(($res * 100)/($total), 0);

                return round(($result/10), 0);
            }

            // Check if there is active trade first
            if($trade['ctrader'] > 0):
                $ptime = strtotime($trade['cdate']);
                $ctime = strtotime(date('Y-m-d H:i:s'));

                $daysCount = (60 * 60) * 24;

                $daysCount = (($ctime - $ptime)/$daysCount);

                $daysCount = floor($daysCount);

                // First check if the counting days is equal to the current date - start date
                // If so it means that the trade is up to date and no need to update the profit anymore
                // Just end the package and update the wallet with the current profit
                if($trade['ccount'] !== $daysCount && $daysCount >= $trade['ccount']):
                    // Fetch trader details
                    $data = [
                        'id' => $trade['ctrader'],
                        '1' => '1',
                        'needle' => '*',
                        'table' => 'traders'
                    ];
                    $trader = Func::searchDb($db, $data, "AND");
                    $total = $trader['win'] + $trader['loss'] + $trader['even'];
                    
                    // Get the rates first, to calc how often they'll appear
                    $winRate = calcRate($trader['win'], $total);
                    $lossRate = calcRate($trader['loss'], $total);
                    $evenRate = calcRate($trader['even'], $total);

                    array_push($prob, array_fill(count($prob), $winRate, 1));
                    array_push($prob, array_fill(count($prob), $lossRate, -1));
                    array_push($prob, array_fill(count($prob), $evenRate, 0));
                    $prob = array_merge(...$prob);

                    // Check if last updated has reached 5days or more and round it back to 5
                    // 5 days is the maximum days to copy a trader
                    // Evaluation would be made for those 5 days if so
                    // Else use the remaining days

                    $days = $daysCount - $trade['ccount'];
                    if($daysCount - $trade['ccount'] >= 5) $days = 5;

                    for ($i=0; $i < $days; $i++) { 
                        $probResult += $prob[random_int(0, 9)];
                    }

                    // Now use the probability result to multiply the interest rate
                    // Then add to the cprofit
                    // Walletbalance would be updated if its endtrade or the traded has reached or exceeded 5 days
                    // Using only 10% of amount
                    $interest = ($trade['camount'] * 10)/100;
                    $profit = $interest * $probResult;
                    $updating = new Update($db, "SET cprofit = cprofit + ?, ccount = ccount + ? WHERE id = ?# $profit# $days# $user");
                    if($updating->mutate('sii', 'user')):
                        // If its general, then we have to check if the days has exceeded 5 or is 5 to update and end everything
                        if($type === "general"):
                            $zero = 0;
                            $empty = "";
                            if($daysCount >= 5):
                                $updating = new Update($db, "SET walletbalance = walletbalance + cprofit, ctrader = ?, cprofit = ?, camount = ?, cdate = ?, ccount = ? WHERE id = ?# $zero# $zero# $zero# $empty# $zero# $user");
                                $updating->mutate('iiisii', "user");
                            endif;
                        endif;
                    endif;
                endif;
            endif;

            return $profit;
        }

        public static function generateCode() : string {
            return random_int(1000000000, 99999999999).time();
        }

        public static function save_cookie(string $token) {
            $exp = strtotime('+3 days');

            setcookie("auth_token", $token, $exp, "/", "", FALSE, TRUE);
        }

        public static function fetchStock($symbol) {
            // Stock symbol
            $apiKey = "OICIHF9AY25UXE8N."; // Get free key at https://www.alphavantage.co

            /*$url = "https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol={$symbol}&apikey={$apiKey}";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);

            if (isset($data["Global Quote"])) {
                return $data['Global Quote'];
            } else {
                return false;
            }*/

            // Stock symbol (example: Apple)
            // Yahoo Finance API endpoint
            $url = "https://query1.finance.yahoo.com/v8/finance/chart/{$symbol}?interval=1m";

            // Initialize cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // Add headers (important for Yahoo)
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'User-Agent: Mozilla/5.0'
            ]);
            $response = curl_exec($ch);
            curl_close($ch);

            // Decode JSON
            $data = json_decode($response, true);

            // Extract current price
            if (isset($data["chart"]["result"][0]["meta"])) {
                return $data["chart"]["result"][0]["meta"];
            } else {
                return false;
            }
        }

        public static function getStockOptions($symbol) {

            $token = "d35dit1r01qhqkb1uurgd35dit1r01qhqkb1uus0"; // from finnhub.io

            $url = "https://finnhub.io/api/v1/stock/option-chain?symbol={$symbol}&token={$token}";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);

            if(curl_errno($ch)){
                die("cURL Error: " . curl_error($ch));
            }
            curl_close($ch);

            $data = json_decode($response, true);

            return $data['data'][0];
        }

        public static function fetchCoin() {
            // Binance ticker endpoint (all tradable pairs)
            // Binance API through AllOrigins proxy
        }

    }

?>