<?php

class Database extends PDO {

    const HOST = 'localhost';
    const DATABASE = 'iwgb-llamadas';

    /**
     * Database constructor.
     */
    public function __construct() {

        parent::__construct('mysql:host=' . self::HOST . ';dbname=' . self::DATABASE . ';charset=utf8',
            Keys::DBCredentials['Caller'],
            Keys::DBCredentials['pass'],
            [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]
        );
    }

    /**
     * @param $sql
     * @param null $params
     * @return bool|PDOStatement
     */
    public function run($sql, $params = null) {
        if (!$params) return $this->query($sql);

        $q = $this->prepare($sql);
        $q->execute($params);
        return $q;
    }

    /**
     * @param $tableName string table you wish to update
     * @param $params mixed[] The parameters that should be updated
     * @param $primary string The identifier of the row to be updated
     * @return bool Success, or not
     */
    public function update($tableName, $params, $primary) {
        $sql = 'UPDATE :tableName SET';
        foreach ($params as $key => $value) {
            $sql = self::appendQuery($sql, "$key = :$key,");
        }
        $sql = substr($sql, 0, -1);
        $sql = self::appendQuery($sql, "WHERE $primary = :$primary");
        $q = $this->run($sql, array_merge($params, [
            'tableName' => $tableName,
            $primary    => $params[$primary],
        ]));
        return (bool) $q->rowCount();
    }

    public function insert($tableName, $params) {
        $sql = /** @lang text */
            'INSERT INTO :tableName (';
        foreach ($params as $key => $value) {
            $sql = self::appendQuery($sql, "$key,");
        }
        $sql = substr($sql, 0, -1);
        $sql = self::appendQuery($sql, ') VALUES (');
        foreach ($params as $key => $value) {
            $sql = self::appendQuery($sql, ":$key,");
        }
        $sql = substr($sql, 0, -1);
        $sql = self::appendQuery($sql, ')');
        $this->run($sql,
            array_merge($params, ['tableName' => $tableName]));
    }

    public function exists($tableName, $primaryKey, $primaryValue) {
        return $this->run("SELECT * 
            FROM $tableName 
            WHERE $primaryKey = :primaryValue",
            ['primaryValue' => $primaryValue]
        )->fetchColumn();
    }

    public function save($tableName, $params, $primary) {
        if ($this->exists($tableName, $primary, $params[$primary])) {
            $this->update($tableName, $params, $primary);
        } else {
            $this->insert($tableName, $params);
        }
    }

    /**
     * @param string $q The query string
     * @param string $append The SQL to append
     * @return string $append appended to $q
     */
    public static function appendQuery($q, $append) {
        return "$q $append";
    }

    /* CALLERS */

    /**
     * @param $email
     * @return Caller|bool
     */
    public function getCaller($id) {
        return $this->run('SELECT * 
            FROM callers 
            WHERE id = :id',
            ['id' => $id]
        )->fetchObject('Caller');
    }

    public function getCallers($campaign) {
        return $this->run('SELECT *
            FROM callers
            WHERE campaign = :campaign',
            ['campaign' => $campaign]
        );
    }

    /* CALLEES */

    /**
     * @param $number
     * @return Callee|bool
     */
    public function getCallee($id) {
        return $this->run('SELECT *
            FROM callees
            WHERE id = :id',
            ['number' => $id]
        )->fetchObject('Callee');
    }

    public function getCallees($campaign) {
        return $this->run('SELECT *
            FROM callees',
            ['campaign' => $campaign]
        );
    }

    public function getNextCallee($campaign) {
        return $this->run('SELECT *
            FROM callees 
            WHERE recall = TRUE
            AND campaign = :campaign',
            ['campaign' => $campaign]
        )->fetchObject('Callee');
    }

    /* CALLS */

    public function getCall($id) {
        return $this->run('SELECT *
            FROM calls
            WHERE id = :id',
            ['id' => $id]
        )->fetchObject('Call');
    }

    public function getCalls(Callee $callee) {
        return $this->run('SELECT *
            FROM calls
            WHERE callee = :callee',
            ['callee' => $callee->id]);
    }

    /* CAMPAIGNS */

    /**
     * @param $name
     * @return Campaign|bool
     */
    public function getCampaign($name) {
        return $this->run('SELECT *
            FROM campaigns
            WHERE name = :name',
            ['name' => $name]
        )->fetchObject('Campaign');
    }
}