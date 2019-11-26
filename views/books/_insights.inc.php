<?php


?>
<div class="card mb-4">
    <div class="card-header">
        <?php HtmlHelper::render_card_header("Insights"); ?>
    </div>

    <div class="card-body pb-4 pt-3">

        <div class="row">

            <div class="col-3">

                <div class="alert alert-secondary">
                    <p class="font-weight-bold">Current Month</p>
                    <div class="chart-container" style="position: relative;">
                        <canvas id="books-canvas"></canvas>
                    </div>
                </div>
            </div>


        </div><!--.row-->

    </div>
</div><!--.card-->

<script defer>
    var ctx = document.getElementById('books-canvas');

    let pie_data = {
        datasets: [{
            data: [<?= 136 ?>, 20],
            backgroundColor: [
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)',
            ]
        }],
        labels: [
            'Total Books',
            'Borrowed Books',
        ]
    };

    var myChart = new Chart(ctx, {
        type: 'pie',
        data: pie_data,

    });
</script>