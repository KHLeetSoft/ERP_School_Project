
// public/js/sidebar.js
$(function () {
    $('#sidebarToggle').on('click', function () {
        $('#sidebar').toggleClass('d-none');
    });
    const currentUrl = window.location.href;
    $('#main-menu-navigation a').each(function () {
        if (currentUrl.startsWith(this.href)) {
            $(this).addClass('active');
            $(this).closest('ul.menu-content').show().closest('li.parent').addClass('open');
            return false;
        }
    });
});
