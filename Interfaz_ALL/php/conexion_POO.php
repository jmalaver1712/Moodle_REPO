<?php

/* VARIABLES DE CONFIGURACION */
$array_config = json_decode(json_encode($CFG),true);
// print_r($array_config); 

define('DB_HOST',$array_config['dbhost']);
define('DB_USER',$array_config['dbuser']);
define('DB_PASS',$array_config['dbpass']);
define('DB_NAME',$array_config['dbname']);
define('DB_PRE',$array_config['prefix']);
define('URL_ROOT',$array_config['wwwroot']);
define('DB_CHARSET','utf-8');
define('URL_CAMPUS','http://campusvirtualurepublicana.edu.co');

class Conexion{
    protected $_db;
    public function __construct(){
        $this->_db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ( $this->_db->connect_errno ){
            echo "Fallo al conectar a MySQL: ". $this->_db->connect_error;
            return;    
        }
        $this->_db->set_charset(DB_CHARSET);
    }
}

?>