<?php

class AccountController extends BaseController {

    public static function login() {
        View::make('session/login.html');
    }

    public static function register() {
        View::make('account/register.html');
    }

    public static function store() {
        $params = $_POST;

        $account = new Account(array(
            'username' => $params['username'],
            'password' => $params['password'],
            'administrator' => false,
            'banned' => false
        ));
        $errors = $account->errors();
        if($params['passwordConfirmation'] != $account->password){
            $errors[] = 'Salasanat eivät täsmää!';
        }
        if(count($errors) == 0){
            $unique = $account->unique($params['username']);
            if($unique){
                $account->save();
                Redirect::to('/', array('notice' => 'Tervetuloa ' . $account->username));
            } else {
                $errors[] = 'Käyttäjätunnus on jo käytössä!';
                View::make('account/register.html', array('errors' => $errors));
            }
        } else {
            View::make('account/register.html', array('errors' => $errors));
        }
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

    public static function logout() {
        $_SESSION['user'] = null;
        Redirect::to('/', array('notice' => 'Olet kirjautunut ulos'));
    }

}
