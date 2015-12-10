<?php

// Si la constante n'est pas defini on bloque l'execution du fichier
if(!defined('INDEX_LOCK') || @INDEX_LOCK != 'ok') 
{
    die('Erreur 404 - Le fichier n\'a pas été trouvé');
}


class Collaborateur {

    public $id_collaborateur;
    public $prenom;
    public $nom;
    public $couleur;
}
