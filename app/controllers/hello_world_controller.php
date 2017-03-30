<?php
class HelloWorldController extends BaseController {

    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        echo 'Tämä on etusivu!';
    }

    public static function sandbox() {
        // Testaa koodiasi täällä
        $users = Account::all();
        $messages = Message::find_by_movie(1);
        
        Kint::dump($users);
        Kint::dump($messages);
    }

    public static function login() {
        View::make('suunnitelmat/login.html');
    }

    public static function movie_list() {
        View::make('suunnitelmat/movie_list.html');
    }

    public static function movie_show() {
        View::make('suunnitelmat/movie_show.html');
    }

    public static function movie_edit() {
        View::make('suunnitelmat/movie_edit.html');
    }

    public static function message_edit() {
        View::make('suunnitelmat/message_edit.html');
    }

    public static function genre_list() {
        View::make('suunnitelmat/genre_list.html');
    }

    public static function genre_show() {
        View::make('suunnitelmat/genre_show.html');
    }

    public static function genre_edit() {
        View::make('suunnitelmat/genre_edit.html');
    }

}
