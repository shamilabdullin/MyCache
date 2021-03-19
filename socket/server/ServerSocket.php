<?php


namespace socket\server;


use Exception;
use socket\CustomSocket;

/**
 * Class ServerSocket
 * Класс для сокет-сервера
 * @package socket\server
 */
class ServerSocket extends CustomSocket implements IServerSocket
{
    /**
     * @inheritDoc
     * @param string $address
     * @param int $port
     * @throws Exception
     */
    public function create(string $address, int $port): void
    {
        if (!socket_bind($this->socket, $address, $port)) {
            throw new Exception(socket_strerror(socket_last_error($this->socket)));
        }
    }

    /**
     * @inheritDoc
     * @param $socketResource
     * @param string $message
     */
    public function sendReply($socketResource, string $message): void
    {
        socket_write($socketResource, $message, strlen($message));
    }

    /**
     * @inheritDoc
     * @return mixed|resource|\Socket
     * @throws Exception
     */
    public function accept()
    {
        $socketResource = socket_accept($this->socket);

        if ($socketResource === false) {
            throw new Exception(socket_strerror(socket_last_error($this->socket)));
        }

        return $socketResource;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function listen(): void
    {
        if (!socket_listen($this->socket, 5)) {
            throw new Exception(socket_strerror(socket_last_error($this->socket)));
        }
    }

    /**
     * @inheritDoc
     * @param $socketResource
     * @return string
     * @throws Exception
     */
    public function getMessage($socketResource): string
    {
        $message = socket_read($socketResource, 2048);

        if ($message === false) {
            throw new Exception(socket_strerror(socket_last_error($socketResource)));
        }

        return $message;
    }
}