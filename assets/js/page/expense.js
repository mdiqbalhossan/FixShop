$(function () {
    "use strict";
    let options = {
        position: "top-right",
    };
    let notifier = new AWN(options);
    let add_expense = $("#add_expense").val();
    let edit_expense = $("#edit_expense").val();
    let submit_expense = $("#submit_expense").val();
    let update = $("#update").val();
    let update_route = $("#update_route").val();
    //Add Event
    $("#addBtn").on("click", function () {
        $("#title").html(add_expense);
        $("#submitBtn").html(submit_expense);
        $("#name").val("");
        $("#address").val("");
    });

    //Edit Event
    $(document).on("click", ".editBtn", function () {
        let id = $(this).data("id");
        let name = $(this).data("name");
        let date = $(this).data("date");
        let amount = $(this).data("amount");
        let note = $(this).data("note");
        let type = $(this).data("type");
        $("#title").html(edit_expense);
        $("#name").val(name);
        $("#date").val(date);
        $("#amount").val(amount);
        $("#note").val(note);
        $("#type").val(type);

        $("#submitBtn").html(update);
        let url = update_route;
        url = url.replace(":id", id);
        $("#form").attr("action", url);
        $("#method_sec").html(
            '<input type="hidden" name="_method" value="PUT">'
        );
    });
});
