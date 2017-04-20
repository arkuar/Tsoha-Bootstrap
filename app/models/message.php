<?php

class Message extends BaseModel {

    public $id, $user_id, $movie_id, $content, $posted_at;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function find_by_movie($id) {
        $query = DB::connection()->prepare('SELECT * FROM Message WHERE movie_id = :id');
        $query->execute(array('id' => $id));

        $rows = $query->fetchAll();
        $messages = array();

        foreach ($rows as $row) {
            $time = strtotime($row['posted_at']);
            $posted = date("d.m.Y H:i:s", $time);
            $messages[] = new Message(array(
                'id' => $row['id'],
                'user_id' => $row['user_id'],
                'movie_id' => $row['movie_id'],
                'content' => $row['content'],
                'posted_at' => $posted
            ));
        }

        return $messages;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Message (user_id, movie_id, content, posted_at) '
                . 'VALUES (:user_id, :movie_id, :content, CURRENT_TIMESTAMP)');
        $query->execute(array('user_id' => $this->user_id, 'movie_id' => $this->movie_id, 'content' => $this->content));
    }

}
