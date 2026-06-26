<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VetSys | Recuperar contraseña</title>

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
            linear-gradient(rgba(36,16,48,.58), rgba(82,38,110,.78)),
            url('/SoftwareVet/img/login_vet.png');
            background-size:cover;
            background-position:center;
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

        .login-sub{
            color:#888;
            margin-bottom:30px;
        }

        .input-group{
            position:relative;
            margin-bottom:22px;
        }

        .input-group > i{
            position:absolute;
            left:16px;
            top:50%;
            transform:translateY(-50%);
            color:#52266E;
        }

        .input{
            width:100%;
            height:56px;
            border:none;
            background:#f4f2f7;
            border-radius:14px;
            padding-left:45px;
            padding-right:20px;
            font-size:15px;
            outline:none;
        }

        .input:focus{
            background:#fff;
            border:2px solid #52266E;
            box-shadow:0 0 0 4px rgba(82,38,110,.12);
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
            <div class="mini">Software Veterinario</div>
            <h1>VetSys</h1>
            <div class="line"></div>
            <p>
                Recuperá el acceso a tu cuenta de forma segura mediante tu correo electrónico.
            </p>
        </div>
    </div>

    <div class="login-right">
        <div class="login-box">

            <?php if (isset($_GET['mensaje'])) { ?>

                <?php if ($_GET['mensaje'] == 'ok') { ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        Se envió el link de recuperación a tu correo.
                    </div>
                <?php } ?>

                <?php if ($_GET['mensaje'] == 'error') { ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-times-circle"></i>
                        El email no existe.
                    </div>
                <?php } ?>

                <?php if ($_GET['mensaje'] == 'mail_error') { ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-times-circle"></i>
                        No se pudo enviar el correo.
                    </div>
                <?php } ?>

            <?php } ?>

            <h2 class="login-title">Recuperar contraseña</h2>
            <p class="login-sub">Ingresá tu email para recibir el link.</p>

            <form action="send_email.php" method="POST">

                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" class="input" placeholder="Ingrese su email" required>
                </div>

                <button type="submit" class="btn-login">
                    Enviar link
                </button>

            </form>

            <div class="forgot">
                <a href="index.php">Volver al login</a>
            </div>

        </div>
    </div>

</div>

<script>
setTimeout(() => {
    const alert = document.querySelector('.alert');
    if(alert){
        alert.style.display = 'none';
    }
}, 4000);
</script>

</body>
</html>