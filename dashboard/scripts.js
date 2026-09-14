$(document).ready(function() {
    // Function to fetch data and update the dashboard
    function fetchDashboardData(startDate, endDate) {
        $.ajax({
            url: 'dashboardController.php',
            method: 'POST',
            data: {
                action: "fetchDashboardData",
                startDate: startDate,
                endDate: endDate
            },
            success: function(response) {
                //console.log(response);
                // Assuming the response is a JSON object with data for the dashboard
                updateDashboard(response);
            },
            error: function(error) {
                console.error('Error fetching dashboard data:', error);
                // Handle error, possibly show a message to the user
            }
        });
    }

    let salesChartInstance = null;
    let statusChartInstance = null;

    // Function to update the dashboard with the fetched data
    function updateDashboard(data) {
        try {
            // Parse JSON if data is a string
            if (typeof data === 'string') {
                data = JSON.parse(data);
            }
            //console.log("response from server"+data[0].newJobs);
            $('#newjobscount').text(data.newJobs);
            $('#newjobvalue').text(data.newJobsValue); 
            
            $('#pendingcount').text(data.pendingJobs);
            $('#pendingvalue').text(data.pendingvalue); 
            
            $('#readycount').text(data.readyJobs);
            $('#readyvalue').text(data.readyvalue); 
            
            $('#delvcount').text(data.deliveredJobs);
            $('#delvvalue').text(data.delvvalue); 
            
            $('#cash').text(data.cash);
            $('#card').text(data.card);
            $('#cheque').text(data.cheque);
            $('#wallet').text(data.wallet);
            
            $('#creditcount').text(data.creditJobs);
            $('#creditvalue').text(data.creditJobsValue); 

            // Render Charts if data is available
            if (data.chartData) {
                renderCharts(data.chartData);
            }
        } catch (error) {
            console.error('Error updating dashboard:', error);
        }
    }

    function renderCharts(chartData) {
        // Sales Trend Chart
        const salesCtx = document.getElementById('salesTrendChart').getContext('2d');
        if (salesChartInstance) {
            salesChartInstance.destroy();
        }
        salesChartInstance = new Chart(salesCtx, {
            type: 'bar',
            data: {
                labels: chartData.salesTrend.labels,
                datasets: [{
                    label: 'Revenue',
                    data: chartData.salesTrend.data,
                    backgroundColor: '#F58220',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [2, 4] } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Status Distribution Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        if (statusChartInstance) {
            statusChartInstance.destroy();
        }
        statusChartInstance = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: chartData.statusDistribution.labels,
                datasets: [{
                    data: chartData.statusDistribution.data,
                    backgroundColor: ['#3E6FD9', '#1E9E6B', '#F58220'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } }
                }
            }
        });
    }


    // Set default dates to today if not provided
    function getDefaultDates() {
        let today = new Date().toISOString().split('T')[0];
        return {
            startDate: today,
            endDate: today
        };
    }

    // Fetch data on page load with default dates
    let defaultDates = getDefaultDates();
    fetchDashboardData(defaultDates.startDate, defaultDates.endDate);

    // Fetch data on form submission
    $('#frmsearch').submit(function(event) {
        event.preventDefault();
        let startDate = $('input[name="fdate"]').val();
        let endDate = $('input[name="tdate"]').val();
        fetchDashboardData(startDate, endDate);
    });
});
