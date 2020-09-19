<?php

namespace App\Model\Repository;
use App\Http\Request;
use PDO;

Abstract class Db extends PDO {

    public function __construct($dsn = 'mysql:host=localhost:8889;dbname=P5_blog;charset=utf8', $username = 'root', $passwd = 'mdHjQ36M', $options = NULL)//array(parent::ATTR_ERRMODE => parent::ERRMODE_EXCEPTION))
    {
        try {
            parent::__construct($dsn, $username, $passwd, $options);
        } catch (\Exception $e) {
            die('Error : ' . $e->getMessage());
        }
    }

    protected $request;

    public function request(){
        return new Request();
    }

    public function exist($table, $id){
        if(isset($id) && $id>0){
            if ($this->callDbCount([$table,['id'=>'$id']])[0])
                return true;
        }else{
            return false;
        }
    }
    /************ CRUD **************/

    /* CREATE */

    /**
     * @param $table  : SQL table's name,  for example 'users'
     * @param $values : associative array,  for example ['name' => 'Dupont']
     * @return array
     * @throws \Exception
     */
    public function dbCreate($table, $values): array {
        try {
            $request = "INSERT INTO $table (";
            foreach ($values as $column => $value) {
                $request .= " $column,";
            }
            $request = rtrim($request, ",");
            $request .= ") VALUES (";
            foreach ($values as $column => $value) {
                $request .= " '$value',";
            }
            $request = rtrim($request, ",");
            $request .= ")";
            $req = $this->prepare($request);
            $req->execute([$table, $values]);

            return $req->fetchAll();
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }

    /**
     * @param $params [$table :string, $values :array[$column => $value]]
     * @return array
     */
    public function callDbCreate($params): array {
        return call_user_func_array([$this, 'dbCreate'], $params);
    }

    /* READ */

    /**
     * @param          $table     : SQL table's name,  for example 'users'
     * @param string[] $condition : associative array ($key => $value) for example : ['id' = 1, 'name' = 'Dupont']
     * @param string   $order     : for example 'creation_date'
     * @param string   $datas     : for example 'ID, name, DATE_FORMAT(creation_date, "%d/%m%/%Y") AS date'
     * @return array
     */
    public function dbRead(string $table, array $condition = ['id' => '"%"'], string $order = 'id', string $datas = '*'): array {
        try {
            $request = "SELECT $datas FROM $table WHERE ";
            foreach ($condition as $key => $value) {
                $value = trim($value);
                if (!empty($value)) {
                    $request .= " $key LIKE $value AND";
                }
            }
            $request = rtrim($request, "AND");
            $request .= 'ORDER BY ' . $order;

            $req = $this->prepare($request);
            @$req->execute([$table, $condition, $order, $datas]);

            return $req->fetchAll();
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }

    /**
     * @param $params [$table :string, $condition :array[$key=>"'"$value"'"], $orderBy :string]
     * @return array
     */
    public function callDbRead($params): array {
        return call_user_func_array([$this, 'dbRead'], $params);
    }

    /* UPDATE */

    /**
     * @param $table     : SQL table's name,  for example 'users'
     * @param $values    : associative array,  for example ['name' => 'Dupont']
     * @param $condition : associative array ($key => $value) for example : ['id' = 1]
     * @return array
     */
    public function dbUpdate($table, $values, $condition): array {
        try {
            $request = "UPDATE $table SET";
            foreach ($values as $column => $value) {
                $request .= " $column = '$value',";
            }
            $request = rtrim($request, ",");

            $request .= "WHERE ";
            foreach ($condition as $key => $value) {
                $value = trim($value);
                if (!empty($value)) {
                    $request .= " $key LIKE '$value' AND";
                }
            }
            $request = rtrim($request, "AND");

            $req = $this->prepare($request);
            @$req->execute([$table, $values, $condition]);

            return $req->fetchAll();
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }

    /**
     * @param $params [$table :string, $values :array[$column => $value], $condition :array[$key => $value]]
     * @return array
     */
    public function callDbUpdate($params): array {
        try {
            return call_user_func_array([$this, 'dbUpdate'], $params);
        } catch (\Exception $e) {
            die('Error : ' . $e->getMessage());
        }
    }

    /* DELETE */

    /**
     * @param $table     : SQL table's name,  for example 'users'
     * @param $condition : associative array ($key => $value) for example : ['id' = 1]
     * @return array
     */
    public function dbDelete($table, $condition): array {
        try {
            $request = "DELETE FROM $table WHERE ";
            foreach ($condition as $key => $value) {
                $value = trim($value);
                if (!empty($value)) {
                    $request .= " $key = '$value' AND";
                }
            }
            $request = rtrim($request, "AND");

            $req = $this->prepare($request);
            $req->execute([$table, $condition]);

            return $req->fetchAll();
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }

    /**
     * @param $params [$table :string, $condition :array[$key => $value]]
     * @return array
     */
    public function callDbDelete($params): array {
        return call_user_func_array([$this, 'dbDelete'], $params);
    }

    /* READ */

    /**
     * @param          $table     : SQL table's name,  for example 'users'
     * @param string[] $condition : associative array ($key => $value) for example : ['id' = 1, 'name' = 'Dupont']
     * @param string   $datas     : for example 'content'
     * @return array
     */
    public function dbCount(string $table, array $condition = ['ID' => '"%"'], string $datas = '*'): array {

        try {
            $request = "SELECT COUNT($datas) FROM $table WHERE ";
            foreach ($condition as $key => $value) {
                $value = trim($value);
                if (!empty($value)) {
                    $request .= " $key LIKE $value AND";
                }
            }
            $request = rtrim($request, "AND");

            $req = $this->prepare($request);
            @$req->execute([$table, $condition, $datas]);

            return $req->fetchAll();
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }

    /**
     * @param $params [$table :string, $condition :array[$key=>"'"$value"'"]]
     * @return array
     */
    public function callDbCount($params): array {
        return call_user_func_array([$this, 'dbCount'], $params)[0];
    }
}