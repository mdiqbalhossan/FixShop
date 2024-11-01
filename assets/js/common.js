$(function () {
    `use strict`;

    let options = {
        position: "top-right",
    };
    let notifier = new AWN(options);
    let type = $(".notification_type").val();
    let message = $(".notification_message").val();
    console.log(message, type);
    $(".js-example-basic-single").select2();
    flatpickr("#date", {
        mode: "range",
        dateFormat: "Y-m-d",
    });

    if (type == "success") {
        notifier.success(message);
    }

    if (type == "error") {
        notifier.alert(message);
    }

    if (type == "info") {
        notifier.info(message);
    }

    if (type == "warning") {
        notifier.warning(message);
    }

    // Perpage 
     $("#perPage").on("change", function () {
         $("#tableHeaderForm").submit();
     });
});
