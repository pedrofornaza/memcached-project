<?php

$start = microtime(true);

include '../vendor/autoload.php';

use App\Gateways\Tweet as Gateway;
use App\Entities\Tweet as Entity;

$conn = new \PDO('pgsql:host=127.0.0.1;dbname=tweet;port=5432', 'tweet', 'tweet');
$cache = new \Memcached('tweets');
$cache->addServer('localhost', 11211);

$tweetGateway = new Gateway($conn, $cache);
$tweets = $tweetGateway->fetchAll();

?>
<!doctype html>
<html lang="en-us">
    <head>
        <title>Best Tweets of all times</title>
        <meta charset="utf-8" />

        <link rel="stylesheet" href="css/general.css">
    </head>
    <body>
        <article>
            <h1>Best Tweets of all times</h1>
            <?php foreach($tweets as $tweet) : ?>
            <section>
                <div class="tweet"><p><?php echo $tweet->getContent(); ?></p></div>
                <div class="author"><p>by <a href="http://twitter.com/<?php echo $tweet->getAuthor()->getUsername(); ?>"><?php echo $tweet->getAuthor()->getName(); ?></a> at <?php echo date('m/d/Y', strtotime($tweet->getCreated())); ?></p></div>
            </section>
            <?php endforeach; ?>
        </article>
        <footer>
            <p>Developed by Felipe Bitencourt, Gerson Donscoi and Pedro Fornaza.</p>
            <p>Pagina gerada em <?php echo round(microtime(true) - $start, 2) ?> segundos.</p>
        </footer>
    </body>
</html>