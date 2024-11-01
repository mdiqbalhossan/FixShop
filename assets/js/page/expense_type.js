$(function () {
    "use strict";
    let options = {
        position: "top-right",
    };
    let notifier = new AWN(options);
    let edit_expense_type = $("#edit_expense_type").val();
    let update_route = $("#update_route").val();
    let delete_text = $("#delete_text").val();
    let destroy_route = $("#destroy_route").val();
    //Edit Event
    $(document).on("click", ".editBtn", function () {
        let id = $(this).data("id");
        let name = $(this).data("name");
        $("#title").html(edit_expense_type);
        $("#name").val(name);

        let url = update_route;
        url = url.replace(":id", id);
        $("#form").attr("action", url);
        $("#method_sec").html(
            '<input type="hidden" name="_method" value="PUT">'
        );
    });

    //delete Event
    $(document).on("click", ".dltBtn", function () {
        let onOk = () => {
            let id = $(this).data("id");
            let url = destroy_route;
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
