<?php
    include("../db.php");
    $output = "";
    if(isset($_POST['id_docteur']) AND !empty($_POST['id_docteur'])){
        $statement = $bdd->prepare("SELECT * FROM docteur INNER JOIN personne ON id_personne_fk_docteur=id_personne INNER JOIN departement ON id_departement_fk_docteur=id_departement WHERE del_personne = '0' AND id_docteur = ?");
        $statement->execute(array($_POST['id_docteur']));
        $row = $statement->fetch();
        $output = $row['nom_departement'];
    }
    echo json_encode($output);