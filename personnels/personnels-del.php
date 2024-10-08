<?php
    include("../control_if_user_is_connected.php");
    include("../if_test_env_json.php");
    if(isset($_POST['id']) && !$_TEST_ENV){
        update(" personnel INNER JOIN personne ON id_personne_fk_personnel = id_personne ",[
            "del_personne" => "1"
        ]," id_personnel = '".$_POST['id']."'");
        $statement = $bdd->prepare("SELECT * FROM personnel INNER JOIN personne ON id_personne_fk_personnel = id_personne  WHERE id_personnel = ?");
        $statement->execute(array($_POST['id']));
        $result = $statement->fetch();
        
        header('Content-Type: application/json');
        echo json_encode($result['nom_personne']." ".$result['prenom_personne']);
        // Vérification d'erreur
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo 'Erreur JSON : ' . json_last_error_msg();
        }
    }