<?php include('./parts/menu.php') ?>


<div class="container text-center mt-3">
    <div>
        <span class="h3">Agregar o Editar Usuario</span>
    </div>
    <div>
    <div class="container px-5 my-5" style="width: 500px;">
        <form id="formaCuartos" >
            <div class="form-floating mb-3">
                <input class="form-control" id="txtusuario" type="text" placeholder="Usuario"  require />
                <label for="txtusuario">Usuario</label>
                <div class="invalid-feedback" >Usuario es requerido.</div>
            </div>
            <div class="form-floating mb-3">
                <input class="form-control" id="txtpassword" type="password" placeholder="Contraseña" require />
                <label for="txtpassword">Contraseña</label>
                <div class="invalid-feedback">Contraseña Requerida.</div>
            </div>
            
            <div class="form-floating mb-3">
                <select class="form-select" id="roles" aria-label="Rol">
                </select>
                <label for="roles">Rol</label>
            </div>
            <div class="d-none" >
                <div class="text-center mb-3">
                    <div class="fw-bolder" id="submitSuccessMessage">Datos Guardados Existosamente!</div>
                </div>
            </div>
            <div class="d-none" >
                <div class="text-center text-danger mb-3" id="submitErrorMessage">Error sending message!</div>
            </div>
            <div class="d-grid">
                <input type="hidden" value="<?php echo (isset($_GET["action"])) ? $_GET["action"]  : "new" ;?>" id="action" />
                <input type="hidden" value="<?php echo (isset($_GET["id"])) ? $_GET["id"]  : "" ;?>" id="formIdSelected" />
                <button class="btn btn-primary btn-lg" id="submitButton" type="button" onclick="GuardaInfo()">Guardar</button>
            </div>
        </form>
</div>
    </div>
</div>

<?php include('./parts/modals.php') ?>

<script src="./js/config.js"></script>
<script src="./js/login.js"></script>
<script src="./js/usuarios.js"></script>
<script src="./js/modals.js"></script>
<script>
    $(document).ready(()=>{
        loadRolesSelector();
    });
</script>