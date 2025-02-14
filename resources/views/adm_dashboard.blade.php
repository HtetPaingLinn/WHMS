<x-adm-dsh-nav>
    <div class="container mx-auto p-6">
        <!-- Welcome Message Section -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-6 rounded-lg shadow-md text-center text-black">
            <h1 class="text-3xl font-bold">Welcome to the Admin Dashboard</h1>
            <p class="text-sm mt-2">Manage your models, customers, service centers, warehouse branches, and reports with ease.</p>
        </div>

        <!-- Cards Section -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 my-6 mx-auto">
            @foreach ([
                ['title' => 'Models', 'icon' => 'fas fa-cogs', 'count' => 'models-count', 'bg' => 'bg-blue-500'],
                ['title' => 'Customers', 'icon' => 'fas fa-users', 'count' => 'customers-count', 'bg' => 'bg-green-500'],
                ['title' => 'Service Centers', 'icon' => 'fas fa-tools', 'count' => 'service-centers-count', 'bg' => 'bg-yellow-500'],
                ['title' => 'Branches', 'icon' => 'fas fa-warehouse', 'count' => 'warehouse-branches-count', 'bg' => 'bg-red-500']
            ] as $card)
                <div class="p-4 rounded-md shadow-md {{ $card['bg'] }} text-white text-center transform transition-transform duration-300 ease-in-out hover:scale-105 hover:shadow-lg cursor-pointer">
                    <i class="{{ $card['icon'] }} text-3xl"></i>
                    <h3 class="text-lg font-semibold mt-1">{{ $card['title'] }}</h3>
                    <p class="text-2xl font-bold mt-1" id="{{ $card['count'] }}">Loading...</p>
                </div>
            @endforeach
        </div>

        <div class="container mx-auto p-4">

            <div class="grid grid-cols-1 gap-6">
                <div class="flex flex-wrap bg-white p-4 rounded-lg shadow-md transition-transform transform hover:scale-105">
                    <!-- First Chart Container (Record vs Prediction) -->
                    <div class="w-full md:w-1/2 p-2">
                        <div class="chart-container w-full mt-10">
                            <div id="container" class="w-full h-[350px] rounded-lg shadow-inner bg-gray-100"></div>
                        </div>
                    </div>

                    <!-- Second Chart Container (Customer Shops) -->
                    <div class="w-full md:w-1/2 p-2">
                        <div class="chart-container w-full mt-10">
                            <div id="myChart2" class="w-full h-[350px] rounded-lg shadow-inner bg-gray-100"></div>
                        </div>
                    </div>
                </div>

                <!-- Hourly Chart -->
                <div class="bg-white p-4 rounded-lg shadow-md transition-transform transform hover:scale-105">
                    <div id="salesContainer" class="w-full h-[400px] mt-4 rounded-lg shadow-inner bg-gray-100"></div>
                </div>

                <!-- Your Monthly Sales Chart -->
                <div class="bg-white p-4 rounded-lg shadow-md transition-transform transform hover:scale-105">
                    <div id="monthSalesContainer" class="w-full h-[400px] mt-4 rounded-lg shadow-inner bg-gray-100"></div>
                </div>

                <!-- Daily Sales Line Chart -->
                <div class="bg-white p-4 rounded-lg shadow-md transition-transform transform hover:scale-105">
                    <div id="dailySalesContainer" class="w-full h-[400px] mt-4 rounded-lg shadow-inner bg-gray-100"></div>
                </div>
            </div>
        </div>

        <div class="mt-8 bg-gray-50 p-4 rounded-xl shadow-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Stock Report Download -->
                <div class="text-center border border-gray-200 p-4 rounded-lg bg-white">
                    <h2 class="text-lg font-bold text-indigo-700 mb-2">Stock Report</h2>
                    <p class="text-sm text-gray-500 mb-4">Download the latest stock report in CSV format.</p>
                    <form action="{{ route('stock.downloadCSV') }}" method="get">
                        @csrf
                        <div class="flex flex-col md:flex-row justify-center gap-2 mb-4">
                            <input type="date" name="start_date" class="p-2 text-sm border border-gray-300 rounded-md focus:ring focus:ring-indigo-200">
                            <input type="date" name="end_date" class="p-2 text-sm border border-gray-300 rounded-md focus:ring focus:ring-indigo-200">
                        </div>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md shadow-md hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">Download CSV</button>
                    </form>
                </div>

                <!-- View Stock Report Analyzer -->
                <div class="flex flex-col items-center justify-center border border-gray-200 p-4 rounded-lg bg-white h-full">
                    <h2 class="text-lg font-bold text-indigo-700 mb-2">Report Analyzer</h2>
                    <p class="text-sm text-gray-500 mb-2 text-center">Quickly analyze all stock data with a single click.</p>
                    <button type="submit" id="openModalBtn" class="px-4 py-2 mt-4 bg-indigo-600 text-white text-sm rounded-md shadow-md hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                        View Analyzer
                    </button>
                </div>

            </div>
        </div>

        <!-- Modal Structure -->
        <div id="modal" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden flex justify-center items-center z-[70]">
            <div class="bg-white rounded-lg w-full max-w-4xl p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Stock Analyzer</h2>
                    <button id="closeModalBtn" class="text-gray-500 hover:text-gray-700 text-lg">&times;</button>
                </div>
                <div class="w-full h-[600px] rounded-lg shadow-lg overflow-hidden">
                    <iframe src="https://myantech-stock-analyzer.streamlit.app/?embedded=true"
                            class="w-full h-full border-0 rounded-lg">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>
