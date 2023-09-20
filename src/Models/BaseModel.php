<?php

namespace Crellan\App\Models;

use Crellan\App\Traits\Connection;
use PDO;
use PDOStatement;
use stdClass;

abstract class BaseModel
{
    use Connection;

    /** @var PDO $conn */
    private $conn;
    protected $table = '';
    protected $query = '';
    /** @var PDOStatement $preparedQuery */
    protected $preparedQuery;
    protected $parameters;

    /**
     * Essa função tem por finalidade começar a montar uma string de consulta SQL (SELECT).
     * Você pode definir o nome de uma coluna ou até mesmo um array de colunas ex: array(nome, email)
     * Ou você pode escolher por não definir nenhuma coluna e a consulta trará todos as colunas (*).
     *
     * Ex: $pessoa = new Pessoa(); $pessoa->all('nome')->get(); ou new Pessoa(); $pessoa->all(array('nome', 'email', 'telefone'))->get();
     * @param array|string|null $columns
     * @return $this
     */
    public function all(array|string $columns = null): BaseModel
    {
        $this->conn = $this->connect();
        $this->query = 'SELECT ';
        if (!is_null($columns)) {
            if (is_array($columns)) {
                foreach ($columns as $key => $column) {
                    $this->query .= $column;
                    if ($key == array_key_last($columns)) {
                        $this->query .= ' ';
                    } else {
                        $this->query .= ', ';
                    }
                }
            } else if (is_string($columns)) {
                $this->query .= $columns . ' ';
            }
        } else {
            $this->query .= '* ';
        }
        $this->query .= "FROM {$this->table} ";
        return $this;
    }

    /**
     * Essa função tem por finalidade adicionar a claúsula WHERE na sua consulta SQL.
     *
     * Ex: $pessoa = new Pessoa(); $pessoa->all('nome')->where('id', '=', 1)->get();
     * @param $column
     * @param $operator
     * @param $parameter
     * @return $this
     */
    public function where($column, $operator, $parameter): BaseModel
    {
        $this->query .= "WHERE {$column} {$operator} ?";
        $this->parameters[] = $parameter;
        return $this;
    }

    /**
     * Essa função tem por finalidade adicionar o operador OR e a claúsula WHERE na sua consulta SQL.
     *
     * Ex: $pessoa = new Pessoa(); $pessoa->all('nome')->where('id', '=', 1)orWhere('id', '>', 0)->get();
     * @param $column
     * @param $operator
     * @param $parameter
     * @return $this
     */
    public function orWhere($column, $operator, $parameter): BaseModel
    {
        if (strpos($this->query, 'WHERE')) {
            $this->query .= " OR {$column} {$operator} ?";
            $this->parameters[] = $parameter;
        } else {
            $this->query .= "WHERE {$column} {$operator} ?";
            $this->parameters[] = $parameter;
        }
        return $this;
    }

    /**
     * Função chamada dentro da função GET para dar preparar os parâmetros no PDOStatement
     * @return void
     */
    public function prepareQuery(): void
    {
        $this->preparedQuery = $this->conn->prepare($this->query);

        for ($i = 0; $i < count($this->parameters); $i++) {
            $this->preparedQuery->bindParam($i + 1, $this->parameters[$i]);
        }
    }

    /**
     * Realiza a consulta no banco de dados conforme a query definida pelo Eloquent e retorna os dados
     * com o tipo stdClass. Utiliza fetchAll
     *
     * @return array|false
     */
    public function get(): array|false
    {
        if (is_null($this->parameters)) {
            $this->preparedQuery = $this->conn->prepare($this->query);
        } else {
            $this->prepareQuery();
        }
        $this->preparedQuery->execute();
        return $this->preparedQuery->fetchAll(PDO::FETCH_CLASS);
    }
}