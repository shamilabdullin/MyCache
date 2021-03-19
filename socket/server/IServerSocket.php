<?php

namespace socket\server;


/**
 * Interface IServerSocket
 * Интерфейс, описывающих характерное поведение сокет-сервера
 * @package socket\server
 */
interface IServerSocket
{
    /**
     * Создание сокет-сервера
     * @param string $address
     * @param int $port
     */
    public function create(string $address, int $port): void;

    /**
     * Отправка сообщения клиенту
     * @param $socketResource
     * @param string $message
     */
    public function sendReply($socketResource, string $message): void;

    /**
     * Получение подключения к сокету
     * @return mixed
     */
    public function accept();

    /**
     * Ожидание подключений к сокету
     */
    public function listen(): void;

    /**
     * Получение сообщения от клиента
     * @param $socketResource
     * @return string
     */
    public function getMessage($socketResource): string;
}