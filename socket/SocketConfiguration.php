<?php


namespace socket;


/**
 * Class SocketConfiguration
 * Класс конфигурации сокета
 * @package socket
 */
class SocketConfiguration
{
    /**
     * Параметр настроки домена
     * @var int
     */
    private int $domain;

    /**
     * Параметр настроки типа общения сокета
     * @var int
     */
    private int $type;

    /**
     * Параметр настроки протокола
     * @var int
     */
    private int $protocol;

    /**
     * SocketConfiguration constructor.
     * @param int $domain
     * @param int $type
     * @param int $protocol
     */
    public function __construct(int $domain, int $type, int $protocol)
    {
        $this->domain = $domain;
        $this->type = $type;
        $this->protocol = $protocol;
    }

    /**
     * Получение домена
     * @return int
     */
    public function getDomain(): int
    {
        return $this->domain;
    }

    /**
     * Получение типа
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * Получение протокола
     * @return int
     */
    public function getProtocol(): int
    {
        return $this->protocol;
    }

}