<?php
    include("../control_if_user_is_connected.php");
    if(isset($_POST['id'])){
        $now = new DateTime();
        update("planning",[
            "del_planning" => '1',
            "date_del_planning" => $now->format('Y-m-d H:i:s'),
            "user_del_planning" => $_SESSION['nom']." ".$_SESSION['prenom']
        ], " id_planning = ".$_POST['id']);

        echo json_encode(" ");
    }