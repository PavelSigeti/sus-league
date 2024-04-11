export default function bodyOverflow(type) {
    if(type) {
        document.body.style.overflow = '';
    } else {
        document.body.style.overflow = 'hidden';
    }
}
