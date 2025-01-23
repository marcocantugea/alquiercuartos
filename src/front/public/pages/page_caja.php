<div style=" position: absolute;top: 0; right: 15px; bottom: 0; left: 0;">
    <div class="row p-3">
        <!-- aqui van los cuartos rentados o para alquilar -->
        <div class="col-sm-9">
            <div class="row p-2" id="cuartosContent">

            </div>
        </div>
        <div class="col-sm-3">
            <div>
                <input id="searchFolio" class="form from-control bg bg-primary text-light text-center" style="width: 100%;" onkeypress="handle(event)">
            </div>
            <div class="mt-2">
                <div class="card" style="height:500px">
                    <h5 class="card-header">Cobro</h5>
                    <div class="card-body text-center" id="cobroDiv">
                        <h5 class="card-title  text-left" id="cobro_title">Cuarto - 54 Descripcion</h5>
                        <table style="width: 100%;">
                            <tr>
                                <td class="text-left">Folio :</td>
                                <td class="text-right"><strong><span id="cobro_folio"> 1550 - kksjhfskdjfh</span> </strong></td>
                            </tr>
                            <tr>
                                <td class="text-left">Entrada :</td>
                                <td class="text-right"> <strong><span id="cobro_fechaInicio">2023-08-01 05:04</span></strong></td>
                            </tr>
                            <tr>
                                <td class="text-left">Salida :</td>
                                <td class="text-right"><strong><span id="cobro_fechaSalida">2023-08-01 05:19</span></strong></td>
                            </tr>
                            <tr>
                                <td class="text-left">Total de Minutos :</td>
                                <td class="text-right"><strong><span id="cobro_totalmin">15 minutos</span></strong></td>
                            </tr>
                            <tr>
                                <td class="text-left">Cobro :</td>
                                <td class="text-right"><strong>$ <span id="cobro_monto">50.00</span></strong></td>
                            </tr>
                            <tr>
                                <td class="text-left">Extra :</td>
                                <td class="text-right"><strong>$ <span id="cobro_montoExtra">50.00</span></strong></td>
                            </tr>
                        </table>
                        <br />
                        <p class="h4"> Total a Pagar</p>
                        <p class="h2 text-success"><strong>$ <span id="cobro_totalmonto">100.00</span> </strong></p>
                        <br />
                        <input type="hidden" id="cobro_alquilerId" value="" />
                        <input type="hidden" id="cobto_fechaSalida" value="" />
                        <button id="btnCobrar" class="btn btn-primary" style="width: 65%;" onkeypress="handleCobrar(event)" onclick="CobrarAlquiler()">Cobrar</button>

                        <br />
                        <br />
                        <!-- <button href="#" class="btn btn-danger" style="width: 65%;" onclick="ConfirmaCancelacionParcial()">Cancelacion Parcial</button> -->
                    </div>
                </div>
                <div class="text-center m-3">
                    <button class="btn btn-secondary" style="width: 65%;" onclick="ConfirmaCorteCaja()">Corte Caja</button>
                    <br />
                    <br />
                    <button class="btn btn-primary" style="width: 65%;" onclick="ShowReimprimirTicketModal()">Reimprimir Ticket</button>
                    <br />
                    <br />
                    <a href="#" class="btn btn-secondary " style="width: 65%;" onclick="cerrarSession()">Cerrar Session</a>
                </div>
            </div>
        </div>
    </div>

</div>


<?php include('./parts/modals.php') ?>

<script src="./js/config.js"></script>
<script src="./js/system_config.js"></script>
<script src="./js/caja.js"></script>
<script>
    $(document).ready(async function() {
        //$('#modalSuccess').modal('show');
        $('#cobroDiv').hide();
        loadData();
    })

    $('#corte_fechaInicio').datepicker().val("<?php $timezone= new DateTimeZone('America/Mexico_City'); echo (new DateTime('now',$timezone))->format('m/d/Y');?>");

    $('#corte_fechaFin').datepicker({
        minDate: function() {
            return $('#corte_fechaInicio').val();
        }
    }).val("<?php $timezone= new DateTimeZone('America/Mexico_City'); echo (new DateTime('now',$timezone))->format('m/d/Y');?>");

    function closeModal(modal) {
        $('#' + modal).modal('hide');
        setTimeout(() => {
            $('#searchFolio').val('');
            $('#searchFolio').focus();
        }, 800);
    }
</script>