
<?php include('./parts/menu.php') ?>
<div class="container text-center mt-3">
    <div>
        <span class="h3">Agregar o Editar Cuarto</span>
    </div>
    <div>
    <div class="container px-5 my-5" style="width: 500px;">
        <form id="formaCuartos" >
            <div class="form-floating mb-3">
                <input class="form-control" id="codigo" type="text" placeholder="Codigo"  require />
                <label for="codigo">Codigo</label>
                <div class="invalid-feedback" >Codigo es requerido.</div>
            </div>
            <div class="form-floating mb-3">
                <input class="form-control" id="txtdescripcion" type="text" placeholder="Descripcion" require />
                <label for="descripcion">Descripcion</label>
                <div class="invalid-feedback">Descripcion es requerida.</div>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="estado" aria-label="Estado">
                    <option value="1" selected>Activo</option>
                    <option value="3">Inactivo</option>
                </select>
                <label for="estado">Estado</label>
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
                <button class="btn btn-primary btn-lg" id="submitButton" type="button" onclick="GuardaInfoCuarto()">Guardar</button>
            </div>
        </form>
</div>
    </div>
</div>


<?php include('./parts/modals.php') ?>

<script src="./js/config.js"></script>
<script src="./js/login.js"></script>
<script src="./js/cuartos.js"></script>
<script src="./js/modals.js"></script>