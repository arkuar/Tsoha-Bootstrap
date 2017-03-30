<?php

class Movie extends BaseModel {

    public $id, $creator_id, $name, $year, $description, $messagecount;

    public function __construct($attributes) {
        parent::__construct($attributes);
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

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Movie (creator_id, name, year, description)'
                . ' VALUES (:creator_id, :name, :year, :description) RETURNING id');
        
        $query->execute(array('creator_id' => 1, 'name' => $this->name, 'year' => $this->year, 'description' => $this->description));
        
        $row = $query->fetch();
        $this->id = $row['id'];
    }

}
