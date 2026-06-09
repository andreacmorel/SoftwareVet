<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VetSys | Iniciar sesión</title>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/SoftwareVet/vendor/fontawesome-free/css/all.min.css">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:'Nunito',sans-serif;
            background:#edf1f5;
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:20px;
        }

        .login-container{
            width:100%;
            max-width:1050px;
            min-height:650px;
            background:#fff;
            border-radius:22px;
            overflow:hidden;
            display:flex;
            box-shadow:0 20px 45px rgba(0,0,0,.15);
        }

        .login-left{
            width:50%;
            position:relative;
            background:
            linear-gradient(
                rgba(36,16,48,.58),
                rgba(82,38,110,.78)
            ),
            url('/SoftwareVet/img/login_vet.png');
            background-size:cover;
            background-position:center;
            background-repeat:no-repeat;
            color:#fff;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:50px;
        }

        .overlay-content{
            max-width:380px;
        }

        .overlay-content .mini{
            letter-spacing:5px;
            font-size:.75rem;
            text-transform:uppercase;
            margin-bottom:20px;
            opacity:.9;
        }

        .overlay-content h1{
            font-size:4rem;
            font-weight:800;
            line-height:1;
            margin-bottom:20px;
        }

        .overlay-content p{
            font-size:1rem;
            opacity:.9;
            line-height:1.7;
        }

        .line{
            width:80px;
            height:4px;
            background:#fff;
            border-radius:20px;
            margin:25px 0;
        }

        .login-right{
            width:50%;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:50px;
            background:#fff;
        }

        .login-box{
            width:100%;
            max-width:380px;
        }

        .login-title{
            font-size:2.2rem;
            color:#52266E;
            font-weight:800;
            margin-bottom:10px;
        }

        .input-group{
            position:relative;
            margin-bottom:22px;
        }

        .input-group > i {
            position:absolute;
            left:16px;
            top:28px;
            transform:translateY(-50%);
            color:#52266E;
        }

        .input {
            width:100%;
            height:56px;
            border:2px solid transparent;
            background:#f4f2f7;
            border-radius:14px;
            padding-left:45px;
            padding-right:50px;
            font-size:15px;
            transition:.2s;
            outline:none;
        }

        .input:focus{
            background:#fff;
            border:2px solid #52266E;
            box-shadow:0 0 0 4px rgba(82,38,110,.12);
        }

        .input.is-invalid{
            border:2px solid #dc3545;
            background:#fff5f5;
        }

        .input.is-invalid:focus{
            border:2px solid #dc3545;
            box-shadow:0 0 0 4px rgba(220,53,69,.12);
        }

        .invalid-feedback{
            display:none;
            color:#dc3545;
            font-size:13px;
            font-weight:700;
            margin-top:6px;
            padding-left:4px;
        }

        .input.is-invalid ~ .invalid-feedback{
            display:block;
        }

        .toggle-password {
            position:absolute;
            right:18px;
            top:28px;
            transform:translateY(-50%);
            cursor:pointer;
            color:#52266E;
            z-index:10;
        }

        .toggle-password i {
            position:static;
            transform:none;
        }

        .btn-login{
            width:100%;
            height:55px;
            border:none;
            border-radius:14px;
            background:#52266E;
            color:#fff;
            font-size:1rem;
            font-weight:700;
            cursor:pointer;
            transition:.25s;
            margin-top:10px;
        }

        .btn-login:hover{
            background:#6d3391;
            transform:translateY(-2px);
            box-shadow:0 12px 24px rgba(82,38,110,.25);
        }

        .forgot{
            text-align:center;
            margin-top:18px;
        }

        .forgot a{
            text-decoration:none;
            color:#52266E;
            font-size:.92rem;
            font-weight:700;
        }

        .forgot a:hover{
            text-decoration:underline;
        }

        .alert{
            border-radius:12px;
            padding:14px;
            margin-bottom:20px;
            font-size:.92rem;
            font-weight:600;
            animation:fade .3s ease;
        }

        .alert-success{
            background:#e8f8ef;
            color:#198754;
            border:1px solid #c7eed8;
        }

        .alert-danger{
            background:#fdecec;
            color:#c0392b;
            border:1px solid #f5c6cb;
        }

        .vet-alert-danger{
            display:flex;
            gap:12px;
            align-items:flex-start;
            background:#fdecec;
            color:#c0392b;
            border:1px solid #f5c6cb;
            border-radius:12px;
            padding:14px;
            margin-bottom:20px;
            animation:fade .3s ease;
        }

        .vet-alert-icon{
            font-size:20px;
            margin-top:2px;
        }

        .vet-alert-content h5{
            margin:0 0 4px 0;
            font-size:15px;
            font-weight:800;
        }

        .vet-alert-content p{
            margin:0;
            font-size:14px;
            font-weight:600;
        }

        @keyframes fade{
            from{
                opacity:0;
                transform:translateY(-8px);
            }
            to{
                opacity:1;
                transform:translateY(0);
            }
        }

        @media(max-width:900px){
            .login-container{
                flex-direction:column;
            }

            .login-left{
                width:100%;
                min-height:260px;
            }

            .login-right{
                width:100%;
                padding:40px 25px;
            }

            .overlay-content h1{
                font-size:2.8rem;
            }
        }
    </style>
