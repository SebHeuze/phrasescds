<?php

// Si la constante n'est pas defini on bloque l'execution du fichier
if(!defined('INDEX_LOCK') || @INDEX_LOCK != 'ok') 
{
    die('Erreur 404 - Le fichier n\'a pas été trouvé');
}

class BouletteManager {

    public static function getBoulettes(){
        global $file_db;

        $result = $file_db->query('SELECT * FROM boulette b, phrase p, collaborateur c'
           . ' WHERE b.id_boulette=p.id_boulette AND p.id_collaborateur = c.id_collaborateur ORDER BY b.timestamp DESC');
     
        $boulette = new Boulette;
        foreach($result as $row) {
            if ($boulette->id_boulette != $row['id_boulette']){
                if(count($boulette->phrases)>0){
                    $boulettes[] = $boulette;
                }
                $boulette = new Boulette;
                $boulette->id_boulette = $row['id_boulette'];
                $boulette->timestamp = $row['timestamp'];
            }

            $collaborateur = new Collaborateur;
            $collaborateur->id_collaborateur =  $row['id_collaborateur'];
            $collaborateur->prenom =  $row['prenom'];
            $collaborateur->nom =  $row['nom'];
            $collaborateur->couleur = $row['couleur'];

            $phrase = new Phrase;
            $phrase->id_phrase =  $row['id_phrase'];
            $phrase->message =  $row['message'];
            $phrase->collaborateur =  $collaborateur;
            $boulette->phrases[] = $phrase; 
        }
        $boulettes[] = $boulette;


        return $boulettes;
    }

}