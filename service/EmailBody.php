<?php
    namespace service;

    class EmailBody {
        private array $data;
        private array $list;

        function __construct(array $data)
        {
            $this->data = $data;
            $this->list = [];
        }
        public function main() : string {
            $head = $this->data['head'];
            return '
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Document</title>
                <style>
                    .container{
                        width: 95%;
                        border: 1px solid #c1c1c1;
                        border-radius: 5px;
                        padding: 3%;
                    }
                    .container h1{
                        color: #CCA354;
                    }
                    .container .main{
                        border: 1px solid #f1f1f1;
                        padding: 3%;
                        border-top-left-radius: 5px;
                        border-top-right-radius: 5px;
                    }
                    .container h2{
                        font-variant: small-caps;
                    }
                    .container .linkPath{
                        margin-top: 5%;
                    }
                    .container .siteLink{
                        background-color: #444;
                        color: #fff;
                        padding: 1.5% 7%;
                        border-radius: 5px;
                        text-decoration: none;
                    }
                    .container .brief-body{
                        margin-top: 2%;
                    }
                    .container .brief{
                        font-size: 14px;
                        color: grey;
                    }
                    .container footer{
                        background-color: #f1f1f1;
                        text-align: center;
                        padding:2% 0;
                        padding-bottom: 4%;
                        color: grey;
                    }
                    footer h3{
                        color: #fff;
                        font-variant: small-caps;
                    }
                    footer .bottom-footer{
                        padding: 3% 0;
                    }
                    footer a{
                        color: #0c5e3e;
                        text-decoration: none;
                    }
                    @media screen and (min-width: 992px) {
                        .container .content{
                            width: 45%;
                        }
                        footer .footer-content{
                            width: 60%;
                        }
                    }
                </style>
            </head>
            <body>
            <div class="container">
                <h1>QFS<span style="color: #6731a9;">Ledger</span>Connect</h1>
                <div>
                    <div class="main">
                        <h2>'.$head.'</h2>
                        <div class="content">
                            '.$this->checkContent().'
                        </div>
                
                        <div class="brief-body">
                            <p class="brief">
                            QFSLedgerConnect - Store all your wallets in one place!
                            </p>
                        </div>
                    </div>
                </div>
                <footer>
                    <p>QFSLedgerConnect - General Self-custodial wallet</p>
                </footer>
            </div>
            </body>
            </html>';
        }

        private function checkContent() {
            if(is_string($this->data['elements'])) return $this->data['elements'];

            if(is_array($this->data['elements'])):
                $this->displayList();
                return "<ul>".implode("", $this->list)."</ul>";
            endif;
        }

        private function displayList() {
            foreach(array_keys($this->data['elements']) as $elem):
                $val = $this->data['elements'][$elem];
                array_push($this->list, '<li>'.$elem.': &ensp; '.$val.'</li>');
                //return '<li>'.$elem.': &ensp; '.$val.'</li>';
            endforeach;
            
            return $this->list;
        }
    
    }
?>