</x-adm-dsh-nav>

<!-- Script to handle modal behavior -->
<script>
    const openModalBtn = document.getElementById('openModalBtn');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const modal = document.getElementById('modal');

    openModalBtn.addEventListener('click', () => {
        modal.classList.remove('hidden');
    });

    closeModalBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    // Close the modal if clicked outside
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.classList.add('hidden');
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    window.onload = function() {
        // Get elements where data will be inserted
        const modelsCount = document.getElementById('models-count');
        const customersCount = document.getElementById('customers-count');
        const serviceCentersCount = document.getElementById('service-centers-count');
        const warehouseBranchesCount = document.getElementById('warehouse-branches-count');

        // Fetch real data from the backend
        fetch('/get-stock-data')  // Ensure this matches the route you defined in routes/web.php
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Set data and animate counter numbers
                animateCounter(modelsCount, 0, data.modelsCount, 3000);
                animateCounter(customersCount, 0, data.customersCount, 3000);
                animateCounter(serviceCentersCount, 0, data.serviceCentersCount, 3000);
                animateCounter(warehouseBranchesCount, 0, data.warehouseBranchesCount, 3000);
            })
            .catch(error => {
                console.error('Error fetching stock data:', error);
                modelsCount.textContent = 'Error loading data';
                customersCount.textContent = 'Error loading data';
                serviceCentersCount.textContent = 'Error loading data';
                warehouseBranchesCount.textContent = 'Error loading data';
            });
    };

    // Counter Animation for the numbers
    function animateCounter(element, start, end, duration) {
        let current = start;
        const stepTime = Math.abs(Math.floor(duration / (end - start)));
        const timer = setInterval(function () {
            current += 1;
            element.textContent = current;
            if (current == end) {
                clearInterval(timer);
            }
        }, stepTime);
    }
</script>

<!-- Highcharts Script -->
<script src="https://code.highcharts.com/highcharts.js"></script>

