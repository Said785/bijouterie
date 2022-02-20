<?php

function tableau($tableau)
{
    echo'<hr>';
    echo '<pre>';
    print_r($tableau);
    echo'</pre>';
    echo '<hr>';
}

function membreConnecte()
{
    if(isset($_SESSION['membre']))

        return true;
    else
    {
        return false ;
    }

}


/*
 le but de la connexion est que si tout est ok, on  cree unn tableau  'membre' dans la $_SESSION 
 le bit de cette fonction est de verifier si ce tableau est defini dans la $_SESSION 

 si oui ==> fonction, retourne un boolean true 
 si non ==> fonction, retourne un boolran flase

 */