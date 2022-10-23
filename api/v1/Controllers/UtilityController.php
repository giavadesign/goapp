<?php 

class UtilityController{

  public static function checkJWT(){

    #-----------------------------------------------------------
    $headers = apache_request_headers();    
    #-----------------------------------------------------------
    $goapp_jwt_token = '';
    if (!empty($headers)) {
      if( isset( $headers['Authorization'] ) ){
        if (preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
          $goapp_jwt_token = $matches[1];
        }
      }
    }
    #-----------------------------------------------------------
    
    try{
      $jwt = new JWTCodec();
      $jwt->decode( $goapp_jwt_token );
    }
    catch( Exception $e ){
      ResponseController::FastReponse( false, 401, "Unauthorized" );
    }

  }

}

?>