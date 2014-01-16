<?php

class MoviesController {

    public function actionFind() {

        $tab = Movie::liste();
        Api::response(200, $tab);
    }
    
    public function actionFindOne() {
        //$data = array('Find one user with name: ' . F3::get('PARAMS.id'));
        $tab = Movie::search((int) F3::get('PARAMS.id'));
        Api::response(200, $tab);
    }

    public function actionCreate() {

        $tab = Movie::create();
        Api::response($tab['code'], $tab['msg']);
    }

    public function actionUpdate() {
        
        $tab = Movie::update(F3::get('PARAMS.id'));
        Api::response($tab['code'], $tab['msg']);

    }

    public function actionDelete() {
        
        $tab = Movie::delete(F3::get('PARAMS.id'));
        Api::response($tab['code'], $tab['msg']);
        
    }

}
