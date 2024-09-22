

let totalesCorte={};

async function LoadTableAlquilerHistorial(fechaInicio,fechaFin){

    var table=$('#tblHistorialAqluiler').DataTable({
        destroy: true,
        ajax: async function (data, callback, settings) {
            try {
                const response=await fetch(apiHost+apiPath+"corte/caja/detalle/fecha?fechaInicio="+fechaInicio+"&fechaFin="+fechaFin,{
                    method:"get",
                    headers:headersRequest
                });
    
                if(response.ok){
                    const content= await response.json();
                    console.log(content.data.detalle);
                    totalesCorte=content.data;
                    callback(content.data.detalle);
                }else{
                    totalesCorte={
                        MontoTotal:0,
                        ticketsCobrados:0
                    };
                    callback({
                        length:0,
                        data:[]
                    });
                }   
            } catch (error) {
                totalesCorte={
                    MontoTotal:0,
                    ticketsCobrados:0
                };
                callback({
                    length:0,
                    data:[]
                });
            }
          },
        columns:[
            {
                data:"folio",
                title: 'Folio',
                targets: 0
            },
            {
                data:"codigo",
                title: 'Lugar Codigo',
                targets: 1
            },
            {
                data:"cuartoDescripcion",
                title: 'Lugar Desc',
                targets: 2
            },
            {
                data:"fecha_entrada",
                title: 'Fecha Entrada',
                targets: 3
            },
            {
                data:"fecha_salida",
                title: 'Fecha Salida',
                targets: 4
            },
            {
                data:"total_minutos",
                title: 'Total Minutos',
                targets: 5
            },
            {
                data:"total_pagado",
                title: 'Total',
                targets: 6,
                render: function (data, type, row, meta) {
                    return "$ "+data
                }
            }
        ]
    });

    table.on( 'draw', function () {
        $('#lblMontoTotal').empty();
        $('#lblMontoTotal').append(totalesCorte.MontoTotal);
        $('#lblTotalTickets').empty();
        $('#lblTotalTickets').append(totalesCorte.ticketsCobrados);
    } );
}

function loadData(){
    let fechaInicio= new Date();
    let fechaStr=fechaInicio.getFullYear()+"-"+(fechaInicio.getMonth()+1)+"-"+fechaInicio.getDate();
    let fechaStrInicio=fechaInicio.getFullYear()+"-"+(fechaInicio.getMonth()+1)+"-01";
    LoadTableAlquilerHistorial(fechaStrInicio,fechaStr);
}

let accion= setTimeout(() => {}, 600);

function buscarAlquiler(){
    clearInterval(accion);
    accion=setTimeout(() => {
        let fechaInicio= $('#fechaInicio').val();
        let fechaFin= $('#fechaFin').val();

        let fechaInicioSplited= fechaInicio.split('/');
        let fechaFinSplited= fechaFin.split('/');

        let fechaInicioStr= fechaInicioSplited[2]+"-"+fechaInicioSplited[0]+"-"+fechaInicioSplited[1];
        let fechafinStr= fechaFinSplited[2]+"-"+fechaFinSplited[0]+"-"+fechaFinSplited[1];

        $('#lblMontoTotal').empty();
        $('#lblTotalTickets').empty();

        LoadTableAlquilerHistorial(fechaInicioStr,fechafinStr);
    }, 800);

    accion;
}