<?php

namespace ru\f_technology\logger;

/**
 * Класс Logger реализует механизм логирования.
 * Является базовым классом для разных типов логирования:
 * в консоль, базу данных и файл.
 *
 * @author Bushuev Nikita
 */
abstract class Logger {

    /**
     * Массив логгеров определенного типа.
     * Всего может быть создан один логгер каждого типа.
     * Используется для реализации шаблона Singleton.    
     * @var array
     */
    private static $instances;

    /**
     * Максимальная глубина массива, которая записывается в лог.
     * 
     * @var type int
     */
    private static $arrayDepth = 2;

    public static function getArrayDepth() {
        return self::$arrayDepth;
    }

    public static function setArrayDepth(type $arrayDepth) {
        self::$arrayDepth = $arrayDepth;
    }

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
        if (!isset(self::$instances)) {
            self::$instances = [];
        }
        $signature = serialize(array($logType, $name, $id, $params));
        if (!isset(self::$instances[$signature])) {
            self::$instances[$signature] = self::factory($logType, $name, $id, $params);
        }
        return self::$instances[$signature];
    }

    // Функция инициализации логирования
    abstract function open();

    // Функция деинициализации логирования
    abstract function close();

    // Функция сброса данных в поток
    abstract function flush();

    /**
     *  Функция логирования сообщения $message
     * @param mixed $message Строка, массив, объект или исключение
     * @return boolean true в случае успешного логирования и false в обратном случае.
     */
    abstract function log($message);

    /**
     * Функция получения строкового значения сообщения лога
     * Сообщение может быть: объектом, исключением, массивом, строкой.
     * 
     * @param mixed $message    Сообщение для логирования
     * @return  string          Строковое значение сообщения лога
     */
    function getMessage($message) {
        if (is_array($message)) {
            // Возвращаем обрезанный массив до глубины self::getArrayDepth()
            $message = var_export(self::slice_array_depth($message, self::getArrayDepth()));
        } else if (is_object($message)) {
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

    /**
     * Возвращает обрезанный массив до глубины $depth.
     * Глубина = 0, означает, что будет выведен только сам массив.
     * 
     * @param type $array массив
     * @param type $depth глубина массива
     * @return type массив
     */
    private function slice_array_depth($array, $depth = 0) {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if ($depth > 0) {
                    $array[$key] = self::slice_array_depth($value, $depth - 1);
                } else {
                    unset($array[$key]);
                }
            }
        }
        return $array;
    }

}