<script>
    // Pass PHP data to JavaScript for Chart 1
    // Pass PHP data to JavaScript
    const labels = @json($labels);
    const salesData = @json($data);
    const predictionData = @json($predictions); // Now this will contain the Flask predictions

    console.log(labels, salesData, predictionData); // Debugging: Check if data is correct

    // Chart.js Integration
    Highcharts.chart('container', {
        chart: {
            type: 'spline',
            backgroundColor: 'rgba(255, 255, 255, 0.8)',
            borderRadius: 8,
            borderWidth: 0,
            plotBackgroundColor: 'rgba(255, 255, 255, 0.9)',
            plotBorderColor: '#fff',
            plotBorderWidth: 2,
            events: {
                render: function () {
                    // Apply a smooth hover effect on chart elements
                    const chart = this;
                    const elements = chart.series[0].points;
                    elements.forEach((el) => {
                        el.graphic.element.style.transition = 'all 0.3s ease';
                        el.graphic.element.addEventListener('mouseover', function () {
                            el.graphic.element.style.transform = 'scale(1.1)';
                        });
                        el.graphic.element.addEventListener('mouseout', function () {
                            el.graphic.element.style.transform = 'scale(1)';
                        });
                    });
                }
            }
        },
        title: {
            text: 'Yearly Sales',
        },
        subtitle: {
            text: 'Comparison Between Last Year & This Year',
        },
        xAxis: {
            categories: @json($labels),
            labels: {
                style: {
                    fontSize: '12px',
                    color: '#555'
                }
            },
            gridLineWidth: 1,
            gridLineColor: '#eaeaea'
        },
        yAxis: {
            title: {
                text: 'Sales (MMK)',
                style: {
                    color: '#333',
                    fontSize: '14px'
                }
            },
            labels: {
                style: {
                    fontSize: '12px',
                    color: '#555'
                }
            },
            gridLineWidth: 1,
            gridLineColor: '#eaeaea'
        },
        tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.7)',
            borderColor: '#fff',
            borderWidth: 1,
            style: {
                color: '#fff',
                fontSize: '12px',
                fontWeight: 'bold'
            },
            pointFormat: '{series.name}: <b>{point.y:,.0f}</b>',
            shared: true
        },
        plotOptions: {
            series: {
                marker: {
                    radius: 5,
                    lineColor: '#fff',
                    lineWidth: 2
                },
                states: {
                    hover: {
                        marker: {
                            radius: 7
                        }
                    }
                }
            },
            line: {
                lineWidth: 3,
                shadow: true
            },
            area: {
                fillOpacity: 0.2
            }
        },
        series: [
            {
                name: 'Monthly Sales',
                data: @json($data),
                color: '#36A2EB', // Blue color for the line
                fillOpacity: 0.2, // Shading under the line
                zIndex: 1, // Ensure shading is under the line
                type: 'area', // Enable area chart for shading
            },
            {
                name: 'Predicted Sales',
                data: @json($predictions).map(item => item.prediction),
                color: '#FF6384', // Red color for the line
                dashStyle: 'Dash',
                fillOpacity: 0.2, // Shading under the line
                zIndex: 1, // Ensure shading is under the line
                type: 'area', // Enable area chart for shading
            }
        ]
    });
</script>

<script>
    const dataPrevMonth = {
        labels: @json($shopLabelsPrevMonth), // Shop names for the previous month
        data: @json($shopDataValuesPrevMonth) // Quantities sold by each shop in the previous month
    };

    const dataCurrMonth = {
        labels: @json($shopLabelsCurrMonth), // Shop names for the current month
        data: @json($shopDataValuesCurrMonth) // Quantities sold by each shop in the current month
    };

    const getChartData = (labels, data, color) => {
        return labels.map((label, index) => ({
            name: label,
            y: data[index],
            color: color
        }));
    };

    const chart = Highcharts.chart('myChart2', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Shop-based Sales Comparison'
        },
        subtitle: {
            text: 'Comparison of sales between the previous month and the current month'
        },
        xAxis: {
            categories: dataCurrMonth.labels,
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Buying Count'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Previous Month',
            data: getChartData(dataPrevMonth.labels, dataPrevMonth.data, 'rgba(75, 192, 192, 0.6)')
        }, {
            name: 'Current Month',
            data: getChartData(dataCurrMonth.labels, dataCurrMonth.data, 'rgba(54, 162, 235, 0.6)')
        }]
    });
</script>

