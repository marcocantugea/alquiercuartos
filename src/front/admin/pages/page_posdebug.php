<?php include('./parts/menu.php') ?>
<div class="container text-center mt-3" style="width: 100%;">
    <div>
        <h3>Debug Printer POS</h3>
    </div>
    <div class="m-3 p-3">
        <button class="btn btn-secondary" style="width: 250px;" onclick="AccionDebugCodigoBarras()">Tamaño codigos de barras</button>
    </div>
    <div class="m-3 p-3">
        <button class="btn btn-secondary" style="width: 250px;" onclick="AccionTextSize()">Tamaño de fuentes</button>
    </div>
    <div class="m-3 p-3">
        <button class="btn btn-secondary" style="width: 250px;" onclick="AccionDebugDemo()">Prueba funcional de impresora</button>
    </div>
    
</div>



<?php include('./parts/modals.php') ?>

<script src="./js/config.js"></script>
<script src="./js/login.js"></script>
<script src="./js/debugpos.js"></script>
<script src="./js/modals.js"></script>
<script>
    $(document).ready(()=>{});
</script>