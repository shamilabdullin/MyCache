<?php

declare(strict_types=1);

use cache\CustomCache;
use socket\server\ServerSocket;
use socket\SocketConfiguration;

/* Вывод ошибок */
error_reporting(E_ALL);

/* Позволяет скрипту ожидать соединения бесконечно. */
set_time_limit(0);

/* Включает скрытое очищение вывода так, что мы видим данные
 * как только они появляются. */
ob_implicit_flush();

require_once 'vendor/autoload.php';

$address = '127.0.0.1';
$port = 8080;

$config = new SocketConfiguration(AF_INET, SOCK_STREAM, SOL_TCP);
$socket = new ServerSocket($config);

try {
    $socket->create($address, $port);
    $socket->listen();

    do {
        $msgSocket = $socket->accept();

        $msg = "\nДобро пожаловать на сервер. \n";
        $socket->sendReply($msgSocket, $msg);

        do {
            try {
                $incomingMessage = $socket->getMessage($msgSocket);
            } catch (Exception $exception) {
                echo $exception->getMessage();
                break 2;
            }

            if (empty($incomingMessage)) {
                continue;
            }

            $incomingData = json_decode($incomingMessage, true);
            $returnMessage = null;


            /*
                Если сообщение в формате JSON - пытаемся сохранить все пары ключ-значение,
                в противном случае получаем значение по строке, приняв ее за ключ
             */
            if (json_last_error() === JSON_ERROR_NONE) {
                foreach ($incomingData as $key => $value) {
                    try {
                        $cache = new CustomCache($key, $value);
                        $cache->add();

                        $returnMessage .= "Пара ($key - $value) была успешно добавлена в кеш на " . CustomCache::TIME_TO_LIVE . " секунд \n";
                    } catch (TypeError | Exception $exception) {
                        if ($exception instanceof TypeError) {
                            $returnMessage .= "Ключ или значение пары ($key - $value) не является строкой \n";
                        } else {
                            $returnMessage .= $exception->getMessage() . PHP_EOL;
                        }
                    }
                }
            } else {
                try {
                    $customCache = new CustomCache($incomingMessage);
                    $value = $customCache->getValueByKey();

                    $returnMessage = "Значение для ключа \"$incomingMessage\" : $value \n";
                } catch (Exception $exception) {
                    $returnMessage = $exception->getMessage() . "\n";
                }
            }

            // Отправка ответа + разрыв текущего подключения клиента
            $socket->sendReply($msgSocket, $returnMessage);
            break;
        } while (true);

        $socket->close($msgSocket);
    } while (true);

    $socket->close();
} catch (Exception $exception) {
    echo 'Ошибка сокет сервера: ' . $exception->getMessage() . PHP_EOL;
}
?>
