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
            return array('code' => '400', 'msg' => 'Movie does not exist');
        } else {
            return $tab;
        }
    }

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
                $rq = "DELETE FROM movies WHERE id=?";
                $res = DBcontroller::get_instance()->prepare($rq);
                $res->execute(array($id));
                return array('code' => '200', 'msg' => 'Movie deleted');
            }
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
                if (isset($_GET['name']) || isset($_GET['kind']) || isset($_GET['description'])) {
                    $tmp = array();
                    if (isset($_GET['name']) && !empty($_GET['name'])) {
                        $tmp['name'] = $_GET['name'];
                    }
                    if (isset($_GET['kind']) && !empty($_GET['kind'])) {
                        $tmp['kind'] = $_GET['kind'];
                    }
                    if (isset($_GET['description']) && !empty($_GET['description'])) {
                        $tmp['description'] = $_GET['description'];
                    }
                    print_r($tmp);
                    $rq = "SELECT name, kind, description FROM movies WHERE id=?";
                    $res = DBcontroller::get_instance()->prepare($rq);
                    $res->execute(array($id));
                    $user = $res->fetchAll(PDO::FETCH_ASSOC);
                    print_r($user[0]);
                    if (isset($tmp['name']))
                        $user[0]['name'] = $tmp['name'];
                    if (isset($tmp['kind']))
                        $user[0]['kind'] = $tmp['kind'];
                    if (isset($tmp['description']))
                        $user[0]['description'] = $tmp['description'];
                    print_r($user[0]);
                    $rq = "UPDATE movies SET name=?, kind=?, description=? WHERE id=?";
                    $res = DBcontroller::get_instance()->prepare($rq);
                    $res->execute(array($user[0]['name'], $user[0]['kind'], $user[0]['description'], $id));
                    return array('code' => '200', 'msg' => 'Movie updated');
                }
            }
        }
    }

}
