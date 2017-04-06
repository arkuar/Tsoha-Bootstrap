<?php

class AccountController extends BaseController {

    public static function login() {
        View::make('session/login.html');
    }

    public static function handle_login() {
        $params = $_POST;

        $account = Account::authenticate($params['username'], $params['password']);

        if (!$account) {
            View::make('session/login.html', array('error' => 'Väärä käyttäjätunnus tai salasana', 'username' => $params['username']));
        } else if ($account->banned) {
            View::make('session/login.html', array('error' => 'Tunnuksesi on jäädytetty, ota yhteys ylläpitoon'));
        } else {
            $_SESSION['user'] = $account->id;

            Redirect::to('/', array('notice' => 'Tervetuloa takaisin ' . $account->username));
        }
    }
    
    public static function logout(){
        $_SESSION['user'] = null;
        Redirect::to('/', array('notice' => 'Olet kirjautunut ulos'));
    }

}
