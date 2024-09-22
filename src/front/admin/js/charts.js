

async function GetReportAmountByMonth(year){
    try {
        var response= await fetch(apiHost+apiPath+"reportes/mensual/montos?anio="+year,{
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

async function loadChart(){

    showLoading();
    let dataAmounts=[];

    try {
        const response= await GetReportAmountByMonth(2023);

        if(!response){
            showErrorModal('error al cargar roles');
            closeLoading();
            return;
        }    

        dataAmounts=response.data;
    } catch (error) {
        showErrorModal('error al cargar roles');
        closeLoading();
        return;
    }

    const ctx = document.getElementById('myChart');

    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Ene', 'Feb', 'Mar', 'Abr', 'Mayo', 'Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
        datasets: [{
          label: '$ Total Acumulado',
          data: dataAmounts,
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        },
        plugins:{
            
        }
      }
    });

    closeLoading();
  
}
