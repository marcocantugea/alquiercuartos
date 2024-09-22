

async function loadData(){
    await LoadTableConfiguracionCuartos();
    await LoadTableConfiguracionSistema();
}

async function getConfiguracionAlquiler(configId){

    try {
        var response= await fetch(apiHost+apiPath+"cuartos/configuracion/"+configId,{
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

async function getConfiguracionSistema(configId){

    try {
        var response= await fetch(apiHost+apiPath+"configuracion/"+configId,{
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

async function agregarConfiguracionCuarto(nombre,valor){

    const request={
        nombre:nombre,
        valor:valor
    }

    try {
        var response= await fetch(apiHost+apiPath+"cuartos/configuracion",{
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

async function agregarConfiguracionSistema(nombre,valor){

    const request={
        variable:nombre,
        valor:valor
    }

    try {
        var response= await fetch(apiHost+apiPath+"configuracion",{
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

async function deleteConfiguracionSistema(configuracionId){
    try {
        var response= await fetch(apiHost+apiPath+"configuracion/"+configuracionId,{
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

async function deleteConfiguracionCuartos(configuracionId){
    try {
        var response= await fetch(apiHost+apiPath+"cuartos/configuracion/"+configuracionId,{
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

async function updateAlquilerConfig(nombre,valor,configId){

    const request={
        nombre:nombre,
        valor:valor
    }

    try {
        var response= await fetch(apiHost+apiPath+"cuartos/configuracion/"+configId,{
            method:'put',
            headers:headersRequest,
            body: JSON.stringify(request)
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

async function updateConfigSistema(nombre,valor,configId){

    const request={
        variable:nombre,
        valor:valor
    }

    try {
        var response= await fetch(apiHost+apiPath+"configuracion/"+configId,{
            method:'put',
            headers:headersRequest,
            body: JSON.stringify(request)
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

async function LoadTableConfiguracionCuartos(){

    $('#tblConfiguracionCuartos').DataTable({
        destroy: true,
        ajax: async function (data, callback, settings) {
            const response=await fetch(apiHost+apiPath+"cuartos/configuracion",{
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
                data:"nombre",
                title: 'Configuracion',
                targets: 0
            },
            {
                data:"valor",
                title: 'Valor Configuracion',
                targets: 1
            },
            {
                data:"publicId",
                title: 'Opciones',
                targets: 3,
                render: function (data, type, row, meta) {
                    //let btnDelete= "<button class=\"btn btn-danger ml-3\" onclick=\"BorrarConfiguracionSistema('"+data+"')\">Eliminar</button>"
                    let btnDelete= ""
                    return "<button class=\"btn btn-info\" onclick=\"EditarConfiguracionCuarto('"+data+"')\">Editar</button>"+btnDelete
                }
            },
        ]
    });
}

async function LoadTableConfiguracionSistema(){

    $('#tblConfiguracionSistema').DataTable({
        destroy: true,
        ajax: async function (data, callback, settings) {
            const response=await fetch(apiHost+apiPath+"configuracion",{
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
                data:"variable",
                title: 'Configuracion',
                targets: 0
            },
            {
                data:"valor",
                title: 'Valor Configuracion',
                targets: 1
            },
            {
                data:"publicId",
                title: 'Opciones',
                targets: 3,
                render: function (data, type, row, meta) {
                    //let btnDelete= "<button class=\"btn btn-danger ml-3\" onclick=\"BorrarConfiguracionCuartos('"+data+"')\">Eliminar</button>"
                    let btnDelete= ""
                    return "<button class=\"btn btn-info\" onclick=\"EditarConfiguracionSistema('"+data+"')\">Editar</button>"+btnDelete
                }
            },
        ]
    });
}

async function loadForm(){
    let configFrom= $('#configuracion').val();
    let idconfig=$('#formIdSelected').val();
    $('#txtNombre').prop('disabled', true)
    $('#SelConfiguracion').prop('disabled', true)

    if(configFrom=="cuartos"){
        const response = await getConfiguracionAlquiler(idconfig);
        let config=response.data[0];
        console.log(response);
        $("#txtNombre").val(config.nombre);
        $("#txtValor").val(config.valor);
        $('#SelConfiguracion option[value="1"]').prop('selected', true)
    }

    if(configFrom=="sistema"){
        const response = await getConfiguracionSistema(idconfig);
        let config=response.data;
        console.log(response);
        $("#txtNombre").val(config.variable);
        $("#txtValor").val(config.valor);
        $('#SelConfiguracion option[value="3"]').prop('selected', true)
    }
}


function EditarConfiguracionCuarto(cuartoId){
    document.location.href="./?p=configuracionform&config=cuartos&action=edit&id="+cuartoId;
}

function EditarConfiguracionSistema(cuartoId){
    document.location.href="./?p=configuracionform&config=sistema&action=edit&id="+cuartoId;
}

async function GuardarDatos(){
    showLoading();
    let nombre=$('#txtNombre').val();
    let valor=$('#txtValor').val();
    let config=$('#SelConfiguracion').val();
    let action=$('#action').val();

    if(config==1 && action=="new"){
        const response= await agregarConfiguracionCuarto(nombre,valor);
        if(!response){
            showErrorModal('error al agregar configuracion');
            closeLoading();
            return;
        }

        closeLoading();
        window.document.location.href="./?p=configuracion";
    }

    if(config==3 && action=="new"){
        const response= await agregarConfiguracionSistema(nombre,valor);
        if(!response){
            showErrorModal('error al agregar configuracion');
            closeLoading();
            return;
        }

        closeLoading();
        window.document.location.href="./?p=configuracion";
    }

    if(config==1 && action=="edit"){
        let idconfig=$('#formIdSelected').val();
        const response= await updateAlquilerConfig(nombre,valor,idconfig);
        if(!response){
            showErrorModal('error al agregar configuracion');
            closeLoading();
            return;
        }

        closeLoading();
        window.document.location.href="./?p=configuracion";
    }

    if(config==3 && action=="edit"){
        let idconfig=$('#formIdSelected').val();
        const response= await updateConfigSistema(nombre,valor,idconfig);
        if(!response){
            showErrorModal('error al agregar configuracion');
            closeLoading();
            return;
        }

        closeLoading();
        window.document.location.href="./?p=configuracionsistema";
    }


}