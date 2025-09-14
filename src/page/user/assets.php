<?php require "php/general.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
        use Src\Config\Head; 
        Head::tags();
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../src/assets/css/general.css">
    <link rel="stylesheet" href="../assets/css/line-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <title>QFSLedgerConnect - Storage for your wallets</title>
</head>
<body class="data-light-body">
    <?php include "src/template/quick-notice.php"; ?>
    <div class="section">
        <div class="wrapper d-flex align-items-stretch">
            <?php include "src/template/sb/sidebar.php"; ?>

            <section class="content p-4 p-md-5 pt-5">
                <?php include "src/template/head.php"; ?>
                <main>
                    <header>
                        <p class="title">My Assets</p>
                    </header>

                    <section class="chart">
                        <div id="chart">
                        </div>
                    </section>

                    <div class="mt-4 card-box">
                        <header>
                            <p class="title">Account</p>
                        </header>

                        <section class="data-section">
                            <div class="data-header">
                                <div id="nav-tab" role="tablist">
                                    <div class="wallet-sec active" id="stock-tab" data-bs-toggle="tab" data-bs-target="#stock" type="button" role="tab" aria-controls="stock" aria-selected="true" onclick="tradeTab('ST')">Stocks Trading</div>
                                    &emsp;
                                    <div class="wallet-sec" id="crypto-tab" data-bs-toggle="tab" data-bs-target="#crypto" type="button" role="tab" aria-controls="crypto" aria-selected="false" onclick="tradeTab('CT')">Cryptocurrency Trading</div>
                                </div>
                            </div>
                            <div class="data-body">
                                <div class="row justify-content-between align-items-center mb-4">
                                    <div class="col-5">
                                        <div class="sub-sect">TRADING ID</div>
                                        <p class="trade-id sub-head"><span id="initials">ST</span>-VT-4868680821</p>
                                    </div>
                                    <div class="col-5 text-end">
                                        <a href="deposit" class="btn btn-transparent">Deposit</a>
                                    </div>
                                </div>
                                
                                <div class="assets-info tab-content" id="nav-tabContent">
                                    <div class="tab-pane show fade stock active row justify-content-around px-3" id="stock" role="tabpanel" aria-labelledby="stock-tab">
                                        <div class="row col-12">
                                            <div class="col-6">
                                                <div class="sub-sub-head">
                                                    Stock Balance
                                                </div>
                                                <h2>
                                                    $0.00
                                                </h2>
                                            </div>
                                            <div class="col-6 text-end">
                                                <div class="sub-sub-head">
                                                    Stock Profit
                                                </div>
                                                <h2>
                                                    $0.00
                                                </h2>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-3">
                                            <div class="table-responsive">
                                                <table class="table table-borderless table-hover table-box border-0 bg-transparent">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">SYMBOL</th>
                                                            <th scope="col">TOTALSHARES</th>
                                                            <th scope="col">COST OF PURCHASE</th>
                                                            <th scope="col">PROFIT</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Mark</td>
                                                            <td>Otto</td>
                                                            <td>@mdo</td>
                                                            <td>homo</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade crypto row justify-content-around px-3" id="crypto" role="tabpanel" aria-labelledby="crypto-tab">
                                        <div class="row col-12">
                                            <div class="col-6">
                                                <div class="sub-sub-head">
                                                    Crypto Balance
                                                </div>
                                                <h2>
                                                    $0.00
                                                </h2>
                                            </div>
                                            <div class="col-6 text-end">
                                                <div class="sub-sub-head">
                                                    Crypto Profit
                                                </div>
                                                <h2>
                                                    $0.00
                                                </h2>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-3">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-box border-0 .table-borderless bg-transparent">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Asset</th>
                                                            <th scope="col">SIZE</th>
                                                            <th scope="col">ENTRY</th>
                                                            <th scope="col">DURATION</th>
                                                            <th>STATUS</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Mark</td>
                                                            <td>Otto</td>
                                                            <td>@mdo</td>
                                                            <td>homo</td>
                                                            <td>homox2</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </main>
            </section>
        </div>
    </div>
</body>
<script>
    window.addEventListener('DOMContentLoaded', (event) => {
        var options = {
            chart: {
                type: 'area',
                height: '100%',
                width: '100%',
            },
            series: [{
                name: 'Wallet',
                data: [],
                hidden: false
            }],
            labels: ['Wallet'],
            title: {
                text: "Assets Overview",
                align: 'left',
                margin: 10,
                offsetX: 0,
                offsetY: 0,
                floating: false,
                style: {
                fontSize:  '14px',
                fontWeight:  'bold',
                fontFamily:  undefined,
                color:  '#f1f1f1'
                },
            },
            noData: {
                text: "No Assets Overview",
                align: 'center',
                verticalAlign: 'middle',
                offsetX: 0,
                offsetY: 0,
                style: {
                    color: undefined,
                    fontSize: '14px',
                    fontFamily: undefined
                }
            },
            dataLabels: {
                enabled: false,
            },
            fill:{
                type: "gradient",
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.9,
                    stops: [0, 90, 100]
                }
            },
            responsive: [{
                breakpoint: undefined,
                options: {},
            }],
            xaxis: {
                type: 'category',
                categories: [1,2,3,4,5,6,7,8,9,10],
                title: {
                    text: 'Time (Days)',
                    style: {
                        color: 'grey',
                        fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 600,
                        cssClass: 'apexcharts-xaxis-title',
                    },
                }
            },
            yaxis: {
                title: {
                    text: 'Amount (USD)',
                    rotate: -90,
                    offsetX: 0,
                    offsetY: 0,
                    style: {
                        color: '#ffffff',
                        fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 600,
                        cssClass: 'apexcharts-yaxis-title',
                    },
                }
            },
            stroke: {
                curve: 'smooth',
            },
            grid: {
                show: true,
                borderColor: '#90A4AE',
                strokeDashArray: 0,
                position: 'back',
                xaxis: {
                    lines: {
                        show: false
                    }
                },   
                yaxis: {
                    lines: {
                        show: false
                    }
                },  
                row: {
                    colors: undefined,
                    opacity: 0.5
                },  
                column: {
                    colors: undefined,
                    opacity: 0.5
                },  
                padding: {
                    top: 0,
                    right: 0,
                    bottom: 0,
                    left: 0
                },  
            }
        }

        var chart = new ApexCharts(document.querySelector("#chart"), options);

        chart.render();
    });

    function tradeTab(initials) {
        document.getElementById("initials").innerText = initials
    }
</script>
</html>