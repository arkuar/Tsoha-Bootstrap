<?php

class GenreController extends BaseController {

    public static function index() {
        $genres = Genre::all();
        View::make('genre/list.html', array('genres' => $genres));
    }

    public static function show($id) {
        $genre = Genre::find($id);
        $movies = Movie::find_by_genre($id);
        if ($genre) {
            View::make('genre/show.html', array('genre' => $genre, 'movies' => $movies));
        } else {
            Redirect::to('/genres');
        }
        
    }

    public static function create() {
        self::check_logged_in();
        View::make('genre/new.html');
    }

    public static function store() {
        $params = $_POST;

        $genre = new Genre(array(
            'name' => $params['name'],
            'description' => $params['description']
        ));
        $errors = $genre->errors();
        if (count($errors) == 0) {
            $genre->save();
            Redirect::to('/genres/' . $genre->id, array('notice' => 'Genre lisätty'));
        } else {
            View::make('genre/new.html', array('errors' => $errors, 'attributes' => $genre));
        }
    }

    public static function edit($id) {
        self::check_logged_in();
        if (self::user_is_admin()) {
            $genre = Genre::find($id);
            View::make('genre/edit.html', array('attributes' => $genre));
        } else {
            Redirect::to('/genres', array('notice' => 'Sinulla ei ole tarvittavia oikeuksia'));
        }
    }

    public static function update($id) {
        self::check_logged_in();
        $params = $_POST;
        $genre = new Genre(array(
            'id' => $params['id'],
            'name' => $params['name'],
            'description' => $params['description']
        ));

        if (!self::user_is_admin()) {
            Redirect::to('/genres', array('notice' => 'Sinulla ei ole tarvittavia oikeuksia'));
        }
        $errors = $genre->errors();
        if (count($errors) == 0) {
            $genre->update();
            Redirect::to('/genres/' . $genre->id, array('notice' => 'Genreä muokattu onnistuneesti'));
        } else {
            View::make('genre/edit.html', array('errors' => $errors, 'attributes' => $genre));
        }
    }

    public static function destroy($id) {
        self::check_logged_in();
        if (!self::user_is_admin()) {
            Redirect::to('/genres', array('notice' => 'Sinulla ei ole tarvittavia oikeuksia'));
        }
        $genre = new Genre(array('id' => $id));
        $genre->destroy();
        Redirect::to('/genres', array('notice' => 'Genre poistettu onnistuneesti'));
    }

}
