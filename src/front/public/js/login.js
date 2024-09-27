
async function GetVersion(){
    try {
        
        var response=await fetch(apiHost+apiPath+"version", { 
            method: 'get'
        });

        if(response.ok){
            const content= await response.json();
            return content;
        }else{
            //print error on modal loading
        }
    } catch (error) {
        console.error(error);
    }
}

async function LoadData(){
    let version = await GetVersion();
    console.log(version);
    $("#versionlbl").append(version.version);
}

async function login(){
    let user=$('#user').val();
    let password=$('#password').val();
    $('#error_message').empty();
    $(':input[type="submit"]').prop('disabled', true);
    $("#loading").removeClass('esconderContent');
    try {

        let request={
            "usuario":user,
            "password":password
        };
    
        var response= await fetch(apiHost+apiPath+"auth",{
            method:'post',
            body:JSON.stringify(request)
        });

        if(response.ok){
            var content= await response.json();
            sessionStorage.setItem('token',content.data.token);
            sessionStorage.setItem('usuarioId',content.data.usuarioId);
            var responseUserDetail= await fetch(apiHost+apiPath+"usuario/"+content.data.usuarioId,{
                method:'get',
                headers: new Headers({
                    'Authorization': 'Basic '+content.data.token
                })
            });
            if(responseUserDetail.ok){
                var contentUser=await responseUserDetail.json()
                sessionStorage.setItem('role',JSON.stringify(contentUser.data.role));
            }
            window.location.href="./?p=caja"

        }else{
            //display error on screen
            setErrorMessage("Usuario invalido o contraseña invalida");
            $(':input[type="submit"]').prop('disabled', false);
            $("#loading").addClass('esconderContent');
        }
        //console.log(response);

    } catch (error) {
        console.log(error);
    }
 }

 function setErrorMessage(message){
    $('#error_message').empty();
    $('#error_message').append(message);
 }