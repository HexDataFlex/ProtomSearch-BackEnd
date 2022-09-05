<?php 
/**
  * File for database class.
  * 
  * This file contains a class that connects to the database and makes queries.
  * 
  * PHP version 8.1.9
  *
  * Copyright (c) 2022 Protopixel
  *
  * LICENSE: This source file is subject to version 3 of the GNU GPL license
  * that is available through the world-wide-web at the following URI:
  * http://www.gnu.org/licenses/gpl-3.0.html.
  * 
  * @category   Core
  * @author     Protopixel <protopixel06@gmail.com>
  * @license    http://www.gnu.org/licenses/gpl-3.0.html  GNU GPL v3
  * @version    1.0
  * @since      File available since v1.0.0-pre1
  */

  include_once getcwd() . "../config.php";
  
/**
 * Database class
 *
 * A class that connects to the database and makes queries.
 * 
 * @license    http://www.gnu.org/licenses/gpl-3.0.html  GNU GPL v3
 * @version    1.0
 * @since      Class available since v1.0.0-pre1
 */ 
class DB {
    private static function connect() {
        $pdo = new PDO(DB_DSN . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    public static function query($query, $params = array()) {
        $stmt = self::connect()->prepare($query);
        $stmt->execute($params);

        if(explode(' ', $query)[0] == 'SELECT') {
            $data = $stmt->fetchAll();
            return $data;
        }
    }

    public static function count($query, $params = array()) {
        if(explode(' ', $query)[0] == 'SELECT') {
            $stmt = self::connect()->prepare($query);
            $stmt->execute($params);
            return count($stmt->fetchAll());
        }
    }
}