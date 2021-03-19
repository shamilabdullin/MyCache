<?php

namespace socket;

/**
 * Class CustomSocket
 * @package socket
 */
class CustomSocket
{
    /**
     * Сокет
     * @var false|resource|\Socket
     */
    protected $socket;

    /**
     * CustomSocket constructor.
     * Создание сокета
     * @param SocketConfiguration $config
     */
    public function __construct(SocketConfiguration $config)
    {
        $this->socket = socket_create(
            $config->getDomain(),
            $config->getType(),
            $config->getProtocol()
        );
    }

    /**
     * Закрытие сокета (по дефолту - текущий), но можно подать необходимый
     * @param null $socketToClose
     */
    public function close($socketToClose = null): void
    {
        socket_close($socketToClose ?? $this->socket);
    }

}