<?php
class News extends Db {

   
    public function list() {
        $sql = "SELECT * FROM news ORDER BY id DESC";
        return $this->select($sql);
    }

    public function detail($id) {
        $sql = "SELECT * FROM news WHERE id = :id";
        return $this->select($sql, [":id" => $id]);
    }

    public function insertNews($title, $content) {
        $sql = "INSERT INTO news(title, content) VALUES(:t, :c)";
        return $this->insert($sql, [":t" => $title, ":c" => $content]);
    }


    public function deleteNews($id) {
        $sql = "DELETE FROM news WHERE id = :id";
        return $this->delete($sql, [":id" => $id]);
    }
}
?>
