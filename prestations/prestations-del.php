<?php
    include("../control_if_user_is_connected.php");
    if(isset($_POST['id'])){
        $statement = $bdd->prepare("SELECT * FROM prestation WHERE id_prestation = ?");
        $statement->execute(array($_POST['id']));
        $result = $statement->fetch();
        $now = new DateTime();

        update("prestation",[
            "del_prestation" => '1',
            "date_del_prestation" => $now->format('Y-m-d H:i:s'),
            "user_del_prestation" => $_SESSION['nom']." ".$_SESSION['prenom']
        ], " id_prestation = ".$_POST['id']);

        echo json_encode($result['nom_prestation']);
    }