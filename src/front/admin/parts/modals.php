<!-- loading modal -->
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

<div class="modal fade bd-example-modal-sm" id="modalConfirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Desea eliminar el registro?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal('modalConfirm')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
                <table style="width: 100%;">
                    <tr>
                        <td class="text-left">
                            <a class="btn btn-danger text-light mt-3" onclick="closeModal('modalConfirm')">No</a>
                        </td>

                        <td class="text-right">
                            <inpu type="hidden" value="" id="idSelected" />
                            <inpu type="hidden" value="" id="action" />
                            <button class="btn btn-success text-light" onclick="ConfirmModalSi();" id="btnCerrarAlquilerSi">Si</button>
                        </td>
                    </tr>
                </table>
            </div>

        </div>
    </div>
</div>