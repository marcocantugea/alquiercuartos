<?php include('./parts/menu.php') ?>
<div class="container text-center">
    <div>
    <h3 class="p-3">Acumulado de Monto Anual</h3>
    <canvas id="myChart"></canvas>
    </div>
</div>



<?php include('./parts/modals.php') ?>


<script src="./js/config.js"></script>
<script src="./js/login.js"></script>
<script src="./js/modals.js"></script>
<script src="./js/charts.js"></script>

<script>
    $(document).ready(()=>{
        loadChart();
    });
</script>