<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container mt-4">
    <h2 class="mb-4 text-center">لوحة التحليلات - Analytics</h2>

    <!-- ===== Filters Form ===== -->
    <div class="card p-3 mb-4">
        <form id="filtersForm" class="row g-3">
            <!-- Brand -->
            <div class="col-md-3">
                <label class="form-label">Brand</label>
                <select name="brand" id="brand" class="form-select">
                    <option value="">All</option>
                    <?php foreach($brands as $b){ 
                        echo '<option value="'.htmlspecialchars($b['id']).'">'.htmlspecialchars($b['name']).'</option>'; 
                    } ?>
                </select>
            </div>

            <!-- Model -->
            <div class="col-md-3">
                <label class="form-label">Model</label>
                <select name="model" id="model" class="form-select">
                    <option value="">All</option>
                    <?php foreach($models as $m){ 
                        echo '<option value="'.htmlspecialchars($m['id']).'">'.htmlspecialchars($m['name']).'</option>'; 
                    } ?>
                </select>
            </div>

            <!-- State -->
            <div class="col-md-3">
                <label class="form-label">State</label>
                <select name="state" id="state" class="form-select">
                    <option value="">All</option>
                    <?php foreach($states as $s){ 
                        echo '<option value="'.htmlspecialchars($s['id']).'">'.htmlspecialchars($s['name']).'</option>'; 
                    } ?>
                </select>
            </div>

            <!-- City -->
            <div class="col-md-3">
                <label class="form-label">City</label>
                <select name="city" id="city" class="form-select">
                    <option value="">All</option>
                    <?php foreach($cities as $c){ 
                        echo '<option value="'.htmlspecialchars($c['id']).'">'.htmlspecialchars($c['name']).'</option>'; 
                    } ?>
                </select>
            </div>

            <!-- Price Range -->
            <div class="col-md-3">
                <label class="form-label">Min Price</label>
                <input type="number" name="min_price" class="form-control" placeholder="0">
            </div>
            <div class="col-md-3">
                <label class="form-label">Max Price</label>
                <input type="number" name="max_price" class="form-control" placeholder="0">
            </div>

            <!-- Date Range -->
            <div class="col-md-3">
                <label class="form-label">From</label>
                <input type="date" name="date_from" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">To</label>
                <input type="date" name="date_to" class="form-control">
            </div>

            <div class="col-12 mt-3">
                <button id="applyFilters" class="btn btn-primary">Apply</button>
                <button id="resetFilters" type="button" class="btn btn-secondary">Reset</button>
            </div>
        </form>
    </div>

    <!-- ===== Results & Charts ===== -->
    <div class="row g-4">
        <div class="col-md-12">
            <div class="card p-3 text-center">
                <h5>Total Posts</h5>
                <h2><span id="totalCount">...</span></h2>
            </div>
        </div>

        <!-- Brand Chart -->
        <div class="col-md-4">
            <div class="card p-3">
                <h5 class="text-center">By Brand</h5>
                <canvas id="brandChart" style="height:300px;"></canvas>
            </div>
        </div>

        <!-- City Chart -->
        <div class="col-md-4">
            <div class="card p-3">
                <h5 class="text-center">By City</h5>
                <canvas id="cityChart" style="height:300px;"></canvas>
            </div>
        </div>

        <!-- Date Chart -->
        <div class="col-md-4">
            <div class="card p-3">
                <h5 class="text-center">By Date</h5>
                <canvas id="dateChart" style="height:300px;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Dependencies -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
jQuery(function($){
    var brandChart, cityChart, dateChart;

    function buildChart(ctx, type, labels, data, labelTxt){
        if(ctx._chart) ctx._chart.destroy();
        ctx._chart = new Chart(ctx, {
            type: type,
            data: {
                labels: labels,
                datasets: [{
                    label: labelTxt,
                    data: data,
                    backgroundColor: [
                        '#007bff','#28a745','#ffc107','#dc3545','#17a2b8','#6f42c1'
                    ],
                    borderWidth: 1,
                    fill: type==='line' ? false : true,
                    tension: 0.3
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
        return ctx._chart;
    }

    function fetchDataAndRender(){
        var params = $('#filtersForm').serialize();
        $('#totalCount').text('Loading...');
        $.getJSON('<?php echo site_url("admin/analytics/ajax_data"); ?>', params, function(res){
            $('#totalCount').text(res.total);

            // brand chart
            var bLabels = res.by_brand.map(x => x.label || 'Unknown');
            var bData = res.by_brand.map(x => parseInt(x.total));
            brandChart = buildChart(document.getElementById('brandChart').getContext('2d'), 'bar', bLabels, bData, 'By Brand');

            // city chart
            var cLabels = res.by_city.map(x => x.label || 'Unknown');
            var cData = res.by_city.map(x => parseInt(x.total));
            cityChart = buildChart(document.getElementById('cityChart').getContext('2d'), 'pie', cLabels, cData, 'By City');

            // date chart (line)
            var dLabels = res.by_date.map(x => x.date);
            var dData = res.by_date.map(x => parseInt(x.total));
            dateChart = buildChart(document.getElementById('dateChart').getContext('2d'), 'line', dLabels, dData, 'By Date');
        }).fail(function(e){
            console.error(e);
            $('#totalCount').text('Error');
        });
    }

    // initial load
    fetchDataAndRender();

    $('#applyFilters').on('click', function(e){
        e.preventDefault(); fetchDataAndRender();
    });

    $('#resetFilters').on('click', function(){
        $('#filtersForm')[0].reset();
        fetchDataAndRender();
    });
});
</script>