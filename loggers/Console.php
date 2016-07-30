<?php

namespace ru\f_technology\logger;

/**
 * Класс реализует логирование сообщений в консоль
 *
 * @author Bushuev Nikita
 */
class Console extends Logger {

    /**
     * Формат сообщения лога
     * @var string
     */
    private $lineFormat = '%1$s [%2$s] %3$s';

    /**
     * Формат даты и времени лога: YYYY-MM-DD HH:MM:SS
     * @var string
     */
    private $timeFormat = '%Y-%m-%d %H:%M:%S';

    /**
     * Поток для записи сообщения логов
     * @var resource 
     */
    private $stream = null;

    /**
     * Нужно ли закрывать поток $stream?
     * @var bool
     */
    var $_closeResource = false;

    /**
     * Создаёт новый логгер с возможностью записывать логи в заданный поток.
     * По умолчанию это STDOUT. Если STDOUT не определен, то создаётся новый поток.
     *
     * @param string $name         Не используется
     * @param string $label        Уникальный идентификатор логгера.
     * @param array $params        Массив настроек.
     */
    function __construct($name, $label = '', $params = []) {
        $this->label = $label;
        if (!empty($params['stream'])) {
            $this->stream = $params['stream'];
        } elseif (defined('STDOUT')) {
            $this->stream = STDOUT;
        } else {
            $this->stream = fopen('php://output', 'a');
            $this->closeResource = true;
        }
        if (!empty($params['lineFormat'])) {
            $this->lineFormat = str_replace(array_keys($this->formatMap), array_values($this->formatMap), $params['lineFormat']);
        }
        if (!empty($params['timeFormat'])) {
            $this->timeFormat = $params['timeFormat'];
        }
    }

    /**
     * Закрываем поток, перед этим сбрасываем данные в поток.
     *
     * @return boolean   True в случае успеха и false в обратном случае.
     */
    function close() {
        $this->flush();
        $this->opened = false;
        if ($this->closeResource === true && is_resource($this->stream)) {
            fclose($this->stream);
        }
        return true;
    }

    /**
     * Ставим флаг в значение истина, что поток для записи открыт.
     *
     * @return boolean   True в случае успеха и false в обратном случае.
     */
    function open() {
        $this->opened = true;
        return true;
    }

    /**
     * Сбрасывает все данные в поток
     *
     */
    function flush() {
        if (is_resource($this->stream)) {

            return fflush($this->stream);
        }

        return false;
    }

    /**
     * Выводит сообщение лога $message в поток $stream.
     *
     * @param mixed $message    Строка, массив, объект или исключение
     * @return boolean          True в случае успеха и false в обратном случае.
     */
    function log($message) {
        $message = $this->getMessage($message);
        $line = $this->formatMessage($this->lineFormat, strftime($this->timeFormat), $message) . "\n";
        fwrite($this->stream, $line);
    }

}
