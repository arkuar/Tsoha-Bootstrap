<?php

class AccountController extends BaseController {

    public static function login() {
        View::make('session/login.html');
    }

    public static function show($id) {
        self::check_logged_in();
        $current = self::get_user_logged_in();
        if ($current->id == $id || $current->administrator) {
            $account = Account::find($id);
            if ($account) {
                View::make('account/show.html', array('account' => $account));
            } else {
                Redirect::to('/');
            }
        }
        Redirect::to('/');
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
        if ($params['passwordConfirmation'] != $account->password) {
            $errors[] = 'Salasanat eivät täsmää!';
        }
        if (count($errors) == 0) {
            $unique = $account->unique($params['username']);
            if ($unique) {
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

    public static function edit($id) {
        self::check_logged_in();
        $current = self::get_user_logged_in();
        if ($current->id == $id) {
            View::make('account/edit.html', array('account' => $current));
        } else {
            Redirect::to('/', array('notice' => 'Sinulla ei ole tarvittavia oikeuksia'));
        }
    }

    public static function update($id) {
        self::check_logged_in();
        $params = $_POST;
        $account = new Account(array(
            'id' => $params['id'],
            'password' => $params['password'],
        ));
        if (self::get_user_logged_in()->id != $account->id) {
            Redirect::to('/', array('notice' => 'Sinulla ei ole tarvittavia oikeuksia'));
        }

        $errors = array();
        if ($account->password != $params['passwordConfirmation']) {
            $errors[] = 'Salasana väärin';
        }
        $validator = $account->validate_password();
        $errors = array_merge($errors, $validator);
        if (count($errors) == 0) {
            $account->update();
            Redirect::to('/accounts/' . $account->id . '/show', array('notice' => 'Salasana päivitetty'));
        } else {
            View::make('account/edit.html', array('errors' => $errors, 'account' => $account));
        }
    }

    public static function toggle_ban($id) {
        self::check_logged_in();
        if (!self::user_is_admin()) {
            Redirect::to('/', array('notice' => 'Sinulla ei ole tarvittavia oikeuksia'));
        }

        $account = Account::find($id);
        $account->toggle_ban();
        Redirect::to('/accounts/' . $account->id . '/show');
    }

}
