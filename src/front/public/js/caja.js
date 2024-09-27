let _cuartos=null;
let _cuartosAlquilados=null;
let _timers=[];
let _systemConfig=null;
let UserRol="{\"role\":\"Cja\"}";
let actionOpenAlquiler=setTimeout(() => {
}, 800);
let actionClosealquiler=setTimeout(() => {
}, 800);

let setFocusScanner=setInterval(()=>{
    $('#searchFolio').focus();
},30000);

const headersRequest=new Headers({
    'Authorization': 'Basic '+sessionStorage.getItem('token'), 
});

function cerrarSession(){
    sessionStorage.clear();
    document.location.href="./";
}

function handledescripcionAlquiler(event){
    clearInterval(setFocusScanner);
    console.log("reseting the focus scanner");
    setFocusScanner=setInterval(()=>{
        $('#searchFolio').focus();
    },30000);
}

async function getCuartos(){
    try {
        
        var response=await fetch(apiHost+apiPath+"cuartos", { 
            method: 'get', 
            headers: headersRequest
        });

        if(response.ok){
            const content= await response.json();
            if(content.success){
                return content.data;
            }
        }else{
            //print error on modal loading
        }
    } catch (error) {
        console.error(error);
    }
}

async function getCuartosAlquilados(){
    try {
        var response=await fetch(apiHost+apiPath+"alquiler", { 
            method: 'get', 
            headers: headersRequest
        });

        if(response.ok){
            const content= await response.json();
            
            if(content.success){
                return content.data;
            } 

        }else{
            //print error on modal loading
        }
    } catch (error) {
        console.error(error);
    }
}

