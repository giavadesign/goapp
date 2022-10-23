<?php 
#-----------------------------------------------------------
declare( strict_types = 1 );
#-----------------------------------------------------------
use Dotenv\Dotenv;
#-----------------------------------------------------------
require_once( dirname( __DIR__ ) . '/vendor/autoload.php' );
#-----------------------------------------------------------
$dotenv = Dotenv::createImmutable( dirname(__DIR__) );
$dotenv->load();
#-----------------------------------------------------------
UtilityController::checkJWT();
#-----------------------------------------------------------


$u_id = array_key_exists( 'u_id', $_GET ) ? (int)$_GET['u_id'] : null;

$users = new UsersController();
$users->processRequest( $_SERVER['REQUEST_METHOD'] , $u_id );



?>