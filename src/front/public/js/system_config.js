
async function getTipoAlquilerFlag(){
    try {
        
        var response=await fetch(apiHost+apiPath+"configuracion/79bdcefe", { 
            method: 'get', 
            headers: headersRequest
        });

        if(response.ok){
            const content= await response.json();
            if(content.success){
                return content.data;
            }
        }else{
            console.log(response.message);
        }
    } catch (error) {
        console.error(error);
    }
}

async function getConfigurationesSistema(){
    try {
        
        var response=await fetch(apiHost+apiPath+"configuracion", { 
            method: 'get', 
            headers: headersRequest
        });

        if(response.ok){
            const content= await response.json();
            if(content.success){
                return content.data;
            }
        }else{
            console.log(response.message);
        }
    } catch (error) {
        console.error(error);
    }
}