<script>
    window.onload = function() {
        // Get elements where data will be inserted
        const modelsCount = document.getElementById('models-count');
        const customersCount = document.getElementById('customers-count');
        const serviceCentersCount = document.getElementById('service-centers-count');
        const warehouseBranchesCount = document.getElementById('warehouse-branches-count');

        // Fetch real data from the backend
        fetch('/get-stock-data')  // Ensure this matches the route you defined in routes/web.php
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Set data and animate counter numbers
                animateCounter(modelsCount, 0, data.modelsCount, 3000);
                animateCounter(customersCount, 0, data.customersCount, 3000);
                animateCounter(serviceCentersCount, 0, data.serviceCentersCount, 3000);
                animateCounter(warehouseBranchesCount, 0, data.warehouseBranchesCount, 3000);
            })
            .catch(error => {
                console.error('Error fetching stock data:', error);
                modelsCount.textContent = 'Error loading data';
                customersCount.textContent = 'Error loading data';
                serviceCentersCount.textContent = 'Error loading data';
                warehouseBranchesCount.textContent = 'Error loading data';
            });
    };

    // Counter Animation for the numbers
    function animateCounter(element, start, end, duration) {
        let current = start;
        const stepTime = Math.abs(Math.floor(duration / (end - start)));
        const timer = setInterval(function () {
            current += 1;
            element.textContent = current;
            if (current == end) {
                clearInterval(timer);
            }
        }, stepTime);
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var salesHours = @json($salesHours);
        var salesValues = @json($salesValues);
        var maxSale = @json($maxSale);
        var maxSaleHour = @json($maxSaleHour);

        // Convert sales data to Highcharts format
        var salesData = salesHours.map((hour, index) => ({
            x: parseInt(hour),
            y: salesValues[index]
        }));

        // Find index of peak sales hour
        var peakIndex = salesHours.indexOf(maxSaleHour);

        Highcharts.chart('salesContainer', {
            chart: {
                type: 'area',
                zooming: {
                    type: 'x'
                },
                panning: true,
                panKey: 'shift',
                scrollablePlotArea: {
                    minWidth: 600
                }
            },

            title: {
                text: 'Hourly Chart'
            },
            subtitle: {
                text: 'Hourly Sales Trend for Today',
            },
            xAxis: {
                categories: salesHours.map(h => h + ':00'),
                title: {
                    text: 'Time (Hours)'
                }
            },

            yAxis: {
                title: {
                    text: 'Sales (MMK)'
                },
                labels: {
                    format: '{value} MMK'
                }
            },

            tooltip: {
                headerFormat: '<b>Hour: {point.key}</b><br>',
                pointFormat: 'Sales: {point.y} MMK'
            },

            series: [{
                name: 'Sales',
                data: salesData,
                lineColor: '#FF5733', // Highlight line color
                color: 'rgba(255, 87, 51, 0.5)', // Transparent fill
                fillOpacity: 0.5,
                marker: {
                    enabled: true,
                    symbol: 'circle'
                },
                threshold: null
            }],

            annotations: [{
                draggable: '',
                labelOptions: {
                    backgroundColor: 'rgba(255,255,255,0.8)',
                    verticalAlign: 'top',
                    y: 10
                },
                labels: [{
                    point: {
                        xAxis: 0,
                        yAxis: 0,
                        x: peakIndex,
                        y: maxSale
                    },
                    text: '📍 Peak Hour: ' + maxSaleHour + ':00 (' + maxSale.toLocaleString() + ' MMK)'
                }]
            }]
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Data passed from controller
        var months = @json($months); // Months like "Jan", "Feb", etc.
        var salesValues = @json($monthlySales); // Monthly sales values
        var maxSale = @json($maxSale); // Maximum sale value
        var maxSaleMonth = @json($maxSaleMonth); // Peak month

        // Convert sales data to Highcharts format
        var salesData = months.map((month, index) => ({
            x: index,
            y: salesValues[index]
        }));

        // Find index of peak sales month
        var peakIndex = months.indexOf(maxSaleMonth);

        Highcharts.chart('monthSalesContainer', {
            chart: {
                type: 'column',
                zooming: {
                    type: 'x'
                },
                panning: true,
                panKey: 'shift',
                scrollablePlotArea: {
                    minWidth: 600
                }
            },

            title: {
                text: 'Monthly Sales Chart'
            },
            subtitle: {
                text: 'Monthly Sales Trend for 2024',
            },
            xAxis: {
                categories: months, // Using months like "Jan", "Feb", etc.
                title: {
                    text: 'Month'
                }
            },

            yAxis: {
                title: {
                    text: 'Sales (MMK)'
                },
                labels: {
                    format: '{value} MMK'
                }
            },

            tooltip: {
                headerFormat: '<b>Month: {point.key}</b><br>',
                pointFormat: 'Sales: {point.y} MMK'
            },

            series: [{
                name: 'Sales',
                data: salesData,
                color: '#028391', // Highlight column color
                fillOpacity: 0.5,
                marker: {
                    enabled: true,
                    symbol: 'circle'
                },
                threshold: null
            }],

            annotations: [{
                draggable: '',
                labelOptions: {
                    backgroundColor: 'rgba(255,255,255,0.8)',
                    verticalAlign: 'top',
                    y: 10
                },
                labels: [{
                    point: {
                        xAxis: 0,
                        yAxis: 0,
                        x: peakIndex,
                        y: maxSale
                    },
                    text: '📍 Peak Month: ' + maxSaleMonth + ' (' + maxSale.toLocaleString() + ' MMK)'
                }]
            }]
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var days = @json($days); // Days in the month
        var salesValues = @json($dailySales); // Daily sales values
        var maxSale = @json($maxSale); // Maximum sale value
        var maxSaleDay = @json($maxSaleDay); // Peak day

        // Ensure all 31 days are represented (or adjust for the correct month days)
        var allSalesData = [];
        for (var i = 1; i <= 31; i++) {
            var day = i.toString().padStart(2, '0'); // Format day as '01', '02', etc.
            var salesIndex = days.indexOf(day);

            // If sales data for the day exists, use it; otherwise, use 0
            allSalesData.push({
                x: i - 1, // x-axis position
                y: salesIndex !== -1 ? salesValues[salesIndex] : 0 // Sales or 0 if no data
            });
        }

        // Find index of peak sales day
        var peakIndex = days.indexOf(maxSaleDay);

        Highcharts.chart('dailySalesContainer', {
            chart: {
                type: 'area', // Change type to 'area' for shaded region
                zooming: {
                    type: 'x'
                },
                panning: true,
                panKey: 'shift',
                scrollablePlotArea: {
                    minWidth: 600
                }
            },

            title: {
                text: 'Daily Sales Chart'
            },
            subtitle: {
                text: 'Sales Trend for January 2024',
            },
            xAxis: {
                categories: Array.from({ length: 31 }, (_, i) => (i + 1).toString().padStart(2, '0')), // Days of the month
                title: {
                    text: 'Day of the Month'
                }
            },

            yAxis: {
                title: {
                    text: 'Sales (MMK)'
                },
                labels: {
                    format: '{value} MMK'
                }
            },

            tooltip: {
                headerFormat: '<b>Day: {point.key}</b><br>',
                pointFormat: 'Sales: {point.y} MMK'
            },

            series: [{
                name: 'Sales',
                data: allSalesData,
                color: '#08314a', // Line color
                fillColor: 'rgba(51, 161, 255, 0.2)', // Shaded area color (light blue)
                marker: {
                    enabled: true,
                    symbol: 'circle'
                },
                lineWidth: 2
            }],

            annotations: [{
                draggable: '',
                labelOptions: {
                    backgroundColor: 'rgba(255,255,255,0.8)',
                    verticalAlign: 'top',
                    y: 10
                },
                labels: [{
                    point: {
                        xAxis: 0,
                        yAxis: 0,
                        x: peakIndex,
                        y: maxSale
                    },
                    text: '📍 Peak Day: ' + maxSaleDay + ' (' + maxSale.toLocaleString() + ' MMK)'
                }]
            }]
        });
    });
</script>
