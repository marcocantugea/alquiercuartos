<?php include('./parts/menu.php') ?>

<div class="container text-center mt-3" style="width: 100%;">
    <div>
        <h3>Configuraciones de Cuartos</h3>
    </div>
    <div>
        <!-- <a href="./?p=configuracionform" class="btn btn-primary m-4" >Agregar nueva configuracion</a> -->
    </div>
    <div class="">
        <table class="table text-left" id="tblConfiguracionCuartos"></table>
    </div>
</div>


<?php include('./parts/modals.php') ?>

<script src="./js/config.js"></script>
<script src="./js/login.js"></script>
<script src="./js/configuracion.js"></script>
<script src="./js/modals.js"></script>

<script>
    $(document).ready(()=>{
        loadData(); 
    });
</script>