<?php

namespace Src\Database;

class Database
{
    /**
     * @var array
     */
    private array $config;

    /**
     * @var \PDO
     */
    private \PDO $pdoConnection;

    public function __construct()
    {
        $this->loadConfig();
        $this->initConnection();
    }

    /**
     * @return void
     */
    private function initConnection()
    {
        try {
            $this->pdoConnection = new \PDO("mysql:host={$this->config['database_hostname']};port={$this->config['database_port']};dbname={$this->config['database_name']};", $this->config['database_username'], $this->config['database_password']);
        } catch (\PDOException $exc) {
            var_dump($exc);
        }
    }

    /**
     * @param string $sqlQuery
     * @param array $params
     * @return array|bool
     */
    public function preparedQuery(string $sqlQuery, array $params = []): array|bool
    {
        $stmt = $this->pdoConnection->prepare($sqlQuery);
        $executed = $stmt->execute($params);

        if (str_contains($sqlQuery, 'INSERT'))
        {
            return $executed;
        }

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @return void
     */
    private function loadConfig(): void
    {
        $this->config = include BASE_DIR . '/app/Config/database.php';
    }
}
