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

}
