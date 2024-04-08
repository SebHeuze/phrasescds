<?php

class CategorieAPI
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
                $this->getCategorie($id);
            } else {
                $this->getCategories();
            }
        }
    }

    public function getCategorie($id)
    {
        $getCategorieQuery = $this->db->prepare('SELECT * FROM categorie WHERE id_categorie = :id');
        $getCategorieQuery->bindValue(':id', $id, SQLITE3_INTEGER);
        $getCategorieQuery->execute();

        header('Content-Type: application/json');
        echo json_encode($getCategorieQuery->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getCategories()
    {
        $getCategoriesQuery = $this->db->prepare('SELECT * FROM categorie');
        $getCategoriesQuery->execute();

        header('Content-Type: application/json');
        echo json_encode($getCategoriesQuery->fetchAll(PDO::FETCH_ASSOC));
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