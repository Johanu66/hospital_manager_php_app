<?php
    include("../control_if_user_is_connected.php");
    $menu_docteurs = "active";
?>
<!doctype html>
<html class="no-js" lang="en">
<?php include("../parts/head.php"); ?>

    <body>
        <div class="wrapper">
            <?php include("../parts/header.php") ?>
            <div class="page-wrap">
                <?php include("../parts/sidebar.php") ?>

                <?php include("../if_test_env.php") ?>

                <div class="main-content">
                    <div class="container-fluid">
                        <div class="page-header">
                            <div class="row align-items-end">
                                <div class="col-lg-8">
                                    <div class="page-header-title">
                                        <i class="ik ik-inbox bg-blue"></i>
                                        <div class="d-inline">
                                            <h5>Modifier un docteur</h5>
                                            <span>En remplissant ce formulaire, vous pouvez modifier un docteur existant.</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item">
                                                <a href="/"><i class="ik ik-home"></i></a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="#">Docteur</a>
                                            </li>
                                            <li class="breadcrumb-item active" aria-current="page">Modifier docteur</li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <?php
                            if(isset($_POST['submit']) && !$_TEST_ENV){
                                if(!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['email']) && !empty($_POST['sexe']) && !empty($_POST['specialite']) && !empty($_POST['departement']) && !empty($_POST['date_prise_fonction']) && !empty($_POST['adresse']) && !empty($_POST['tel']) && !empty($_POST['date_naissance'])){
                                    $id_docteur = htmlspecialchars($_POST['id_docteur']);
                                    $statement = $bdd->prepare("SELECT * FROM personne INNER JOIN docteur ON id_personne = id_personne_fk_docteur INNER JOIN specialite ON id_specialite_fk_docteur = id_specialite INNER JOIN departement ON id_departement = id_departement_fk_docteur WHERE id_docteur = ?");
                                    $statement->execute(array($id_docteur));
                                    $value = $statement->fetch();
                                    $photo = $value['photo_personne'];
                                    // Testons si le fichier a bien été envoyé et s'il n'y a pas d'erreur
                                    if (isset($_FILES['photo']) AND $_FILES['photo']['error'] == 0)
                                    {
                                        // Testons si le fichier n'est pas trop gros
                                        if ($_FILES['photo']['size'] <= 1000000)
                                        {
                                            // Testons si l'extension est autorisée
                                            $infosfichier = pathinfo($_FILES['photo']['name']);
                                            $extension_upload = $infosfichier['extension'];
                                            $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
                                            if (in_array($extension_upload, $extensions_autorisees))
                                            {
                                                // On peut valider le fichier et le stocker définitivement
                                                //unlink("../img/personnes/".$photo);
                                                $photo = htmlspecialchars(basename($_FILES['photo']['name']));
                                                move_uploaded_file($_FILES['photo']['tmp_name'], "../img/personnes/".$photo);
                                                
                                            }
                                            else{
                                                ?>
                                                <script>swal("Échoué", "Extension de la photo de profil n'est acceptable.", "error")</script>
                                                <?php
                                            }
                                        }
                                        else{
                                            ?>
                                            <script>swal("Échoué", "La photo de profil est trop volumineux.", "error")</script>
                                            <?php
                                        }            
                                    }
                                    else{
                                        
                                    }
                                    $nom = htmlspecialchars($_POST['nom']);
                                    $prenom = htmlspecialchars($_POST['prenom']);
                                    $email = htmlspecialchars($_POST['email']);
                                    $sexe = htmlspecialchars($_POST['sexe']);
                                    $specialite = htmlspecialchars($_POST['specialite']);
                                    $departement = htmlspecialchars($_POST['departement']);
                                    $date_prise_fonction = htmlspecialchars($_POST['date_prise_fonction']);
                                    $adresse = htmlspecialchars($_POST['adresse']);
                                    $tel = htmlspecialchars($_POST['tel']);
                                    $date_naissance = htmlspecialchars($_POST['date_naissance']);
                                    $notes = htmlspecialchars($_POST['notes']);
                                    $now = new DateTime();
                                    update(" docteur INNER JOIN personne ON id_personne_fk_docteur = id_personne ",[
                                        "date_prise_fonction_docteur" => $date_prise_fonction,
                                        "notes_docteur" => $notes,
                                        "id_specialite_fk_docteur" => $specialite,
                                        "id_departement_fk_docteur" => $departement,
                                        "nom_personne" => $nom,
                                        "prenom_personne" => $prenom,
                                        "email_personne" => $email,
                                        "tel_personne" => $tel,
                                        "adresse_personne" => $adresse,
                                        "sexe_personne" => $sexe,
                                        "date_naissance_personne" => $date_naissance,
                                        "photo_personne" => $photo,
                                        "date_last_modif_personne" => $now->format('Y-m-d H:i:s'),
                                        "user_last_modif_personne" => $_SESSION['nom']." ".$_SESSION['prenom']
                                    ],"id_docteur = '".$id_docteur."'");
                                    ?>
                                    <script>
                                        window.location.replace("docteurs.php?success=Le docteur <?php echo $nom." ".$prenom; ?> a été modifié.");
                                    </script>
                                    <?php
                                }
                                else{
                                    ?>
                                    <script>swal("Échoué", "Veuillez renseigner tous les chmaps.", "error")</script>
                                    <?php
                                }
                            }

                            if(isset($_GET['id']) && !empty($_GET['id'])){
                                $statement = $bdd->prepare("SELECT * FROM personne INNER JOIN docteur ON id_personne = id_personne_fk_docteur INNER JOIN specialite ON id_specialite_fk_docteur = id_specialite INNER JOIN departement ON id_departement = id_departement_fk_docteur WHERE id_docteur = ?");
                                $statement->execute(array($_GET['id']));
                                $old_value = $statement->fetch();
                            }

                        ?>
                        <h1><?php  ?></h1>
                        <div class="row">
                            <div class="m-auto col-md-11">
                                    <div class="card">
                                        <div class="card-header"><h3>Formulaire de modification de docteur</h3></div>
                                        <div class="card-body">
                                            <form class="forms-sample" enctype="multipart/form-data" action="" method="post">
                                                <input type="text" name="id_docteur" value="<?php if(isset($_POST['id_docteur'])){ echo($_POST['id_docteur']); }else if(isset($old_value)){ echo($old_value['id_docteur']); }  ?>" style="display:none;">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputName1">Nom</label>
                                                            <input type="text" name="nom" value="<?php if(isset($_POST['nom'])){ echo($_POST['nom']); }else if(isset($old_value)){ echo($old_value['nom_personne']); }  ?>" class="form-control" id="exampleInputName1">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputName1">Prénom</label>
                                                            <input type="text" name="prenom" value="<?php if(isset($_POST['prenom'])){ echo($_POST['prenom']); }else if(isset($old_value)){ echo($old_value['prenom_personne']); } ?>" class="form-control" id="exampleInputName1">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail3">Email</label>
                                                            <input type="email" name="email" value="<?php if(isset($_POST['email'])){ echo($_POST['email']); }else if(isset($old_value)){ echo($old_value['email_personne']); } ?>" class="form-control" id="exampleInputEmail3">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleSelectGender">Sexe</label>
                                                            <select name="sexe" class="form-control" id="exampleSelectGender">
                                                                <option value="">---</option>
                                                                <option value="M" <?php if(isset($_POST['sexe'])){ if($_POST['sexe']=="M") echo("selected"); }else if(isset($old_value)){ if($old_value['sexe_personne']=="M") echo("selected"); } ?>>Masculin</option>
                                                                <option value="F" <?php if(isset($_POST['sexe'])){ if($_POST['sexe']=="F") echo("selected"); }else if(isset($old_value)){ if($old_value['sexe_personne']=="F") echo("selected"); } ?>>Féminin</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputName1">Spécialités</label>
                                                            <select name="specialite" class="form-control" id="exampleSelectGender">
                                                                <option value="">---</option>
                                                                <?php
                                                                    if(isset($_POST['specialite'])){ 
                                                                        echo(charger_specialite($_POST['specialite']));
                                                                    }
                                                                    else if(isset($old_value)){
                                                                        echo(charger_specialite($old_value['id_specialite']));
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputName1">Service</label>
                                                            <select name="departement" class="form-control" id="exampleSelectGender">
                                                                <option value="">---</option>
                                                                <?php
                                                                    if(isset($_POST['departement'])){ 
                                                                        echo(charger_departement($_POST['departement']));
                                                                    }
                                                                    else if(isset($old_value)){
                                                                        echo(charger_departement($old_value['id_departement']));
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputName1">Adresse</label>
                                                            <input type="text" name="adresse" value="<?php if(isset($_POST['adresse'])){ echo($_POST['adresse']); }else if(isset($old_value)){ echo($old_value['adresse_personne']); } ?>" class="form-control" id="exampleInputName1">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputName1">Téléphone</label>
                                                            <input type="text" name="tel" value="<?php if(isset($_POST['tel'])){ echo($_POST['tel']); }else if(isset($old_value)){ echo($old_value['tel_personne']); } ?>" class="form-control" id="exampleInputName1">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputName1">Date de naissance</label>
                                                            <input type="date" name="date_naissance" value="<?php if(isset($_POST['date_naissance'])){ echo($_POST['date_naissance']); }else if(isset($old_value)){ echo($old_value['date_naissance_personne']); } ?>" class="form-control" id="exampleInputName1">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="exampleInputName1">Date de prise de fonction</label>
                                                            <input type="date" name="date_prise_fonction" value="<?php if(isset($_POST['date_prise_fonction'])){ echo($_POST['date_prise_fonction']); }else if(isset($old_value)){ echo($old_value['date_prise_fonction_docteur']); } ?>" class="form-control" id="exampleInputName1">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Photo</label>
                                                            <input type="file" name="photo" class="file-upload-default">
                                                            <div class="input-group col-xs-12">
                                                                <input type="text" class="form-control file-upload-info" disabled>
                                                                <span class="input-group-append">
                                                                <button class="file-upload-browse btn btn-primary" type="button">Choisir</button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleTextarea1">Notes</label>
                                                    <textarea class="form-control" name="notes" id="exampleTextarea1" rows="4"><?php if(isset($_POST['notes'])){ echo($_POST['notes']); }else if(isset($old_value)){ echo($old_value['notes_docteur']); } ?></textarea>
                                                </div>
                                                <button type="submit" name="submit" class="btn btn-primary mr-2">Enregistrer</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php include("../parts/footer.php") ?>
                </div>  
            </div>
        </div>
        
        <script src="../assets/modules/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="src/js/vendor/jquery-3.3.1.min.js"><\/script>')</script>
        <script src="../plugins/popper.js/dist/umd/popper.min.js"></script>
        <script src="../plugins/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="../plugins/perfect-scrollbar/dist/perfect-scrollbar.min.js"></script>
        <script src="../plugins/screenfull/dist/screenfull.js"></script>
        <script src="../plugins/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="../plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="../plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script src="../plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
        <script src="../plugins/jvectormap/jquery-jvectormap.min.js"></script>
        <script src="../plugins/jvectormap/tests/assets/jquery-jvectormap-world-mill-en.js"></script>
        <script src="../plugins/moment/moment.js"></script>
        <script src="../plugins/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js"></script>
        <script src="../plugins/d3/dist/d3.min.js"></script>
        <script src="../plugins/c3/c3.min.js"></script>
        <script src="../scripts_js/js/form-components.js"></script>
        <script src="../js/tables.js"></script>
        <script src="../js/widgets.js"></script>
        <script src="../js/charts.js"></script>
        <script src="../dist/js/theme.min.js"></script>

    </body>
</html>
