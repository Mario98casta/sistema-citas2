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
    <?php

    $sqlmain = "select * from schedule inner join doctor on schedule.docid=doctor.docid where schedule.scheduledate>='$today'  order by schedule.scheduledate asc";
    $sqlpt1 = "";
    $insertkey = "";
    $q = '';
    $searchtype = "Cantidad ";
    if ($_POST) {
        //print_r($_POST);

        if (!empty($_POST["search"])) {

            $keyword = $_POST["search"];
            $sqlmain = "select * from schedule inner join doctor on schedule.docid=doctor.docid where schedule.scheduledate>='$today' and (doctor.docname='$keyword' or doctor.docname like '$keyword%' or doctor.docname like '%$keyword' or doctor.docname like '%$keyword%' or schedule.title='$keyword' or schedule.title like '$keyword%' or schedule.title like '%$keyword' or schedule.title like '%$keyword%' or schedule.scheduledate like '$keyword%' or schedule.scheduledate like '%$keyword' or schedule.scheduledate like '%$keyword%' or schedule.scheduledate='$keyword' )  order by schedule.scheduledate asc";
            //echo $sqlmain;
            $insertkey = $keyword;
            $searchtype = "Resultados de búsqueda: ";
            $q = '"';
        }
    }


    $result = $database->query($sqlmain)


    ?>

    <div class="dash-body">
        <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:25px; ">
            <tr>
                <td width="13%">
                    <a href="index.php"><button class="login-btn btn-primary-soft btn btn-icon-back" style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px">
                            <font class="tn-in-text">Volver</font>
                        </button></a>
                </td>
                <td>
                    <form action="" method="post" class="header-search">

                        <input type="search" name="search" class="input-text header-searchbar" placeholder="Búsqueda por Nombre, Doctor o Correo or Date (DD-MM-YYYY)" list="doctors" value="<?php echo $insertkey ?>">&nbsp;&nbsp;

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
                    <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)"><?php echo $searchtype . " Citas" . "(" . $result->num_rows . ")"; ?> </p>
                    <p class="heading-main12" style="margin-left: 45px;font-size:22px;color:rgb(49, 49, 49)"><?php echo $q . $insertkey . $q; ?> </p>
                </td>

            </tr>



            <tr>
                <td colspan="4">
                    <center>
                        <div class="abc scroll">
                            <table width="100%" class="sub-table scrolldown" border="0" style="padding: 50px;border:none">

                                <tbody>

                                    <?php




                                    if ($result->num_rows == 0) {
                                        echo '<tr>
                                    <td colspan="4">
                                    <br><br><br><br>
                                    <center>
                                    <img src="../img/notfound.svg" width="25%">
                                    
                                    <br>
                                    <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">We  couldnt find anything related to your keywords !</p>
                                    <a class="non-style-link" href="schedule.php"><button  class="login-btn btn-primary-soft btn"  style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Mostrar todas las sesiones &nbsp;</font></button>
                                    </a>
                                    </center>
                                    <br><br><br><br>
                                    </td>
                                    </tr>';
                                    } else {
                                        //echo $result->num_rows;
                                        for ($x = 0; $x < ($result->num_rows); $x++) {
                                            echo "<tr>";
                                            for ($q = 0; $q < 3; $q++) {
                                                $row = $result->fetch_assoc();
                                                $scheduleid = isset($row["scheduleid"]) ? $row["scheduleid"] : '';
                                                $title = isset($row["title"]) ? $row["title"] : '';
                                                $docname = isset($row["docname"]) ? $row["docname"] : '';
                                                $scheduledate = isset($row["scheduledate"]) ? $row["scheduledate"] : '';
                                                $scheduletime = isset($row["scheduletime"]) ? $row["scheduletime"] : '';

                                                if ($scheduleid == "") {
                                                    break;
                                                }

                                                echo '
                                        <td style="width: 25%;">
                                                <div  class="dashboard-items search-items"  >
                                                
                                                    <div style="width:100%">
                                                            <div class="h1-search">
                                                                ' . substr($title, 0, 21) . '
                                                            </div><br>
                                                            <div class="h3-search">
                                                                ' . substr($docname, 0, 30) . '
                                                            </div>
                                                            <div class="h4-search">
                                                                ' . $scheduledate . '<br>Empieza: <b>@' . substr($scheduletime, 0, 5) . '</b> (24h)
                                                            </div>
                                                            <br>
                                                            <a href="booking.php?id=' . $scheduleid . '" ><button  class="login-btn btn-primary-soft btn "  style="padding-top:11px;padding-bottom:11px;width:100%"><font class="tn-in-text">Reservar Ahora</font></button></a>
                                                    </div>
                                                            
                                                </div>
                                            </td>';
                                            }
                                            echo "</tr>";


                                            // echo '<tr>
                                            //     <td> &nbsp;'.
                                            //     substr($title,0,30)
                                            //     .'</td>

                                            //     <td style="text-align:center;">
                                            //         '.substr($scheduledate,0,10).' '.substr($scheduletime,0,5).'
                                            //     </td>
                                            //     <td style="text-align:center;">
                                            //         '.$nop.'
                                            //     </td>

                                            //     <td>
                                            //     <div style="display:flex;justify-content: center;">

                                            //     <a href="?action=view&id='.$scheduleid.'" class="non-style-link"><button  class="btn-primary-soft btn button-icon btn-view"  style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;"><font class="tn-in-text">Ver</font></button></a>
                                            //    &nbsp;&nbsp;&nbsp;
                                            //    <a href="?action=drop&id='.$scheduleid.'&name='.$title.'" class="non-style-link"><button  class="btn-primary-soft btn button-icon btn-delete"  style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;"><font class="tn-in-text">Cancelar Sesión</font></button></a>
                                            //     </div>
                                            //     </td>
                                            // </tr>';

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