<?php

class MoviesController {

    public $id;
    public $name;
    public $kind;
    public $description;
    
    public function __construct($id=0, $name='', $kind='', $description='') {
        $this->Id = $id;
        $this->Name = $name;
        $this->Kind = $kind;
        $this->Description = $description;
    }

    public function actionFind() {
        Api::response(200, array('Get all movies'));
        $f3->set('result', $db->exec('SELECT * FROM movies'));
        echo Template::instance()->render('abc.html');
    }

    public function actionCreate() {
        if (isset($_POST['name'])) {
            $data = array('Create movie with name ' . $_POST['name']);
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
