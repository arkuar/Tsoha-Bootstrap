<?php

class MovieController extends BaseController {

    public static function index() {
        $movies = Movie::all();
        View::make('movie/index.html', array('movies' => $movies));
    }

    public static function show($id) {
        $movie = Movie::find($id);
        $messages = Message::find_by_movie($id);
        $users = Account::all();
        View::make('movie/show.html', array('movie' => $movie,
            'messages' => $messages, 'user' => $users));
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


        $movie->save();

        Redirect::to('/movies/' . $movie->id, array('notice' => 'Elokuva lisätty'));
    }

}
