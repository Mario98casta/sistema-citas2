<?php

    session_start();

    if(isset($_SESSION["user"])){
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='admin'){
            header("location: ../login.php");
        }

    }else{
        header("location: ../login.php");
    }
    
    
    if($_GET){
        //import database
        include("../connection.php");
        $id=$_GET["id"];
        // Cambiar estado a 'cancelada' en vez de borrar
        $sql= $database->query("UPDATE appointment SET appostatus='cancelada' WHERE appoid='$id';");
        header("location: appointment.php");
    }


?>