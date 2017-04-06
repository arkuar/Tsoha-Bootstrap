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
        View::make('movie/new.html');
    }

    public static function store() {
        $params = $_POST;

        $movie = new Movie(array(
            'name' => $params['name'],
            'year' => $params['year'],
            'description' => $params['description']
        ));

        $errors = $movie->errors();
        if (count($errors) == 0) {
            $movie->save();
            Redirect::to('/movies/' . $movie->id, array('notice' => 'Elokuva lisätty'));
        } else {
            View::make('/movie/new.html', array('errors' => $errors, 'attributes' => $movie));
        }
    }

    public static function edit($id) {
        $movie = Movie::find($id);
        View::make('movie/edit.html', array('attributes' => $movie));
    }

    public static function update($id) {
        $params = $_POST;

        $movie = new Movie(array(
            'id' => $id,
            'name' => $params['name'],
            'year' => $params['year'],
            'description' => $params['description']
        ));


        $errors = $movie->errors();
        if (count($errors) == 0) {
            $movie->update();
            Redirect::to('/movies/' . $movie->id, array('notice' => 'Elokuvaa muokattu onnistuneesti'));
        } else {
            View::make('movie/edit.html', array('errors' => $errors, 'attributes' => $movie));
        }
    }

    public static function destroy($id) {
        $movie = new Movie(array('id' => $id));

        $movie->destroy();

        Redirect::to('/movies', array('notice' => 'Elokuva poistettu onnistuneesti'));
    }

}
