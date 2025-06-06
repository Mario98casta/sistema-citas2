<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="icon" type="image/png" sizes="16x16" href="../img/user.png">

    <title>Citas</title>
    <style>
        .popup {
            animation: transitionIn-Y-bottom 0.5s;
        }

        .sub-table {
            animation: transitionIn-Y-bottom 0.5s;
        }
    </style>
</head>

<body>
    <?php

    //learn from w3schools.com

    session_start();

    if (isset($_SESSION["user"])) {
        if (($_SESSION["user"]) == "" or $_SESSION['usertype'] != 'patient') {
            header("location: ../login.php");
        } else {
            $useremail = $_SESSION["user"];
        }
    } else {
        header("location: ../login.php");
    }


    //import database
    include("../connection.php");
    $userrow = $database->query("select * from patient where pemail='$useremail'");
    $userfetch = $userrow->fetch_assoc();
    $userid = $userfetch["pid"];
    $username = $userfetch["pname"];


    //echo $userid;
    //echo $username;


    $sqlmain = "select appointment.appoid,schedule.scheduleid,schedule.title,doctor.docname,patient.pname,schedule.scheduledate,schedule.scheduletime,appointment.apponum,appointment.appodate from schedule inner join appointment on schedule.scheduleid=appointment.scheduleid inner join patient on patient.pid=appointment.pid inner join doctor on schedule.docid=doctor.docid  where  patient.pid=$userid ";

    if ($_POST) {
        //print_r($_POST);




        if (!empty($_POST["sheduledate"])) {
            $sheduledate = $_POST["sheduledate"];
            $sqlmain .= " and schedule.scheduledate='$sheduledate' ";
        };



        //echo $sqlmain;

    }

    $sqlmain .= "order by appointment.appodate  asc";
    $result = $database->query($sqlmain);
    ?>
    <div class="container">
        <div class="menu">
            <table class="menu-container" border="0">
                <tr>
                    <td style="padding:10px" colspan="2">
                        <table border="0" class="profile-container">
                            <tr>
                                <td width="30%" style="padding-left:20px">
                                    <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                                </td>
                                <td style="padding:0px;margin:0px;">
                                    <p class="profile-title"><?php echo substr($username, 0, 13)  ?>..</p>
                                    <p class="profile-subtitle"><?php echo substr($useremail, 0, 22)  ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="../logout.php"><input type="button" value="Cerrar Sesión" class="logout-btn btn-primary-soft btn"></a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-home">
                        <a href="index.php" class="non-style-link-menu ">
                            <div>
                                <p class="menu-text">Inicio</p>
                        </a>
        </div></a>
        </td>
        </tr>
        <tr class="menu-row">
            <td class="menu-btn menu-icon-doctor">
                <a href="doctors.php" class="non-style-link-menu">
                    <div>
                        <p class="menu-text">Doctores</p>
                </a>
    </div>
    </td>
    </tr>

    <tr class="menu-row">
        <td class="menu-btn menu-icon-session">
            <a href="schedule.php" class="non-style-link-menu">
                <div>
                    <p class="menu-text">Citas</p>
                </div>
            </a>
        </td>
    </tr>
    <tr class="menu-row">
        <td class="menu-btn menu-icon-appoinment  menu-active menu-icon-appoinment-active">
            <a href="appointment.php" class="non-style-link-menu non-style-link-menu-active">
                <div>
                    <p class="menu-text">Mis Reservas</p>
            </a></div>
        </td>
    </tr>
    <tr class="menu-row">
        <td class="menu-btn menu-icon-settings">
            <a href="settings.php" class="non-style-link-menu">
                <div>
                    <p class="menu-text">Configuración</p>
            </a></div>
        </td>
    </tr>

    </table>
    </div>
    <div class="dash-body">
        <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:25px; ">
            <tr>
                <td width="13%">
                    <a href="index.php"><button class="login-btn btn-primary-soft btn btn-icon-back" style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px">
                            <font class="tn-in-text">Volver</font>
                        </button></a>
                </td>
                <td>
                    <p style="font-size: 23px;padding-left:12px;font-weight: 600;">Historial de Mis Reservas</p>

                </td>
                <td width="15%">
                    <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                        Fecha
                    </p>
                    <p class="heading-sub12" style="padding: 0;margin: 0;">
                        <?php

                        date_default_timezone_set('America/Bogota');

                        $today = date('Y-m-d');
                        echo $today;


                        ?>
                    </p>
                </td>
                <td width="10%">
                    <button class="btn-label" style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                </td>


            </tr>

            <tr>
                <td colspan="4" style="padding-top:10px;width: 100%;">

                    <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">Mis Reservas (<?php echo $result->num_rows; ?>)</p>
                </td>

            </tr>
            <tr>
                <td colspan="4" style="padding-top:0px;width: 100%;">
                    <center>
                        <table class="filter-container" border="0">
                            <tr>
                                <td width="10%">

                                </td>
                                <td width="5%" style="text-align: center;">
                                    Fecha:
                                </td>
                                <td width="30%">
                                    <form action="" method="post">

                                        <input type="date" name="sheduledate" id="date" class="input-text filter-container-items" style="margin: 0;width: 95%;">

                                </td>

                                <td width="12%">
                                    <input type="submit" name="filter" value=" Filtro" class=" btn-primary-soft btn button-icon btn-filter" style="padding: 15px; margin :0;width:100%">
                                    </form>
                                </td>

                            </tr>
                        </table>

                    </center>
                </td>

            </tr>



            <tr>
                <td colspan="4">
                    <center>
                        <div class="abc scroll">
                            <table width="93%" class="sub-table scrolldown" border="0" style="border:none">

                                <tbody>

                                    <?php




                                    if ($result->num_rows == 0) {
                                        echo '<tr>
                                    <td colspan="7">
                                    <br><br><br><br>
                                    <center>
                                    <img src="../img/notfound.svg" width="25%">
                                    
                                    <br>
                                    <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">¡No pudimos encontrar nada relacionado con tus términos de búsqueda!</p>
                                    <a class="non-style-link" href="appointment.php"><button  class="login-btn btn-primary-soft btn"  style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Mostrar todas las citas &nbsp;</font></button>
                                    </a>
                                    </center>
                                    <br><br><br><br>
                                    </td>
                                    </tr>';
                                    } else {

                                        for ($x = 0; $x < ($result->num_rows); $x++) {
                                            echo "<tr>";
                                            for ($q = 0; $q < 3; $q++) {
                                                $row = $result->fetch_assoc();
                                                $scheduleid = isset($row["scheduleid"]) ? $row["scheduleid"] : '';
                                                $title = isset($row["title"]) ? $row["title"] : '';
                                                $docname = isset($row["docname"]) ? $row["docname"] : '';
                                                $scheduledate = isset($row["scheduledate"]) ? $row["scheduledate"] : '';
                                                $scheduletime = isset($row["scheduletime"]) ? $row["scheduletime"] : '';
                                                $apponum = isset($row["apponum"]) ? $row["apponum"] : '';
                                                $appodate = isset($row["appodate"]) ? $row["appodate"] : '';
                                                $appoid = isset($row["appoid"]) ? $row["appoid"] : '';

                                                if ($scheduleid == "") {
                                                    break;
                                                }

                                                echo '
                                            <td style="width: 25%;">
                                                    <div  class="dashboard-items search-items"  >
                                                    
                                                        <div style="width:100%;">
                                                        <div class="h3-search">
                                                                    Fecha de Reserva: ' . substr($appodate, 0, 30) . '<br>
                                                                    Número de Reserva: OC-000-' . $appoid . '
                                                                </div>
                                                                <div class="h1-search">
                                                                    ' . substr($title, 0, 21) . '<br>
                                                                </div>
                                                                <div class="h3-search">
                                                                    Número de Reserva:<div class="h1-search">0' . $apponum . '</div>
                                                                </div>
                                                                <div class="h3-search">
                                                                    ' . substr($docname, 0, 30) . '
                                                                </div>
                                                                
                                                                
                                                                <div class="h4-search">
                                                                    Fecha Reserva: ' . $scheduledate . '<br>Inicio: <b>@' . substr($scheduletime, 0, 5) . '</b> (24h)
                                                                </div>
                                                                <br>';
                                                                $estado = '';
                                                                $status_query = $database->query("SELECT appostatus FROM appointment WHERE appoid='$appoid'");
                                                                $status_row = $status_query->fetch_assoc();
                                                                $appostatus = isset($status_row['appostatus']) ? strtolower($status_row['appostatus']) : 'pendiente';
                                                                switch ($appostatus) {
                                                                    case 'atendida':
                                                                        $estado = '<div style="background:#b2e0b2;color:#2d6a2d;font-weight:bold;padding:10px 0;border-radius:8px;width:100%;text-align:center;margin-bottom:8px;">Atendida</div>';
                                                                        break;
                                                                    case 'cancelada':
                                                                        $estado = 'Cancelada';
                                                                        break;
                                                                    case 'reagendada':
                                                                        $estado = 'Reagendada';
                                                                        break;
                                                                    default:
                                                                        $estado = ucfirst($appostatus);
                                                                        break;
                                                                }
                                                                if ($appostatus === 'atendida') {
                                                                    echo $estado;
                                                                } else {
                                                                    echo '<div class="h4-search">Estado: ' . $estado . '</div>';
                                                                }
                                                                $btns = '';
                                                                if ($appostatus === 'reagendada' || $appostatus === 'pendiente') {
                                                                    $btns .= '<a href="?action=drop&id=' . $appoid . '&title=' . urlencode($title) . '&doc=' . urlencode($docname) . '" style="flex:1;"><button  class="login-btn btn-primary-soft btn "  style="padding-top:11px;padding-bottom:11px;width:100%"><font class="tn-in-text">Cancelar Reserva</font></button></a>';
                                                                    $btns .= '<a href="?action=reschedule&id=' . $appoid . '" style="flex:1;"><button  class="login-btn btn-primary-soft btn "  style="padding-top:11px;padding-bottom:11px;width:100%"><font class="tn-in-text">Reagendar</font></button></a>';
                                                                } elseif ($appostatus === 'cancelada') {
                                                                    $btns .= '<a href="?action=reschedule&id=' . $appoid . '" style="flex:1;"><button  class="login-btn btn-primary-soft btn "  style="padding-top:11px;padding-bottom:11px;width:100%"><font class="tn-in-text">Reagendar</font></button></a>';
                                                                }
                                                                if ($btns !== '') {
                                                                    echo '<div style="display:flex;gap:10px;justify-content:center;align-items:center;margin-top:8px;width:100%;">' . $btns . '</div>';
                                                                }
                                                                echo '</div>
                                                                
                                                    </div>
                                                </td>';
                                            }
                                            echo "</tr>";

                                        }
                                    }

                                    ?>

                                </tbody>

                            </table>
                        </div>
                    </center>
                </td>
            </tr>



        </table>
    </div>
    </div>
    <?php

    if ($_GET) {
        $id = $_GET["id"];
        $action = $_GET["action"];
        if ($action == 'booking-added') {

            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                    <br><br>
                        <h2>Reserva realizada con éxito.</h2>
                        <a class="close" href="appointment.php">&times;</a>
                        <div class="content">
                        Tu número de cita es ' . $id . '.<br><br>
                            
                        </div>
                        <div style="display: flex;justify-content: center;">
                        
                        <a href="appointment.php" class="non-style-link"><button  class="btn-primary btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"><font class="tn-in-text">&nbsp;&nbsp;OK&nbsp;&nbsp;</font></button></a>
                        <br><br><br><br>
                        </div>
                    </center>
            </div>
            </div>
            ';
        } elseif ($action == 'drop') {
            $title = $_GET["title"];
            $docname = $_GET["doc"];

            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                        <h2>Estás segur@?</h2>
                        <a class="close" href="appointment.php">&times;</a>
                        <div class="content">
                            Deseas cancelar esta cita?<br><br>
                            Nombre de Cita: &nbsp;<b>' . substr($title, 0, 40) . '</b><br>
                            Nombre Doctor&nbsp; : <b>' . substr($docname, 0, 40) . '</b><br><br>
                            
                        </div>
                        <div style="display: flex;justify-content: center;">
                        <a href="delete-appointment.php?id=' . $id . '" class="non-style-link"><button  class="btn-primary btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"<font class="tn-in-text">&nbsp;Si&nbsp;</font></button></a>&nbsp;&nbsp;&nbsp;
                        <a href="appointment.php" class="non-style-link"><button  class="btn-primary btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"><font class="tn-in-text">&nbsp;&nbsp;No&nbsp;&nbsp;</font></button></a>

                        </div>
                    </center>
            </div>
            </div>
            ';
        } elseif ($action == 'view') {
            $sqlmain = "select * from doctor where docid='$id'";
            $result = $database->query($sqlmain);
            $row = $result->fetch_assoc();
            $name = $row["docname"];
            $email = $row["docemail"];
            $spe = $row["specialties"];

            $spcil_res = $database->query("select sname from specialties where id='$spe'");
            $spcil_array = $spcil_res->fetch_assoc();
            $spcil_name = $spcil_array["sname"];
            $nic = $row['docnic'];
            $tele = $row['doctel'];
            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                        <h2></h2>
                        <a class="close" href="doctors.php">&times;</a>
                        <div class="content">
                            ConfiguroWeb<br>
                            
                        </div>
                        <div style="display: flex;justify-content: center;">
                        <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                        
                            <tr>
                                <td>
                                    <p style="padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;">Ver Detalles</p><br><br>
                                </td>
                            </tr>
                            
                            <tr>
                                
                                <td class="label-td" colspan="2">
                                    <label for="name" class="form-label">Nombre: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    ' . $name . '<br><br>
                                </td>
                                
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="Correo" class="form-label">Correo: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                ' . $email . '<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="nic" class="form-label">Documento de Identificación: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                ' . $nic . '<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="Tele" class="form-label">Teléfono:: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                ' . $tele . '<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="spec" class="form-label">Especialidad: </label>
                                    
                                </td>
                            </tr>
                            <tr>
                            <td class="label-td" colspan="2">
                            ' . $spcil_name . '<br><br>
                            </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="doctors.php"><input type="button" value="OK" class="login-btn btn-primary-soft btn" ></a>
                                
                                    
                                </td>
                
                            </tr>
                           

                        </table>
                        </div>
                    </center>
                    <br><br>
            </div>
            </div>
            ';
        } elseif (isset($_GET['action']) && $_GET['action'] === 'reschedule' && isset($_GET['id'])) {
            $id = $_GET['id'];
            // Obtener fecha y hora actuales
            $res = $database->query("SELECT schedule.scheduledate, schedule.scheduletime FROM appointment INNER JOIN schedule ON appointment.scheduleid = schedule.scheduleid WHERE appointment.appoid='$id'");
            $row = $res->fetch_assoc();
            $fecha_actual = $row['scheduledate'];
            $hora_actual = $row['scheduletime'];
            $hoy = date('Y-m-d');
            echo '<div id="popup1" class="overlay">
        <div class="popup">
            <center>
                <h2>Reagendar Cita</h2>
                <a class="close" href="appointment.php">&times;</a>
                <div class="content">
                    <form action="appointment.php" method="POST">
                        <input type="hidden" name="reschedule_id" value="' . $id . '">
                        <label>Fecha nueva:</label><br>
                        <input type="date" name="new_date" class="input-text" value="' . $fecha_actual . '" min="' . $hoy . '" required><br><br>
                        <label>Hora nueva:</label><br>
                        <input type="time" name="new_time" class="input-text" value="' . $hora_actual . '" required><br><br>
                        <input type="submit" name="reschedule_submit" value="Guardar Cambios" class="btn-primary btn">
                    </form>
                </div>
            </center>
        </div>
    </div>';
        }
    }
    if (isset($_POST['reschedule_submit'])) {
        $appoid = $_POST['reschedule_id'];
        $new_date = $_POST['new_date'];
        $new_time = $_POST['new_time'];
        // Buscar el scheduleid de la cita
        $res = $database->query("SELECT scheduleid FROM appointment WHERE appoid='$appoid'");
        $row = $res->fetch_assoc();
        $scheduleid = $row['scheduleid'];
        // Actualizar la fecha y hora en schedule
        $database->query("UPDATE schedule SET scheduledate='$new_date', scheduletime='$new_time' WHERE scheduleid='$scheduleid'");
        // Cambiar estado a 'reagendada' (NO actualizar appodate)
        $database->query("UPDATE appointment SET appostatus='reagendada' WHERE appoid='$appoid'");
        // Eliminar el EVENT previo si existe para evitar conflictos
        $database->query("DROP EVENT IF EXISTS set_pending_$appoid;");
        // Programar cambio a 'pendiente' en 5 minutos usando un EVENT de MySQL
        $database->query("CREATE EVENT set_pending_$appoid ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL 5 MINUTE DO UPDATE appointment SET appostatus='pendiente' WHERE appoid='$appoid' AND appostatus='reagendada';");
        session_write_close();
        header('Location: appointment.php');
        exit();
    }

    // Al inicio del <body> o antes de mostrar las citas:
    if (isset($_GET['justRescheduled'])) {
        echo '<script>if(window.location.search.indexOf("justRescheduled")!==-1){window.location.replace("appointment.php");}</script>';
    }
    ?>
    </div>

</body>

</html>