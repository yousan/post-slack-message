$(function() {
    $('img.preset').on('click', function() {
        $('#touch_icon').val($(this).attr('src'));
    });
});