</head>

<body>

<div class="login-container">

    <div class="login-left">

        <div class="overlay-content">

            <div class="mini">
                Software Veterinario
            </div>

            <h1>VetSys</h1>

            <div class="line"></div>

            <p>
                Gestioná mascotas, turnos, historias clínicas y clientes
                desde una plataforma moderna, segura y profesional.
            </p>

        </div>

    </div>

    <div class="login-right">

        <div class="login-box">

            <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'ok') { ?>

                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    Contraseña actualizada correctamente.
                </div>

            <?php } ?>

            <?php if (isset($_GET['error']) && $_GET['error'] == 'sesion_requerida') { ?>

                <div class="vet-alert-danger">
                    <div class="vet-alert-icon">
                        <i class="fas fa-lock"></i>
                    </div>

                    <div class="vet-alert-content">
                        <h5>Acceso restringido</h5>
                        <p>Debe iniciar sesión para acceder a esta sección del sistema.</p>
                    </div>
                </div>

            <?php } elseif (isset($_GET['error'])) { ?>

                <div class="alert alert-danger">
                    <i class="fas fa-times-circle"></i>
                    <?= htmlspecialchars($_GET['error']) ?>
                </div>

            <?php } ?>

            <h2 class="login-title">
                Iniciar sesión
            </h2>

            <br>

            <form action="login.php" method="POST" id="frmLogin" novalidate>

                <div class="input-group">
                    <i class="fas fa-user"></i>

                    <input type="text" name="usuario"class="input"id="usuario"
                        placeholder="Usuario">

                    <div class="invalid-feedback" id="err-usuario">
                        El usuario es obligatorio.
                    </div>
                </div>

                <div class="input-group">
                    <i class="fas fa-lock"></i>

                    <input type="password" name="clave" class="input" id="password"
                    placeholder="Contraseña">

                    <span class="toggle-password" onclick="togglePassword()">
                        <i class="fas fa-eye" id="eye"></i>
                    </span>

                    <div class="invalid-feedback" id="err-clave">
                        La contraseña es obligatoria.
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    Ingresar
                </button>

            </form>

            <div class="forgot">
                <a href="forgot_password.php">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>

        </div>

    </div>

</div>

<script>
function togglePassword(){

    const input = document.getElementById('password');
    const eye   = document.getElementById('eye');

    if(input.type === 'password'){
        input.type = 'text';
        eye.classList.remove('fa-eye');
        eye.classList.add('fa-eye-slash');
    }else{
        input.type = 'password';
        eye.classList.remove('fa-eye-slash');
        eye.classList.add('fa-eye');
    }
}

document.getElementById('frmLogin').addEventListener('submit', function(e) {
    let valido = true;

    const usuario = document.getElementById('usuario');
    const clave = document.getElementById('password');

    usuario.classList.remove('is-invalid');
    clave.classList.remove('is-invalid');

    if (usuario.value.trim() === '') {
        usuario.classList.add('is-invalid');
        valido = false;
    }

    if (clave.value.trim() === '') {
        clave.classList.add('is-invalid');
        valido = false;
    }

    if (!valido) {
        e.preventDefault();
    }
});

document.getElementById('usuario').addEventListener('input', function() {
    if (this.value.trim() !== '') {
        this.classList.remove('is-invalid');
    }
});

document.getElementById('password').addEventListener('input', function() {
    if (this.value.trim() !== '') {
        this.classList.remove('is-invalid');
    }
});

setTimeout(() => {

    const alert = document.querySelector('.alert, .vet-alert-danger');

    if(alert){
        alert.style.display = 'none';
    }

}, 4000);
</script>

</body>
</html>