<?php
    include("../control_if_user_is_connected.php");
    include("../if_test_env_json.php");
    if(isset($_POST['id']) && !$_TEST_ENV){
        $statement = $bdd->prepare("SELECT * FROM docteur INNER JOIN personne ON id_personne_fk_docteur = id_personne  WHERE id_docteur = ?");
        $statement->execute(array($_POST['id']));
        $result = $statement->fetch();

        $statement = $bdd->prepare("DELETE FROM docteur WHERE id_docteur = ?");
        $statement->execute(array($_POST['id']));

        $statement = $bdd->prepare("DELETE FROM personne WHERE id_personne = ?");
        $statement->execute(array($result['id_personne']));
        $docteur = $result['nom_personne']." ".$result['prenom_personne'];
        
        header('Content-Type: application/json');
        echo json_encode($docteur);
        // Vérification d'erreur
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo 'Erreur JSON : ' . json_last_error_msg();
        }
    }