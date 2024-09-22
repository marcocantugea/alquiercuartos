
$(document).ready(()=>{
    LoadTable(); 
    CargaCuartoInfo();
});

async function disableCuarto(cuartoId){
    
    try {
        var response= await fetch(apiHost+apiPath+"cuarto/"+cuartoId+"/desactivar",{
            method:'put',
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

async function enableCuarto(cuartoId){
    
    try {
        var response= await fetch(apiHost+apiPath+"cuarto/"+cuartoId+"/activar",{
            method:'put',
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

async function deleteCuarto(cuartoId){
    
    try {
        var response= await fetch(apiHost+apiPath+"cuarto/"+cuartoId,{
            method:'delete',
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

async function getCuartoInfo(cuartoId){
    try {
        var response= await fetch(apiHost+apiPath+"cuarto/"+cuartoId,{
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

async function addCuarto(codigo,descripcion, estatusId){

    let request={
        codigo:codigo,
        estatusId:estatusId,
        descripcion:descripcion
    }

    try {
        var response= await fetch(apiHost+apiPath+"cuarto",{
            method:'post',
            headers:headersRequest,
            body:JSON.stringify(request)
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

async function editCuarto(cuartoId,codigo,descripcion, estatusId){

    let request={
        codigo:codigo,
        estatusId:estatusId,
        descripcion:descripcion
    }

    try {
        var response= await fetch(apiHost+apiPath+"cuarto/"+cuartoId,{
            method:'put',
            headers:headersRequest,
            body:JSON.stringify(request)
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


async function add300Cuartos(){
    try {
        var response= await fetch(apiHost+apiPath+"cuartos/crear/sinlimite",{
            method:'post',
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

async function restoreCuartosBasicos(){
    try {
        var response= await fetch(apiHost+apiPath+"cuartos/restaurar/basicos",{
            method:'post',
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


function LoadTable(){

    $('#tblCuartos').DataTable({
        destroy: true,
        ajax: async function (data, callback, settings) {
            const response=await fetch(apiHost+apiPath+"cuartos",{
                method:"get",
                headers:headersRequest
            });

            if(response.ok){
                const content= await response.json();
                console.log(content.data);
                callback(content);
            }
          },
        columns:[
            {
                data:"codigo",
                title: 'Codigo',
                targets: 0
            },
            {
                data:"descripcion",
                title: 'Descripcion',
                targets: 1
            },
            {
                data:"estatus",
                title: 'Estado',
                targets: 2,
                render: function (data, type, row, meta) {
                    return "<span id=\""+row.publicId+"_estatus\">"+data+"</span>"
                }
            },
            {
                data:"publicId",
                title: 'Opciones',
                targets: 3,
                render: function (data, type, row, meta) {
                    let btnActivarDesactivar=(row.estatus=="Activo") ? renderDesactivarBtn(data) : renderActivarBtn(data);
                    let btnDelete= "<button class=\"btn btn-danger ml-3\" onclick=\"BorrarCuarto('"+data+"')\">Eliminar</button>"
                    return "<button class=\"btn btn-info\" onclick=\"EditarCuarto('"+data+"')\">Editar</button><span id=\""+data+"_btnActivarDesactivar\">"+btnActivarDesactivar+"</span>"+btnDelete
                }
            },
        ]
    });
}

async function DesactivarCuarto(cuartoId){
    showLoading();

    const response=await disableCuarto(cuartoId);

    if(!response){
        showErrorModal('Error al desactivar el cuarto');
        closeLoading();
        return;
    }

    $('#'+cuartoId+"_btnActivarDesactivar").empty();
    $('#'+cuartoId+"_btnActivarDesactivar").append(renderActivarBtn(cuartoId));
    $('#'+cuartoId+"_estatus").empty();
    $('#'+cuartoId+"_estatus").append("Inactivo");

    closeLoading();

}

async function ActivarCuarto(cuartoId){
    showLoading();

    const response=await enableCuarto(cuartoId);

    if(!response){
        showErrorModal('Error al activar el cuarto');
        closeLoading();
        return;
    }

    $('#'+cuartoId+"_btnActivarDesactivar").empty();
    $('#'+cuartoId+"_btnActivarDesactivar").append(renderDesactivarBtn(cuartoId));
    $('#'+cuartoId+"_estatus").empty();
    $('#'+cuartoId+"_estatus").append("Activo");

    closeLoading();

}

function BorrarCuarto(cuartoId){
  
    showConfirmModal(cuartoId,"deletecuarto");
}

async function GuardaInfoCuarto(){

    showLoading();

    let action=$('#action').val();
    let codigo= $('#codigo').val();
    let descripcion=$('#txtdescripcion').val();
    let estatusId=$('#estado').val();
    let idSelected=$('#formIdSelected').val();

    if(action=="new"){
        const response = await addCuarto(codigo, descripcion, estatusId);
        if (!response) {
            showErrorModal("Error al guardar la informacion");
            closeLoading();
            return;
        }
    }else if(action=="edit"){
        const response = await editCuarto(idSelected,codigo,descripcion,estatusId);
        if (!response) {
            showErrorModal("Error al guardar la informacion");
            closeLoading();
            return;
        }
    }

    closeLoading();
    window.document.location.href="./?p=cuartos";
}

async function CargaCuartoInfo(){
    let cuartoId=$('#formIdSelected').val();
    if(!cuartoId || cuartoId=='') return;

    const response = await getCuartoInfo(cuartoId);
    if(!response){
        $('#formIdSelected').val('');
        $('#action').val('new');
        return;
    }

    const cuarto= response.data[0];
    $('#codigo').val(cuarto.codigo)
    $('#txtdescripcion').val(cuarto.descripcion)
    //$('#estado').find('option[text="'+cuarto.estatus+'"]').prop('selected', true);
    //$('#estado option:eq('+cuarto.estatus+')').prop('selected', true)
    $('#estado option:contains("'+cuarto.estatus+'")').attr('selected', 'selected');



}

function EditarCuarto(cuartoId){
    document.location.href="./?p=cuartosform&action=edit&id="+cuartoId;
}

function renderActivarBtn(cuartoId){
    return "<button id=\""+cuartoId+"_activar\" class=\"btn btn-success ml-3\" onclick=\"ActivarCuarto('"+cuartoId+"')\">Activar</button>"
}

function renderDesactivarBtn(cuartoId){
    return "<button id=\""+cuartoId+"_btndesactivar\" class=\"btn btn-secondary ml-3\" onclick=\"DesactivarCuarto('"+cuartoId+"')\">Desactivar</button>"
}

async function ConfirmModalSi(){

      $('#modalConfirm').modal('hide');
    const action= $('#action').val();
    const idSelected= $('#idSelected').val();

    if(action=="deletecuarto"){
        showLoading();

        const response=await deleteCuarto(idSelected);

        if(!response){
            showErrorModal('Error al eliminar el cuarto');
            closeLoading();
            return;
        }

       LoadTable();

        closeLoading();
        return;
    }

}

let action = setTimeout(() => {}, 600);

async function agregar300cuartos() {
    clearInterval(action);
    action=setTimeout(async () => {
        showLoading();
        const response=await add300Cuartos();
        if(!response){
            showErrorModal('Error al crear espacios');
            closeLoading();
            return;
        }
        LoadTable();
        closeLoading();
    }, 600);

    action;
}

async function restaurarEspacios() {
    clearInterval(action);
    action=setTimeout(async () => {
        showLoading();
        const response=await restoreCuartosBasicos();
        if(!response){
            showErrorModal('Error al restaurar espacios');
            closeLoading();
            return;
        }
        LoadTable();
        closeLoading();
    }, 600);

    action;
}