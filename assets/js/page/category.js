$(function () {
    "use strict";
    let options = {
        position: "top-right",
    };
    let notifier = new AWN(options);
    let edit_text = $("#edit_text").val();
    let update_route = $("#update_route").val();
    let delete_route = $("#delete_route").val();
    let delete_text = $("#delete_text").val();
    //Edit Event
    $(document).on("click", ".editBtn", function () {
        let id = $(this).data("id");
        let name = $(this).data("name");
        let status = $(this).data("status");
        $("#title").html(edit_text);
        $("#name").val(name);
        status === 1
            ? $("#success-outlined").attr("checked", "checked")
            : $("#danger-outlined").attr("checked", "checked");

        let url = update_route;
        url = url.replace(":id", id);
        $("#categoryForm").attr("action", url);
        $("#method_sec").html(
            '<input type="hidden" name="_method" value="PUT">'
        );
    });

    //delete Event
    $(document).on("click", ".dltBtn", function () {
        let onOk = () => {
            let id = $(this).data("id");
            let url = delete_route;
            url = url.replace(":id", id);
            $("#dltForm").attr("action", url);
            $("#dltForm").submit();
        };
        let onCancel = () => {
            notifier.info("Your Item is safe");
        };
        notifier.confirm(delete_text, onOk, onCancel);
    });
});
