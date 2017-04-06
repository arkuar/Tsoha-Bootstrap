<?php

class Genre extends BaseModel {

    public $id, $name, $description, $moviecount;

    public function __construct($attributes = null) {
        parent::__construct($attributes);
        $this->validators = array('validate_name');
    }
    
    public function validate_name(){
        $errors = array();
        $message = parent::validate_field($this->name, 'Nimi ei saa olla tyhjä!');
        if ($message != '') {
            $errors[] = $message;
        }
        if (strlen($this->name) > 10) {
            $errors[] = 'Nimen tulee olla alle 10 merkkiä!';
        }
        return $errors;
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT g.*, COUNT(mg.movie_id) FROM Genre g '
                . 'LEFT JOIN MovieGenre mg ON mg.genre_id = g.id '
                . 'GROUP BY g.name, g.id, g.description');
        $query->execute();

        $rows = $query->fetchAll();
        $genres = array();

        foreach ($rows as $row) {
            $genres[] = new Genre(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'moviecount' => $row['count']
            ));
        }
        return $genres;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Genre WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));

        $row = $query->fetch();
        if ($row) {
            $genre = new Genre(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'description' => $row['description']
            ));
            return $genre;
        }
        return null;
    }

    public static function find_by_movie($id) {
        $query = DB::connection()->prepare('SELECT genre.id, genre.name FROM Genre '
                . 'JOIN MovieGenre mg ON mg.movie_id = :id AND genre.id = mg.genre_id');
        $query->execute(array('id' => $id));
        $rows = $query->fetchAll();
        $genres = array();
        foreach ($rows as $row) {
            $genres[] = new Genre(array(
                'id' => $row['id'],
                'name' => $row['name'],
            ));
        }
        return $genres;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Genre (name, description) '
                . 'VALUES (:name, :description) RETURNING id');
        $query->execute(array('name' => $this->name, 'description' => $this->description));

        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE Genre SET '
                . 'name = :name, description = :description '
                . 'WHERE id = :id');
        $query->execute(array('id' => $this->id, 'name' => $this->name, 'description' => $this->description));
    }

    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM Genre WHERE id = :id');
        $query->execute(array('id' => $this->id));
    }

}
