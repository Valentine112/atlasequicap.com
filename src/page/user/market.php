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
    <link rel="stylesheet" href="../src/assets/css/landing.css">
    <link rel="stylesheet" href="../src/assets/css/general.css">
    <title>QFSLedgerConnect - Storage for your wallets</title>
</head>
<body class="data-light-body">
    <?php include "src/template/sb/index.html"; ?>
    
    <main class="deposit">
    <?php include "src/template/head.php"; ?>

        <section class="mt-3 deposit content">
            <div>
                <div class="referred referred-section mb-3">
                    <header class="text-center">
                        <h5>Don't miss out</h5>
                        <p>See where the market is right now</p>
                    </header>

                    <div class="row justify-content-around align-items-center">
                        <div class="col-12 col-md-8 mt-2 mb-4 mx-auto text-center">
                            <!-- TradingView Widget BEGIN -->
                            <div class="tradingview-widget-container data-light" style="margin: auto;">
                                <div class="tradingview-widget-container__widget"></div>
                                <div class="tradingview-widget-copyright"><a href="https://www.tradingview.com/" rel="noopener nofollow" target="_blank"><span class="blue-text">Track all markets on TradingView</span></a></div>
                                <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-market-overview.js" async>
                                {
                                "colorTheme": "light",
                                "dateRange": "12M",
                                "showChart": true,
                                "locale": "en",
                                "largeChartUrl": "",
                                "isTransparent": false,
                                "showSymbolLogo": true,
                                "showFloatingTooltip": false,
                                "width": "400",
                                
                                "plotLineColorGrowing": "rgba(41, 98, 255, 1)",
                                "plotLineColorFalling": "rgba(41, 98, 255, 1)",
                                "gridLineColor": "rgba(240, 243, 250, 0)",
                                "scaleFontColor": "rgba(15, 15, 15, 1)",
                                "belowLineFillColorGrowing": "rgba(41, 98, 255, 0.12)",
                                "belowLineFillColorFalling": "rgba(41, 98, 255, 0.12)",
                                "belowLineFillColorGrowingBottom": "rgba(41, 98, 255, 0)",
                                "belowLineFillColorFallingBottom": "rgba(41, 98, 255, 0)",
                                "symbolActiveColor": "rgba(41, 98, 255, 0.12)",
                                "tabs": [
                                    {
                                    "title": "Indices",
                                    "symbols": [
                                        {
                                        "s": "FOREXCOM:SPXUSD",
                                        "d": "S&P 500 Index"
                                        },
                                        {
                                        "s": "FOREXCOM:NSXUSD",
                                        "d": "US 100 Cash CFD"
                                        },
                                        {
                                        "s": "FOREXCOM:DJI",
                                        "d": "Dow Jones Industrial Average Index"
                                        },
                                        {
                                        "s": "INDEX:NKY",
                                        "d": "Japan 225"
                                        },
                                        {
                                        "s": "INDEX:DEU40",
                                        "d": "DAX Index"
                                        },
                                        {
                                        "s": "FOREXCOM:UKXGBP",
                                        "d": "FTSE 100 Index"
                                        }
                                    ],
                                    "originalTitle": "Indices"
                                    },
                                    {
                                    "title": "Forex",
                                    "symbols": [
                                        {
                                        "s": "FX:EURUSD",
                                        "d": "EUR to USD"
                                        },
                                        {
                                        "s": "FX:GBPUSD",
                                        "d": "GBP to USD"
                                        },
                                        {
                                        "s": "FX:USDJPY",
                                        "d": "USD to JPY"
                                        },
                                        {
                                        "s": "FX:USDCHF",
                                        "d": "USD to CHF"
                                        },
                                        {
                                        "s": "FX:AUDUSD",
                                        "d": "AUD to USD"
                                        },
                                        {
                                        "s": "FX:USDCAD",
                                        "d": "USD to CAD"
                                        }
                                    ],
                                    "originalTitle": "Forex"
                                    },
                                    {
                                    "title": "Futures",
                                    "symbols": [
                                        {
                                        "s": "BMFBOVESPA:ISP1!",
                                        "d": "S&P 500 Index Futures"
                                        },
                                        {
                                        "s": "BMFBOVESPA:EUR1!",
                                        "d": "Euro Futures"
                                        },
                                        {
                                        "s": "PYTH:WTI3!",
                                        "d": "WTI CRUDE OIL"
                                        },
                                        {
                                        "s": "BMFBOVESPA:ETH1!",
                                        "d": "Hydrous ethanol"
                                        },
                                        {
                                        "s": "BMFBOVESPA:CCM1!",
                                        "d": "Corn"
                                        }
                                    ],
                                    "originalTitle": "Futures"
                                    },
                                    {
                                    "title": "Bonds",
                                    "symbols": [
                                        {
                                        "s": "EUREX:FGBL1!",
                                        "d": "Euro Bund"
                                        },
                                        {
                                        "s": "EUREX:FBTP1!",
                                        "d": "Euro BTP"
                                        },
                                        {
                                        "s": "EUREX:FGBM1!",
                                        "d": "Euro BOBL"
                                        }
                                    ],
                                    "originalTitle": "Bonds"
                                    }
                                ]
                                }
                                </script>
                            </div>
                            <!-- TradingView Widget END -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
<script src="../src/assets/js/main.js"></script>
</html>