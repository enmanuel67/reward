document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.getElementById('menuToggle');
    const menu = document.querySelector('.menu');

    menuToggle.addEventListener('change', function () {
        menu.classList.toggle('open', menuToggle.checked);
    });
});
