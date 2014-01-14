<?php

class UsersController {

    public $id;
    public $login;
    public $password;
    public $mail;

    public function __construct($id = 0, $login = '', $password = '', $mail = '') {
        $this->Id = $id;
        $this->Login = $login;
        $this->Password = $password;
        $this->Mail = $mail;
    }

    public function actionFind() {

        $rq = "SELECT id, login, mail FROM users";
        $res = DBcontroller::get_instance()->prepare($rq);
        $res->execute();
        $tab = $res->fetchAll();
        Api::response(200, $tab);
    }

    public function actionLogin() {
        if (isset($_GET['mail']) && isset($_GET['password'])) {
            $rq = "SELECT * FROM users WHERE mail=?";
            $res = DBcontroller::get_instance()->prepare($rq);
            $res->execute(array($_GET['mail']));
            $tab = $res->fetchAll();
            if ($res->rowCount() == 0) {
                Api::response(400, array('error' => 'Wrong mail / password'));
            } else {
                if ($tab[0]['password'] == $_GET['password']) {
                    Api::response(200, array('success', 'User authentified'));
                } else {
                    Api::response(400, array('error' => 'Wrong mail / password'));
                }
            }
        } else {
            Api::response(400, array('error' => 'Complete all fields'));
        }
    }

    public function actionCreate() {
        if (isset($_GET['user']) && isset($_GET['mail']) && isset($_GET['login'])) {
            $rq = "SELECT admin FROM users WHERE access_token=?";
            $res = DBcontroller::get_instance()->prepare($rq);
            $res->execute(array($_GET['user']));
            $tab = $res->fetchAll();
            if ($res->rowCount() == 0) {
                Api::response(400, array('error' => 'Your account is invalid'));
            } else {
                if ($tab['admin'] == 0) {
                    Api::response(400, array('error' => 'Your are not able to create a user'));
                } else {
                    $rq = "INSERT INTO users(login, password, access_token, mail, admin) VALUES (?,?,?,?,?)";
                    $res = DBcontroller::get_instance()->prepare($rq);
                    $res->execute(array($_GET['login'], 'password', md5($_GET['mail']), $_GET['mail'], 0));
                    Api::response(400, array('success' => 'Account created'));
                }
            }
        } else {
            Api::response(400, array('error' => 'Complete all fields'));
        }
    }

    public function actionFindOne() {
        $data = array('Find one user with name: ' . F3::get('PARAMS.id'));
        Api::response(200, $data);
    }

    public function actionUpdate() {
        $data = array('Update user with name: ' . F3::get('PARAMS.id'));
        Api::response(200, $data);
    }

    public function actionDelete() {
        $data = array('Delete user with name: ' . F3::get('PARAMS.id'));
        Api::response(200, $data);
    }

}
