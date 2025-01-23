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
                        <td colspan="2" class="text-left">
                            <div id="inputPasswordReimprimir" style="display: none;">
                                <input id="passwordReimprimir" type="password" class="form-cotrol p-2" style="width: 100%;" placeholder="Contraseña" />
                            </div>
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