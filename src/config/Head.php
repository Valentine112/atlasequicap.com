<?php
    namespace Src\Config;
    class Head {

        public static function tags() {
            $struct = substr_count($_SERVER['REQUEST_URI'], '/');

            $struct -= 1;
            $struct == 0 ? $struct = "" : $struct = str_pad("../", $struct);
            echo
            '<head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" href="'.$struct.'src/assets/css/general.css">
                <link rel="stylesheet" href="'.$struct.'src/config/config.css">
                <link rel="stylesheet" href="'.$struct.'src/font/fonts.css">
                <script src="'.$struct.'src/assets/js/Func.js"></script>
                <script src="'.$struct.'src/assets/js/jquery.min.js"></script>
                <link href="'.$struct.'assets/bootstrap-5/css/bootstrap.min.css" rel="stylesheet">
                
                <script src="'.$struct.'assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>
                <link rel="preconnect" href="https://fonts.googleapis.com">
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

            </head>';
        }
    }
?>