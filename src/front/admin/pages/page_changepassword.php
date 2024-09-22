<?php include('./parts/menu.php') ?>

<div class="container text-center mt-3">
    <div>
        <span class="h3">Editar Password</span>
    </div>
    <div>
    <div class="container px-5 my-5" style="width: 500px;">
        <form id="formaCuartos" >
            
            <div class="form-floating mb-3">
                <input class="form-control" id="txtpassword" type="password" placeholder="Nueva Contraseña" require />
                <label for="txtpassword">Nueva Contraseña</label>
                <div class="invalid-feedback">Contraseña Requerida.</div>
            </div>

            <div class="form-floating mb-3">
                <input class="form-control" id="txtConfirmPassword" type="password" placeholder="Confirmar Contraseña" require />
                <label for="txtConfirmPassword">Confirmar Contraseña</label>
                <div class="invalid-feedback">Contraseña Requerida.</div>
            </div>
            
            <div class="d-none" >
                <div class="text-center mb-3">
                    <div class="fw-bolder" id="submitSuccessMessage">Datos Guardados Existosamente!</div>
                </div>
            </div>
            <div class="d-none" id="errorMessageBoard" >
                <div class="text-center text-danger mb-3" id="submitErrorMessage">Error sending message!</div>
            </div>
            <div class="d-grid">
                <input type="hidden" value="<?php echo (isset($_GET["action"])) ? $_GET["action"]  : "new" ;?>" id="action" />
                <input type="hidden" value="<?php echo (isset($_GET["id"])) ? $_GET["id"]  : "" ;?>" id="formIdSelected" />
                <button class="btn btn-primary btn-lg" id="submitButton" type="button" onclick="ActualizarContraseña()">Guardar</button>
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
    $(document).ready(()=>{});
</script>