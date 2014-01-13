<?php

$f3 = require('framework/base.php');

$f3->set('DEBUG', 1);
if ((float) PCRE_VERSION < 7.9)
    trigger_error('PCRE version is out of date');

$f3->config('api/configs/config.ini');
$f3->config('api/configs/routes.ini');

$f3->route('GET /', function($f3) {
    Api::response(404, 0);
}
);


  $db = new DB\SQL(
    'mysql:host=localhost;port=3306;dbname=api_cifacom',
    'root',
    ''
  );

$f3->set('result',$db->exec('SELECT * FROM users'));
echo Template::instance()->render('abc.html');

$f3->run();