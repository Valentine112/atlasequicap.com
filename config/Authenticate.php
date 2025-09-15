<?php
    namespace Config;
    
    use Config\Database;
    use Query\Select;
    use Service\Func;
    // Check if user is logged in

    class Authenticate{

        public static function check_user() {
            $result = [
                "type" => "",
                "content" => ""
            ];

            if(!empty($_COOKIE['auth_token'])):
                $sessionToken = Func::cleanData($_COOKIE['auth_token'], 'string');

                // Check if the token actually exist
                $selecting = new Select(new Database);
                $selecting->more_details("WHERE token = ?# $sessionToken");
                $action = $selecting->action("id", "users");

                if($action != null) return $action;

                $value = $selecting->pull();
                if($value[1] > 0):
                    $result = [
                        "type" => 2,
                        "content" => $value[0][0]['id']
                    ];

                else:
                    $result = [
                        "type" => 0,
                        "content" => "You need to <a href='../login' style='color: dodgerblue'>Login</a>"
                    ];

                endif;
            else:
                $result = [
                    "type" => 0,
                    "content" => "You need to <a href='../login' style='color: dodgerblue'>Login</a> to perform this action"
                ];

            endif;
            return $result;
        }
    }

    // ENDS
?>