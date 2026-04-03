$(function () {

    // Add event listener to the languageModelSwitcher tabs
    $('.languageModelSwitcher a[data-toggle="tab"]').on('click', function (e) {
        var target = $(e.target).attr("href"); // activated tab

        // Show the activated tab and hide the others
        $(target).show().addClass('show active').siblings().removeClass('show active').hide();

        // Remove 'active' class from all nav links and add it to the clicked tab
        $('.nav.nav-tabs .nav-link').removeClass('active');
        $(this).addClass('active');
    });

    // Trigger the 'shown.bs.tab' event on the first tab
    $('.languageModelSwitcher a[data-toggle="tab"]').first().trigger('shown.bs.tab');
});