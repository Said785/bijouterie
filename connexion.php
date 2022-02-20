<?php

include_once("include/init.php");
    if(!membreConnecte())
{}

   

    
    

  
$emailErreur = "";
$mdpErreur = "";


if(isset($_SESSION['notification']) && isset($_SESSION['notification']['inscription']) && $_SESSION['notification']['inscription'] == "ajouter")

    $notification = "<div class='alert alert-success text-center col-md-6 mx-auto'>Votre inscription a bien été prise en compte, vous pouvez vous connecter</div>";


// if($_POST)
// {
//     echo "<pre>";
//     print_r($_POST);
//     echo "</pre>";
// }


if($_POST)
{
        tableau($_POST);

        foreach($_POST as $value)
        {
            if(!empty($value))
            {
                $counter++;
            }
        }

        if($counter > 0)
        {
            if($_POST['email'])
            {

                // 1e étape : préparation de la requête
                $pdoStatementObject = $pdoObject->prepare("SELECT * FROM membre WHERE email = :email");

                // 2e étape : association des marqueurs à leurs valeurs et leurs types
                $pdoStatementObject->bindValue(":email", $_POST['email'], PDO::PARAM_STR);

                // 3e étape : exécution de la requête 
                $pdoStatementObject->execute();


                $membreArray = $pdoStatementObject->fetch();

                //tableau($membreArray);die;
                // $membreArray peut retourner un tableau de données (email existe en bdd) sinon retourne du vide (email inexistant en bdd)

                if($membreArray)
                {
                    if($_POST['mdp'])
                    {
                        // comparer le mot de passe saisi dans le formulaire avec celui dans la bdd
                        // password_verify() Vérifie qu'un mot de passe correspond à un hachage
                        // retourne un boolean (true / false)
                        // 2 arguments
                        // 1e : str
                        // 2e : hash
                        if(password_verify($_POST['mdp'],$membreArray['mdp'] ))
                        {
                            // tout est ok, email et mdp

                            foreach($membreArray as $key => $value)
                            {
                                $_SESSION['membre'][$key] = $value;
                            }
                            // c'est la seule fois qu'on crée le tableau 'membre' dans la $_SESSION 

                            header("Location:" . URL . "index.php");
                        }
                        else
                        {
                            $mdpErreur = "<div class='text-danger'>Mot de passe incorrect</div>";
                        }
                    }
                    else
                    {
                        $mdpErreur = "<div class='text-danger'>Veuillez saisir votre mot de passe</div>";
                    }
                }
                else
                {
                    $emailErreur = "<div class='text-danger'>L'email $_POST[email] n'est pas associé à un compte. Veuillez vous <a href='" . URL ."inscription.php'>inscrire</a></div>";
                }
            }
            else // pas d'email saisi dans le form
            {
                $emailErreur = "<div class='text-danger'>Veuillez saisir votre email</div>";
            }
        }
        else
        {
            $notification = "<div class='alert alert-danger text-center col-md-6 mx-auto'>Veuillez remplir le formulaire</div>";
        }

 }





    


include_once("include/header.php");
?>



<h1 class="text-center m-4">Connexion</h1>

<form method="post" class="col-md-6 mx-auto">
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

        
        <input type="submit" class="btn btn-success col-12" value="Envoyer">
    </form>


<?php

    include_once("include/footer.php");

?>