<?php

class CollaborateurAPI
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $id = $this->validateParam('id', null);
        if ($method == 'GET') {
            if ($id !== null) {
                $this->getCollaborateur($id);
            } else {
                $this->getCollaborateurs();
            }
        }
    }

    public function getCollaborateur($id)
    {
        $getCollaborateurQuery = $this->db->prepare('SELECT * FROM collaborateur WHERE id_collaborateur = :id');
        $getCollaborateurQuery->bindValue(':id', $id, SQLITE3_INTEGER);
        $getCollaborateurQuery->execute();

        header('Content-Type: application/json');
        echo json_encode($getCollaborateurQuery->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getCollaborateurs()
    {
        $getCollaborateursQuery = $this->db->prepare('SELECT * FROM collaborateur');
        $getCollaborateursQuery->execute();

        header('Content-Type: application/json');
        echo json_encode($getCollaborateursQuery->fetchAll(PDO::FETCH_ASSOC));
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