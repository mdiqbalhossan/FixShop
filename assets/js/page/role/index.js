$(function () {
    "use strict";
    let options = {
        position: "top-right",
    };
    let notifier = new AWN(options);
    let destoryUrl = $('[name="destory_url"]').val();
    //delete Event
    $(document).on("click", ".dltBtn", function () {
        let onOk = () => {
            let id = $(this).data("id");
            let url = destoryUrl.replace(":id", id);
            $("#dltForm").attr("action", url);
            $("#dltForm").submit();
        };
        let onCancel = () => {
            notifier.info("Your Item is safe");
        };
        notifier.confirm(
            "Are you sure you want to delete the items",
            onOk,
            onCancel
        );
    });
});
