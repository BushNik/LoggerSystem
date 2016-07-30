<?php

namespace ru\f_technology\logger;

/**
 * Класс Logger реализует механизм логирования.
 * Является базовым классом для разных типов логирования:
 * в консоль, базу данных и файл.
 *
 * @author Bushuev Nikita
 */
class Logger {

    /**
     * Массив логгеров определенного типа.
     * Всего может быть создан один логгер каждого типа.
     * Используется для реализации шаблона Singleton.    
     * @var array
     */
    private static $instances;

    /**
     * Уникальный строковый идентификатор логгера
     */
    protected $label = '';

    /**
     * Определяет может ли быть открыт и использоваться логгер.
     *
     * @var boolean
     */
    protected $opened = false;

    /**
     * Ассоциативный массив соответствия параметра и его порядка в
     * форматированном сообщении лога $lineFormat
     *
     * @var array
     */
    protected $formatMap = array('%{timestamp}' => '%1$s',
        '%{label}' => '%2$s',
        '%{message}' => '%3$s');

    // Конструктор
    function __construct() {
        
    }

    /**
     * Создает экземпляр логера конкретного типа $type
     *
     * @param string $logType   Тип логгера. Динамически создаем экземпляр 
     *                          класса соответствующего логгера, имя которого
     *                          передаем в $logType. 
     *                          Валидные значения: 'Console', 'MySQL', 'File'.
     *
     * @param string $name      Имя файла, таблицы базы данных или другого
     *                          хранилища в зависимости от типа логгера
     *
     * @param string $id        Уникальный идентификатор логгера
     *
     * @param array $params     Дополнительные параметры необходимые для создания
     *                          конкретного типа логгера
     *
     * @return object Logger    Возвращает новый экземпляр логгера определенного
     *                          типа или null в случае ошибки.
     */
    static function factory($logType, $name = '', $id = '', $params = []) {
        // используем автозагрузку классов
        spl_autoload_register(function ($className) {
            // получаем имя класса без пространства имен и включаем файл
            $className = basename($className);
            $file = dirname(__FILE__) . "/loggers/{$className}.php";
            if (file_exists($file)) {
                require_once $file;
            }
        });
        // создаем класс в текущем пространстве имен
        $logType = __NAMESPACE__ . '\\' . $logType;
        return new $logType($name, $id, $params);
    }

    /**
     * Создает экземпляр логера конкретного типа $type, 
     * только если он не существует.
     * Используем шаблон Singleton.
     *
     * <b>Вызывать нужно через синтаксис $var = &Log::singleton()
     * Без амперсанда (&) перед вызовом метода, вернется копия
     * а не ссылка</b>
     *
     * @param string $logType   Тип логгера. Динамически создаем экземпляр 
     *                          класса соответствующего логгера, имя которого
     *                          передаем в $logType. 
     *                          Валидные значения: 'Console', 'MySQL', 'File'.
     *
     * @param string $name      Имя файла, таблицы базы данных или другого
     *                          хранилища в зависимости от типа логгера
     *
     * @param string $id        Уникальный идентификатор логгера
     *
     * @param array $params     Дополнительные параметры необходимые для создания
     *                          конкретного типа логгера
     *
     * @return object Logger    Возвращает новый экземпляр логгера определенного
     *                          типа или null в случае ошибки.
     */
    static function singleton($logType, $name = '', $id = '', $params = []) {
        if (!isset($instances)) {
            $instances = [];
        }
        $signature = serialize(array($logType, $name, $id, $params));
        if (!isset($instances[$signature])) {
            $instances[$signature] = Logger::factory($logType, $name, $id, $params);
        }
        return $instances[$signature];
    }

    // Функция инициализации логирования
    function open() {
        return false;
    }

    // Функция деинициализации логирования
    function close() {
        return false;
    }

    // Функция сброса данных в поток
    function flush() {
        return false;
    }

    /**
     *  Функция логирования сообщения $message
     * @param mixed $message Строка, массив, объект или исключение
     * @return boolean true в случае успешного логирования и false в обратном случае.
     */
    function log($message) {
        return false;
    }

    /**
     * Функция получения строкового значения сообщения лога
     * Сообщение может быть: объектом, исключением, массивом, строкой
     * @param mixed $message    Сообщение для логирования
     * @return  string          Строковое значение сообщения лога
     */
    function getMessage($message) {
        if (is_object($message)) {
            // если это объект, то вызываем метод __toString, если он существует
            if (method_exists($message, '__toString')) {
                $message = (string) $message;
            } else {
                $message = var_export($message, true);
            }
        } else if ($message instanceof \Exception) {
            // если исключение, то выводим сообщение исключения
            $message = $message->getMessage();
        } else {
            // иначе получаем строковое значение переменной
            $message = var_export($message, true);
        }
        return $message;
    }

    /**
     * Возвращает сообщение лога в заданном формате $format
     * @param string $format    Формат сообщения лога
     * @param string $timestamp Дата и время лога
     * @param string $message   Сообщение лога
     * @return  string    
     */
    function formatMessage($format, $timestamp, $message) {
        return sprintf($format, $timestamp, $this->label, $message);
    }

}
