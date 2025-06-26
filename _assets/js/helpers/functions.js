export default function  setReadModePosition(container, sideMenu, setInside = false) {
    if(window.innerWidth > 980) {
        console.log('this');
        console.log(window.innerWidth);
        const containerOffset = container.getBoundingClientRect().left;
        const minus = setInside ? sideMenu.clientWidth + 16 : 0;
        if (setInside) {
            sideMenu.classList.add('inside');
        } else {
            sideMenu.classList.remove('inside');
        }
        sideMenu.style.left = `${(container.clientWidth + containerOffset) - minus}px`;
    } else {
        sideMenu.removeAttribute('style');
        console.log("ELSE");
    }
}
