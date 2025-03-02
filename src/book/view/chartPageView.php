<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<h2>THỐNG KÊ</h2>
<div style="display: flex; justify-content: center; align-items: center;">
    <canvas id="myChart" style="max-width: 600px; max-height:
600px;"></canvas>
</div>
<script>
    // Dữ liệu cho biểu đồ
    var data = <?php echo json_encode($data); ?>;
    // Khởi tạo biểu đồ
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    enabled: true,
                }
            }
        }
    });
</script>