<?php include('./parts/menu.php') ?>
<div class="container text-center mt-3" style="width: 100%;">
    <div>
        <h3>Administraci&oacute;n de Espacio</h3>
    </div>
    <div>
        <a href="./?p=cuartosform" class="btn btn-primary m-4" >Agregar nuevo espacio</a>
        <button class="btn btn-primary m-4" onclick="agregar300cuartos()" > Agregar 300 espacios </button>
        <button class="btn btn-primary m-4" onclick="restaurarEspacios()" > Restaurar Espacios </button>
    </div>
    <div class="">
        <table class="table" id="tblCuartos"></table>
    </div>
</div>

<?php include('./parts/modals.php') ?>

<script src="./js/config.js"></script>
<script src="./js/login.js"></script>
<script src="./js/cuartos.js"></script>
<script src="./js/modals.js"></script>