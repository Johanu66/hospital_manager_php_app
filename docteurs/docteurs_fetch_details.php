<?php
    include("../control_if_user_is_connected.php");
    $query = "SELECT * FROM docteur
    INNER JOIN personne ON id_personne_fk_docteur = id_personne
    INNER JOIN specialite ON id_specialite_fk_docteur = id_specialite
    INNER JOIN departement ON id_departement_fk_docteur = id_departement
    WHERE id_docteur = ?";

    $statement = $bdd->prepare($query);
    $statement->execute(array($_POST["id_view"]));
    $result = $statement->fetchAll();
    $output = '
    <div class="table-responsive">
        <table class="table">
    ';

    //$nom_personne ='';

    foreach ($result as $row) {
        $output .= '
        <tr>
            <td>Photo de profil</td>
            <td><div style="width: 80px; height: 80px; overflow: hidden; border-radius: 50%;"><img style="height: 100%; margin: auto;" src="../img/personnes/'.$row['photo_personne'].'"></div></td>
        </tr>
        <tr>
            <td>Civilité</td>
            <td>';
        if($row['sexe_personne']=="M"){
            $output .= "Monsieur";
        }
        else{
            $output .= "Madame";
        }
            
        $output .= '</td>
        </tr>
        <tr>
            <td>Nom</td>
            <td>' . $row["nom_personne"] . '</td>
        </tr>
        <tr>
            <td>Prénom</td>
            <td>' . $row["prenom_personne"] . '</td>
        </tr>
        <tr>
            <td>Email</td>
            <td>' . $row["email_personne"] . '</td>
        </tr>
        <tr>
            <td>Téléphone</td>
            <td>' . $row["tel_personne"] . '</td>
        </tr>
        <tr>
            <td>Adresse</td>
            <td>' . $row["adresse_personne"] . '</td>
        </tr>
        <tr>
            <td>Spécialité</td>
            <td>' . $row["nom_specialite"] . '</td>
        </tr>
        <tr>
            <td>Service</td>
            <td>' . $row["nom_departement"] . '</td>
        </tr>
        <tr>
            <td>Notes</td>
            <td>' . $row["notes_docteur"] . '</td>
        </tr>
        <tr>
            <td>Date de prise de fonction</td>
            <td>' . date("d-m-Y", strtotime($row["date_prise_fonction_docteur"])) . '</td>
        </tr>
        <tr>
            <td>Statut</td>
            <td>';
        if($row['statut_personne']=="Actif"){
            $output .= "<span class='badge badge-pill badge-success mb-1'>".$row['statut_personne']."</span>";
        }
        else if($row['statut_personne']=="Inactif"){
            $output .= "<span class='badge badge-pill badge-danger mb-1'>".$row['statut_personne']."</span>";
        }
        $output .= '</td>
        </tr>
        <tr>
            <td>Date de création</td>
            <td>' . date("d-m-Y", strtotime($row["date_create_personne"])) . ' à ' . date("H:i", strtotime($row["date_create_personne"])) . '</td>
        </tr>
        <tr>
            <td>Dernièrement modifié le</td>
            <td>' . date("d-m-Y", strtotime($row["date_last_modif_personne"])) . ' à ' . date("H:i", strtotime($row["date_last_modif_personne"])) . '</td>
        </tr>
        <tr>
            <td>Enregistrée par</td>
            <td>' . $row['user_create_personne'] . '</td>
        </tr>
        <tr>
            <td>Dernièrement modifié par</td>
            <td>' . $row['user_create_personne'] . '</td>
        </tr>
        ';
    }


    $output .= '
        </table>
    </div>
    ';
    
    header('Content-Type: application/json');
    echo json_encode($output);