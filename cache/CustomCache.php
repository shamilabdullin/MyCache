<?php

namespace cache;

use Exception;

/**
 * Class CustomCache
 * Класс для работы с кешем
 * @package cache
 */
class CustomCache
{
    /**
     * Приходящий от клиента ключ
     * @var string
     */
    private string $key;

    /**
     * Приходящее от клиента значение
     * @var string|null
     */
    private ?string $value;

    /**
     * Максимальное количество символов в строке ключа/значения
     */
    private const MAXIMUM_ALLOWED_SIZE = 5;

    /**
     * Время нахождения в кеше в секундах
     */
    public const TIME_TO_LIVE = 10;

    /**
     * CustomCache constructor.
     * Конструктор с валидацией приходящей пары ключ-значение
     * @param string $key
     * @param string|null $value
     * @throws Exception
     */
    public function __construct(string $key, ?string $value = null)
    {
        if (!$this->validate($key, $value)) {
            throw new Exception(
                "Ключ или значение ($key - $value) больше дозволенного размера в " . self::MAXIMUM_ALLOWED_SIZE . ' символов'
            );
        }

        $this->key = $key;
        $this->value = $value;
    }

    /**
     * Метод валидирует данные длина строки ключа, длина строки значения (если отправлено)
     * @param string $key
     * @param string|null $value
     * @return bool
     */
    private function validate(string $key, ?string $value): bool
    {
        $correctData = true;

        if ((strlen($key) > self::MAXIMUM_ALLOWED_SIZE) || (isset($value) && strlen($value) > self::MAXIMUM_ALLOWED_SIZE)) {
            $correctData = false;
        }

        return $correctData;
    }

    /**
     * Функция добавления в кеш пары ключ-значение
     * @return bool
     * @throws Exception
     */
    public function add(): bool
    {
        if (!isset($this->value)) {
            throw new Exception('Значение не отправлено');
        }

        return apcu_add($this->key, $this->value, self::TIME_TO_LIVE);
    }

    /**
     * Функция получения значения из кеша по его ключу
     * @return string
     * @throws Exception
     */
    public function getValueByKey(): string
    {
        $value = apcu_fetch($this->key);

        if (!$value) {
            throw new Exception(
                'Переменной, которую вы хотите получить, нет в кеше. Возможно истекло время хранения.'
            );
        }

        return $value;
    }
}