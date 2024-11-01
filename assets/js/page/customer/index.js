$(function () {
    "use strict";
    let options = {
        position: "top-right",
    };
    let notifier = new AWN(options);
    let editBtn = $("#editBtn").val();
    let addCustomer = $("#addCustomer").val();
    let saveChanges = $("#saveChanges").val();
    let editCustomer = $("#editCustomer").val();
    //Add Event
    $("#addBtn").on("click", function () {
        $("#title").html(addCustomer);
        $("#submitBtn").html(saveChanges);
        $("#name").val("");
        $("#address").val("");
    });

    //Edit Event
    $(document).on("click", ".editBtn", function () {
        let id = $(this).data("id");
        let name = $(this).data("name");
        let address = $(this).data("address");
        let email = $(this).data("email");
        let phone = $(this).data("phone");
        $("#title").html(editCustomer);
        $("#name").val(name);
        $("#address").val(address);
        $("#email").val(email);
        $("#phone").val(phone);

        $("#submitBtn").html("Update");
        let url = editBtn;
        url = url.replace(":id", id);
        $("#form").attr("action", url);
        $("#method_sec").html(
            '<input type="hidden" name="_method" value="PUT">'
        );
    });
});
