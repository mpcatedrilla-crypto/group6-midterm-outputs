<?php

class Database
{
    private string $host = '127.0.0.1';
    private string $port = '5432';
    private string $dbName = 'event_management_db';
    private string $username = 'postgres';
    private string $password = 'postgres';

    public function getConnection(): PDO
    {
        $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbName}";
        $pdo = new PDO($dsn, $this->username, $this->password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    }
}
