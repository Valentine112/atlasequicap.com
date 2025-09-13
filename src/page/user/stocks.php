<?php //require "php/general.php"; ?>
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
                        <p class="title">Stocks</p>
                    </header>

                    <section class="payment-methods card-box col-lg-8 mx-lg-auto mt-4">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-4">
                                <span class="sub-sub-head">Stocks</span>
                            </div>
                            <div class="col-8">
                                <input type="search" name="" id="" placeholder="Find Stocks Here" class="form-control form-inp" onkeyup="searchStock(this)">
                            </div>
                        </div>
                        <hr>
                        <div class="stocks-list px-lg-3">

                        </div>
                    </section>
                </main>
            </section>
        </div>
    </div>
</body>
<script>
    window.addEventListener('DOMContentLoaded', () => {
        // Append the stocks list using JS
        let data = [{
                name: 'Tesla, Inc.',
                symbol: 'TSLA',
                logo: 'tsla.png',
                stockUrl: 'stocks/terminal?symbol=tsla',
                optionsUrl: 'stocks/terminal?symbol=tsla'
            },
            {
                name: 'Apple Inc.',
                symbol: 'AAPL',
                logo: 'aapl.png',
                stockUrl: 'stocks/terminal?symbol=aapl',
                optionsUrl: 'stocks/terminal?symbol=aapl'
            },
            {
                name: 'Microsoft Corporation',
                symbol: 'MSFT',
                logo: 'msft.png',
                stockUrl: 'stocks/terminal?symbol=msft',
                optionsUrl: 'stocks/terminal?symbol=msft'
            },
            {
                name: 'Alphabet Inc.',
                symbol: 'GOOGL',
                logo: 'googl.png',
                stockUrl: 'stocks/terminal?symbol=googl',
                optionsUrl: 'stocks/terminal?symbol=googl'
            },
            {
                name: 'Amazon.com, Inc.',
                symbol: 'AMZN',
                logo: 'amzn.png',
                stockUrl: 'stocks/terminal?symbol=amzn',
                optionsUrl: 'stocks/terminal?symbol=amzn'
            },
            {
                name: 'Meta Platforms, Inc.',
                symbol: 'META',
                logo: 'meta.png',
                stockUrl: 'stocks/terminal?symbol=meta',
                optionsUrl: 'stocks/terminal?symbol=meta'
            },
            {
                name: 'NVIDIA Corporation',
                symbol: 'NVDA',
                logo: 'nvda.png',
                stockUrl: 'stocks/terminal?symbol=nvda',
                optionsUrl: 'stocks/terminal?symbol=nvda'
            },
            {
                name: 'Netflix, Inc.',
                symbol: 'NFLX',
                logo: 'nflx.png',
                stockUrl: 'stocks/terminal?symbol=nflx',
                optionsUrl: 'stocks/terminal?symbol=nflx'
            },
            {
                name: 'Adobe Inc.',
                symbol: 'ADBE',
                logo: 'adbe.png',
                stockUrl: 'stocks/terminal?symbol=adbe',
                optionsUrl: 'stocks/terminal?symbol=adbe'
            },
            {
                name: 'BYND Cannasoft Enterprises Inc.',
                symbol: 'BCAN',
                logo: 'bynd.png',
                stockUrl: 'stocks/terminal?symbol=bynd',
                optionsUrl: 'stocks/terminal?symbol=bynd'
            },
            {
                name: 'DocuSign, Inc.',
                symbol: 'DOCU',
                logo: 'docu.png',
                stockUrl: 'stocks/terminal?symbol=docu',
                optionsUrl: 'stocks/terminal?symbol=docu'
            },
            {
                name: 'Affirm Holdings, Inc.',
                symbol: 'AFRM',
                logo: 'afrm.png',
                stockUrl: 'stocks/terminal?symbol=afrm',
                optionsUrl: 'stocks/terminal?symbol=afrm'
            },
            {
                name: 'Applovin Corporation',
                symbol: 'APP',
                logo: 'app.png',
                stockUrl: 'stocks/terminal?symbol=app',
                optionsUrl: 'stocks/terminal?symbol=app'
            },
            {
                name: 'Intel Corporation',
                symbol: 'INTC',
                logo: 'intc.png',
                stockUrl: 'stocks/terminal?symbol=intc',
                optionsUrl: 'stocks/terminal?symbol=intc'
            },
            {
                name: 'PayPal Holdings, Inc',
                symbol: 'PYPL',
                logo: 'pypl.png',
                stockUrl: 'stocks/terminal?symbol=pypl',
                optionsUrl: 'stocks/terminal?symbol=pypl'
            },
            {
                name: 'Qualcom Incorporated',
                symbol: 'QCOM',
                logo: 'qcom.png',
                stockUrl: 'stocks/terminal?symbol=qcom',
                optionsUrl: 'stocks/terminal?symbol=qcom'
            },
            {
                name: 'Rivian Automotive, Inc.',
                symbol: 'RIVN',
                logo: 'rivn.png',
                stockUrl: 'stocks/terminal?symbol=rivn',
                optionsUrl: 'stocks/terminal?symbol=rivn'
            },
            {
                name: 'Starbucks Corporation',
                symbol: 'SBUX',
                logo: 'sbux.png',
                stockUrl: 'stocks/terminal?symbol=sbux',
                optionsUrl: 'stocks/terminal?symbol=sbux'
            },
            {
                name: 'United Airlines Holdings, Inc.',
                symbol: 'UAL',
                logo: 'ual.png',
                stockUrl: 'stocks/terminal?symbol=ual',
                optionsUrl: 'stocks/terminal?symbol=ual'
            },
            {
                name: 'Harrow, Inc.',
                symbol: 'HROW',
                logo: 'hrow.png',
                stockUrl: 'stocks/terminal?symbol=hrow',
                optionsUrl: 'stocks/terminal?symbol=hrow'
            },
            {
                name: 'Hydrofarm Holdings Group, Inc.',
                symbol: 'HYFM',
                logo: 'hyfm.png',
                stockUrl: 'stocks/terminal?symbol=hyfm',
                optionsUrl: 'stocks/terminal?symbol=hyfm'
            },
            {
                name: 'Hyzon Motors Inc.',
                symbol: 'HYZN',
                logo: 'hyzn.png',
                stockUrl: 'stocks/terminal?symbol=hyzn',
                optionsUrl: 'stocks/terminal?symbol=hyzn'
            },
        ]

        let stocksList = document.querySelector('.stocks-list');
        data.forEach(stock => {
            let stockItems = `
                <div class="row align-items-center my-2 stock-box">
                    <div class="col-6 row justify-content-start align-items-center">
                        <div class="col-4">
                            <img src="../src/assets/logos/${stock.logo}" alt="">
                        </div>
                        <div class="col-8">
                            <span class="nameSymbol"><div class="stock-name">${stock.name}</div>${stock.symbol}</span>
                        </div> 
                    </div>
                    <div class="col-6 text-end">
                        <a href="${stock.stockUrl}" class="btn btn-transparent" style="background-color: #303C51;">Stock</a>
                        <a href="${stock.optionsUrl}" class="btn btn-transparent" style="background-color: #303C51;">Options</a>
                    </div>
                    <hr class="mt-2">
                </div>
            `;
            stocksList.insertAdjacentHTML('beforeend', stockItems)
        })
    })

    function searchStock(self) {
        let val = self.value
        let search = new RegExp(self.value, 'g')
        let name = document.querySelectorAll(".nameSymbol")
        Array.from(name).forEach(elem => {
            let parent = elem.closest(".stock-box")
            if(elem.innerText.match(search)){
                parent.style.display = "flex"
            }
            else{
                parent.style.display = "none"
            }
        })
    }
</script>
</html>
    