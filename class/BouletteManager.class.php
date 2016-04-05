<?php

// Si la constante n'est pas defini on bloque l'execution du fichier
if(!defined('INDEX_LOCK') || @INDEX_LOCK != 'ok') 
{
    die('Erreur 404 - Le fichier n\'a pas été trouvé');
}

class BouletteManager {

    public static function getBoulettes($page){
        global $file_db;

        $getBoulettesQuery = $file_db->prepare('SELECT cat.id_categorie as id_categorie, cat.nom as nom_categorie, b.*, p.*, c.* FROM boulette b, phrase p, collaborateur c, categorie cat'
       . ' WHERE b.id_boulette=p.id_boulette AND cat.id_categorie = b.id_categorie AND b.archive<>1 AND p.id_collaborateur = c.id_collaborateur ORDER BY b.timestamp DESC');
        $getBoulettesQuery->execute();
        $result = $getBoulettesQuery->fetchAll();

        $boulette = new Boulette;
        foreach($result as $row) {
            if ($boulette->id_boulette != $row['id_boulette']){
                if(count($boulette->phrases)>0){
                    $boulettes[] = $boulette;
                }
                $boulette = new Boulette;
                $boulette->id_boulette = $row['id_boulette'];
                $boulette->timestamp = $row['timestamp'];
                $categorie = new Categorie;
                $categorie->id_categorie = $row['id_categorie'];
                $categorie->nom = $row['nom_categorie'];
                $boulette->categorie = $categorie;
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

        return array_slice($boulettes, ($page-1)*NB_BOULETTES_PAGE, NB_BOULETTES_PAGE);
    }

}