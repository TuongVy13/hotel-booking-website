<?php
class Book extends Db {


    public function getRand($n)
    {
        $sql = "SELECT book_id, book_name, img 
                FROM book 
                ORDER BY RAND() 
                LIMIT 0, $n";
        return $this->select($sql);
    }

 
    public function getByPublisher($manhaxb)
    {
        $sql = "SELECT book_id, book_name, img 
                FROM book 
                WHERE publisher_id = :p 
                ORDER BY book_id DESC";
        return $this->select($sql, [":p" => $manhaxb]);
    }


    public function listPage($start, $limit)
    {
        $sql = "SELECT * FROM book 
                ORDER BY book_id DESC 
                LIMIT :start, :limit";

        return $this->select($sql, [
            ":start" => $start,
            ":limit" => $limit
        ]);
    }

   
    public function totalBook()
    {
        $sql = "SELECT COUNT(*) AS total FROM book";
        $row = $this->select($sql);
        return $row[0]['total'];
    }
}
?>
