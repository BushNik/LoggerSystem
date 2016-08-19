<?php

namespace ru\f_technology\logger;

/**
 * Класс реализует логирование сообщений в базу данных.
 * Параметры БД берутся из файла DB.php
 * Запись происходит в следующую таблицу
 * CREATE TABLE IF NOT EXISTS log_table (
  id          INT NOT NULL AUTO_INCREMENT,
  logtime     TIMESTAMP NOT NULL,
  label       CHAR(16) NOT NULL,
  message     VARCHAR(200),
  PRIMARY KEY (id)
  );
 *
 * @author Bushuev Nikita
 */
class SQL extends Logger {

    /**
     * Строка содержащая SQL выражение для вставки данных в базу данных.
     *
     * @var string
     */
    private $sql = '';

    /**
     * Массив содержащий настройки для подключения к БД
     * Подключение к базе постоянное
     * @var array
     */
    private $options = array('persistent' => true);

    /**
     * Объект, содержащий ссылку на БД
     * @var object
     */
    private $db = null;

    /**
     * Ресурс, содержащий SQL запрос для вставки лога в БД
     * @var resource
     */
    private $statement = null;

    /**
     * Флаг, определяющий, что мы используем уже существующее соединение к БД
     * @var boolean
     */
    private $existingConnection = false;

    /**
     * Ассоциативный массив содержащий информацию для подключения к БД
     * @var array
     */
    private $dbProperties = array(
        'host' => 'localhost',
        'dbName' => 'test_db',
        'username' => 'test',
        'password' => '123456',
        'table' => 'log_table',
        'charset' => 'utf8'
    );

    /**
     * Максимальная длина уникального строкового идентификатора логгера
     * Ограничена длиной колонки label в БД
     * @var integer
     */
    private $labelLimit = 16;

    /**
     * Создаёт новый логгер с возможностью записывать логи в MySQL базу данных.
     *
     * @param string $properties   Информация для подключения к БД
     * @param string $label        Уникальный идентификатор логгера.
     * @param array $params        Массив настроек.
     */
    function __construct($properties, $label = '', $params = array()) {
        $this->dbProperties = $properties;

        // Составляем SQL запрос для вставки в БД
        if (!empty($params['sql'])) {
            $this->sql = $params['sql'];
        } else {
            $this->sql = 'INSERT INTO ' . $this->dbProperties['table'] .
                    ' (logtime, label, message)' .
                    ' VALUES(CURRENT_TIMESTAMP, ?, ?)';
        }

        // Если передали опции логгера
        if (isset($params['options']) && is_array($params['options'])) {
            $this->options = $params['options'];
        }

        // Ограничение на длину уникального идентификатора(label) логгера 
        if (isset($params['labelLimit'])) {
            $this->labelLimit = $params['labelLimit'];
        }

        // Устанавливаем label, учитывая максимальную длину
        $this->setLabel($label);

        // Если предоставили информацию о подлкючении к БД, используем её
        if (isset($params['db'])) {
            $this->db = &$params['db'];
            $this->existingConnection = true;
            $this->opened = true;
        }
    }

    /**
     * Открывает соединение к БД, если это не сделано уже.
     *
     * @return boolean   True в случае успеха и false в обратном случае.
     */
    function open() {
        if (!$this->opened) {
            // Используем информацию DSN и настройки для соединения с БД
            $dsn = 'mysql:host=' . $this->dbProperties['host'] .
                    ';dbname=' . $this->dbProperties['dbName'] .
                    ';charset=' . $this->dbProperties['charset'];
            try {
                $this->db = new \PDO($dsn, $this->dbProperties['username'], $this->dbProperties['password'], $this->options);
            } catch (PDOException $e) {
                return false;
            }

            // Создаём подготовленный SQL запрос
            if (!$this->prepareStatement()) {
                return false;
            }

            $this->opened = true;
        }

        return $this->opened;
    }

    /**
     * Закрывает соединение к БД, если оно открыто.
     *
     * @return boolean   True в случае успеха и false в обратном случае.
     */
    function close() {
        if ($this->opened && !$this->existingConnection) {
            $this->opened = false;
            $this->statement = NULL;
            return $this->db->disconnect();
        }

        return ($this->opened === false);
    }

    /**
     * Устанавливает label логгера на основе максимальной длины labelLimit
     *
     * @param string    $label      Новый строковый идентификатор.
     *
     */
    function setLabel($label) {
        $this->label = substr($label, 0, $this->labelLimit);
    }

    /**
     * Вставляет сообщение лога $message в БД. Открывает соединение к БД 
     * через open(), если это необходимо.
     *
     * @param mixed $message    Строка, массив, объект или исключение
     * @return boolean          True в случае успеха и false в обратном случае.
     */
    function log($message) {
        // Если соединение к БД не открыто, пытаемся его открыть, иначе возврат
        if (!$this->opened && !$this->open()) {
            return false;
        }

        // Проверяем наличие подготовленного запроса SQL
        if (!is_object($this->statement) && !$this->prepareStatement()) {
            return false;
        }

        // Получаем строковое сообщение лога
        $message = $this->getMessage($message);

        // Формируем массив параметров для вставки в БД
        $values = array($this->label, $message);

        // Выполняем подготовленный SQL запрос
        $result = $this->statement->execute($values);
        if ($result === false) {
            return false;
        }

        return true;
    }

    /**
     * Создаём подготовленный SQL запрос.
     * При использовании подготовленного запроса
     * СУБД анализирует/компилирует/оптимизирует запрос любой сложности 
     * только один раз, а приложение запускает на выполнение уже 
     * подготовленный шаблон. Таким образом подготовленные запросы 
     * потребляют меньше ресурсов и работают быстрее.
     *
     * @return boolean  True если выражение было успешно создано.
     *
     */
    function prepareStatement() {
        $this->statement = $this->db->prepare($this->sql);
        return $this->statement;
    }

    function flush() {
        
    }

}
