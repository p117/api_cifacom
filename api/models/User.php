<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class User {

    public $id;
    public $login;
    public $mail;
    public $password;
    public $access_token;

    public function __construct($login = '', $mail = '', $password = '', $access_token = '', $id = -1) {

        parent::__construct();
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
                return array('code' => '400', 'msg' => 'Error : Wrong mail / password');
            } else {
                if ($tab[0]['password'] == $_GET['password']) {
                    return array('code' => '200', 'msg' => 'User authentified');
                } else {
                    return array('code' => '400', 'msg' => 'Error : Wrong mail / password');
                }
            }
        } else {
            return array('code' => '400', 'msg' => 'Error : Complete all fields');
        }
    }

    //Enregistrement en base d'un utilisateur
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
                    return array('code' => '400', 'msg' => 'Your are not able to create a user');
                } else {
                    if (isset($_POST['mail']) && isset($_POST['login'])) {
                        $rq = "INSERT INTO users(login, password, access_token, mail, admin) VALUES (?,?,?,?,?)";
                        $res = DBcontroller::get_instance()->prepare($rq);
                        $res->execute(array($_POST['login'], 'password', md5($_POST['mail']), $_POST['mail'], 0));
                        return array('code' => '200', 'msg' => 'Account created');
                    } else {
                        return array('code' => '400', 'msg' => 'Error : Complete all fields');
                    }
                }
            }
        } else {
            return array('code' => '400', 'msg' => 'Error : You have to be logged as admin');
        }
    }

    //Suppression d'un utilisateur
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
                    $rq = "DELETE FROM users WHERE id=?";
                    $res = DBcontroller::get_instance()->prepare($rq);
                    $res->execute(array($id));
                    return array('code' => '200', 'msg' => 'Success : User deleted');
                }
            }
        } else {
            return array('code' => '400', 'msg' => 'Error : You have to be logged as admin');
        }
    }

    //Affichage des informations d'un utilisateur
    public static function search($id) {

        $rq = "SELECT * FROM users WHERE id=?";
        $res = DBcontroller::get_instance()->prepare($rq);
        $res->execute(array($id));
        $tab = $res->fetchAll(PDO::FETCH_ASSOC);
        if ($res->rowCount() == 0) {
            return array('code' => '400', 'msg' => 'Error : User does not exist');
        } else {
            return $tab;
        }
    }

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
                    if (isset($put['password']) || isset($put['login']) || isset($put['mail'])) {
                        $tmp = array();
                        if (isset($put['password']) && !empty($put['password'])) {
                            $tmp['password'] = $put['password'];
                        }
                        if (isset($put['mail']) && !empty($put['mail'])) {
                            $tmp['mail'] = $put['mail'];
                        }
                        if (isset($put['login']) && !empty($put['login'])) {
                            $tmp['login'] = $put['login'];
                        }
                        $rq = "SELECT password, mail, login FROM users WHERE id=?";
                        $res = DBcontroller::get_instance()->prepare($rq);
                        $res->execute(array($id));
                        $user = $res->fetchAll(PDO::FETCH_ASSOC);
                        if (isset($tmp['password']))
                            $user[0]['password'] = $tmp['password'];
                        if (isset($tmp['mail']))
                            $user[0]['mail'] = $tmp['mail'];
                        if (isset($tmp['login']))
                            $user[0]['login'] = $tmp['login'];
                        $rq = "UPDATE users SET password=?, login=?, mail=? WHERE id=?";
                        $res = DBcontroller::get_instance()->prepare($rq);
                        $res->execute(array($user[0]['password'], $user[0]['login'], $user[0]['mail'], $id));
                        return array('code' => '200', 'msg' => 'User updated');
                    }
                }
            }
        } else {
            return array('code' => '400', 'msg' => 'Forbidden : access token not specified');
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
