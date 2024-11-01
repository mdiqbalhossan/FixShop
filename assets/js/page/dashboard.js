(function () {
    "use strict";

    dragula([
        document.querySelector("#dragable_card"),
        document.querySelector("#dragable_card1"),
        document.querySelector("#dragable_card2"),
        document.querySelector("#dragable_card3"),
        document.querySelector("#dragable_card4"),
    ]);
})();
let purchase_date = $("input[name='purchase_date']").val();
let sale_date = $("input[name='sale_date']").val();
let category_date = $("input[name='category_date']").val();
let manage_widget = $("input[name='manage_widget']").val();
let save_exit = $("input[name='save_exit']").val();
let currency_symbol = $("input[name='currency_symbol']").val();
let options = {
    series: [
        {
            name: "Purchase",
            data: JSON.parse(purchase_date),
        },
        {
            name: "Sale",
            data: JSON.parse(sale_date),
        },
    ],
    chart: {
        height: 320,
        type: "line",
        dropShadow: {
            enabled: true,
            color: "#000",
            top: 18,
            left: 7,
            blur: 10,
            opacity: 0.2,
        },
        toolbar: {
            show: false,
        },
    },
    colors: ["#0162e8", "#00b9ff"],
    dataLabels: {
        enabled: true,
    },
    stroke: {
        curve: "smooth",
    },
    title: {
        text: "Daily Sales & Purchase Report",
        align: "left",
        style: {
            fontSize: "13px",
            fontWeight: "bold",
            color: "#8c9097",
        },
    },
    grid: {
        borderColor: "#f2f5f7",
    },
    markers: {
        size: 1,
    },
    xaxis: {
        categories: JSON.parse(category_date),
        title: {
            text: "Days",
            fontSize: "13px",
            fontWeight: "bold",
            style: {
                color: "#8c9097",
            },
        },
        labels: {
            show: true,
            style: {
                colors: "#8c9097",
                fontSize: "11px",
                fontWeight: 600,
                cssClass: "apexcharts-xaxis-label",
            },
        },
    },
    yaxis: {
        title: {
            text: `${currency_symbol}`,
            fontSize: "13px",
            fontWeight: "bold",
            style: {
                color: "#8c9097",
            },
        },
        labels: {
            show: true,
            style: {
                colors: "#8c9097",
                fontSize: "11px",
                fontWeight: 600,
                cssClass: "apexcharts-yaxis-label",
            },
        },
    },
    legend: {
        position: "top",
        horizontalAlign: "right",
        floating: true,
        offsetY: -25,
        offsetX: -5,
    },
};
let chart = new ApexCharts(
    document.querySelector("#line-chart-datalabels"),
    options
);
chart.render();

/*Widget Handle Modal*/
$("#manageWidget").on("click", function () {
    $("#title").html(manage_widget);
    $("#submitBtn").html(save_exit);
});
