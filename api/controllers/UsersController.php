<?php

$db = new DB\SQL('mysql:host=localhost;port=3306;dbname=api_cifacom', 'root', '');

class UsersController {

    public $id;
    public $login;
    public $password;
    public $mail;
    
    public function __construct($id=0, $login='', $password='', $mail='') {
        $this->Id = $id;
        $this->Login = $login;
        $this->Password = $password;
        $this->Mail = $mail;
    }

    public function actionFind() {
        Api::response(200, array('Get all users'));
        $f3->set('result', $db->exec('SELECT * FROM users'));
        echo Template::instance()->render('abc.html');
    }

    public function actionCreate() {
        if (isset($_POST['name'])) {
            $data = array('Create user with name ' . $_POST['name']);
            Api::response(200, $data);
        } else {
            Api::response(400, array('error' => 'Name is missing'));
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
