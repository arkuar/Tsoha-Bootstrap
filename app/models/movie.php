<?php

class Movie extends BaseModel {

    public $id, $creator_id, $name, $year, $description, $messagecount, $genres;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_name', 'validate_year');
    }

    public function validate_name() {
        $errors = array();
        $message = parent::validate_field($this->name, 'Nimi ei saa olla tyhjä!');
        if ($message != '') {
            $errors[] = $message;
        }
        if (strlen($this->name) > 250) {
            $errors[] = 'Nimen tulee olla alle 250 merkkiä!';
        }
        return $errors;
    }

    public function validate_year() {
        $errors = array();
        $message = parent::validate_field($this->year, 'Vuosi ei saa olla tyhjä!');
        if ($message != '') {
            $errors[] = $message;
        }
        if (intval($this->year) < 0) {
            $errors[] = 'Vuoden pitää olla positiivinen!';
        }
        if (intval($this->year) > intval(date("Y"))) {
            $errors[] = 'Vuosi ei voi olla suurempi kuin nykyinen!';
        }
        return $errors;
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT movie.*, COUNT(m.id) FROM Movie movie'
                . ' LEFT JOIN Message m ON m.movie_id = Movie.id GROUP BY movie.id, movie.creator_id'
                . ', movie.name, movie.year, movie.description');
        $query->execute();

        $rows = $query->fetchAll();
        $movies = array();

        foreach ($rows as $row) {
            $movies[] = new Movie(array(
                'id' => $row['id'],
                'creator_id' => $row['creator_id'],
                'name' => $row['name'],
                'year' => $row['year'],
                'description' => $row['description'],
                'messagecount' => $row['count']
            ));
        }
        return $movies;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Movie WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));

        $row = $query->fetch();
        if ($row) {
            $movie = new Movie(array(
                'id' => $row['id'],
                'creator_id' => $row['creator_id'],
                'name' => $row['name'],
                'year' => $row['year'],
                'description' => $row['description']
            ));
            return $movie;
        }
        return null;
    }

    public static function find_by_genre($id) {
        $query = DB::connection()->prepare('SELECT movie.name, movie.id, movie.year FROM Movie '
                . 'INNER JOIN MovieGenre mg ON mg.movie_id = movie.id AND mg.genre_id = :id');
        $query->execute(array('id' => $id));
        $rows = $query->fetchAll();
        $movies = array();
        foreach ($rows as $row) {
            $movies[] = new Movie(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'year' => $row['year']
            ));
        }
        return $movies;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Movie (creator_id, name, year, description)'
                . ' VALUES (:creator_id, :name, :year, :description) RETURNING id');

        $query->execute(array('creator_id' => $this->creator_id, 'name' => $this->name, 'year' => $this->year, 'description' => $this->description));

        $row = $query->fetch();
        $this->id = $row['id'];

        $query = DB::connection()->prepare('INSERT INTO MovieGenre (movie_id, genre_id) VALUES (:movie_id, :genre_id)');
        foreach ($this->genres as $genre) {
            $query->execute(array('movie_id' => $this->id, 'genre_id' => $genre));
        }
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE Movie SET '
                . 'name = :name, year = :year, description = :description'
                . ' WHERE id = :id');

        $query->execute(array('id' => $this->id, 'name' => $this->name, 'year' => $this->year, 'description' => $this->description));

        $query = DB::connection()->prepare('DELETE FROM MovieGenre WHERE movie_id = :id');
        $query->execute(array('id' => $this->id));

        $query = DB::connection()->prepare('INSERT INTO MovieGenre (movie_id, genre_id) VALUES (:movie_id, :genre_id)');
        foreach ($this->genres as $genre) {
            $query->execute(array('movie_id' => $this->id, 'genre_id' => $genre));
        }
    }

    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM MovieGenre WHERE movie_id = :id');
        $query->execute(array('id' => $this->id));

        $query = DB::connection()->prepare('DELETE FROM Movie WHERE id = :id');
        $query->execute(array('id' => $this->id));
    }

}
