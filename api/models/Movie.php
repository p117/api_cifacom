<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Movie {

    public $id;
    public $name;
    public $kind;
    public $description;

    public function __construct($name = '', $kind = '', $description = '', $id = -1) {

        parent::__construct();
        $this->Id = $id;
        $this->Login = $name;
        $this->Mail = $kind;
        $this->Password = $description;
    }

    //Listage de tous les films enregistrés
    public static function liste() {
        $rq = "SELECT * FROM movies";
        $res = DBcontroller::get_instance()->prepare($rq);
        $res->execute();
        $tab = $res->fetchAll(PDO::FETCH_ASSOC);
        return $tab;
    }

    //Listage de tous les films enregistrés
    public static function search($id) {
        $rq = "SELECT * FROM movies WHERE id=?";
        $res = DBcontroller::get_instance()->prepare($rq);
        $res->execute(array($id));
        $tab = $res->fetchAll(PDO::FETCH_ASSOC);
        if ($res->rowCount() == 0) {
            return array('code' => '400', 'msg' => 'Movie does not exist');
        } else {
            return $tab;
        }
    }

    //Enregistrement en base d'un film
    public static function create() {

        if (isset($_GET['access_token'])) {
            $rq = "SELECT admin FROM users WHERE access_token=?";
            $res = DBcontroller::get_instance()->prepare($rq);
            $res->execute(array($_GET['access_token']));
            $tab = $res->fetchAll(PDO::FETCH_ASSOC);
            if ($res->rowCount() == 0) {
                return array('code' => '400', 'msg' => 'Your account is invalid');
            } else {
                if ($tab[0]['admin'] == 0) {
                    return array('code' => '400', 'msg' => 'Your are not able to create a movie');
                } else {
                    if (isset($_POST['name']) && isset($_POST['kind']) && isset($_POST['description'])) {
                        $rq = "INSERT INTO movies(name, kind, description) VALUES (?,?,?)";
                        $res = DBcontroller::get_instance()->prepare($rq);
                        $res->execute(array($_POST['name'], $_POST['kind'], $_POST['description']));
                        return array('code' => '200', 'msg' => 'Movie created');
                    } else {
                        return array('code' => '400', 'msg' => 'Complete all fields');
                    }
                }
            }
        } else {
            return array('code' => '400', 'msg' => 'You have to be logged');
        }
    }

    //Suppression d'un film
    public static function delete($id) {

        if (isset($_GET['access_token'])) {
            $rq = "SELECT admin FROM users WHERE access_token=?";
            $res = DBcontroller::get_instance()->prepare($rq);
            $res->execute(array($_GET['access_token']));
            $tab = $res->fetchAll(PDO::FETCH_ASSOC);
            if ($res->rowCount() == 0) {
                return array('code' => '400', 'msg' => 'Error : Access token not valid');
            } else {
                if ($tab[0]['admin'] == 0) {
                    return array('code' => '400', 'msg' => 'Error : Admin permission needed');
                } else {
                    $rq = "SELECT * FROM movies WHERE id=?";
                    $res = DBcontroller::get_instance()->prepare($rq);
                    $res->execute(array($id));
                    if ($res->rowCount() == 0) {
                        return array('code' => '200', 'msg' => 'Error : Movie doesn\'t exist');
                    } else {
                        $rq = "DELETE FROM movies WHERE id=?";
                        $res = DBcontroller::get_instance()->prepare($rq);
                        $res->execute(array($id));
                        return array('code' => '200', 'msg' => 'Success : Movie deleted');
                    }
                }
            }
        } else {
            return array('code' => '400', 'msg' => 'Error : You have to be logged as admin');
        }
    }

    //Mise à jour d'un film
    public static function update($id) {

        $put = array();
        $put = PUT::get();
        if (isset($_GET['access_token'])) {
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
                    if (isset($put['name']) || isset($put['kind']) || isset($put['description'])) {
                        $tmp = array();
                        if (isset($put['name']) && !empty($put['name'])) {
                            $tmp['name'] = $put['name'];
                        }
                        if (isset($put['kind']) && !empty($put['kind'])) {
                            $tmp['kind'] = $put['kind'];
                        }
                        if (isset($put['description']) && !empty($put['description'])) {
                            $tmp['description'] = $put['description'];
                        }
                        $rq = "SELECT name, kind, description FROM movies WHERE id=?";
                        $res = DBcontroller::get_instance()->prepare($rq);
                        $res->execute(array($id));
                        $user = $res->fetchAll(PDO::FETCH_ASSOC);
                        if (isset($tmp['name']))
                            $user[0]['name'] = $tmp['name'];
                        if (isset($tmp['kind']))
                            $user[0]['kind'] = $tmp['kind'];
                        if (isset($tmp['description']))
                            $user[0]['description'] = $tmp['description'];
                        $rq = "UPDATE movies SET name=?, kind=?, description=? WHERE id=?";
                        $res = DBcontroller::get_instance()->prepare($rq);
                        $res->execute(array($user[0]['name'], $user[0]['kind'], $user[0]['description'], $id));
                        return array('code' => '200', 'msg' => 'Movie updated');
                    }
                }
            }
        } else {
            return array('code' => '400', 'msg' => 'Forbidden : access token not specified');
        }
    }

}
