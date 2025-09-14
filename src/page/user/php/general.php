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
        $email = $user['email'];
        $fullname = $user['fullname'];
    endif;
?>