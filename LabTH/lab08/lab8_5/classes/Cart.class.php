<?php
class Cart extends Db {

    
    public function getCart($user_id) {
        $sql = "SELECT c.*, b.book_name, b.price
                FROM cart c 
                JOIN book b ON c.book_id = b.book_id
                WHERE c.user_id = :uid";
        return $this->select($sql, [":uid" => $user_id]);
    }

   
    public function addToCart($user_id, $book_id, $qty) {
        $sql = "INSERT INTO cart(user_id, book_id, quantity)
                VALUES(:u, :b, :q)";
        return $this->insert($sql, [
            ":u" => $user_id,
            ":b" => $book_id,
            ":q" => $qty
        ]);
    }


    public function updateQty($cart_id, $qty) {
        $sql = "UPDATE cart SET quantity = :q WHERE cart_id = :cid";
        return $this->update($sql, [
            ":q" => $qty,
            ":cid" => $cart_id
        ]);
    }

 
    public function removeItem($cart_id) {
        $sql = "DELETE FROM cart WHERE cart_id = :cid";
        return $this->delete($sql, [":cid" => $cart_id]);
    }
}
?>
