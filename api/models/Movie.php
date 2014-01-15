<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Movie {

    public static function liste() {
        $rq = "SELECT * FROM movies";
        $res = DBcontroller::get_instance()->prepare($rq);
        $res->execute();
        $tab = $res->fetchAll(PDO::FETCH_ASSOC);
        return $tab;
    }

    public static function search($id) {
        $rq = "SELECT * FROM movies WHERE id=?";
        $res = DBcontroller::get_instance()->prepare($rq);
        $res->execute(array($id));
        $tab = $res->fetchAll(PDO::FETCH_ASSOC);
        if ($res->rowCount() == 0) {
            return array('code' => '400', 'msg' => 'User does not exist');
        } else {
            return $tab;
        }
    }

}
