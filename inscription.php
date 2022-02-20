<?php 
    // fichier init.php est la première inclusion
    include_once("include/init.php");

    $nomErreur = "";
    $prenomErreur = "";
    $emailErreur = "";
    $mdpErreur = "";

    if($_POST)
    {
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";

        foreach($_POST as $value)
        {
            if(!empty($value))
            {
                $counter++;
            }
        }

        // si $counter est supérieur à zéro c'est qu'il y a des données dans le $_POST
        if($counter > 0)
        {
            // si l'email saisi dans le formulaire est défini
            if($_POST['email'])
            {
                // requête de sélection lorsque l'email est égal à celui dans le form $_POST['email']

                // 1e étape : préparation de la requête
                $pdoStatementObject = $pdoObject->prepare("SELECT * FROM membre WHERE email = :email");

                // 2e étape : association des marqueurs à leurs valeurs et leurs types
                $pdoStatementObject->bindValue(":email", $_POST['email'], PDO::PARAM_STR);

                // 3e étape : exécution de la requête 
                $pdoStatementObject->execute();


                $membreArray = $pdoStatementObject->fetch();

                /*

                    2 possibilités pour $membreArray
                    si l'email saisi existe en bdd, $membreArray contiendra les données de l'utilisateur
                    si l'email saisi n'existe pas, $membreArray ne contient rien

                */

                // if($membreArray)
                // {
                //     echo "<pre>";
                //     print_r($membreArray);
                //     echo "</pre>";
                // }
                // else
                // {
                //     echo "l'email n'est pas associé à un compte";
                // }

                if(!$membreArray) // $membreArray 
                {
                    if( strlen($_POST['nom']) < 3  || strlen($_POST['nom']) > 30 )
                    {
                        $nomErreur = "<p class='text-center text-danger'>Veuillez saisir un nom entre 3 et 30 caractères</p>";
                        $counterErreur++;
                    }
        
                    if( strlen($_POST['prenom']) < 3  || strlen($_POST['prenom']) > 30 )
                    {
                        $prenomErreur = "<p class='text-center text-danger'>Veuillez saisir un prenom entre 3 et 30 caractères</p>";
                        $counterErreur++;
                    }


                    if($_POST['mdp'] != $_POST["confirm_mdp"])
                    {
                        $mdpErreur = "<p class='text-center text-danger'>Les mots de passe ne sont pas identiques</p>";
                        $counterErreur++;
                    }
                    else // mdp identiques
                    {
                        if(!$_POST['mdp'])
                        {
                            $mdpErreur = "<p class='text-center text-danger'>Veuillez saisir les mots de passe</p>";
                            $counterErreur++;
                        }
        
                      
        
                    }   


                    if(empty($counterErreur))
                    {
                        // hashage du mot de passe
                        // password_hash()
                        // 2 arguments
                        // 1e : la string à hasher
                        // 2e : clé de hashage : PASSWORD_DEFAULT PASSWORD_BCRYPT

                        $mdpHash = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

                        //echo $mdpHash; die;
                        // die; permet de stopper le code
                        // insertion en bdd

                        // 1e étape 
                        $pdoStatementObject = $pdoObject->prepare("INSERT INTO membre (email, mdp, nom, prenom, statut, date_enregistrement) VALUES (:email, :mdp, :nom, :prenom, :statut, :date_enregistrement)");

                        // 2e étape
                        $pdoStatementObject->bindValue(":email", $_POST['email'], PDO::PARAM_STR);
                        $pdoStatementObject->bindValue(":mdp", $mdpHash, PDO::PARAM_STR);
                        $pdoStatementObject->bindValue(":nom", $_POST['nom'], PDO::PARAM_STR);
                        $pdoStatementObject->bindValue(":prenom", $_POST['prenom'], PDO::PARAM_STR);
                        $pdoStatementObject->bindValue(":statut", 0, PDO::PARAM_INT);
                        $pdoStatementObject->bindValue(":date_enregistrement", date("Y-m-d H:i:s"), PDO::PARAM_STR);
        
                        // 3e étape 
                        $pdoStatementObject->execute();


                        // notification ==> connexion.php

                        // redirection 
                        // s'il y a un pb dans le code, avec la redirection on change de page mais l'erreur ne suit pas 
                        header("Location:" . URL . "connexion.php");exit;
                    }

                }
                else
                {
                    $emailErreur = "<div class='text-danger'>L'email $_POST[email] est déjà associé à un compte.</div>";
                }



                
            }
            else // pas d'email saisi dans le form
            {
                $emailErreur = "<div class='text-danger'>Veuillez saisir votre email</div>";
            }
        }
        else // $counter reste à zéro
        {
            $notification = "<div class='alert alert-danger text-center col-md-6 mx-auto'>Veuillez remplir le formulaire</div>";
        }


    } // if($_POST)


    include_once("include/header.php");
?>


    <h1 class="text-center m-3">Inscription</h1>


    <?= $notification ?>

    <form method="post" class="col-md-6 mx-auto">


        <div class="form-group mb-4">
            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" placeholder="Saisir le prenom" class="form-control" name="prenom">
            <?= $prenomErreur ?>
        </div>

        <div class="form-group mb-4">
            <label for="nom">Nom</label>
            <input type="text" id="nom" placeholder="Saisir le nom" class="form-control" name="nom">
            <?= $nomErreur ?>
        </div>
        
        <div class="form-group mb-4">
            <label for="email">Email</label>
            <input type="text" id="email" placeholder="Saisir l'email" class="form-control" name="email">
            <?= $emailErreur ?>
        </div>


        <div class="form-group mb-4">
            <label for="mdp">Mot de passe</label>
            <input type="text" id="mdp" placeholder="Saisir le mot de passe" class="form-control" name="mdp">
            <?= $mdpErreur ?>
        </div>


        <div class="form-group mb-4">
            <label for="confirm_mdp">Confirmation du mot de passe</label>
            <input type="text" id="confirm_mdp" placeholder="confirmer le mot de passe" class="form-control" name="confirm_mdp">
        </div>







        <input type="submit" class="btn btn-success col-12" value="Envoyer">


    </form>



<?php 

    include_once("include/footer.php");
?>