<?php
    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: application/json');

    require "config/config.php";

    use Config\Database;
    use Router\Request;
    use Controller\{
        User
    };

    use Service\Response;

    $db = new Database;
    $request = new Request($db);

    $request->is_post(
        'user',
        [User::class, 'main']
    );    
    
    print_r(Response::sendJSON($request->listen()));


?>