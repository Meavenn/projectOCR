<?php

namespace App\Model\Repository;
use App\Config\DBConnectPrivate;
use App\Http\Request;
use Exception;
use PDOException;

Abstract class Db extends DBConnectPrivate {


    protected $request;

    public function request(){
        return new Request();
    }

    public function exist($table, $id){

        if ($this->callDbCount([$table,['id'=>$id]])[0]) {
            return true;
        }
            return false;
    }
    /************ CRUD **************/

    /* CREATE */

    /**
     * @param $table  : SQL table's name,  for example 'users'
     * @param $values : associative array,  for example ['name' => 'Dupont']
     * @return array
     * @throws Exception
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
                $value = addslashes($value);
                $request .= " '$value',";

            }
            $request = rtrim($request, ",");
            $request .= ")";
            $req = $this->prepare($request);


            $req->execute($values);

            return $req->fetchAll();
        } catch (PDOException $e) {
            header('Location: /');
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
     * @param array[] $condition : associative array ($key => $value) for example : ['id' = 1, 'name' = 'Dupont']
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
            //var_dump($request);
            $req = $this->prepare($request);
            @$req->execute([$table, $condition, $order, $datas]);
            //echo"<pre>";var_dump($req->fetchAll());echo"</pre>";
            return $req->fetchAll();
        } catch (PDOException $e) {
            header('Location: /');
        }
    }

    /**
     * @param $params [$table :string, $condition :array[$key=>"'"$value"'"], $orderBy :string, $datas :string]
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
                $value = addslashes($value);
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
        } catch (PDOException $e) {
            header('Location: /');
        }
    }

    /**
     * @param $params [$table :string, $values :array[$column => $value], $condition :array[$key => $value]]
     * @return array
     */
    public function callDbUpdate($params): array {
            return call_user_func_array([$this, 'dbUpdate'], $params);
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
        } catch (PDOException $e) {
            header('Location: /');
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
        } catch (PDOException $e) {
            header('Location: /');
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