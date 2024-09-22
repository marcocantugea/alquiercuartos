<?php include('./parts/menu.php') ?>

<div class="container text-center mt-3" style="width: 100%;">
    <div>
        <h3>Usuarios</h3>
    </div>
    <div class="m-2 p-2">
        <a href="./?p=usuarioform" class="btn btn-primary" >Agregar Usuario</a>
    </div>
    <div class="">
        <table class="table text-left" id="tblUsuarios"></table>
    </div>
</div>


<?php include('./parts/modals.php') ?>

<script src="./js/config.js"></script>
<script src="./js/login.js"></script>
<script src="./js/usuarios.js"></script>
<script src="./js/modals.js"></script>
<script>
    $(document).ready(()=>{
        loadData(); 
    });
</script>