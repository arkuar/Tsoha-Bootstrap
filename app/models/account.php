<?php

class Account extends BaseModel {

    public $id, $username, $password, $administrator, $banned;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_username', 'validate_password');
    }

    public function validate_username() {
        $errors = array();
        $message = parent::validate_field($this->username, 'Käyttäjätunnus ei saa olla tyhjä!');
        if ($message != '') {
            $errors[] = $message;
        }
        if (strlen($this->username) > 15) {
            $errors[] = 'Käyttäjätunnuksen tulee olla alle 15 merkkiä!';
        }
        return $errors;
    }

    public function validate_password() {
        $errors = array();
        $message = parent::validate_field($this->password, 'Salasana ei saa olla tyhjä!');
        if ($message != '') {
            $errors[] = $message;
        }
        if(strlen($this->password) > 50){
            $errors[] = 'Salasanan tulee olla alle 50 merkkiä!';
        }
        return $errors;
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Account');
        $query->execute();
        $rows = $query->fetchAll();
        $users = array();

        foreach ($rows as $row) {
            $users[$row['id']] = new Account(array(
                'id' => $row['id'],
                'username' => $row['username'],
                'administrator' => $row['administrator'],
                'banned' => $row['banned']
            ));
        }

        return $users;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Account WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        if ($row) {
            $account = new Account(array(
                'id' => $row['id'],
                'username' => $row['username'],
                'password' => $row['password'],
                'administrator' => $row['administrator'],
                'banned' => $row['banned']
            ));
            return $account;
        }
        return null;
    }

    public static function authenticate($username, $password) {
        $query = DB::connection()->prepare('SELECT * FROM Account WHERE username = :username LIMIT 1');
        $query->execute(array('username' => $username));
        $row = $query->fetch();

        if ($row) {
            if ($row['password'] === $password) {
                return new Account(array(
                    'id' => $row['id'],
                    'username' => $row['username'],
                    'password' => $row['password'],
                    'administrator' => $row['administrator'],
                    'banned' => $row['banned']
                ));
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public static function unique($username) {
        $query = DB::connection()->prepare('SELECT * FROM Account WHERE username = :username LIMIT 1');
        $query->execute(array('username' => $username));
        $row = $query->fetch();
        if ($row) {
            return false;
        } else {
            return true;
        }
    }
    
    public function save(){
        $query = DB::connection()->prepare('INSERT INTO Account (username, password) VALUES (:username, :password) RETURNING id');
        $query->execute(array('username' => $this->username, 'password' => $this->password));
        $row = $query->fetch();
        $_SESSION['user'] = $row['id'];
    }
    
    public function update(){
        $query = DB::connection()->prepare('UPDATE Account SET '
                . 'password = :password WHERE id = :id');
        $query->execute(array('id' => $this->id, 'password' => $this->password));
    }
    
    public function toggle_ban(){
        $query = DB::connection()->prepare('UPDATE Account SET banned = NOT :banned WHERE id = :id');
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);
        $query->bindValue(':banned', $this->banned, PDO::PARAM_BOOL);
        $query->execute();
    }
    
    public function destroy(){
        $query = DB::connection()->prepare('DELETE FROM Account WHERE id = :id');
        $query->execute(array('id' => $this->id));
    }

}
