<?php

class GenreController extends BaseController {

    public static function index() {
        $genres = Genre::all();
        View::make('genre/list.html', array('genres' => $genres));
    }

    public static function show($id) {
        $genre = Genre::find($id);
        $movies = Movie::find_by_genre($id);
        View::make('genre/show.html', array('genre' => $genre, 'movies' => $movies));
    }

    public static function create() {
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
        $genre = Genre::find($id);
        View::make('genre/edit.html', array('attributes' => $genre));
    }

    public static function update($id) {
        $params = $_POST;
        $genre = new Genre(array(
            'id' => $params['id'],
            'name' => $params['name'],
            'description' => $params['description']
        ));
        $errors = $genre->errors();
        if (count($errors) == 0) {
            $genre->update();
            Redirect::to('/genres/' . $genre->id, array('notice' => 'Genreä muokattu onnistuneesti'));
        } else {
            View::make('genre/edit.html', array('errors' => $errors, 'attributes' => $genre));
        }
    }

    public static function destroy($id) {
        $genre = new Genre(array('id' => $id));
        $genre->destroy();
        Redirect::to('/genres', array('notice' => 'Genre poistettu onnistuneesti'));
    }

}
