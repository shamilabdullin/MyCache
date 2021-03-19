<?php


namespace socket\client;


use Exception;
use socket\CustomSocket;

/**
 * Class ClientSocket
 * Класс для сокет-клиента
 * @package socket\client
 */
class ClientSocket extends CustomSocket implements IClientSocket
{
    /**
     * @inheritDoc
     * @param string $address
     * @param int $port
     * @throws Exception
     */
    public function connect(string $address, int $port): void
    {
        if (!socket_connect($this->socket, $address, $port)) {
            throw new Exception(socket_strerror(socket_last_error($this->socket)));
        }
    }

    /**
     * @inheritDoc
     * @param string|null $message
     * @throws Exception
     */
    public function sendMessage(?string $message): void
    {
        $sentMessage = socket_write($this->socket, $message, strlen($message));

        if ($sentMessage === false) {
            throw new Exception(socket_strerror(socket_last_error($this->socket)));
        }
    }

    /**
     * @inheritDoc
     */
    public function printReplies(): void
    {
        while ($out = socket_read($this->socket, 2048)) {
            echo $out;
        }
    }
}