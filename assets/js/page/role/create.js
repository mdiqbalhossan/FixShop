$(function () {
    "use strict";
    // select all checkbox
    $("#select_all").on("change", function () {
        $('input[type="checkbox"]').prop("checked", $(this).prop("checked"));
    });
});
