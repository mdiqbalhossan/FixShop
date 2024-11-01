$(function () {
    "use strict";
    let options = {
        position: "top-right",
    };
    let notifier = new AWN(options);
    let edit_text = $("#edit_text").val();
    let update_url = $("#update_url").val();
    let delete_url = $("#delete_url").val();
    //Edit Event
    $(document).on("click", ".editBtn", function () {
        let id = $(this).data("id");
        let name = $(this).data("name");
        let values = $(this).data("values");
        $("#title").html(edit_text);
        $("#name").val(name);
        $("#insert_field").html("");
        $(".default_html").html("");
        // value is comma separated string and first item show add more button but another item show remove button
        values = values.split(",");
        values.forEach(function (value, index) {
            let html;
            if (index === 0) {
                html =
                    '<div class="row mt-2"><div class="col-10"><input type="text" class="form-control" id="values" name="values[]" value="' +
                    value +
                    '" placeholder="Values"></div><div class="col-2"><button type="button" class="btn btn-primary btn-sm" id="addMore"><i class="ri-add-line"></i></button></div></div>';
            } else {
                html =
                    '<div class="row mt-2"><div class="col-10"><input type="text" class="form-control" id="values" name="values[]" value="' +
                    value +
                    '" placeholder="Values"></div><div class="col-2"><button type="button" class="btn btn-danger btn-sm" id="removeField"><i class="ri-close-line"></i></button></div></div>';
            }
            $("#insert_field").append(html);
        });

        let url = update_url;
        url = url.replace(":id", id);
        $("#unitForm").attr("action", url);
        $("#method_sec").html(
            '<input type="hidden" name="_method" value="PUT">'
        );
    });

    // add more field
    $(document).on("click", "#addMore", function () {
        const html =
            '<div class="row mt-2"><div class="col-10"><input type="text" class="form-control" id="values" name="values[]" placeholder="Values"></div><div class="col-2"><button type="button" class="btn btn-danger btn-sm" id="removeField"><i class="ri-close-line"></i></button></div></div>';
        $("#insert_field").append(html);
    });
    // remove field
    $(document).on("click", "#removeField", function () {
        $(this).closest(".row").remove();
    });

    //delete Event
    $(document).on("click", ".dltBtn", function () {
        let onOk = () => {
            const id = $(this).data("id");
            let url = delete_url;
            url = url.replace(":id", id);
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
