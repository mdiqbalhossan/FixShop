$(function () {
    "use strict";
    // select all checkbox
    $("#select_all").on("change", function () {
        $('input[type="checkbox"]').prop("checked", $(this).prop("checked"));
    });

    //if all checkbox are selected, check the select all checkbox
    $('input[type="checkbox"]').change(function () {
        if (
            $('input[type="checkbox"]:checked').length ==
            $('input[type="checkbox"]').length
        ) {
            $("#select_all").prop("checked", true);
        } else {
            $("#select_all").prop("checked", false);
        }
    });
});
