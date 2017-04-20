<?php

class MessageController extends BaseController {

    public static function create($id) {
        self::check_logged_in();
        $movie = Movie::find($id);
        View::make('message/new.html', array('movie' => $movie));
    }

    public static function store($id) {
        self::check_logged_in();
        $params = $_POST;

        $message = new Message(array(
            'user_id' => self::get_user_logged_in()->id,
            'movie_id' => $id,
            'content' => $params['content'],
        ));
        $message->save();
        Redirect::to('/movies/' . $message->movie_id, array('notice' => 'Viesti lisätty onnistuneesti!'));
    }

}
