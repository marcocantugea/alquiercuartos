
async function GetReportByDates(fechaIicio,fechaFin){
    try {
        var response= await fetch(apiHost+apiPath+"reportes/diario/montos?fechaInicio="+fechaIicio+"&fechaFin="+fechaFin,{
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

let totalesCorte={};

async function LoadTableDiasHistorial(fechaInicio,fechaFin){

    var table=$('#tblHistorialDiasCobrados').DataTable({
        destroy: true,
        ajax: async function (data, callback, settings) {
            try {
                const response= await fetch(apiHost+apiPath+"reportes/diario/montos?fechaInicio="+fechaInicio+"&fechaFin="+fechaFin,{
                    method:'get',
                    headers:headersRequest
                })
    
                if(response.ok){
                    const content= await response.json();
                    console.log(content.data.data);
                    totalesCorte=content.data;
                    callback(content.data);
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
                data:"fecha",
                title: 'Dia (año-mes-dia)',
                targets: 0,
                render: function (data, type, row, meta) {
                    const date = new Date(data);
                    const formattedDate = date.toLocaleDateString('es-MX'); // "dd/mm/yyyy"
                    const [day, month, year] = formattedDate.split('/');
                    const result = `${year}-${month}-${day}`;
                    console.log(result); // "d-m-yyyy"
                    return result;
                }
            },
            {
                data:"total",
                title: 'Total',
                targets: 1,
                render: function (data, type, row, meta) {

                    const amount =data;
                    const formattedAmount = amount.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' });
                    console.log(formattedAmount); // "$ 12,234.93"
                    return formattedAmount
                }
            }
        ]
    });

    table.on( 'draw', function () {
        $('#lblMontoTotal').empty();

        const amount =totalesCorte.montoTotal;
        const formattedAmount = amount.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' });

        $('#lblMontoTotal').append(formattedAmount);
        // $('#lblTotalTickets').empty();
        // $('#lblTotalTickets').append(totalesCorte.ticketsCobrados);
    } );
}

function loadData(){
    GenerarReportePorDias();
}

let accion= setTimeout(() => {}, 600);

function GenerarReportePorDias(){
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

        LoadTableDiasHistorial(fechaInicioStr,fechafinStr);
    }, 800);

    accion;
}