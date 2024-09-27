<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center mb-5">

        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="wrap border">
                <div class="img" style="background-image: url(./images/gb-1.jpg);"></div>
                <div class="login-wrap p-4 p-md-5">
                    <div class="d-flex">
                        <div class="w-100 text-center mb-4" style="width: 100%;">
                            <h3 class="">Iniciar Session</h3>
                            <span style="font-size: x-small;">version:<span id="versionlbl"></span> </span>                           
                        </div>
                    </div>
                    <form id="formlogin" action="#" class="signin-form">
                        <div class="form-group mt-3">
                            <input id="user" type="text" class="form-control" required="">
                            <label class="form-control-placeholder" for="username">Usuario</label>
                        </div>
                        <br>
                        <div class="form-group">
                            <input id="password" type="password" class="form-control" required="">
                            <label class="form-control-placeholder" for="password">Contraseña</label>
                            <!-- <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span> -->
                        </div>
                        <div class="form-group">
                            <button type="submit" class="form-control btn btn-primary rounded submit px-3" id="loginbtn">Iniciar</button>
                        </div>
                        <div class="text-center esconderContent" id="loading">
                            <img src="./images/Fountain.gif">
                        </div>
                        <!-- <div class="form-group d-md-flex">
                            <div class="w-50 text-left">
                                <label class="checkbox-wrap checkbox-primary mb-0">Remember Me
                                    <input type="checkbox" checked="">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="w-50 text-md-right">
                                <a href="#">Forgot Password</a>
                            </div>
                        </div> -->
                    </form>
                    <!-- <p class="text-center">Not a member? <a data-toggle="tab" href="#signup">Sign Up</a></p> -->
                    <div class="text-danger">
                        <span id="error_message"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="./js/config.js"></script>
<script src="./js/login.js"></script>
<script>
    $("#formlogin").submit(async function(e) {
        e.preventDefault();
        await login();
    });
    $(document).ready(async function() {
        LoadData();
    })
</script>