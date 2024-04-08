<?php

class BouletteAPI
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $offset = $this->validateParam('offset', 0);
        $limit = $this->validateParam('limit', 10);
        $id = $this->validateParam('id', null);
        switch ($method) {
            case 'GET':
                if ($id !== null) {
                    if ($this->checkBouletteId($id)) {
                        if (isset($_GET['mode']) && $_GET['mode'] == 'eager') {
                            $this->getBouletteEager($id);
                        } else {
                            $this->getBoulette($id);
                        }
                    }
                } else {
                    if (isset($_GET['mode']) && $_GET['mode'] == 'eager') {
                        $this->getBoulettesEager($offset, $limit);
                    } else {
                        $this->getBoulettes($offset, $limit);
                    }
                }
                break;
            case 'POST':
                $this->createBoulette(file_get_contents("php://input"));
                break;
            case 'PUT':
                $this->updateBoulette(file_get_contents("php://input"));
                break;
            case 'DELETE':
                if ($id !== null && $this->checkBouletteId($id)) {
                    $this->deleteBoulette($_GET['id']);
                }
                break;
        }
    }

    public function getBoulette($id)
    {
        $query = $this->db->prepare('SELECT b.*, p.* FROM boulette b, phrase p'
            . ' WHERE b.id_boulette=p.id_boulette AND b.archive<>1 AND b.id_boulette = :id ORDER BY b.timestamp DESC ');
        $query->bindValue(':id', $id, SQLITE3_INTEGER);
        $query->execute();

        header('Content-Type: application/json');
        echo json_encode($this->parseBoulettes($query->fetchAll())[0]);
    }

    public function getBoulettes($offset, $limit)
    {
        $query = $this->db->prepare('SELECT b.*, p.* FROM boulette b, phrase p'
            . ' WHERE b.id_boulette=p.id_boulette AND b.archive<>1  ORDER BY b.timestamp DESC LIMIT :offset, :limit');
        $query->bindValue(':offset', $offset, SQLITE3_INTEGER);
        $query->bindValue(':limit', $limit, SQLITE3_INTEGER);
        $query->execute();

        header('Content-Type: application/json');
        echo json_encode($this->parseBoulettes($query->fetchAll()));
    }

    public function getBouletteEager($id)
    {
        $query = $this->db->prepare('SELECT cat.id_categorie as id_categorie, cat.nom as nom_categorie, b.*, p.*, c.* FROM boulette b, phrase p, collaborateur c, categorie cat'
            . ' WHERE b.id_boulette=p.id_boulette AND cat.id_categorie = b.id_categorie AND b.archive<>1 AND p.id_collaborateur = c.id_collaborateur AND b.id_boulette = :id ORDER BY b.timestamp DESC');
        $query->bindValue(':id', $id, SQLITE3_INTEGER);
        $query->execute();

        header('Content-Type: application/json');
        echo json_encode($this->parseBoulettesEager($query->fetchAll())[0]);
    }

    public function getBoulettesEager($offset, $limit)
    {
        $query = $this->db->prepare('SELECT cat.id_categorie as id_categorie, cat.nom as nom_categorie, b.*, p.*, c.* FROM boulette b, phrase p, collaborateur c, categorie cat'
            . ' WHERE b.id_boulette=p.id_boulette AND cat.id_categorie = b.id_categorie AND b.archive<>1 AND p.id_collaborateur = c.id_collaborateur ORDER BY b.timestamp DESC LIMIT :offset, :limit');
        $query->bindValue(':offset', (int) trim($offset), SQLITE3_INTEGER);
        $query->bindValue(':limit', (int) trim($limit), SQLITE3_INTEGER);
        $query->execute();

        $boulettes = $this->parseBoulettesEager($query->fetchAll());

        header('Content-Type: application/json');
        echo json_encode($boulettes);
    }

    public function createBoulette($bouletteJson)
    {
        $boulette = json_decode($bouletteJson, true);

        if (!$this->checkCategoryId($boulette['id_categorie'])) {
            return;
        }

        foreach ($boulette['phrases'] as $phrase) {
            if (!$this->checkCollaboratorId($phrase['id_collaborateur'])) {
                return;
            }
        }

        $insertBouletteQuery = $this->db->prepare('INSERT INTO boulette (id_categorie,timestamp, archive) VALUES (?,?,?)');
        $insertBouletteQuery->execute(array($boulette['id_categorie'], time(), 0));

        $bouletteId = $this->db->lastInsertId();
        foreach ($boulette['phrases'] as $phrase) {
            $insertPhraseQuery = $this->db->prepare('INSERT INTO phrase (id_boulette, message, id_collaborateur) VALUES (?, ?, ?)');
            $insertPhraseQuery->execute(array($bouletteId, $phrase['message'], $phrase['id_collaborateur']));
        }
        $this->getBoulette($bouletteId);
    }

    public function updateBoulette($bouletteJson)
    {
        $boulette = json_decode($bouletteJson, true);
        $idBoulette = isset($boulette['id_boulette']) ? $boulette['id_boulette'] : null;
        if (!$this->checkBouletteId($idBoulette)) {
            return;
        }

        if (!$this->checkCategoryId($boulette['id_categorie'])) {
            return;
        }

        foreach ($boulette['phrases'] as $phrase) {
            if (!$this->checkCollaboratorId($phrase['id_collaborateur'])) {
                return;
            }
        }

        $updateBouletteQuery = $this->db->prepare('UPDATE boulette SET id_categorie = :id_categorie, timestamp = :timestamp WHERE id_boulette = :id');
        $updateBouletteQuery->bindValue(':id', $idBoulette, SQLITE3_INTEGER);
        $updateBouletteQuery->bindValue(':id_categorie', $boulette['id_categorie'], SQLITE3_INTEGER);
        $updateBouletteQuery->bindValue(':timestamp', $boulette['timestamp'], SQLITE3_TEXT);
        $updateBouletteQuery->execute();

        $deletePhrasesQuery = $this->db->prepare('DELETE FROM phrase WHERE id_boulette = :id_boulette');
        $deletePhrasesQuery->bindValue(':id_boulette', $idBoulette, SQLITE3_INTEGER);
        $deletePhrasesQuery->execute();

        foreach ($boulette['phrases'] as $phrase) {
            $insertPhraseQuery = $this->db->prepare('INSERT INTO phrase (id_boulette, message, id_collaborateur) VALUES (:id_boulette, :message, :id_collaborateur)');
            $insertPhraseQuery->bindValue(':id_boulette', $idBoulette, SQLITE3_INTEGER);
            $insertPhraseQuery->bindValue(':message', $phrase['message'], SQLITE3_TEXT);
            $insertPhraseQuery->bindValue(':id_collaborateur', $phrase['id_collaborateur'], SQLITE3_INTEGER);
            $insertPhraseQuery->execute();
        }
        $this->getBoulette($idBoulette);
    }

    public function deleteBoulette($id)
    {
        $deleteBouletteQuery = $this->db->prepare('DELETE FROM boulette WHERE id_boulette = :id');
        $deleteBouletteQuery->bindValue(':id', $id, SQLITE3_INTEGER);
        $deleteBouletteQuery->execute();


        header('Content-Type: application/json');
        echo json_encode(['status' => 'success']);
    }
    private function parseBoulettes($result)
    {
        $boulettes = [];
        $boulette = new Boulette;

        foreach ($result as $row) {
            if ($boulette->id_boulette != $row['id_boulette']) {
                if ($boulette->phrases && count($boulette->phrases) > 0) {
                    $boulettes[] = (object) array_filter((array) $boulette);
                }
                $boulette = new Boulette;
                $boulette->id_boulette = $row['id_boulette'];
                $boulette->timestamp = $row['timestamp'];
                $boulette->id_categorie = $row['id_categorie'];
            }


            $phrase = new Phrase;
            $phrase->id_phrase = $row['id_phrase'];
            $phrase->message = $row['message'];
            $phrase->id_collaborateur = $row['id_collaborateur'];
            $boulette->phrases[] = (object) array_filter((array) $phrase);
        }
        $boulettes[] = (object) array_filter((array) $boulette);
        return $boulettes;
    }

    private function parseBoulettesEager($result)
    {
        $boulettes = [];
        $boulette = new Boulette;

        foreach ($result as $row) {
            if ($boulette->id_boulette != $row['id_boulette']) {
                if ($boulette->phrases && count($boulette->phrases) > 0) {
                    $boulettes[] = (object) array_filter((array) $boulette);
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
            $collaborateur->id_collaborateur = $row['id_collaborateur'];
            $collaborateur->prenom = $row['prenom'];
            $collaborateur->nom = $row['nom'];
            $collaborateur->couleur = $row['couleur'];

            $phrase = new Phrase;
            $phrase->id_phrase = $row['id_phrase'];
            $phrase->message = $row['message'];
            $phrase->collaborateur = $collaborateur;
            $boulette->phrases[] = (object) array_filter((array) $phrase);
        }
        $boulettes[] = (object) array_filter((array) $boulette);
        return $boulettes;
    }

    private function checkBouletteId($id)
    {
        if (!isset($id) || $id === null) {
            header('HTTP/1.0 404 Not Found');
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Id should not be null']);
            return false;
        }
        $checkBouletteQuery = $this->db->prepare('SELECT * FROM boulette WHERE id_boulette = :id');
        $checkBouletteQuery->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $checkBouletteQuery->execute();

        if (!$result || $checkBouletteQuery->fetchAll() == false) {
            header('HTTP/1.0 404 Not Found');
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Boulette not found']);
            return false;
        }

        return true;
    }


    private function checkCategoryId($id)
    {
        $checkCategoryQuery = $this->db->prepare('SELECT * FROM categorie WHERE id_categorie = :id');
        $checkCategoryQuery->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $checkCategoryQuery->execute();

        if (!$result || $checkCategoryQuery->fetchAll() == false) {
            header('HTTP/1.0 404 Not Found');
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Category not found']);
            return false;
        }

        return true;
    }

    private function checkCollaboratorId($id)
    {
        $checkCollaboratorQuery = $this->db->prepare('SELECT * FROM collaborateur WHERE id_collaborateur = :id');
        $checkCollaboratorQuery->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $checkCollaboratorQuery->execute();

        if (!$result || $checkCollaboratorQuery->fetchAll() == false) {
            header('HTTP/1.0 404 Not Found');
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Collaborator not found']);
            return false;
        }

        return true;
    }

    private function validateParam($param, $default)
    {
        if (isset($_GET[$param]) && is_numeric($_GET[$param])) {
            return $_GET[$param];
        }
        return $default;
    }
}

?>