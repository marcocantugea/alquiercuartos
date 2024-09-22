<?php include('./parts/menu.php') ?>
<div class="container text-center mt-3">
    <div>
        <span class="h3">Agregar o Editar Cuarto</span>
    </div>
    <div>
    <div class="container px-5 my-5" style="width: 500px;">
        <form id="formaCuartos" >
            <div class="form-floating mb-3">
                <input class="form-control" id="txtNombre" type="text" placeholder="Nombre Variable"  require />
                <label for="codigo">Nombre Variable</label>
                <div class="invalid-feedback" >Nombre de variable requerida</div>
            </div>
            <div class="form-floating mb-3">
                <input class="form-control" id="txtValor" type="text" placeholder="Valor Variable" require />
                <label for="descripcion">Valor Variable</label>
                <div class="invalid-feedback">Valor de variable requerida</div>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="SelConfiguracion" aria-label="Estado">
                    <option value="1" selected>Cuartos Configuracion</option>
                    <option value="3">Sistema</option>
                </select>
                <label for="estado">Configuracion para:</label>
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
                <input type="hidden" value="<?php echo (isset($_GET["config"])) ? $_GET["config"]  : "" ;?>" id="configuracion" />
                <button class="btn btn-primary btn-lg" id="submitButton" type="button" onclick="GuardarDatos()">Guardar</button>
            </div>
        </form>
</div>
    </div>
</div>


<?php include('./parts/modals.php') ?>

<script src="./js/config.js"></script>
<script src="./js/login.js"></script>
<script src="./js/configuracion.js"></script>
<script src="./js/modals.js"></script>
<script>
    $(document).ready(()=>{
        loadForm(); 
    });
</script>