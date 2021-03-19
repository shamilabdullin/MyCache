<?php

declare(strict_types=1);

use socket\client\ClientSocket;
use socket\SocketConfiguration;

/* Вывод ошибок */
error_reporting(E_ALL);

require_once 'vendor/autoload.php';

$address = '127.0.0.1';
$port = 8080;

$config = new SocketConfiguration(AF_INET, SOCK_STREAM, SOL_TCP);
$socket = new ClientSocket($config);

echo "Пытаемся соединиться с '$address' на порту '$port'...\n";
try {
    $socket->connect($address, $port);
} catch (Exception $exception) {
    echo 'Ошибка подключения к сокет-серверу: ' . $exception->getMessage() . PHP_EOL;
}

$message = json_encode([
    'foo' => 'bar',
    'fooooo' => 123456,
    'foooooo' => 'qqqqqqq',
]);

echo "Отправляем запрос...\n";
try {
    $socket->sendMessage($message);
} catch (Exception $exception) {
    echo $exception->getMessage();
}
echo "Message OK.\n";

echo "Читаем ответ:\n";
$socket->printReplies();

echo "Закрываем сокет...\n";
$socket->close();
unset($socket);
echo "OK.\n\n";

/*
    После успешного сохранения пары ключ-значение на сервере, пытаемся получить значение по ключу.
    Так как время жизни 10 секунд - в первый раз получаем значение, во второй его уже нет.
*/
for ($runTimes = 0; $runTimes < 2; $runTimes++) {
    sleep(6);

    $socket = new ClientSocket($config);

    echo "Пытаемся соединиться с '$address' на порту '$port'...\n";
    try {
        $socket->connect($address, $port);
    } catch (Exception $exception) {
        echo 'Ошибка подключения к сокет-серверу: ' . $exception->getMessage() . PHP_EOL;
    }

    $message = 'foo';

    echo "Отправляем запрос...\n";
    try {
        $socket->sendMessage($message);
    } catch (Exception $exception) {
        echo $exception->getMessage();
    }
    echo "Message OK.\n";

    echo "Читаем ответ:\n";
    $socket->printReplies();

    echo "Закрываем сокет...\n";
    $socket->close();
    unset($socket);
    echo "OK.\n\n";
}