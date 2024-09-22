
async function listUsuarios() {

    try {
        var response= await fetch(apiHost+apiPath+"usuario",{
            method:'get',
            headers:headersRequest
        })
        
        if(response.ok){
            const content= await response.json()
            return content;
        }else{
            return;
        }

    } catch (error) {
       console.log(error);
    }   
}

async function getRoles() {
    try {
        var response= await fetch(apiHost+apiPath+"usuarios/roles",{
            method:'get',
            headers:headersRequest
        })
        
        if(response.ok){
            const content= await response.json()
            return content;
        }else{
            return;
        }

    } catch (error) {
       console.log(error);
    }   
}

async function addUsuario(usuario,password,rolId){
    try {

        let request={
            usuario:usuario,
            password:password,
            roleId:rolId
        }

        var response= await fetch(apiHost+apiPath+"usuario",{
            method:'post',
            headers:headersRequest,
            body:JSON.stringify(request)
        })
        
        if(response.ok){
            return true;
        }else{
            return false;
        }

    } catch (error) {
        console.log(error);
    }
}

async function ActivateUsuario(usuarioId){
    try {

        var response= await fetch(apiHost+apiPath+"usuario/"+usuarioId+"/activar",{
            method:'post',
            headers:headersRequest
        })
        
        if(response.ok){
            return true;
        }else{
            return false;
        }

    } catch (error) {
        console.log(error);
    }
}


async function DeactivateUsuario(usuarioId){
    try {

        var response= await fetch(apiHost+apiPath+"usuario/"+usuarioId+"/desactivar",{
            method:'post',
            headers:headersRequest
        })
        
        if(response.ok){
            return true;
        }else{
            return false;
        }

    } catch (error) {
        console.log(error);
    }
}

async function DeleteUsuario(usuarioId) {
    try {

        var response= await fetch(apiHost+apiPath+"usuario/"+usuarioId,{
            method:'delete',
            headers:headersRequest
        })
        
        if(response.ok){
            return true;
        }else{
            return false;
        }

    } catch (error) {
        console.log(error);
    }
}

async function updatePassword(usuarioId,password){
    try {

        const request={
            password:password
        }

        var response= await fetch(apiHost+apiPath+"usuario/"+usuarioId+"/password",{
            method:'put',
            headers:headersRequest,
            body:JSON.stringify(request)
        })
        
        if(response.ok){
            return true;
        }else{
            return false;
        }

    } catch (error) {
        console.log(error);
    }
}

function loadTableUsers() {
    $('#tblUsuarios').DataTable({
        destroy: true,
        ajax: async function (data, callback, settings) {
            const response=await listUsuarios();

            if(response){
                callback({
                    length:response.data.length,
                    data:response.data
                });
            }else{
                callback({
                    length:0,
                    data:[]
                });
            }
          },
        columns:[
            {
                data:"usuario",
                title: 'usuario',
                targets: 0
            },
            {
                data:"role",
                title: 'Rol de usuario',
                targets: 1,
                render: function(data,type,row,meta){
                    return data.role
                }
            },
            {
                data:"publicId",
                title: 'Opciones',
                targets: 2,
                render: function (data, type, row, meta) {
                    console.log(row);
                    let btnActivarDesactivar=(row.activo=="1") ? renderDesactivarBtn(data) : renderActivarBtn(data);
                    let btnDelete= "<button class=\"btn btn-danger ml-3\" onclick=\"BoorarUsuarioConfimar('"+data+"')\">Eliminar</button>";
                    let btnEditar ="";
                    let btnResetPassword= "<button class=\"btn btn-secondary\" onclick=\"cambiaPassword('"+data+"')\">Cambiar Password</button>";
                    if(row.usuario=="admin" || row.usuario=="caja" || row.usuario=="supervisor"){
                        btnDelete ="";
                        btnActivarDesactivar="";
                        btnEditar="";
                    } 

                    return btnEditar+"<span id=\""+data+"_btnActivarDesactivar\">"+btnActivarDesactivar+"</span>"+btnDelete+"&nbsp;&nbsp;&nbsp;"+btnResetPassword
                }
            },
        ]
    });
}

function loadData(){
    loadTableUsers();
}

async function loadRolesSelector(){
    showLoading();
    const response= await getRoles();

    if(!response){
        showErrorModal('error al cargar roles');
        closeLoading();
        return;
    }

    response.data.forEach((item)=>{
        console.log(item);
        $('#roles').append($('<option>', {
                value: item.publicId,
                text: item.rol
            }));
    })

    closeLoading();
}

