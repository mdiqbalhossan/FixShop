$(function () {
    "use strict";
    let options = {
        position: "top-right",
    };
    let notifier = new AWN(options);
    let add_warehouse_text = $("#add_warehouse_text").val();
    let submit_button_text = $("#submit_button_text").val();
    let edit_text = $("#edit_text").val();
    let update_url = $("#update_url").val();
    let delete_url = $("#delete_url").val();
    //Add Event
    $("#addBtn").on("click", function () {
        $("#title").html(add_warehouse_text);
        $("#submitBtn").html(submit_button_text);
        $("#name").val("");
        $("#address").val("");
        $("#is_admin").prop("checked", false);
        $("#hidden_sec").addClass("d-none");
        $("#user_name").val("");
        $("#email").val("");
        $("#password").val("");
    });

    //Edit Event
    $(document).on("click", ".editBtn", function () {
        let id = $(this).data("id");
        let name = $(this).data("name");
        let address = $(this).data("address");
        let status = $(this).data("status");
        let has_admin = $(this).data("hasadmin");
        console.log(has_admin);
        let admin = $(this).data("admin");
        $("#title").html(edit_text);
        $("#name").val(name);
        $("#address").val(address);
        status === 1
            ? $("#success-outlined").attr("checked", "checked")
            : $("#danger-outlined").attr("checked", "checked");
        has_admin != null && has_admin != ""
            ? $("#is_admin").prop("checked", true)
            : $("#is_admin").prop("checked", false);
        has_admin != null && has_admin != ""
            ? $("#hidden_sec").removeClass("d-none")
            : $("#hidden_sec").addClass("d-none");
        $("#user_name").val(admin.name);
        $("#email").val(admin.email);
        $(".required_pass").addClass("d-none");
        $("#submitBtn").html("Update");
        let url = update_url;
        url = url.replace(":id", id);
        $("#form").attr("action", url);
        $("#method_sec").html(
            '<input type="hidden" name="_method" value="PUT">'
        );
    });

    // generate password
    $("#generate_password").on("click", function () {
        let password = Math.random().toString(36).slice(-8);
        $("#password").val(password);
    });

    //is_admin checkbox toggle
    $("#is_admin").on("change", function () {
        if ($(this).is(":checked")) {
            $("#hidden_sec").removeClass("d-none");
        } else {
            $("#hidden_sec").addClass("d-none");
        }
    });
});
