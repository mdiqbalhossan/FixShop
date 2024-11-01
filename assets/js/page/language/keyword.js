(function ($) {
    "use strict";

    $(".edit-language-keyword").on("click", function (e) {
        let key = $(this).data("key");
        let value = $(this).data("value");
        let group = $(this).data("group");
        let language = $(this).data("language");

        $(".key-label").html(key);
        $(".key-key").val(key);
        $(".key-value").val(value);
        $(".key-group").val(group);
        $(".key-language").val(language);

        $("#editKeyword").modal("toggle");
    });
})(jQuery);
