<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../vendor/autoload.php';
date_default_timezone_set('America/Sao_Paulo');

$faker = \Faker\Factory::create();

$conn = new \PDO('pgsql:host=127.0.0.1;dbname=tweet;port=5432', 'tweet', 'tweet');

$userGateway = new \App\Gateways\User($conn);
$tweetGateway = new \App\Gateways\Tweet($conn);

for ($ind = 0; $ind < 150; $ind ++) {
    $user = new \App\Entities\User;
    $user->setName($faker->name)
         ->setEmail($faker->email)
         ->setPass($faker->md5);

    $userGateway->add($user);

    $limit = rand(1, 100);
    for ($ind2 = 0; $ind2 < $limit; $ind2 ++) {
        $created = $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d h:i:s');

        $tweet = new \App\Entities\Tweet;
        $tweet->setContent($faker->text(140))
              ->setCreated($created)
              ->setAuthor($user->getId());

        $tweetGateway->add($tweet);
    }
}