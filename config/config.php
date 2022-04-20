<?php
include_once("secrets.php");

error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set('display_startup_errors', 1);
ini_set("enable_post_data_reading", 1);

define("DRIVE", "mysql"); //qula tipo de banco de dados
define("HOST", "localhost"); //url ou ip do local do banco dados
define("PORT", "3306"); //porta de acesso ao banco de dados
define("USER", "root"); //usuario do banco de dados
define("DB", "loja-costelaadao"); // nome do banco de dados

