<?php

class MovieController extends BaseController {

    public static function index() {
        $movies = Movie::all();
        View::make('movie/index.html', array('movies' => $movies));
    }

    public static function show($id) {
        $movie = Movie::find($id);
        $messages = Message::find_by_movie($id);
        $genres = Genre::find_by_movie($id);
        $users = Account::all();
        View::make('movie/show.html', array('movie' => $movie,
            'messages' => $messages, 'user' => $users, 'genres' => $genres));
    }

    public static function create() {
        self::check_logged_in();
        $genres = Genre::all();
        View::make('movie/new.html', array('genres' => $genres));
    }

    public static function store() {
        self::check_logged_in();
        $params = $_POST;
        $genres = $params['genres'];
        $movie = new Movie(array(
            'creator_id' => parent::get_user_logged_in()->id,
            'name' => $params['name'],
            'year' => $params['year'],
            'description' => $params['description'],
            'genres' => array()
        ));

        foreach ($genres as $genre) {
            $movie->genres[] = $genre;
        }

        $errors = $movie->errors();
        if (count($errors) == 0) {
            $movie->save();
            Redirect::to('/movies/' . $movie->id, array('notice' => 'Elokuva lisätty'));
        } else {
            View::make('/movie/new.html', array('errors' => $errors, 'attributes' => $movie));
        }
    }

    public static function edit($id) {
        self::check_logged_in();
        $movie = Movie::find($id);
        $genres = Genre::all();
        if (self::user_is_author($movie)) {
            View::make('movie/edit.html', array('attributes' => $movie, 'genres' => $genres));
        } else {
            Redirect::to('/movies/' . $movie->id, array('notice' => 'Sinulla ei ole oikeuksia toimintoon!'));
        }
    }

    public static function update($id) {
        self::check_logged_in();
        $params = $_POST;
        $genres = $params['genres'];

        $movie = new Movie(array(
            'id' => $id,
            'creator_id' => $params['creator_id'],
            'name' => $params['name'],
            'year' => $params['year'],
            'description' => $params['description'],
            'genres' => array()
        ));

        foreach ($genres as $genre) {
            $movie->genres[] = $genre;
        }

        $authored = self::user_is_author($movie);
        if (!$authored) {
            Redirect::to('/movies/' . $movie->id, array('notice' => 'Sinulla ei ole oikeuksia toimintoon!'));
        }

        $errors = $movie->errors();
        if (count($errors) == 0) {
            $movie->update();
            Redirect::to('/movies/' . $movie->id, array('notice' => 'Elokuvaa muokattu onnistuneesti'));
        } else {
            View::make('movie/edit.html', array('errors' => $errors, 'attributes' => $movie));
        }
    }

    public static function destroy($id) {
        self::check_logged_in();
        $movie = Movie::find($id);

        if (self::user_is_author($movie)) {
            $movie->destroy();
            Redirect::to('/movies', array('notice' => 'Elokuva poistettu onnistuneesti'));
        } else {
            Redirect::to('/movies/' . $movie->id, array('notice' => 'Sinulla ei ole oikeuksia toimintoon!'));
        }
    }

}
