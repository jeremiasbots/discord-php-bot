<?php
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Intents;
use Discord\WebSockets\Event;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$token = $_ENV['BOT_TOKEN'] ?? null;
if (!$token) {
    die("Error: BOT_TOKEN isn´t a environment variable");
}

$discord = new Discord([
    'token' => $token,
    'intents' => Intents::getDefaultIntents() | Intents::MESSAGE_CONTENT
]);

$discord->on('init', function (Discord $discord) {
    echo "Bot is ready!", PHP_EOL;

    $discord->on(Event::MESSAGE_CREATE, function (Message $message) {
        if ($message->content === '!ping') {
            $user_id = $message->author->id;
            $message->reply("pong, <@$user_id>");
            unset($user_id);
        }
    });
});

$discord->run();
