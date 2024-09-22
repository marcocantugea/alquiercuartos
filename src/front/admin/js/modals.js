function closeModal(modal) {
    
    setTimeout(() => {
        $('#' + modal).modal('hide');
    }, 600);
}

function showLoading(){
    $('#loadingmodal').modal('show');
}

function closeLoading(){
    closeModal('loadingmodal');
}

function showErrorModal(message){
    $('#modalError').modal('show');
    $('#error_message').empty();
    $('#error_message').append(message);
}

function showConfirmModal(idSelected,action){
    $('#idSelected').val(idSelected);
    $('#action').val(action);
    $('#modalConfirm').modal('show');
}

function closeConfirmModal(){
    closeModal('modalConfirm');
}