<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/animations.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/signup.css">
    <link rel="icon" type="image/png" sizes="16x16" href="./img/logo.png">

    <title>Regístrate</title>

</head>

<body>
    <?php

    //learn from w3schools.com
    //Unset all the server side variables

    session_start();

    $_SESSION["user"] = "";
    $_SESSION["usertype"] = "";

    // Set the new timezone
    date_default_timezone_set('America/Guatemala');
    $date = date('d-m-y');

    $_SESSION["date"] = $date;



    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sanitizar y validar entradas
        $fname = trim(htmlspecialchars($_POST['fname']));
        $lname = trim(htmlspecialchars($_POST['lname']));
        $address = trim(htmlspecialchars($_POST['address']));
        $nic = trim(htmlspecialchars($_POST['nic']));
        $dob = $_POST['dob'];

        // Validación básica de fecha
        $dobDate = DateTime::createFromFormat('Y-m-d', $dob);
        $now = new DateTime();
        $signup_error = '';
        if (!$dobDate || $dobDate > $now) {
            $signup_error = 'Fecha de nacimiento inválida';
        } elseif (strlen($fname) < 2 || strlen($lname) < 2) {
            $signup_error = 'Nombre y apellido deben tener al menos 2 caracteres';
        } elseif (!preg_match('/^[a-zA-Z0-9]+$/', $nic)) {
            $signup_error = 'Documento de Identificación inválido';
        } else {
            // Validar que el NIC no exista ya en la base de datos
            include("connection.php");
            $stmt_nic = $database->prepare("SELECT * FROM patient WHERE pnic = ?");
            $stmt_nic->bind_param("s", $nic);
            $stmt_nic->execute();
            $result_nic = $stmt_nic->get_result();
            if ($result_nic->num_rows > 0) {
                $signup_error = 'El número de identificación ya está registrado.';
            }
        }
        if ($signup_error !== '') {
            // Mostrar mensaje solo en el formulario, sin ventana emergente
            $form_data = array(
                'fname' => $fname,
                'lname' => $lname,
                'address' => $address,
                'nic' => $nic,
                'dob' => $dob
            );
        } else {
            // Guardar datos en sesión para el siguiente paso
            $_SESSION["personal"] = array(
                'fname' => $fname,
                'lname' => $lname,
                'address' => $address,
                'nic' => $nic,
                'dob' => $dob
            );
            header("location: create-account.php");
            exit();
        }
    }

    ?>


    <center>
        <div class="container">
            <table border="0">
                <tr>
                    <td colspan="2">
                        <p class="header-text">Empecemos</p>
                        <p class="sub-text">Agregue sus datos personales para continuar</p>
                        <?php if (!empty($signup_error)) { echo '<label class="form-label" style="color:rgb(255, 62, 62);text-align:center;">' . $signup_error . '</label>'; } ?>
                    </td>
                </tr>
                <tr>
                    <form action="" method="POST">
                        <td class="label-td" colspan="2">
                            <label for="name" class="form-label">Nombre: </label>
                        </td>
                </tr>
                <tr>
                    <td class="label-td">
                        <input type="text" name="fname" class="input-text" placeholder="Nombre" required value="<?php echo isset($form_data['fname']) ? htmlspecialchars($form_data['fname']) : ''; ?>">
                    </td>
                    <td class="label-td">
                        <input type="text" name="lname" class="input-text" placeholder="Apellido" required value="<?php echo isset($form_data['lname']) ? htmlspecialchars($form_data['lname']) : ''; ?>">
                    </td>
                </tr>
                <tr>
                    <td class="label-td" colspan="2">
                        <label for="address" class="form-label">Dirección: </label>
                    </td>
                </tr>
                <tr>
                    <td class="label-td" colspan="2">
                        <input type="text" name="address" class="input-text" placeholder="Dirección" required value="<?php echo isset($form_data['address']) ? htmlspecialchars($form_data['address']) : ''; ?>">
                    </td>
                </tr>
                <tr>
                    <td class="label-td" colspan="2">
                        <label for="nic" class="form-label">Documento de Identificación: </label>
                    </td>
                </tr>
                <tr>
                    <td class="label-td" colspan="2">
                        <input type="text" name="nic" class="input-text" placeholder="Documento de Identificación" required value="<?php echo isset($form_data['nic']) ? htmlspecialchars($form_data['nic']) : ''; ?>">
                    </td>
                </tr>
                <tr>
                    <td class="label-td" colspan="2">
                        <label for="dob" class="form-label">Fecha de Nacimiento: </label>
                    </td>
                </tr>
                <tr>
                    <td class="label-td" colspan="2">
                        <input type="date" name="dob" class="input-text" required value="<?php echo isset($form_data['dob']) ? htmlspecialchars($form_data['dob']) : ''; ?>">
                    </td>
                </tr>
                <tr>
                    <td class="label-td" colspan="2">
                    </td>
                </tr>

                <tr>
                    <td>
                        <input type="reset" value="Resetear Valores" class="login-btn btn-primary-soft btn">
                    </td>
                    <td>
                        <input type="submit" value="Siguiente" class="login-btn btn-primary btn">
                    </td>

                </tr>
                <tr>
                    <td colspan="2">
                        <br>
                        <label for="" class="sub-text" style="font-weight: 280;">Ya tienes una cuenta&#63; </label>
                        <a href="login.php" class="hover-link1 non-style-link">Ingresar</a>
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