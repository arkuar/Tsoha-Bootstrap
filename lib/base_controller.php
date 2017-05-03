<?php

class BaseController {

    public static function get_user_logged_in() {
        if (isset($_SESSION['user'])) {
            $account_id = $_SESSION['user'];
            $account = Account::find($account_id);
            return $account;
        }
        return null;
    }

    public static function check_logged_in() {
        if (!isset($_SESSION['user'])) {
            Redirect::to('/login', array('error' => 'Kirjaudu ensin sisään!'));
        } else {
            $account = Account::find($_SESSION['user']);
            if ($account == null) {
                Redirect::to('/login', array('error' => 'Kirjaudu ensin sisään'));
            } else if ($account->banned) {
                $_SESSION['user'] = null;
                Redirect::to('/login', array('error' => 'Käyttäjätili jäädytetty'));
            }
        }
    }

    public static function user_is_admin() {
        if (isset($_SESSION['user'])) {
            $account_id = $_SESSION['user'];
            $account = Account::find($account_id);
            if ($account->administrator) {
                return true;
            } else {
                return null;
            }
        }
        return null;
    }

    public static function user_is_author($movie) {
        if (self::user_is_admin()) {
            return true;
        }
        if (isset($_SESSION['user'])) {
            $account_id = $_SESSION['user'];
            $account = Account::find($account_id);
            if ($movie->creator_id == $account->id) {
                return true;
            } else {
                return null;
            }
        }
        return null;
    }

    public static function user_is_poster($message) {
        if (self::user_is_admin()) {
            return true;
        }
        if (isset($_SESSION['user'])) {
            $account_id = $_SESSION['user'];
            if ($message->user_id == $account_id) {
                return true;
            } else {
                return null;
            }
        }
        return null;
    }

}
