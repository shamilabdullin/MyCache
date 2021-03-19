<?php

namespace socket\client;

/**
 * Interface IClientSocket
 * Интерфейс для сокет-клиента
 * @package socket\client
 */
interface IClientSocket
{
    /**
     * Подлючение к сокет-серверу
     * @param string $address
     * @param int $port
     */
    public function connect(string $address, int $port): void;

    /**
     * Отправка сообщения на сокет-сервер
     * @param string|null $message
     */
    public function sendMessage(?string $message): void;

    /**
     * Вывод ответов, полученных от сокет-сервера
     */
    public function printReplies(): void;
}