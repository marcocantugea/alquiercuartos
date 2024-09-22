async function debugCodigosBarras() {
    try {
        var response= await fetch(apiHost+apiPath+"debug/printer/test/codigobarras",{
            method:'get',
            headers:headersRequest
        })
        
        if(response.ok){
            return true;
        }else{
            return false;
        }

    } catch (error) {
       console.log(error);
       return false;
    }   
}

async function debugTextSize() {
    try {
        var response= await fetch(apiHost+apiPath+"debug/printer/test/textsize",{
            method:'get',
            headers:headersRequest
        })
        
        if(response.ok){
            return true;
        }else{
            return false;
        }

    } catch (error) {
       console.log(error);
       return false;
    }   
}

async function debugDemo() {
    try {
        var response= await fetch(apiHost+apiPath+"debug/printer/test/demo",{
            method:'get',
            headers:headersRequest
        })
        
        if(response.ok){
            return true;
        }else{
            return false;
        }

    } catch (error) {
       console.log(error);
       return false;
    }   
}

let action=setTimeout(() => {}, 800);

async function AccionDebugCodigoBarras() {
    clearInterval(action);
    action=setTimeout(async () => {
        showLoading();
        const response= await debugCodigosBarras();
        console.log(response);
        if(!response){
            showErrorModal('error al imprimr prueba');
            closeLoading();
            return;
        }
        closeLoading();
    }, 800);
}

async function AccionTextSize() {
    clearInterval(action);
    action=setTimeout(async () => {
        showLoading();
        const response= await debugTextSize();
        console.log(response);
        if(!response){
            showErrorModal('error al imprimr prueba');
            closeLoading();
            return;
        }
        closeLoading();
    }, 800);
}


async function AccionDebugDemo() {
    clearInterval(action);
    action=setTimeout(async () => {
        showLoading();
        const response= await debugDemo();
        console.log(response);
        if(!response){
            showErrorModal('error al imprimr prueba');
            closeLoading();
            return;
        }
        closeLoading();
    }, 800);
}