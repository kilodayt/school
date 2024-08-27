const nav = document.querySelector('#nav')
const navBtn = document.querySelector('#nav-button')
const navBtnImg = document.querySelector('#nav-button-img')

navBtn.onclick = () => {
    if (nav.classList.toggle('open')) {
        navBtnImg.src = "images/icons/NAV CLOSE.svg";
    } else {
        navBtnImg.src = "images/icons/NAV.svg";
    }
}

AOS.init();
