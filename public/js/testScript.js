
    // Attach click event listeners to each dropdown item
    document.querySelectorAll('.dropdown-item').forEach(function(item) {
        item.addEventListener('click', function() {
            const year = this.getAttribute('data-year');
            fetchSalesData(year);

            // Update the dropdown button text
            document.getElementById('dropdownMenuButton').textContent = year;
        });
    });

    // Function to fetch data based on the selected year
    function fetchSalesData(year) {
        fetch('path_to_controller/TestController.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ 'year': year })
        })
        .then(response => response.json())
        .then(data => {
            const salesData = data.map(item => item.sales_amount);
            const purchaseData = data.map(item => item.purchase_amount);
            const months = data.map(item => item.month); // Extract month names
            
            renderChart(months, salesData, purchaseData);
        })
        .catch(error => console.error('Error fetching sales data:', error));
    }

    // Function to render the chart
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
        var chart = new ApexCharts(document.querySelector("#sales_charts"), options);
        chart.render();
    }

    // Initial load for the default year (2022)
    fetchSalesData(2022);

