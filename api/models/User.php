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
        $rq = "SELECT id, login, mail FROM users";
        $res = DBcontroller::get_instance()->prepare($rq);
        $res->execute();
        $tab = $res->fetchAll();
        return $tab;
    }

    //Connexion d'un utilisateur
    public static function log() {
        if (isset($_GET['mail']) && isset($_GET['password'])) {
            $rq = "SELECT * FROM users WHERE mail=?";
            $res = DBcontroller::get_instance()->prepare($rq);
            $res->execute(array($_GET['mail']));
            $tab = $res->fetchAll();
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
        if (isset($_POST['user'])) {
            $rq = "SELECT admin FROM users WHERE access_token=?";
            $res = DBcontroller::get_instance()->prepare($rq);
            $res->execute(array($_POST['user']));
            $tab = $res->fetchAll();
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
    public static function delete() {
        print_r($_GET);
        if (isset($_PUT['user'])) {
            
        } else {
            return array('code' => '400', 'msg' => 'You have to be logged');
        }
    }

    public static function search($id) {
        $rq = "SELECT id, login, mail, access_token FROM users WHERE id=?";
        $res = DBcontroller::get_instance()->prepare($rq);
        $res->execute(array($id));
        $tab = $res->fetchAll();
        print_r($res->fetch());
        if ($res->rowCount() == 0) {
            return array('code' => '400', 'msg' => 'User does not exist');
        } else {
            return $tab;
        }
    }

}
