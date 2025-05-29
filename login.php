<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/animations.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="icon" type="image/png" sizes="16x16" href="./img/user.png">

    <title>Acceder</title>

   

</head>

<body>
    <?php

    session_start();

    $_SESSION["user"] = "";
    $_SESSION["usertype"] = "";

    // Set the new timezone
    date_default_timezone_set('America/Guatemala');
    $date = date('d-m-y');

    $_SESSION["date"] = $date;


    //import database
    include("connection.php");





    if ($_POST) {
        $email = $_POST['useremail'];
        $password = $_POST['userpassword'];
        $error = '<label for="promter" class="form-label"></label>';

        $result = $database->prepare("SELECT * FROM webuser WHERE email = ?");
        $result->bind_param("s", $email);
        $result->execute();
        $userResult = $result->get_result();
        if ($userResult->num_rows == 1) {
            $utype = $userResult->fetch_assoc()['usertype'];
            if ($utype == 'patient') {
                $stmt = $database->prepare("SELECT * FROM patient WHERE pemail = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $checker = $stmt->get_result();
                if ($checker->num_rows == 1) {
                    $row = $checker->fetch_assoc();
                    if (password_verify($password, $row['ppassword'])) {
                        $_SESSION['user'] = $email;
                        $_SESSION['usertype'] = 'patient';
                        header('location: patient/index.php');
                        exit();
                    } else {
                        $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Credenciales incorrectas: correo electrónico o contraseña no válidos</label>';
                    }
                } else {
                    $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Credenciales incorrectas: correo electrónico o contraseña no válidos</label>';
                }
            } elseif ($utype == 'admin') {
                $stmt = $database->prepare("SELECT * FROM admin WHERE aemail = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $checker = $stmt->get_result();
                if ($checker->num_rows == 1) {
                    $row = $checker->fetch_assoc();
                    if (password_verify($password, $row['apassword'])) {
                        $_SESSION['user'] = $email;
                        $_SESSION['usertype'] = 'admin';
                        header('location: admin/index.php');
                        exit();
                    } else {
                        $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Credenciales incorrectas: correo electrónico o contraseña no válidos</label>';
                    }
                } else {
                    $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Credenciales incorrectas: correo electrónico o contraseña no válidos</label>';
                }
            } elseif ($utype == 'doctor') {
                $stmt = $database->prepare("SELECT * FROM doctor WHERE docemail = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $checker = $stmt->get_result();
                if ($checker->num_rows == 1) {
                    $row = $checker->fetch_assoc();
                    if (password_verify($password, $row['docpassword'])) {
                        $_SESSION['user'] = $email;
                        $_SESSION['usertype'] = 'doctor';
                        header('location: doctor/index.php');
                        exit();
                    } else {
                        $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Credenciales incorrectas: correo electrónico o contraseña no válidos</label>';
                    }
                } else {
                    $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Credenciales incorrectas: correo electrónico o contraseña no válidos</label>';
                }
            }
        } else {
            $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">No podemos encontrar ninguna cuenta para este correo electrónico</label>';
        }
    } else {
        $error = '<label for="promter" class="form-label">&nbsp;</label>';
    }

    ?>





    <center>
        <div class="container">
            <p class="header-text">Bienvenido a Nuestras Clinicas medicas</p>
            <div class="form-body">
                <p class="sub-text">Inicia sesión con tus datos para continuar</p>
                <form action="" method="POST">
                    <div class="label-td">
                        <label for="useremail" class="form-label">Correo Electrónico: </label>
                        <input type="email" name="useremail" class="input-text" placeholder="Correo Electrónico" required>
                    </div>
                    <div class="label-td">
                        <label for="userpassword" class="form-label">Contraseña: </label>
                        <input type="Password" name="userpassword" class="input-text" placeholder="Contraseña" required>
                    </div>
                    <div class="label-td">
                        <br>
                        <?php echo $error ?>
                    </div>
                    <div class="label-td">
                        <input type="submit" value="Acceder" class="login-btn btn-primary btn">
                    </div>
                </form>
                <div class="label-td">
                    <br>
                    <label for="" class="sub-text" style="font-weight: 280;">Aún no tienes cuenta&#63 </label>
                    <a href="signup.php" class="hover-link1 non-style-link">Regístrate</a>
                    <br><br><br>
                </div>
            </div>
        </div>
    </center>
</body>

</html>