<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/admin.css">

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



    date_default_timezone_set('America/Guatemala');

    $today = date('y-m-d');


    //echo $userid;
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
                    <td class="menu-btn menu-icon-home ">
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
        <td class="menu-btn menu-icon-session menu-active menu-icon-session-active">
            <a href="schedule.php" class="non-style-link-menu non-style-link-menu-active">
                <div>
                    <p class="menu-text">Citas</p>
                </div>
            </a>
        </td>
    </tr>
    <tr class="menu-row">
        <td class="menu-btn menu-icon-appoinment">
            <a href="appointment.php" class="non-style-link-menu">
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
                    <a href="schedule.php"><button class="login-btn btn-primary-soft btn btn-icon-back" style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px">
                            <font class="tn-in-text">Volver</font>
                        </button></a>
                </td>
                <td>
                    <form action="schedule.php" method="post" class="header-search">

                        <input type="search" name="search" class="input-text header-searchbar" placeholder="Búsqueda Doctor, Nombre, Correo, Fecha (DD-MM-YYYY)" list="doctors">&nbsp;&nbsp;

                        <?php
                        echo '<datalist id="doctors">';
                        $list11 = $database->query("select DISTINCT * from  doctor;");
                        $list12 = $database->query("select DISTINCT * from  schedule GROUP BY title;");





                        for ($y = 0; $y < $list11->num_rows; $y++) {
                            $row00 = $list11->fetch_assoc();
                            $d = $row00["docname"];

                            echo "<option value='$d'><br/>";
                        };


                        for ($y = 0; $y < $list12->num_rows; $y++) {
                            $row00 = $list12->fetch_assoc();
                            $d = $row00["title"];

                            echo "<option value='$d'><br/>";
                        };

                        echo ' </datalist>';
                        ?>


                        <input type="Submit" value="Búsqueda" class="login-btn btn-primary btn" style="padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;">
                    </form>
                </td>
                <td width="15%">
                    <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                        Fecha
                    </p>
                    <p class="heading-sub12" style="padding: 0;margin: 0;">
                        <?php


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
                    <!-- <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49);font-weight:400;">Citas / Booking / <b>Review Booking</b></p> -->

                </td>

            </tr>



            <tr>
                <td colspan="4">
                    <center>
                        <div class="abc scroll">
                            <table width="100%" class="sub-table scrolldown" border="0" style="padding: 50px;border:none">

                                <tbody>

                                    <?php

                                    if (($_GET)) {


                                        if (isset($_GET["id"])) {


                                            $id = $_GET["id"];

                                            $sqlmain = "select * from schedule inner join doctor on schedule.docid=doctor.docid where schedule.scheduleid=$id  order by schedule.scheduledate desc";

                                            //echo $sqlmain;
                                            $result = $database->query($sqlmain);
                                            $row = $result->fetch_assoc();
                                            $scheduleid = $row["scheduleid"];
                                            $title = $row["title"];
                                            $docname = $row["docname"];
                                            $docemail = $row["docemail"];
                                            $scheduledate = $row["scheduledate"];
                                            $scheduletime = $row["scheduletime"];
                                            $nop = isset($row["nop"]) ? (int)$row["nop"] : 0; // Obtener el número máximo de pacientes
                                            $sql2 = "select * from appointment where scheduleid=$id";
                                            //echo $sql2;
                                            $result12 = $database->query($sql2);
                                            $apponum = ($result12->num_rows) + 1;

                                            if ($apponum > $nop && $nop > 0) {
                                                // Si ya no hay cupos disponibles
                                                echo '<td colspan="2"><div class="dashboard-items search-items" style="text-align:center; padding:40px; color:red; font-size:20px;">No hay cupos disponibles para esta cita.</div></td>';
                                            } else {
                                                echo '
                                        <form action="booking-complete.php" method="post">
                                            <input type="hidden" name="scheduleid" value="' . $scheduleid . '" >
                                            <input type="hidden" name="apponum" value="' . $apponum . '" >
                                            <input type="hidden" name="date" value="' . $today . '" >

                                        
                                    
                                    ';


                                                // ...resto del código para mostrar la información y el botón de reserva...
                                                echo '
<td style="width: 50%;" rowspan="2">
    <div  class="dashboard-items search-items"  >
        <div style="width:100%">
            <div class="h1-search" style="font-size:25px;">
                Información Citas
            </div><br><br>
            <div class="h3-search" style="font-size:18px;line-height:30px">
                Nombre Doctor:  &nbsp;&nbsp;<b>' . $docname . '</b><br>
                Correo Doctor:  &nbsp;&nbsp;<b>' . $docemail . '</b> 
            </div>
            <div class="h3-search" style="font-size:18px;">
            </div><br>
            <div class="h3-search" style="font-size:18px;">
                Título Cita: ' . $title . '<br>
                Fecha programada de la sesión: ' . $scheduledate . '<br>
                Cita Empieza: ' . $scheduletime . '<br>
            </div>
            <br>
        </div>
    </div>
</td>
<td style="width: 25%;">
    <div  class="dashboard-items search-items"  >
        <div style="width:100%;padding-top: 15px;padding-bottom: 15px;">
            <div class="h1-search" style="font-size:20px;line-height: 35px;margin-left:8px;text-align:center;">
                Tu número de cita
            </div>
            <center>
                <div class=" dashboard-icons" style="margin-left: 0px;width:90%;font-size:70px;font-weight:800;text-align:center;color:var(--btnnictext);background-color: var(--btnice)">' . $apponum . '</div>
            </center>
        </div><br><br><br>
    </div>
</td>
</tr>
<tr>
    <td>
        <input type="Submit" class="login-btn btn-primary btn btn-book" style="margin-left:10px;padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;width:95%;text-align: center;" value="Reserva ya" name="booknow">
    </form>
    </td>
</tr>
';
                                            }
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



    </div>

</body>

</html>