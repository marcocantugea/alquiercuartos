<?php include('./parts/menu.php') ?>

<div class="container text-center mt-3" style="width: 100%;">
    <div>
        <h3>Configuracion de sistema</h3>
    </div>

    <div>
        <!-- <a href="./?p=configuracionform" class="btn btn-primary m-4" >Agregar nueva configuracion</a> -->
    </div>
   
    <div class="">
        <table class="table text-left" id="tblConfiguracionSistema"></table>
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