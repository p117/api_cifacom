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

        $rq = "SELECT * FROM users";
        $res = DBcontroller::get_instance()->prepare($rq);
        $res->execute();
        $tab = $res->fetchAll(PDO::FETCH_ASSOC);
        return $tab;
    }

    //Connexion d'un utilisateur
    public static function log() {

        if (isset($_GET['mail']) && isset($_GET['password'])) {
            $rq = "SELECT * FROM users WHERE mail=?";
            $res = DBcontroller::get_instance()->prepare($rq);
            $res->execute(array($_GET['mail']));
            $tab = $res->fetchAll(PDO::FETCH_ASSOC);
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

        if (isset($_POST['access_token'])) {
            $rq = "SELECT admin FROM users WHERE access_token=?";
            $res = DBcontroller::get_instance()->prepare($rq);
            $res->execute(array($_POST['access_token']));
            $tab = $res->fetchAll(PDO::FETCH_ASSOC);
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
                $rq = "DELETE FROM users WHERE id=?";
                $res = DBcontroller::get_instance()->prepare($rq);
                $res->execute(array($id));
                return array('code' => '200', 'msg' => 'User deleted');
            }
        }
    }

    public static function search($id) {

        $rq = "SELECT * FROM users WHERE id=?";
        $res = DBcontroller::get_instance()->prepare($rq);
        $res->execute(array($id));
        $tab = $res->fetchAll(PDO::FETCH_ASSOC);
        if ($res->rowCount() == 0) {
            return array('code' => '400', 'msg' => 'User does not exist');
        } else {
            return $tab;
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
                if (isset($_GET['password']) || isset($_GET['login']) || isset($_GET['mail'])) {
                    $tmp = array();
                    if (isset($_GET['password']) && !empty($_GET['password'])) {
                        $tmp['password'] = $_GET['password'];
                    }
                    if (isset($_GET['mail']) && !empty($_GET['mail'])) {
                        $tmp['mail'] = $_GET['mail'];
                    }
                    if (isset($_GET['login']) && !empty($_GET['login'])) {
                        $tmp['login'] = $_GET['login'];
                    }
                    print_r($tmp);
                    $rq = "SELECT password, mail, login FROM users WHERE id=?";
                    $res = DBcontroller::get_instance()->prepare($rq);
                    $res->execute(array($id));
                    $user = $res->fetchAll(PDO::FETCH_ASSOC);
                    print_r($user[0]);
                    if (isset($tmp['password']))
                        $user[0]['password'] = $tmp['password'];
                    if (isset($tmp['mail']))
                        $user[0]['mail'] = $tmp['mail'];
                    if (isset($tmp['login']))
                        $user[0]['login'] = $tmp['login'];
                    print_r($user[0]);
                    $rq = "UPDATE users SET password=?, login=?, mail=? WHERE id=?";
                    $res = DBcontroller::get_instance()->prepare($rq);
                    $res->execute(array($user[0]['password'], $user[0]['login'], $user[0]['mail'], $id));
                    return array('code' => '200', 'msg' => 'User updated');
                }
            }
        }
    }

    public static function IntMovie($movieId) {
        $flag = true;
        if (count($_POST) > 1) {
            return array('code' => '400', 'msg' => 'Too many arguments');
        } else {
            foreach ($_POST as $key => $value) {
                $action = $key;
                $userId = $value;
            }
            $rq = "SELECT * FROM users WHERE id=?";
            $res = DBcontroller::get_instance()->prepare($rq);
            $res->execute(array($userId));
            if ($res->rowCount() == 0) {
                return array('code' => '400', 'msg' => 'User does not exist');
            } else {
                switch ($action) {
                    case 'like':
                        $rq = "SELECT * FROM likes WHERE user_id=? AND movie_id=?";
                        $res = DBcontroller::get_instance()->prepare($rq);
                        $res->execute(array($userId, $movieId));
                        if ($res->rowCount() == 0) {
                            $rq = "INSERT INTO likes (user_id, movie_id) VALUES (?, ?)";
                        } else {
                            return array('code' => '400', 'msg' => 'This user already likes this movie');
                            $flag = false;
                        }
                        break;
                    case 'view':
                        $rq = "SELECT * FROM views WHERE user_id=? AND movie_id=?";
                        $res = DBcontroller::get_instance()->prepare($rq);
                        $res->execute(array($userId, $movieId));
                        if ($res->rowCount() == 0) {
                            $rq = "INSERT INTO views (user_id, movie_id) VALUES (?, ?)";
                        } else {
                            return array('code' => '400', 'msg' => 'This user already views this movie');
                            $flag = false;
                        }
                        break;
                    case 'wish':
                        $rq = "SELECT * FROM wishes WHERE user_id=? AND movie_id=?";
                        $res = DBcontroller::get_instance()->prepare($rq);
                        $res->execute(array($userId, $movieId));
                        if ($res->rowCount() == 0) {
                            $rq = "INSERT INTO wishes (user_id, movie_id) VALUES (?, ?)";
                        } else {
                            return array('code' => '400', 'msg' => 'This user already wishes seeing this movie');
                            $flag = false;
                        }
                        break;
                }
                if ($flag == true) {
                    $res = DBcontroller::get_instance()->prepare($rq);
                    $res->execute(array((int) $userId, (int) $movieId));
                    return array('code' => '400', 'msg' => 'Action complete');
                }
            }
        }
    }

    public static function ListViews($userId) {
        $rq = "SELECT * FROM users WHERE id=?";
        $res = DBcontroller::get_instance()->prepare($rq);
        $res->execute(array($userId));
        $tab = $res->fetchAll(PDO::FETCH_ASSOC);
        if ($res->rowCount() == 0) {
            return array('code' => '400', 'msg' => 'User does not exist');
        } else {
            $rq = "SELECT name FROM views INNER JOIN movies ON views.movie_id = movies.id WHERE user_id=?";
            $res = DBcontroller::get_instance()->prepare($rq);
            $res->execute(array($userId));
            $tab = $res->fetchAll(PDO::FETCH_ASSOC);
            return $tab;
        }
    }

    public static function ListLikes($userId) {
        $rq = "SELECT * FROM users WHERE id=?";
        $res = DBcontroller::get_instance()->prepare($rq);
        $res->execute(array($userId));
        $tab = $res->fetchAll(PDO::FETCH_ASSOC);
        if ($res->rowCount() == 0) {
            return array('code' => '400', 'msg' => 'User does not exist');
        } else {
            $rq = "SELECT name FROM likes INNER JOIN movies ON likes.movie_id = movies.id WHERE user_id=?";
            $res = DBcontroller::get_instance()->prepare($rq);
            $res->execute(array($userId));
            $tab = $res->fetchAll(PDO::FETCH_ASSOC);
            return $tab;
        }
    }

    public static function ListWishes($userId) {
        $rq = "SELECT * FROM users WHERE id=?";
        $res = DBcontroller::get_instance()->prepare($rq);
        $res->execute(array($userId));
        $tab = $res->fetchAll(PDO::FETCH_ASSOC);
        if ($res->rowCount() == 0) {
            return array('code' => '400', 'msg' => 'User does not exist');
        } else {
            $rq = "SELECT name FROM wishes INNER JOIN movies ON wishes.movie_id = movies.id WHERE user_id=?";
            $res = DBcontroller::get_instance()->prepare($rq);
            $res->execute(array($userId));
            $tab = $res->fetchAll(PDO::FETCH_ASSOC);
            return $tab;
        }
    }

    public static function DeleteView($userId, $movieId) {
        $rq = "SELECT * FROM users WHERE id=?";
        $res = DBcontroller::get_instance()->prepare($rq);
        $res->execute(array($userId));
        $tab = $res->fetchAll(PDO::FETCH_ASSOC);
        if ($res->rowCount() == 0) {
            return array('code' => '400', 'msg' => 'User does not exist');
        } else {
            $rq = "SELECT * FROM views WHERE user_id=? AND movie_id=?";
            $res = DBcontroller::get_instance()->prepare($rq);
            $res->execute(array($userId, $movieId));
            $tab = $res->fetchAll(PDO::FETCH_ASSOC);
            if ($res->rowCount() == 0) {
                return array('code' => '400', 'msg' => 'This association does not exist');
            } else {
                $rq = "DELETE FROM views WHERE user_id=? AND movie_id=?";
                $res = DBcontroller::get_instance()->prepare($rq);
                $res->execute(array($userId, $movieId));
                return array('code' => '400', 'msg' => 'View deleted');
            }
        }
    }

    public static function DeleteLike($userId, $movieId) {
        $rq = "SELECT * FROM users WHERE id=?";
        $res = DBcontroller::get_instance()->prepare($rq);
        $res->execute(array($userId));
        $tab = $res->fetchAll(PDO::FETCH_ASSOC);
        if ($res->rowCount() == 0) {
            return array('code' => '400', 'msg' => 'User does not exist');
        } else {
            $rq = "SELECT * FROM likes WHERE user_id=? AND movie_id=?";
            $res = DBcontroller::get_instance()->prepare($rq);
            $res->execute(array($userId, $movieId));
            $tab = $res->fetchAll(PDO::FETCH_ASSOC);
            if ($res->rowCount() == 0) {
                return array('code' => '400', 'msg' => 'This association does not exist');
            } else {
                $rq = "DELETE FROM likes WHERE user_id=? AND movie_id=?";
                $res = DBcontroller::get_instance()->prepare($rq);
                $res->execute(array($userId, $movieId));
                return array('code' => '400', 'msg' => 'Like deleted');
            }
        }
    }

    public static function DeleteWish($userId, $movieId) {
        $rq = "SELECT * FROM users WHERE id=?";
        $res = DBcontroller::get_instance()->prepare($rq);
        $res->execute(array($userId));
        $tab = $res->fetchAll(PDO::FETCH_ASSOC);
        if ($res->rowCount() == 0) {
            return array('code' => '400', 'msg' => 'User does not exist');
        } else {
            $rq = "SELECT * FROM wishes WHERE user_id=? AND movie_id=?";
            $res = DBcontroller::get_instance()->prepare($rq);
            $res->execute(array($userId, $movieId));
            $tab = $res->fetchAll(PDO::FETCH_ASSOC);
            if ($res->rowCount() == 0) {
                return array('code' => '400', 'msg' => 'This association does not exist');
            } else {
                $rq = "DELETE FROM wishes WHERE user_id=? AND movie_id=?";
                $res = DBcontroller::get_instance()->prepare($rq);
                $res->execute(array($userId, $movieId));
                return array('code' => '400', 'msg' => 'Wish deleted');
            }
        }
    }

}
