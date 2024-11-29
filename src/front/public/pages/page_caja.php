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

<!-- Modal -->
<div class="modal fade bd-example-modal-sm" id="loadingmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content ">

            <div class="modal-body text-center">
                <img src="./images/Loading_2.gif" width="50px">
                Cargando...
            </div>

        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-sm" id="modalIniciaAlquiler" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Desea iniciar el alquiler?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal('modalIniciaAlquiler')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
                <table style="width: 100%;">
                    <tr>
                        <td colspan="2" class="text-left">
                            Descripcion:
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-left">
                            <textarea id="descripcionAlquiler" class="form-control p-2" rows=3 style="width: 100%;" onkeypress="handledescripcionAlquiler(event)"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            <a class="btn btn-danger text-light mt-3" onclick="closeModal('modalIniciaAlquiler')">Cancelar</a>
                        </td>

                        <td class="text-right">
                            <inpu type="hidden" value="" id="iniciaAqluilerIdSelected" />
                            <a class="btn btn-success text-light" onclick="iniciaAlquiler();">Iniciar</a>
                        </td>
                    </tr>
                </table>
            </div>

        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-sm" id="modalConfirmFinAlquiler" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Desea finalizar el Alquiler?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal('modalConfirmFinAlquiler')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
                <table style="width: 100%;">
                    <tr>
                        <td class="text-left">
                            <a class="btn btn-danger text-light mt-3" onclick="closeModal('modalConfirmFinAlquiler')">No</a>
                        </td>

                        <td class="text-right">
                            <inpu type="hidden" value="" id="finAqluilerIdSelected" />
                            <inpu type="hidden" value="" id="cuartoSelected" />
                            <button class="btn btn-success text-light" onkeypress="handleCerrarAlquiler(event)" onclick="CerrarAlquiler();" id="btnCerrarAlquilerSi">Si</button>
                        </td>
                    </tr>
                </table>
            </div>

        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-sm" id="modalConfirmCancelacionParcial" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Desea cancelar parcialmente el Alquiler?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal('modalConfirmCancelacionParcial')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
                <table style="width: 100%;">
                    <tr>
                        <td colspan="2" class="text-left">
                            Motivo:
                            <textarea id="txtMotivoCancelacion" class="form-control p-2" rows=3 style="width: 100%;"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            <a class="btn btn-danger text-light mt-3" onclick="closeModal('modalConfirmCancelacionParcial')">No</a>
                        </td>

                        <td class="text-right">
                            <inpu type="hidden" value="" id="finAqluilerIdSelected" />
                            <inpu type="hidden" value="" id="cuartoSelected" />
                            <button class="btn btn-success text-light" onclick="CancelarAlquilerParcial();" id="btnCerrarAlquilerSi">Si</button>
                        </td>
                    </tr>
                </table>
            </div>

        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-sm" id="modalCorteCaja" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Realizar corte de caja</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal('modalCorteCaja')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
                <table style="width: 100%;">
                    <tr>
                        <td>
                            Fecha Inicio:
                        </td>
                        <td class="text-left">
                            <input class="text-center" type="text" id="corte_fechaInicio" value="">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Fecha Fin:
                        </td>
                        <td class="text-left">
                            <input class="text-center" type="text" id="corte_fechaFin" value="">
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            <a class="btn btn-danger text-light mt-3" onclick="closeModal('modalCorteCaja')">Cancelar</a>
                        </td>

                        <td class="text-right">
                            <button class="btn btn-success text-light" onclick="ObtenerCorteCaja()" id="btnCerrarAlquilerSi">Realizar Corte</button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal" id="modalError" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">   <img src="./images/error.jpg" style="width: 32px;"><span id="error_title"> Error en sistema</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal('modalError')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-left">
                <span class="text-danger" id="error_message">Error en sistema</span>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal" id="modalSuccess" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">   <img src="./images/success.png" style="width: 32px;"><span id="success_title"> Error en sistema</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal('modalSuccess')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-left">
                <span class="" id="success_message">Error en sistema</span>
            </div>
        </div>
    </div>
</div>


<div class="modal fade bd-example-modal-sm" id="modalReimprimirTicket" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Ticket a Reimprimir</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal('modalReimprimirTicket')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
                <table style="width: 100%;">
                    <tr>
                        <td colspan="2" class="text-left">
                            Folio:
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-left">
                            <input id="folioareimprimirtxt" type="number" class="form-cotrol p-2" style="width: 100%;" />
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            <a class="btn btn-danger text-light mt-3" onclick="closeModal('modalReimprimirTicket')">Cancelar</a>
                        </td>

                        <td class="text-right">
                            <inpu type="hidden" value="" id="folioTicketReimprimir" />
                            <a class="btn btn-success text-light" onclick="ReimprimirTicket();">Reimprimir</a>
                        </td>
                    </tr>
                </table>
            </div>

        </div>
    </div>
</div>

<script src="./js/config.js"></script>
<script src="./js/system_config.js"></script>
<script src="./js/caja.js"></script>
<script>
    $(document).ready(async function() {
        //$('#modalSuccess').modal('show');
        $('#cobroDiv').hide();
        loadData();
    })

    $('#corte_fechaInicio').datepicker().val("<?php echo (new DateTime())->format('m/d/Y');?>");

    $('#corte_fechaFin').datepicker({
        minDate: function() {
            return $('#corte_fechaInicio').val();
        }
    }).val("<?php echo (new DateTime())->format('m/d/Y');?>");

    function closeModal(modal) {
        $('#' + modal).modal('hide');
        setTimeout(() => {
            $('#searchFolio').val('');
            $('#searchFolio').focus();
        }, 800);
    }
</script>