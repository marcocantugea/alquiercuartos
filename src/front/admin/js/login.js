
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

            if(content.data.rol!="Administrador" &&  content.data.rol!="Supervisor") {
                setErrorMessage("Usuario sin permisos para acceder a la administracion del sistema");    
                return;
            }

            sessionStorage.setItem('token',content.data.token);
            sessionStorage.setItem('usuarioId',content.data.usuarioId);
            sessionStorage.setItem('usuarioRol',content.data.rol);
            window.location.href="./?p=home"

        }else{
            //display error on screen
            setErrorMessage("Usuario invalido o contraseña invalida");
        }
        //console.log(response);

    } catch (error) {
        console.log(error);
    }

 }

 function cerrarSession(){
    sessionStorage.clear();
    document.location.href="./?p=login";
}


function setErrorMessage(message){
    $('#error_message').empty();
    $('#error_message').append(message);
 }