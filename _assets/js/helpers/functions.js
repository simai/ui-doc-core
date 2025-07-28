export default function  setReadModePosition(container, sideMenu, setInside = false) {
    if(container.classList.contains('read')) {
        const containerOffset = container.getBoundingClientRect().left;
        const minus = setInside ? sideMenu.clientWidth + 16 : -16;
        if (setInside) {
            sideMenu.classList.add('inside');
        } else {
            sideMenu.classList.remove('inside');
        }
        sideMenu.style.left = `${(container.clientWidth + containerOffset) - minus}px`;
    } else {
        sideMenu.removeAttribute('style');
    }
}
