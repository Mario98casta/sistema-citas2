<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/animations.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/signup.css">

    <title>Crear Cuenta</title>
    <style>
        .container {
            animation: transitionIn-X 0.5s;
        }
    </style>
</head>

<body>
    <?php

    //learn from w3schools.com
    //Unset all the server side variables

    session_start();

    $_SESSION["user"] = "";
    $_SESSION["usertype"] = "";

    // Set the new timezone
    date_default_timezone_set('America/Bogota');
    $date = date('Y-m-d');

    $_SESSION["date"] = $date;


    //import database
    include("connection.php");





    if ($_POST) {
        // Sanitizar entradas
        $email = trim(htmlspecialchars($_POST['newemail']));
        $tele = trim(htmlspecialchars($_POST['tele']));
        $newpassword = $_POST['newpassword'];
        $cpassword = $_POST['cpassword'];

        $fname = $_SESSION['personal']['fname'];
        $lname = $_SESSION['personal']['lname'];
        $name = $fname . " " . $lname;
        $address = $_SESSION['personal']['address'];
        $nic = $_SESSION['personal']['nic'];
        $dob = $_SESSION['personal']['dob'];

        // Validar email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Correo electrónico inválido.</label>';
        } elseif (strlen($newpassword) < 8 || !preg_match('/[A-Z]/', $newpassword) || !preg_match('/[a-z]/', $newpassword) || !preg_match('/[0-9]/', $newpassword)) {
            $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">La contraseña debe tener al menos 8 caracteres, incluir mayúsculas, minúsculas y números.</label>';
        } elseif ($newpassword !== $cpassword) {
            $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">¡Las contraseñas no coinciden!</label>';
        } else {
            // Revisar si el email ya existe usando prepared statement
            $stmt = $database->prepare("SELECT * FROM webuser WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Ya existe una cuenta con este correo electrónico.</label>';
            } else {
                // Revisar si el NIC ya existe
                $stmt_nic = $database->prepare("SELECT * FROM patient WHERE pnic = ?");
                $stmt_nic->bind_param("s", $nic);
                $stmt_nic->execute();
                $result_nic = $stmt_nic->get_result();
                if ($result_nic->num_rows > 0) {
                    $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">El número de identificación ya está registrado.</label>';
                } else {
                    // Hash de la contraseña
                    $hashed_password = password_hash($newpassword, PASSWORD_DEFAULT);
                    // Insertar datos usando prepared statements
                    $stmt1 = $database->prepare("INSERT INTO patient(pemail,pname,ppassword, paddress, pnic,pdob,ptel) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt1->bind_param("sssssss", $email, $name, $hashed_password, $address, $nic, $dob, $tele);
                    $stmt1->execute();
                    $stmt2 = $database->prepare("INSERT INTO webuser VALUES (?, 'patient')");
                    $stmt2->bind_param("s", $email);
                    $stmt2->execute();
                    $_SESSION["user"] = $email;
                    $_SESSION["usertype"] = "patient";
                    $_SESSION["username"] = $fname;
                    header('Location: patient/index.php');
                    exit();
                }
            }
        }
    } else {
        $error = '<label for="promter" class="form-label"></label>';
    }

    ?>


    <center>
        <div class="container">
            <table border="0" style="width: 69%;">
                <tr>
                    <td colspan="2">
                        <p class="header-text">Empecemos</p>
                        <p class="sub-text">Está bien, crear cuenta de usuario.</p>
                    </td>
                </tr>
                <tr>
                    <form action="" method="POST">
                        <td class="label-td" colspan="2">
                            <label for="newemail" class="form-label">Correo: </label>
                        </td>
                </tr>
                <tr>
                    <td class="label-td" colspan="2">
                        <input type="email" name="newemail" class="input-text" placeholder="Email Address" required value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                    </td>

                </tr>
                <tr>
                    <td class="label-td" colspan="2">
                        <label for="tele" class="form-label">Celular: </label>
                    </td>
                </tr>
                <tr>
                    <td class="label-td" colspan="2">
                        <input type="tel" name="tele" class="input-text" placeholder="Ingresa tu móvil" value="<?php echo isset($tele) ? htmlspecialchars($tele) : ''; ?>">
                    </td>
                </tr>
                <tr>
                    <td class="label-td" colspan="2">
                        <label for="newpassword" class="form-label">Crear Nueva Contraseña: </label>
                    </td>
                </tr>
                <tr>
                    <td class="label-td" colspan="2">
                        <input type="password" name="newpassword" class="input-text" placeholder="New Password" required value="<?php echo isset($newpassword) ? htmlspecialchars($newpassword) : ''; ?>">
                    </td>
                </tr>
                <tr>
                    <td class="label-td" colspan="2">
                        <label for="cpassword" class="form-label">Confirmar Contraseña: </label>
                    </td>
                </tr>
                <tr>
                    <td class="label-td" colspan="2">
                        <input type="password" name="cpassword" class="input-text" placeholder="Confirmar Contraseña" required value="<?php echo isset($cpassword) ? htmlspecialchars($cpassword) : ''; ?>">
                    </td>
                </tr>

                <tr>

                    <td colspan="2">
                        <?php echo $error ?>

                    </td>
                </tr>

                <tr>
                    <td>
                        <input type="reset" value="Resetear" class="login-btn btn-primary-soft btn">
                    </td>
                    <td>
                        <input type="submit" value="Sign Up" class="login-btn btn-primary btn">
                    </td>

                </tr>
                <tr>
                    <td colspan="2">
                        <br>
                        <label for="" class="sub-text" style="font-weight: 280;">Already have an account&#63; </label>
                        <a href="login.php" class="hover-link1 non-style-link">Login</a>
                        <br><br><br>
                    </td>
                </tr>

                </form>
                </tr>
            </table>

        </div>
    </center>
</body>

</html>