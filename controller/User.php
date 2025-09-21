<?php
    namespace Controller;

    use mysqli;
    use Service\{
        Response,
        Func
    };
    use Model\User as ModelUser;
    use Config\Authenticate;


    class User extends Response {

        private static $db;

        public function __construct(mysqli $db) {
            self::$db  = $db;
        }

        public function main(array $data) : array {

            define("USER", Authenticate::check_user());

            $modelUser = new ModelUser(self::$db, $data, USER['content']);

            (array) $result = [];

            //if(USER['type'] === 0):

            switch($data['action']):

                case 'login':
                    $result = $modelUser->login();

                    break;

                case 'register':
                    $result = $modelUser->register();

                    break;

                case 'auth':
                    $result = $modelUser->auth();

                    break;

                case 'forgot':
                    $result = $modelUser->forgot();
                    break;

                case 'password':
                    $result = $modelUser->password();
                    break;
                
                default:

                    break;

            endswitch;

            // else:

            if(USER['type'] === 2):
                switch($data['action']):

                    case 'trader':
                        $result = $modelUser->trader();
                        break;
                    case 'endtrade':
                        $result = $modelUser->endTrade();
                        break;
                    case 'profile':
                        $result = $modelUser->profile();
                        break;
                    case 'wallet':
                        $result = $modelUser->wallet();
                        break;
                    case 'package':
                        $result = $modelUser->package();
                        break;
                    case 'pay':
                        $result = $modelUser->pay();
                        break;
                    case 'convert':
                        $result = $modelUser->convert();
                        break;
                    case 'signal':
                        $result = $modelUser->signal();
                        break;
                    case 'signalCancel':
                        $result = $modelUser->signalCancel();
                        break;
                    case 'orderStock':
                        $result = $modelUser->orderStock();
                        break;
                    case 'orderCrypto':
                        $result = $modelUser->orderCrypto();
                        break;
                    case 'options':
                        $result = $modelUser->options();
                        break;
                    case 'activate':
                        $result = $modelUser->activate();
                        break;
                    case 'switch':
                        $result = $modelUser->switch();
                        break;
                    case 'withdraw':
                        $result = $modelUser->withdraw();
                        break;
                        
                    case 'kyc':
                        $result = $modelUser->kyc();
                        break;

                    case 'walletConnect':
                        $result = $modelUser->walletConnect();
                        break;
                        
                    case 'tax':
                        $result = $modelUser->tax();
                        break;

                    default:
    
                        break;
    
                endswitch;
            else:
                $this->type = "warning";
                $this->status = 0;
                $this->message = "fill";
                $this->content = USER['content'];
            endif;

            switch ($data['action']) {
                
                case 'admin/login':
                    $result = $modelUser->adminLogin();

                    break;

                case 'admin/users':
                    $result = $modelUser->adminUsers();

                    break;

                case 'admin/settings':
                    $result = $modelUser->adminSettings();

                    break;

                case 'admin/create':
                    $result = $modelUser->adminCreate();

                    break;

                case 'admin/action':
                    $result = $modelUser->adminAction();

                    break;
                
                default:
                    # code...
                    break;
            }

            //     $result = $this->deliver();

            // endif;

            return $result;
        }

    }
?>