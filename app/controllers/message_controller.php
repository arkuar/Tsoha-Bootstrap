<?php

class MessageController extends BaseController {

    public static function create($id) {
        self::check_logged_in();
        $movie = Movie::find($id);
        View::make('message/new.html', array('movie' => $movie));
    }

    public static function store() {
        self::check_logged_in();
        $params = $_POST;

        $message = new Message(array(
            'user_id' => self::get_user_logged_in()->id,
            'movie_id' => $params['movie_id'],
            'content' => $params['content'],
        ));
        $errors = $message->errors();
        Kint::dump($errors);
        if (count($errors) == 0) {
            $message->save();
            Redirect::to('/movies/' . $message->movie_id, array('notice' => 'Viesti lisätty onnistuneesti!'));
        } else {
            $movie = Movie::find($message->movie_id);
            View::make('/message/new.html', array('errors' => $errors, 'movie' => $movie));
        }
    }
    
    public static function destroy($id){
        self::check_logged_in();
        $message = Message::find($id);
        $movie_id = $message->movie_id;
        $message->destroy();
        Redirect::to('/movies/' . $movie_id, array('notice' => 'Viesti poistettu onnistuneesti'));
    }

}