function renderActivarBtn(usuarioId){
    return "<button id=\""+usuarioId+"_activar\" class=\"btn btn-success ml-3\" onclick=\"ActivarUsuario('"+usuarioId+"')\">Activar</button>"
}

function renderDesactivarBtn(usuarioId){
    return "<button id=\""+usuarioId+"_btndesactivar\" class=\"btn btn-secondary ml-3\" onclick=\"DesactivarUsuario('"+usuarioId+"')\">Desactivar</button>"
}

function cambiaPassword(usuarioId){
    document.location.href="./?p=changepassword&id="+usuarioId;
}

let action=setTimeout(() => {}, 800);
async function GuardaInfo(){
   
    //validaDatos();
    clearInterval(action);
    action=setTimeout(async () => {

        showLoading();

        let usuario=$('#txtusuario').val();
        let password=$('#txtpassword').val();
        let role=$('#roles').val();
        
        const response= await addUsuario(usuario,password,role);
        if(!response){
            showErrorModal('error al cargar roles');
            closeLoading();
            return;
        }

        closeLoading();

        setTimeout(() => {
            window.document.location.href="./?p=usuarios"
        }, 800);
        
    }, 800);

    action;
}

async function ActivarUsuario(usuarioId){
    clearInterval(action);
    action=setTimeout(async () => {
        showLoading();
        const response= await ActivateUsuario(usuarioId);

        if(!response){
            showErrorModal('error al activar usuario');
            closeLoading();
            return;
        }

        loadTableUsers();

        closeLoading();
    }, 800);
    action;
}

async function DesactivarUsuario(usuarioId){
    clearInterval(action);
    action=setTimeout(async () => {
        showLoading();
        const response= await DeactivateUsuario(usuarioId);

        if(!response){
            showErrorModal('error al desactivar usuario');
            closeLoading();
            return;
        }

        loadTableUsers();

        closeLoading();
    }, 800);
    action;
}



async function BoorarUsuarioConfimar(usuarioId){
    clearTimeout(action);
    action=setTimeout(() => {
        showConfirmModal(usuarioId,"deleteUsuario")
    }, 800);
}

async function BorrarUsuario(usuarioId) {

    showConfirmModal

    showLoading();
    const response= await DeleteUsuario(usuarioId);

    if(!response){
        showErrorModal('error al desactivar usuario');
        closeLoading();
        return;
    }

    loadTableUsers();

    closeLoading();   
}

async function ConfirmModalSi(){
    $('#modalConfirm').modal('hide');
    const action= $('#action').val();
    const idSelected= $('#idSelected').val();

    if(action=="deleteUsuario"){
        showLoading();

        const response=await DeleteUsuario(idSelected);

        if(!response){
            showErrorModal('Error al eliminar el usuario');
            closeLoading();
            return;
        }

        loadTableUsers();

        closeLoading();
        return;
    }
}

function validatePassword(){
    let password=$('#txtpassword').val();
    let passwordConfirm=$('#txtConfirmPassword').val();

    if(password=="" || !password){
        $('#submitErrorMessage').empty();
        $('#submitErrorMessage').append("contraseña vacia, favor de intentar nuevamente");
        return false;
    }

    if(passwordConfirm=="" || !passwordConfirm){
        $('#submitErrorMessage').empty();
        $('#submitErrorMessage').append("confirmacion de contraseña vacia, favor de intentar nuevamente");
        return false;
    }

    if(password!==passwordConfirm){
        $('#submitErrorMessage').empty();
        $('#submitErrorMessage').append("No conincide las contraseñas, favor de intentar nuevamente");
        return false;
    }

    return true;
}

async function ActualizarContraseña(){


    clearInterval(action);
    action=setTimeout(async () => {
        
        $('#errorMessageBoard').addClass('d-none');
        if(!validatePassword()){
            $('#errorMessageBoard').removeClass('d-none');
            return;
        };

        showLoading();

        let usuarioId=$('#formIdSelected').val();
        let password = $('#txtpassword').val();

        const response= await updatePassword(usuarioId,password);

        if(!response){
            showErrorModal('Error al eliminar el usuario');
            closeLoading();
            return;
        }

        closeLoading();

        setTimeout(() => {
            window.document.location="./?p=usuarios";
        }, 800);

    }, 700);
    action;

    
}