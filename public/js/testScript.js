
function renderChart(categories, sales, purchases) {
    var options = {
        series: [
            { name: "Sales", data: sales },
            { name: "Purchase", data: purchases },
        ],
        colors: ["#28C76F", "#EA5455"],
        chart: { type: "bar", height: 300, stacked: true, zoom: { enabled: true } },
        responsive: [{ breakpoint: 280, options: { legend: { position: "bottom", offsetY: 0 } } }],
        plotOptions: { bar: { horizontal: false, columnWidth: "20%", endingShape: "rounded" } },
        xaxis: { categories: categories }, // Categories are now month names
        legend: { position: "right", offsetY: 40 },
        fill: { opacity: 1 },
    };
    var chart = new ApexCharts(document.querySelector("#sales_chartsss"), options);
    chart.render();
}