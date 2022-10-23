<?php 
declare( strict_types = 1 );

class ResponseController{

  private $_status_code;
  private $_success;
  private $_messages = array();
  private $_data = array();
  private $_detailed_response = array();
  private static $_fast_response = array();


  /*=========================  fast response  =========================*/
  public static function FastReponse( bool $success, int $http_status_code, string $message , ?string $allowed_methods = null ):void {

    header( 'Content-type: application/json;charset=utf-8' );
    if( 
      ( $success !== false && $success !== true )  
      || !is_numeric( $http_status_code )
    ){
      http_response_code( 500 );
      self::$_fast_response['status_code'] = 500;
      self::$_fast_response['success'] = false;
      self::$_fast_response['message'] = "Response creation error" ;
    }
    else{
      http_response_code( $http_status_code );
      self::$_fast_response['status_code'] = $http_status_code;
      self::$_fast_response['success'] = $success;
      self::$_fast_response['message'] = $message;
    }

    if( $allowed_methods !== null ) header( "Allow: " . $allowed_methods );


    echo json_encode( self::$_fast_response );
    exit;
  }
  #-----------------------------------------------------------
  
  
  /*=============================================
  detailed response
  =============================================*/
  public function setSuccess( bool $success ):void {
    $this->_success = $success;
  }
  
  public function setHttpStatusCode( int $_status_code ):void {
    $this->_status_code = $_status_code;
  }
 
  public function addMessage( string $message ):void {
    $this->_messages[] = $message;
  }

  public function setData( array $data ):void {
    $this->_data = $data;
  }

  public function send(){
    
    header( 'Content-type: application/json;charset=utf-8' );
    
    if( 
      ( $this->_success !== false && $this->_success !== true )  
      || !is_numeric( $this->_status_code )
    ){
      http_response_code(500);
      $this->_detailed_response['status_code'] = 500;
      $this->_detailed_response['success'] = false;
      $this->addMessage( "Response creation error" );
      $this->_detailed_response['messages'] = $this->_messages;
    } 
    else{
      http_response_code( $this->_status_code );
      $this->_detailed_response['status_code'] = $this->_status_code;
      $this->_detailed_response['success'] = $this->_success;
      $this->_detailed_response['messages'] = $this->_messages;
      $this->_detailed_response['data'] = $this->_data;
    }
    header( 'Content-type: application/json;charset=utf-8' );
    echo json_encode( $this->_detailed_response );
    exit;
  }
  /* ===================  detailed response  =================*/

}
?>