<?php

class MoviesController {

    public function actionFind() {
 
        $tab = Movie::liste();
        Api::response(200, $tab);
        
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
