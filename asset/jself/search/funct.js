$(function () {

    table_setup();

    $('#searchnumber').on('search', function () {
        console.log($(this).val());
    });

    $('#searchbutton').on('click', function () {
        console.log($('#searchnumber').val());
    });

});