async function openAlquiler(id,descripcion){

    let fechaEntrada=new Date();
    let fecha= fechaEntrada.getFullYear()+"-"+(fechaEntrada.getMonth()+1)+"-"+fechaEntrada.getDate();

    
    let tiempoentrada= addZeros(fechaEntrada.getHours())+":"+addZeros(fechaEntrada.getMinutes())+":"+addZeros(fechaEntrada.getSeconds());
    const request={
        "cuartoId":id,
        "descripcion":descripcion,
        "fechaEntrada": fecha+" "+ tiempoentrada
    }

    try {
        var response= await fetch(apiHost+apiPath+"alquiler",{
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
        //TODO: show error on screen 
       console.log(error);
    }   
}

async function closeAlquiler(id){

    let fechaEntrada=new Date();
    let fecha= fechaEntrada.getFullYear()+"-"+(fechaEntrada.getMonth()+1)+"-"+fechaEntrada.getDate();

const request={
        "fechaSalida": fecha+" "+ fechaEntrada.toLocaleTimeString()
    };

    try {
        var response= await fetch(apiHost+apiPath+"alquiler/pre/finalizar/"+id,{
            method:'post',
            headers:headersRequest,
            body: JSON.stringify(request)
        });

        if(response.ok){
            const content= await response.json();
            return content;
        }else{
            console.log(response);
            return;
        }

    } catch (error) {
        console.log(error);
    }

}

async function CobrarCerrarAlquiler(id,fechaSalida){
    
    const request={
        "fechaSalida": fechaSalida
    };

    try {
        var response= await fetch(apiHost+apiPath+"alquiler/finalizar/"+id,{
            method:'post',
            headers:headersRequest,
            body: JSON.stringify(request)
        });

        if(response.ok){
            const content= await response.json();
            return content;
        }else{
            //todo: error display
            console.log(response);
            return;
        }

    } catch (error) {
        //todo: error display
        console.log(error);
    }
}

async function PrintTicketCobro(id){
    try {
        var response= await fetch(apiHost+apiPath+"ticket/alquiler/"+id+"/cobro",{
            method:'post',
            headers:headersRequest
        });

        if(response.ok){
            const content= await response.json();
            return content;
        }else{
            //todo: error display
            console.log(response);
            return;
        }

    } catch (error) {
        //todo: error display
        console.log(error);
    }
}

async function PrintTicketInicioAlquiler(id){
    try {
        var response= await fetch(apiHost+apiPath+"ticket/alquiler/"+id+"/incio",{
            method:'post',
            headers:headersRequest
        });

        if(response.ok){
            const content= await response.json();
            return content;
        }else{
            //todo: error display
            console.log(response);
            return;
        }

    } catch (error) {
        //todo: error display
        console.log(error);
    }
}

async function getAlquilerPorFolio(folio){
    
    try {
        var response= await fetch(apiHost+apiPath+"alquiler?folio="+folio,{
            method:'get',
            headers:headersRequest
        });

        if(response.ok){
            const content= await response.json();
            return content;
        }else{
            //todo: error display
            console.log(response);
            return;
        }

    } catch (error) {
        //todo: error display
        console.log(error);
    }
}

async function cancelAlquilerParcial(alquilerId,motivo){

    let request= {
        motivo:motivo,
        usuarioId:sessionStorage.getItem('usuarioId')
    };

    try {
        var response= await fetch(apiHost+apiPath+"alquiler/"+alquilerId+"/cancelacion/parcial",{
            method:'post',
            headers:headersRequest,
            body:JSON.stringify(request)
        });

        if(response.ok){
            const content= await response.json();
            return content;
        }else{
            //todo: error display
            console.log(response);
            return;
        }

    } catch (error) {
        //todo: error display
        console.log(error);
    }
}

async function getCorteCaja(fechaInicio,fechaFin){
    try {
        var response= await fetch(apiHost+apiPath+"ticket/corte/caja/fecha?fechaInicio="+fechaInicio+"&fechaFin="+fechaFin,{
            method:'get',
            headers:headersRequest
        });

        if(response.ok){
            const content= await response.json();
            return content;
        }else{
            //todo: error display
            console.log(response);
            return;
        }

    } catch (error) {
        //todo: error display
        console.log(error);
    }

}

async function loadData(){
    $('#loadingmodal').modal('show');
    let sistemaConfig=await getConfigurationesSistema();
    sessionStorage.setItem('config',JSON.stringify(sistemaConfig));
    _systemConfig=sistemaConfig;
    UserRol= (sessionStorage.getItem('role')) ?  JSON.parse(sessionStorage.getItem('role')) : UserRol;
    try {
        setTimeout(async ()=>{
            let cuartos=await getCuartos();
            _cuartos=cuartos;
            let cuartosAlquilados=await getCuartosAlquilados();
            renderCuartos(cuartos,cuartosAlquilados);
            console.log(_systemConfig);
            $('#loadingmodal').modal('hide');
            $('#searchFolio').focus();
        },800);
    } catch (error) {
        ShowErrorModal('Error al cargar pagina','Error al obtener informacion del sistema Error:7001');
        setTimeout(() => {
            $('#loadingmodal').modal('hide');
        }, 600);
        console.log(error);
    }
}

function confirmaInicioAlquiler(id){

    if($('#cobroDiv').is(':visible')){
        return;
    }

    $('#iniciaAqluilerIdSelected').val(id);
    
    $('#modalIniciaAlquiler').modal('show');
    setTimeout(()=>{
        $('#descripcionAlquiler').val('');
        $('#descripcionAlquiler').focus();
    },600);
    
}

function confirmaFinAlquiler(idcuarto,idAlquiler){

    if($('#cobroDiv').is(':visible')){
        return;
    }

    $('#finAqluilerIdSelected').val(idAlquiler);
    $('#cuartoSelected').val(idcuarto);

    $('#modalConfirmFinAlquiler').modal('show');

    setTimeout(() => {
        $('#btnCerrarAlquilerSi').focus();
    }, 600);
}

function ConfirmaCancelacionParcial(){
    $('#modalConfirmCancelacionParcial').modal('show');

    setTimeout(() => {
        $('#txtMotivoCancelacion').focus();
    }, 600);

}

function ConfirmaCorteCaja(){
    $('#modalCorteCaja').modal('show');
}


async function iniciaAlquiler(){
    $('#modalIniciaAlquiler').modal('hide');
    $('#loadingmodal').modal('show');
    clearInterval(actionOpenAlquiler);
    actionOpenAlquiler= setTimeout(async () => {
        var id=$('#iniciaAqluilerIdSelected').val();
        var descripcionAlquiler= $('#descripcionAlquiler').val();

        var response = await openAlquiler(id,descripcionAlquiler);

        if(!response){
            ShowErrorModal('Error al iniciar alquiler','No se pudo registrar el alquiler Error:8002');
            setTimeout(() => {
                $('#loadingmodal').modal('hide');
            }, 600);
            return;
        }

        let alquilerInfo= response.data;

        $('#'+id).empty();
        $('#'+id).append(renderNewAlquiler(alquilerInfo));

        setTimeout(() => {
            $('#loadingmodal').modal('hide');    
        }, 600);
        
        if(response){
            const responseTicket=await PrintTicketInicioAlquiler(alquilerInfo.publicId);
            if(!responseTicket){
                ShowErrorModal('Error al imprimir ticket','Error al imprimir ticket Error:8005');
                setTimeout(() => {
                    $('#loadingmodal').modal('hide');
                }, 600);
            }
            setTiempo(alquilerInfo.publicId,alquilerInfo.fecha_entrada,id);
            $('#searchFolio').focus();
        }
    }, 900);
    
    actionOpenAlquiler;
}

async function CerrarAlquiler(){
    $('#modalConfirmFinAlquiler').modal('hide');
    $('#loadingmodal').modal('show');

    let idAlquiler= $('#finAqluilerIdSelected').val();

    const response= await closeAlquiler(idAlquiler);
    if(!response){
        ShowErrorModal('Error al cerrar alquiler','No se pudo registrar el alquiler Error:8001');
        setTimeout(() => {
            $('#loadingmodal').modal('hide');
        }, 600);
        return;
    }
    renderCobro(response.data);

    let cuartoSelected= _cuartos.filter(element=>{
        return element.publicId==response.data.cuartoId
    });


    $('#'+cuartoSelected[0].publicId).empty();
    $('#'+cuartoSelected[0].publicId).append(renderReactivarCuarto(response.data.cuartoId));
    clearTiempo(idAlquiler);
    removeCajaClasses(cuartoSelected[0].publicId);

    setTimeout(() => {
        $('#loadingmodal').modal('hide');
        $('#btnCobrar').focus();
    }, 700);
    
}

async function CobrarAlquiler(){
 
    clearInterval(actionClosealquiler);
    actionClosealquiler= setTimeout(async () => {
        let alquilerIdSel=$('#cobro_alquilerId').val();
        let fechaSalidaSel=$('#cobto_fechaSalida').val();
        
        const response= await CobrarCerrarAlquiler(alquilerIdSel,fechaSalidaSel);
        if(!response){
            ShowErrorModal('Error al cobrar alquiler','No se pudo cobrar el alquiler Error:8003');
            setTimeout(() => {
                $('#loadingmodal').modal('hide');
            }, 600);
            return;
        }

        $('#cobroDiv').hide();

        const ticketPrint=await PrintTicketCobro(alquilerIdSel);
        if(!ticketPrint){
            ShowErrorModal('Error al imprimir ticket','Error al imprimir ticket Error:8004');
            setTimeout(() => {
                $('#loadingmodal').modal('hide');
            }, 600);
        }

        $('#searchFolio').focus();
    }, 800);
    
    actionClosealquiler;
}

async function BuscaInfoPorFolio(folio){
    
    const response= await getAlquilerPorFolio(folio);
    if(!response){
        ShowErrorModal('Error al buscar folio','Error al buscar el folio Error:8015');
        setTimeout(() => {
            $('#loadingmodal').modal('hide');
        }, 600);
    }

    if(response.data.length==0){
        ShowErrorModal('Folio no encontrado','Folio no encontrado');

        return;
    }
    let alquiler=response.data[0];
    confirmaFinAlquiler(alquiler.cuarto.publicId,alquiler.publicId);
    $('#searchFolio').val('');

}

async function CancelarAlquilerParcial(){

    $('#modalConfirmCancelacionParcial').modal('hide');
    $('#loadingmodal').modal('show');

     let alquilerIdSel=$('#cobro_alquilerId').val();
     let fechaSalidaSel=$('#cobto_fechaSalida').val();
     let motivo=$('#txtMotivoCancelacion').val();

    const response= await CobrarCerrarAlquiler(alquilerIdSel,fechaSalidaSel);
    if(!response){
        ShowErrorModal('Error al Realizar cancelacion','Error al cerrar ticket y cancelar Error:8006');
        setTimeout(() => {
            $('#loadingmodal').modal('hide');
        }, 600);
         return;
    }
    
    const responseCancelacion= await cancelAlquilerParcial(alquilerIdSel,motivo);
    if(!responseCancelacion){
        ShowErrorModal('Error al Realizar cancelacion','Error al cancelar Error:8007');
        setTimeout(() => {
            $('#loadingmodal').modal('hide');
        }, 600);
         return;
    }

    ShowSuccessModal('Registro completo', 'Cancelacion parcial exitosa');

    setTimeout(() => {
        $('#loadingmodal').modal('hide');
        $('#CancelacionExistosaModal').modal('show');

        $('#cobroDiv').hide();

        clearTiempo(alquilerIdSel);
        $('#searchFolio').focus();

    }, 600);

}

async function ObtenerCorteCaja(){
    
    let fechaInicio= $('#corte_fechaInicio').val();
    let fechaFin= $('#corte_fechaFin').val();

    $('#modalCorteCaja').modal('hide');
    $('#loadingmodal').modal('show');

    let fechaInParsed= fechaInicio.split("/");
    let fechaFinParsed= fechaFin.split("/");

    let formatedFechaIn= fechaInParsed[2]+"-"+fechaInParsed[0]+"-"+fechaInParsed[1];
    let formatedFechaFin= fechaFinParsed[2]+"-"+fechaFinParsed[0]+"-"+fechaFinParsed[1];

    const response = await getCorteCaja(formatedFechaIn,formatedFechaFin);

    if(!response){
        ShowErrorModal('Error al Realizar corte de caja','Usuario sin permiso para realizar corte de caja Error:80010');
        setTimeout(() => {
            $('#loadingmodal').modal('hide');
        }, 600);
        return;
    }
    
    setTimeout(() => {
        $('#loadingmodal').modal('hide');    
    }, 600);
    

}

async function renderCuartos(cuartos,cuartosAlquilados){

    cuartos.forEach(element => {
        //revisamos el estado del cuarto
        if(element.estatus!="Activo"){
            //render cuarto inactivo
            $('#cuartosContent').append(renderCuartoInactivo(element.codigo,element.descripcion,element.publicId));
        }else{
            //si esta alquilado renderea el cuarto esta ocupado
            let rentaIndex=cuartosAlquilados.findIndex(item=>{
                return item.cuarto.publicId==element.publicId
            });
            
            if(rentaIndex>-1){
                itemAlquilado=cuartosAlquilados[rentaIndex];
                //render cuarto alquilado
                $('#cuartosContent').append(renderCuartoAlquilado(itemAlquilado.cuarto.codigo,itemAlquilado.cuarto.descripcion,itemAlquilado.cuarto.publicId,itemAlquilado.fecha_entrada,itemAlquilado.descripcion,itemAlquilado.publicId,itemAlquilado.folio));
                setTiempo(itemAlquilado.publicId,itemAlquilado.fecha_entrada,itemAlquilado.cuarto.publicId,parseInt(itemAlquilado.horasTrans),parseInt(itemAlquilado.minutosTrans),parseInt(itemAlquilado.segundosTrans));
            }else{
                //render cuarto disponible
                $('#cuartosContent').append(renderCuartoActivo(element.codigo,element.descripcion,element.publicId));
            }
            
        }
    });
}

function renderCuartoActivo(codigo,nombreCuarto,id){
    let cuarto="<div class=\"col-sm ml-1 mb-3\" id=\""+id+"\">";
    cuarto+="<div class=\"card\" style=\"width: 13rem; height: 200px;\">";
    cuarto+="<div class=\"card-body\" id=\""+id+"_content\">";
    cuarto+="<h5 class=\"card-title\" style=\"font-size:medium\">"+codigo+" - "+nombreCuarto+" </h5>";
    cuarto+="<h6 class=\"card-subtitle mb-2 text-muted\">Disponible</h6>";
    // cuarto+="<br>";
    // cuarto+="<br>";
    cuarto+="<br>";
    cuarto+="<p class=\"card-text text-center\">";
    cuarto+="<button class=\"btn btn-success mt-3\" style=\"width: 100%;\" onclick=\"confirmaInicioAlquiler('"+id+"')\" >Iniciar Alquiler</button>";
    //cuarto+="<a href=\"#\" class=\"btn btn-secondary mt-3\" style=\"width: 65%;\">Suspender</a>";
    cuarto+="</p>"
    cuarto+="</div>"
    cuarto+="</div>"
    cuarto+="</div>"

    return cuarto;
}

function renderCuartoInactivo(codigo,nombreCuarto,id){
    let cuarto="<div class=\"col-sm ml-1 mb-3\" id=\""+id+"\">";
    cuarto+="<div class=\"card\" style=\"width: 13rem; height: 200px;\">";
    cuarto+="<div class=\"card-body\">";
    cuarto+="<h5 class=\"card-title\" style=\"font-size:medium\">"+codigo+"-"+ nombreCuarto +" </h5>";
    cuarto+="<h6 class=\"card-subtitle mb-2 text-muted\">No Disponible</h6>";
    // cuarto+="<br>";
    // cuarto+="<br>";
    cuarto+="<br>";
    cuarto+="<p class=\"card-text text-center\">";
    cuarto+="<a href=\"#\" class=\"btn btn-secondary mt-3\" style=\"width: 65%;\">No dispoble</a>";
    cuarto+="</p>";
    cuarto+="</div>";
    cuarto+="</div>";
    cuarto+="</div>";

    return cuarto;
}

function renderCuartoAlquilado(codigo,nombreCuarto,id,fechaEntrada,descripcion,idAlquiler,folio){
    let cuarto="<div class=\"col ml-1 mb-3\" id=\""+id+"\">";
    cuarto+="<div class=\"card\" style=\"width: 13rem; height: 235px;\">";
    cuarto+="<div class=\"card-body\">";
    cuarto+="<h5 class=\"card-title\" style=\"font-size:medium\">"+codigo+" - "+nombreCuarto+" </h5>";
    cuarto+="<h6 class=\"card-subtitle mb-2 text-muted\">Folio: "+folio +" - "+descripcion+"</h6>";
    cuarto+="<input type=\"hidden\" value=\""+idAlquiler+"\" id=\""+id+"_alquilerId\">";
    cuarto+="<table style=\"width: 100%;\">";
    cuarto+="<tr><td class=\"text-left\">Entrada:</td></tr>";
    cuarto+="<tr><td class=\"text-right\"><strong>"+fechaEntrada+"</strong></td></tr>";
    // cuarto+="<tr><td><br></td></tr>";
    cuarto+="<tr><td class=\"text-left\">Transcurrido :</td></tr>";
    cuarto+="<tr><td class=\"text-right\"><strong><span id=\""+id+"_timer\">00:00:00</span></strong></td></tr>";
    // cuarto+="<tr><td><br></td></tr>";
    //cuarto+="<tr><td class=\"text-left\">Minutos : <strong><span>0</span> Min.</strong></td></tr>";
    let functBtnFinalizarConfig=_systemConfig.filter((element)=> element.publicId=="6110f89c");

    if( (functBtnFinalizarConfig && functBtnFinalizarConfig[0].valor=="1") || UserRol.role!="Caja"){
        console.log("valor>"+functBtnFinalizarConfig.valor);
        cuarto+="<tr><td class=\"text-center\"><a href=\"#\" class=\"btn btn-danger mt-2\" style=\"width: 65%;\" onclick=\"confirmaFinAlquiler('"+id+"','"+idAlquiler+"')\">Finalizar</a></td></tr>";
    } 
    cuarto+="</table></div></div></div>";

    return cuarto;
}

function renderNewAlquiler(alquilerInfo){
    let cuartoSelected= _cuartos.filter(element=>{
        return element.publicId==alquilerInfo.cuartoId
    });

    let cuarto="<div class=\"card\" style=\"width: 13rem; height: 235px;\">";
    cuarto+="<div class=\"card-body\">";
    cuarto+="<h5 class=\"card-title\" style=\"font-size:medium\">"+alquilerInfo.cuartoCodigo+" - "+cuartoSelected[0].descripcion+" </h5>";
    cuarto+="<h6 class=\"card-subtitle mb-2 text-muted\">Folio: "+alquilerInfo.folio +" - "+alquilerInfo.descripcion+"</h6>";
    cuarto+="<input type=\"hidden\" value=\""+alquilerInfo.publicId+"\" id=\""+alquilerInfo.cuartoId+"_alquilerId\">";
    cuarto+="<table style=\"width: 100%;\">";
    cuarto+="<tr><td class=\"text-left\">Entrada:</td></tr>";
    cuarto+="<tr><td class=\"text-right\"><strong>"+alquilerInfo.fecha_entrada+"</strong></td></tr>";
    // cuarto+="<tr><td><br></td></tr>";
    cuarto+="<tr><td class=\"text-left\">Transcurrido :</td></tr>";
    cuarto+="<tr><td class=\"text-right\"><strong><span id=\""+cuartoSelected[0].publicId+"_timer\">00:00:00</span></strong></td></tr>";
    // cuarto+="<tr><td><br></td></tr>";
    //cuarto+="<tr><td class=\"text-left\">Minutos : <strong><span>0</span> Min.</strong></td></tr>";
    let functBtnFinalizarConfig=_systemConfig.filter((element)=> element.publicId=="6110f89c");

    if( (functBtnFinalizarConfig && functBtnFinalizarConfig[0].valor=="1") || UserRol.role!="Caja"){

        console.log("valor>"+functBtnFinalizarConfig.valor);
        cuarto+="<tr><td class=\"text-center\"><a href=\"#\" class=\"btn btn-danger mt-2\" style=\"width: 65%;\" onclick=\"confirmaFinAlquiler('"+cuartoSelected[0].publicId+"','"+alquilerInfo.publicId+"')\">Finalizar</a></td></tr>";
    }
    cuarto+="</table></div></div>";

    return cuarto;
}

function renderReactivarCuarto(id){

    let cuartoFound= _cuartos.filter(element=>{
        return element.publicId==id
    });

    let cuartoSelected=cuartoFound[0];
    let cuarto="<div class=\"card\" style=\"width: 13rem; height: 200px;\">";
    cuarto+="<div class=\"card-body\" id=\""+cuartoSelected.publicId+"_content\">";
    cuarto+="<h5 class=\"card-title\" style=\"font-size:medium\">"+cuartoSelected.codigo+" - "+cuartoSelected.descripcion+" </h5>";
    cuarto+="<h6 class=\"card-subtitle mb-2 text-muted\">Disponible</h6>";
    // cuarto+="<br>";
    // cuarto+="<br>";
    cuarto+="<br>";
    cuarto+="<p class=\"card-text text-center\">";
    cuarto+="<button class=\"btn btn-success mt-3\" style=\"width: 100%;\" onclick=\"confirmaInicioAlquiler('"+cuartoSelected.publicId+"')\" >Iniciar Alquiler</button>";
    //cuarto+="<a href=\"#\" class=\"btn btn-secondary mt-3\" style=\"width: 65%;\">Suspender</a>";
    cuarto+="</p>"
    cuarto+="</div>"
    cuarto+="</div>"

    return cuarto;
}

function renderCobro(infoCobro){

    let cuartoSelected= _cuartos.filter(element=>{
        return element.publicId==infoCobro.cuartoId
    });

    $('#cobro_title').empty();
    $('#cobro_title').append(cuartoSelected[0].codigo+ " - "+ cuartoSelected[0].descripcion);

    $('#cobro_folio').empty();
    $('#cobro_folio').append(infoCobro.folio+" - "+infoCobro.publicId);

    $('#cobro_fechaInicio').empty();
    $('#cobro_fechaInicio').append(infoCobro.fecha_inicio);

    $('#cobro_fechaSalida').empty();
    $('#cobro_fechaSalida').append(infoCobro.fecha_salida);

    $('#cobro_totalmin').empty();
    $('#cobro_totalmin').append(infoCobro.total_horas);

    $('#cobro_monto').empty();
    $('#cobro_monto').append(infoCobro.costoAlquiler);

    $('#cobro_montoExtra').empty();
    $('#cobro_montoExtra').append(infoCobro.cobro_extra);

    $('#cobro_totalmonto').empty();
    $('#cobro_totalmonto').append(infoCobro.total_pagado);

    $('#cobro_alquilerId').val(infoCobro.publicId);
    $('#cobto_fechaSalida').val(infoCobro.fecha_salida);

    $('#cobroDiv').show();
}

function removeCajaClasses(cuartoId){
    $('#'+cuartoId).find('.card-body').removeClass('bg bg-warning'); 
    $('#'+cuartoId).find('.card-body').removeClass('caja-danger'); 
}

function ShowErrorModal(title, message){
    $('#error_title').empty();
    $('#error_title').append(title);

    $('#error_message').empty();
    $('#error_message').append(message);

    $('#modalError').modal('show');
}

function ShowSuccessModal(title, message){
    $('#success_title').empty();
    $('#success_title').append(title);

    $('#success_message').empty();
    $('#success_message').append(message);

    $('#modalSuccess').modal('show');
}

function setTiempo(alquilerId,fechaInicio,cuartoId,horasTrans=0,minTrans=0,segTrans=0){

    let obj={
        'stamp':alquilerId,
        'fechaInicio':fechaInicio,
        'horasTrans':horasTrans,
        'minTrans':minTrans,
        'segTrans':segTrans,
        'timer': setInterval(()=>{
                let indexTimer= _timers.findIndex(item=>{
                    return item.stamp==alquilerId
                });

                _timers[indexTimer].segTrans +=1

                if(_timers[indexTimer].segTrans>59){
                    _timers[indexTimer].segTrans=0;
                    _timers[indexTimer].minTrans+=1;
                }

                if(_timers[indexTimer].minTrans>59){
                    _timers[indexTimer].horasTrans+=1;
                    _timers[indexTimer].minTrans=0;
                }
                
                let message= addZeros(_timers[indexTimer].horasTrans)+":"+addZeros(_timers[indexTimer].minTrans) +":"+addZeros(_timers[indexTimer].segTrans);

                 $('#'+cuartoId+'_timer').empty();
                 $('#'+cuartoId+'_timer').append(message);

                if( parseInt(_timers[indexTimer].minTrans)>=23 
                 && parseInt(_timers[indexTimer].horasTrans)>=0
                 ){
                    
                    $('#'+cuartoId).find('.card-body').removeClass('caja-warning' ); 
                    $('#'+cuartoId).find('.card-body').addClass('caja-danger' );
                }else if(
                    parseInt(_timers[indexTimer].horasTrans)==0
                    && parseInt(_timers[indexTimer].minTrans)>19 
                    && parseInt(_timers[indexTimer].minTrans)<=22 
                ){
                    $('#'+cuartoId).find('.card-body').addClass('caja-warning');
                }               

            },1000)
    }

    _timers.push(obj);
    
}

function clearTiempo(alquilerId){

    let fileteredIndex=_timers.findIndex(item=>{
        return item.stamp==alquilerId
    });

    clearInterval(_timers[fileteredIndex].timer);

    _timers=_timers.filter(item=>{
        return item.stamp!=alquilerId;
    });

}

function addZeros(valor){
    return (parseInt(valor)<10) ? "0"+valor : valor;
}


let startTimeScannerChecker=undefined;

function handle(e){
    if(!startTimeScannerChecker) startTimeScannerChecker=performance.now();

    if(e.keyCode === 13){
        let duration = performance.now() - startTimeScannerChecker;

        if(duration>300 && UserRol.role=='Caja'){
            alert('Ticket no escaneado.. por favor de escanear el ticket. ');
            startTimeScannerChecker=undefined;
            return;
        }

        e.preventDefault(); // Ensure it is only this code that runs

        let valor=$('#searchFolio').val();
        valor= valor.replace('*','');

        BuscaInfoPorFolio(valor);
        startTimeScannerChecker=undefined;
    }
}

function handleCerrarAlquiler(e){
    if(e.keyCode === 13){
        e.preventDefault(); // Ensure it is only this code that runs

        CerrarAlquiler();
    }
}

function handleCobrar(e){
    if(e.keyCode === 13){
        e.preventDefault(); // Ensure it is only this code that runs

        CobrarAlquiler();
    }
}