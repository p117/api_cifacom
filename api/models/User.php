<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class User {

    public function __construct() {

        parent::__construct($login = '', $mail = '', $password = '', $access_token = '', $id = -1);
        $this->Id = $id;
        $this->Login = $login;
        $this->Mail = $mail;
        $this->Password = $password;
        $this->Acess_token = $access_token;
    }

    //Listage de tous les utilisateurs enregistrés
    public static function liste() {

        $rq = "SELECT * FROM users";
        $res = DBcontroller::get_instance()->prepare($rq);
        $res->execute();
        $tab = $res->fetchAll(PDO::FETCH_ASSOC);
        return $tab;
    }

    //Connexion d'un utilisateur
    public static function log() {

        if (isset($_GET['mail']) && isset($_GET['password'])) {
            $rq = "SELECT * FROM users WHERE mail=?";
            $res = DBcontroller::get_instance()->prepare($rq);
            $res->execute(array($_GET['mail']));
            $tab = $res->fetchAll(PDO::FETCH_ASSOC);
            if ($res->rowCount() == 0) {
                return array('code' => '400', 'msg' => 'Wrong mail / password');
            } else {
                if ($tab[0]['password'] == $_GET['password']) {
                    return array('code' => '200', 'msg' => 'User authentified');
                } else {
                    return array('code' => '400', 'msg' => 'Wrong mail / password');
                }
            }
        } else {
            return array('code' => '400', 'msg' => 'Complete all fields');
        }
    }

    //Enregistrement en base d'un utilisateur
    public static function create() {

        if (isset($_POST['access_token'])) {
            $rq = "SELECT admin FROM users WHERE access_token=?";
            $res = DBcontroller::get_instance()->prepare($rq);
            $res->execute(array($_POST['access_token']));
            $tab = $res->fetchAll(PDO::FETCH_ASSOC);
            if ($res->rowCount() == 0) {
                return array('code' => '400', 'msg' => 'Your account is invalid');
            } else {
                if ($tab[0]['admin'] == 0) {
                    return array('code' => '400', 'msg' => 'Your are not able to create a user');
                } else {
                    if (isset($_POST['mail']) && isset($_POST['login'])) {
                        $rq = "INSERT INTO users(login, password, access_token, mail, admin) VALUES (?,?,?,?,?)";
                        $res = DBcontroller::get_instance()->prepare($rq);
                        $res->execute(array($_POST['login'], 'password', md5($_POST['mail']), $_POST['mail'], 0));
                        return array('code' => '200', 'msg' => 'Account created');
                    } else {
                        return array('code' => '400', 'msg' => 'Complete all fields');
                    }
                }
            }
        } else {
            return array('code' => '400', 'msg' => 'You have to be logged');
        }
    }

    //Suppression d'un utilisateur
    public static function delete($id) {

        $rq = "SELECT admin FROM users WHERE access_token=?";
        $res = DBcontroller::get_instance()->prepare($rq);
        $res->execute(array($_GET['access_token']));
        $tab = $res->fetchAll(PDO::FETCH_ASSOC);
        if ($res->rowCount() == 0) {
            return array('code' => '400', 'msg' => 'Access token not valid');
        } else {
            if ($tab[0]['admin'] == 0) {
                return array('code' => '400', 'msg' => 'Admin permission needed');
            } else {
                $rq = "DELETE FROM users WHERE id=?";
                $res = DBcontroller::get_instance()->prepare($rq);
                $res->execute(array($id));
                return array('code' => '200', 'msg' => 'User deleted');
            }
        }
    }

    public static function search($id) {

        $rq = "SELECT * FROM users WHERE id=?";
        $res = DBcontroller::get_instance()->prepare($rq);
        $res->execute(array($id));
        $tab = $res->fetchAll(PDO::FETCH_ASSOC);
        if ($res->rowCount() == 0) {
            return array('code' => '400', 'msg' => 'User does not exist');
        } else {
            return $tab;
        }
    }

    public static function update($id) {

        $rq = "SELECT admin FROM users WHERE access_token=?";
        $res = DBcontroller::get_instance()->prepare($rq);
        $res->execute(array($_GET['access_token']));
        $tab = $res->fetchAll(PDO::FETCH_ASSOC);
        if ($res->rowCount() == 0) {
            return array('code' => '400', 'msg' => 'Access token not valid');
        } else {
            if ($tab[0]['admin'] == 0) {
                return array('code' => '400', 'msg' => 'Admin permission needed');
            } else {
                if (isset($_GET['password']) || isset($_GET['login']) || isset($_GET['mail'])) {
                    $tmp = array();
                    if (isset($_GET['password']) && !empty($_GET['password'])) {
                        $tmp['password'] = $_GET['password'];
                    }
                    if (isset($_GET['mail']) && !empty($_GET['mail'])) {
                        $tmp['mail'] = $_GET['mail'];
                    }
                    if (isset($_GET['login']) && !empty($_GET['login'])) {
                        $tmp['login'] = $_GET['login'];
                    }
                    print_r($tmp);
                    $rq = "SELECT password, mail, login FROM users WHERE id=?";
                    $res = DBcontroller::get_instance()->prepare($rq);
                    $res->execute(array($id));
                    $user = $res->fetchAll(PDO::FETCH_ASSOC);
                    print_r($user[0]);
                    if (isset($tmp['password']))
                        $user[0]['password'] = $tmp['password'];
                    if (isset($tmp['mail']))
                        $user[0]['mail'] = $tmp['mail'];
                    if (isset($tmp['login']))
                        $user[0]['login'] = $tmp['login'];
                    print_r($user[0]);
                    $rq = "UPDATE users SET password=?, login=?, mail=? WHERE id=?";
                    $res = DBcontroller::get_instance()->prepare($rq);
                    $res->execute(array($user[0]['password'], $user[0]['login'], $user[0]['mail'], $id));
                    return array('code' => '200', 'msg' => 'User updated');
                }
            }
        }
    }

}
