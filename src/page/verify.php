<?php
    use Config\Database;
    use Query\Update;
    use Service\Func;

    $token = $_GET['token'] ?? '';
    if(empty($token)):
        header("Location: login");
        exit;
    else:
        //Check if token exist verification
        $data = [
            'token' => $token,
            '1' => '1',
            'needle' => '*',
            'table' => 'users'
        ];

        $result = Func::searchDb(new Database, $data, "AND");
        if(!$result):
            $message = "<div style='color: red;'>The verification link is invalid or has expired.</div>";
            // Token does not exist
            // Maybe redirect to login page with error message
                header("Location: login");
        else:
            $updating = new Update(new Database, "SET status = 1 WHERE token = ?# $token");
            if($updating->mutate('s', 'users')):
                $message = "<div style='color: lightgreen;'>Your account has been successfully verified. You can now log in.</div>";
            else:
                $message = "<div style='color: red;'>Something went wrong</div>";
            endif;
        endif;
    endif;
?>

<head>
    <style>
        body, html{
            margin: 0;
            padding: 0;
            height: 100%;
            background-color: #000;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            text-align: center;
        }
        .box{
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            max-width: 400px;
            color: silver;
        }
    </style>
</head>
<body>
    <div class="box">
        <h2>Atlasequicap</h2>
        <p style="padding: 5px; text-align: center; font-size: 18px;">
            <?php echo $message; ?>
        </p>
    </div>
</body>