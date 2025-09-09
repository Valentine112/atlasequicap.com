<html>
    <head>
     <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400&amp;display=swap" rel="stylesheet">
    
        <style>
            .s-body{
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                right: 0;
                background-color: #444;
                opacity: 1;
                margin: auto;
                z-index: 1200;
                font-family: 'Figtree', sans-serif;
                display: none;
            }
            .s-cover{
                position: absolute;
                top: 0;
                bottom: 0;
                left: 0;
                right: 0;
                width: 95%;
                margin: auto;
                padding: 5% 0;
                height: 60vh;
                border: 5px;
            }
            .s-cover main{
                position: absolute;
                top: 5%;
                left: 0;
                right: 0;
                width: 100%;
                width: fit-content;
                margin: auto;
                text-align: center;
            }
            .s-cover h4{
                color: green;
                font-family: 'Figtree', sans-serif;
            }
            .s-cover main p{
                color: #000;
                text-align: left;
            }
            .s-cover img{
                height: 60px;
                width: 60px;
                margin: auto;
            }
            
            .s-cover footer{
                position: absolute;
                bottom: 3%;
                width: 100%;
                text-align: center;
            }
            @media screen and (min-width: 992px) {
                .s-cover{
                    width: 50%;
                }
            }
        </style>
    </head>
    <body>
        <div class="s-body" id="successElem" onclick="this.style.display = 'none';">
        <div class="s-cover data-light">
            <main class="text-center">
              <h4 style="text-align: left; font-weight: 600;">Successfully submitted connection request</h4>
              <p>We would be in touch with you shortly</p>
              <img src="https://cdn-icons-png.flaticon.com/512/190/190411.png">
            </main>
            
            <footer>
                <p>Click anywhere to close</p>
            </footer>
        </div>
        </div>
    </body>
</html>