<?php

namespace App\Db;

use \PDO;
use \PDOException;

class Database {
    
    /**
     * Host de conexão com o banco de dados
     * @var string 
     */
    const HOST = 'localhost';
    
    /**
     * nome do banco de dados
     * @var string
     */
    const NAME= 'hello_kitty';

    /**
     * Usuario do banco
     * @var string
     */
    const USER = 'root';

    /**
     * Senha de acesso ao banco de dados
     * @var string
     */
    const PASS ='';

    /**
     * Nome da tabela a ser manipulada
     * @var string
     */
    private $table;

    /**
     * Instancia de coneção com o banco de dados
     * @var PDO
     */
    private $connection;

    /**
     * Define a tabela e instancia e conexão
     * @param string
     */
    public function __construct($table = null) {
        $this->table = $table;
        $this->setConnection();
    } 
    
    /**
     * Método responsavel por criar uma conexão com o banco de dados
     * 
     */
    private function setConnection(){
        try{
            $this->connection = new PDO('mysql:host='.self::HOST.';dbname='.self::NAME,self::USER,self::PASS);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION );
        }catch(PDOException $e){
            die('ERROR: '.$e->getMessage());

        }
    }
}