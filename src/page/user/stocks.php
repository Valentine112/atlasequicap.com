<?php require "php/general.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
        use Src\Config\Head; 
        use Service\Func;
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
                stockUrl: 'terminal?symbol=tsla&type=stock',
                optionsUrl: 'options?options=tsla&type=stock'
            },
            {
                name: 'Apple Inc.',
                symbol: 'AAPL',
                logo: 'aapl.png',
                stockUrl: 'terminal?symbol=aapl&type=stock',
                optionsUrl: 'options?options=aapl&type=stock'
            },
            {
                name: 'Microsoft Corporation',
                symbol: 'MSFT',
                logo: 'msft.png',
                stockUrl: 'terminal?symbol=msft&type=stock',
                optionsUrl: 'options?options=msft&type=stock'
            },
            {
                name: 'Alphabet Inc.',
                symbol: 'GOOGL',
                logo: 'googl.png',
                stockUrl: 'terminal?symbol=googl&type=stock',
                optionsUrl: 'options?options=googl&type=stock'
            },
            {
                name: 'Amazon.com, Inc.',
                symbol: 'AMZN',
                logo: 'amzn.png',
                stockUrl: 'terminal?symbol=amzn&type=stock',
                optionsUrl: 'options?options=amzn&type=stock'
            },
            {
                name: 'Meta Platforms, Inc.',
                symbol: 'META',
                logo: 'meta.png',
                stockUrl: 'terminal?symbol=meta&type=stock',
                optionsUrl: 'options?options=meta&type=stock'
            },
            {
                name: 'NVIDIA Corporation',
                symbol: 'NVDA',
                logo: 'nvda.png',
                stockUrl: 'terminal?symbol=nvda&type=stock',
                optionsUrl: 'options?options=nvda&type=stock'
            },
            {
                name: 'Netflix, Inc.',
                symbol: 'NFLX',
                logo: 'nflx.png',
                stockUrl: 'terminal?symbol=nflx&type=stock',
                optionsUrl: 'options?options=nflx&type=stock'
            },
            {
                name: 'Adobe Inc.',
                symbol: 'ADBE',
                logo: 'adbe.png',
                stockUrl: 'terminal?symbol=adbe&type=stock',
                optionsUrl: 'options?options=adbe&type=stock'
            },
            {
                name: 'BYND Cannasoft Enterprises Inc.',
                symbol: 'BCAN',
                logo: 'bynd.png',
                stockUrl: 'terminal?symbol=bynd&type=stock',
                optionsUrl: 'options?options=bynd&type=stock'
            },
            {
                name: 'DocuSign, Inc.',
                symbol: 'DOCU',
                logo: 'docu.png',
                stockUrl: 'terminal?symbol=docu&type=stock',
                optionsUrl: 'options?options=docu&type=stock'
            },
            {
                name: 'Affirm Holdings, Inc.',
                symbol: 'AFRM',
                logo: 'afrm.png',
                stockUrl: 'terminal?symbol=afrm&type=stock',
                optionsUrl: 'options?options=afrm&type=stock'
            },
            {
                name: 'Applovin Corporation',
                symbol: 'APP',
                logo: 'app.png',
                stockUrl: 'terminal?symbol=app&type=stock',
                optionsUrl: 'options?options=app&type=stock'
            },
            {
                name: 'Intel Corporation',
                symbol: 'INTC',
                logo: 'intc.png',
                stockUrl: 'terminal?symbol=intc&type=stock',
                optionsUrl: 'options?options=intc&type=stock'
            },
            {
                name: 'PayPal Holdings, Inc',
                symbol: 'PYPL',
                logo: 'pypl.png',
                stockUrl: 'terminal?symbol=pypl&type=stock',
                optionsUrl: 'options?options=pypl&type=stock'
            },
            {
                name: 'Qualcom Incorporated',
                symbol: 'QCOM',
                logo: 'qcom.png',
                stockUrl: 'terminal?symbol=qcom&type=stock',
                optionsUrl: 'options?options=qcom&type=stock'
            },
            {
                name: 'Rivian Automotive, Inc.',
                symbol: 'RIVN',
                logo: 'rivn.png',
                stockUrl: 'terminal?symbol=rivn&type=stock',
                optionsUrl: 'options?options=rivn&type=stock'
            },
            {
                name: 'Starbucks Corporation',
                symbol: 'SBUX',
                logo: 'sbux.png',
                stockUrl: 'terminal?symbol=sbux&type=stock',
                optionsUrl: 'options?options=sbux&type=stock'
            },
            {
                name: 'United Airlines Holdings, Inc.',
                symbol: 'UAL',
                logo: 'ual.png',
                stockUrl: 'terminal?symbol=ual&type=stock',
                optionsUrl: 'options?options=ual&type=stock'
            },
            {
                name: 'Harrow, Inc.',
                symbol: 'HROW',
                logo: 'hrow.png',
                stockUrl: 'terminal?symbol=hrow&type=stock',
                optionsUrl: 'options?options=hrow&type=stock'
            },
            {
                name: 'Hydrofarm Holdings Group, Inc.',
                symbol: 'HYFM',
                logo: 'hyfm.png',
                stockUrl: 'terminal?symbol=hyfm&type=stock',
                optionsUrl: 'options?options=hyfm&type=stock'
            },
            {
                name: 'Hyzon Motors Inc.',
                symbol: 'HYZN',
                logo: 'hyzn.png',
                stockUrl: 'terminal?symbol=hyzn&type=stock',
                optionsUrl: 'options?options=hyzn&type=stock'
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
    