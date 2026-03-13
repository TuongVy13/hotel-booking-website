<?php
class User extends Db {

   
    public function register($username, $password, $email) {
        $sql = "INSERT INTO users(username, password, email)
                VALUES(:u, :p, :e)";
        return $this->insert($sql, [
            ":u" => $username,
            ":p" => password_hash($password, PASSWORD_DEFAULT),
            ":e" => $email
        ]);
    }


    public function login($username, $password) {
        $sql = "SELECT * FROM users WHERE username = :u";
        $user = $this->select($sql, [":u" => $username]);

        if (count($user) > 0) {
            if (password_verify($password, $user[0]["password"])) {
                return $user[0]; 
            }
        }
        return false; 
    }

  
    public function updateUser($id, $email) {
        $sql = "UPDATE users SET email = :e WHERE id = :id";
        return $this->update($sql, [":e" => $email, ":id" => $id]);
    }
}
?>
