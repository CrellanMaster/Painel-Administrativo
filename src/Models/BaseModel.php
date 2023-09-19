<?php

namespace Crellan\App\Models;

use Crellan\App\Traits\Connection;

abstract class BaseModel
{
    use Connection;

    private $conn;
    protected $table = '';
    protected $query = '';

    /**
     * Essa função tem por finalidade realizar começar a montar uma string de consulta SQL (SELECT).
     * Você pode definir o nome de uma coluna ou até mesmo um array de colunas ex: array(nome, email)
     * Ou você pode escolher por não definir nenhuma coluna e a consulta trará todos as colunas (*)
     *
     * @tutorial $pessoa = new Pessoa(); $pessoa->All('nome'); ou new Pessoa(); $pessoa->All(array('nome', 'email', 'telefone'))
     * @param array|string|null $columns
     * @return $this
     */
    public function all(array|string $columns = null): BaseModel
    {
        $conn = $this->connect();
        $this->query = 'SELECT ';
        if (!is_null($columns)) {
            if (is_array($columns)) {
                foreach ($columns as $column) {
                    $this->query .= $column;
                    if (array_key_last($column)) {
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
        $this->query .= "FROM {$this->table}";
        return $this;
    }
}