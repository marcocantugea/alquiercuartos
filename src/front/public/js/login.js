
 async function login(){
    let user=$('#user').val();
    let password=$('#password').val();


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
            window.location.href="./?p=caja"

        }else{
            //display error on screen
            setErrorMessage("Usuario invalido o contraseña invalida");
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