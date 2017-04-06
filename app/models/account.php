<?php

class Account extends BaseModel {

    public $id, $username, $password, $administrator, $banned;

    public function __construct($attributes) {
        parent::__construct($attributes);
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

}
