<?php include('./parts/menu.php') ?>

<div class="container text-center mt-3" style="width: 100%;">
    <div>
        <h3>Reporte por dias</h3>
    </div>
    <div class="" style="margin-top: 45px; margin-bottom: 45px;">
        <form id="frm" class="form-inline" style="margin-left: 200px;">
            <div class="form-group" style="margin-right: 15px;">
                <label for="fechaInicio">De:</label>
                <input type="text" id="fechaInicio" class="form-control mx-sm-3 text-center" aria-describedby=""  >
            </div>
            <div class="form-group" style="margin-right: 35px;">
                <label for="fechaFin">Hasta:</label>
                <input type="text" id="fechaFin" class="form-control mx-sm-3 text-center" aria-describedby="" >
            </div>
            <div class="form-group">
                <button class="btn btn-primary btn-lg" >Buscar</button>
            </div>
        </form>
    </div>
    <div class="h3">
        Moto Total:  <span id="lblMontoTotal"></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <!-- Tickets Cobrados: <span id="lblTotalTickets"></span> -->
    </div>
    <div class="">
        <table class="table text-left" id="tblHistorialDiasCobrados"></table>
    </div>
</div>


<?php include('./parts/modals.php') ?>

<script src="./js/config.js"></script>
<script src="./js/login.js"></script>
<script src="./js/modals.js"></script>
<script src="./js/reportes.js"></script>
<script>
    $(document).ready(()=>{
        loadData(); 
        $('#frm').submit(function(e){
            e.preventDefault();
           GenerarReportePorDias();
        }); 
    });

    $('#fechaInicio').datepicker({}).val("<?php $locale=new DateTimeZone('America/Mexico_City'); echo (new DateTime('now',$locale))->format('m/d/Y');?>");

    $('#fechaFin').datepicker({
        minDate: function() {
            return $('#fechaInicio').val();
        }
    }).val("<?php  $locale=new DateTimeZone('America/Mexico_City'); echo (new DateTime('now',$locale))->format('m/d/Y');?>");

</script>