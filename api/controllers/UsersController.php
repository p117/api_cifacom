<?php

class UsersController {

    public function actionFind() {
        
        $tab = User::liste();
        Api::response(200, $tab);
        
    }

    public function actionLogin() {
        
        $tab = User::log();
        Api::response($tab['code'], $tab['msg']);
        
    }

    public function actionCreate() {
        
        $tab = User::create();
        Api::response($tab['code'], $tab['msg']);
        
    }

    public function actionFindOne() {
        //$data = array('Find one user with name: ' . F3::get('PARAMS.id'));
        $tab = User::search((int)F3::get('PARAMS.id'));
        Api::response(200, $tab);
    }

    public function actionUpdate() {
        
        $tab = User::update(F3::get('PARAMS.id'));
        Api::response($tab['code'], $tab['msg']);
        
        $data = array('Update user with name: ' . F3::get('PARAMS.id'));
        Api::response(200, $data);
    }

    public function actionDelete() {
        
        $tab = User::delete(F3::get('PARAMS.id'));
        Api::response($tab['code'], $tab['msg']);
        
        $data = array('Delete user with name: ' . F3::get('PARAMS.id'));
        Api::response(200, $data);
    }

}
