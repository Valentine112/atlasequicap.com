function who(self) {
    var email = document.getElementById("email")
    var who = document.getElementById("who")

    var elem = self.getAttribute("data-elem")

    if(elem == "everyone"){
        email.style.display = 'none'
        who.value = elem
    }else{
        email.style.display = 'block'
        who.value = elem
    }
    if(self.nextElementSibling !== null) {
        self.nextElementSibling.style.cssText = "background-color: #fff; color:#000;"
    }else{
        self.previousElementSibling.style.cssText = "background-color: #fff; color: #000;"
    }
    self.style.cssText = "background-color: #5e5e5e; color: #fff;"
}