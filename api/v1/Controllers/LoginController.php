<?php 

require_once( dirname(__DIR__) . '/Models/UserModel.php');

class LoginController{

  public function __construct(){


    /*=============================================
    model
    =============================================*/
    $username = isset( $_POST['username'] ) ? $_POST['username'] : '' ;  
    $password = isset( $_POST['password'] ) ? $_POST['password'] : '' ;
    try{
      $check = new UserModel();
      $check->check_user_login_data( $username, $password );
    }
    catch( UserException $e ){
      ResponseController::FastReponse( false, 500, $e->getMessage() );
    }
    #-----------------------------------------------------------
    if( $username != 'johndoe' || sha1( $password . 'g04pp') != $_ENV['DEMO_PSWD'] )
      ResponseController::FastReponse( false, 401, "Credenziali fornite non valide." );
    #-----------------------------------------------------------
    $JWT_payload = [
      'id' => 1,
      'username' => $username
    ];  
    $jwt = new JWTCodec();
    /* ===================  model  =================*/


    /*=========================  curl  =========================*/
    $ch = curl_init();
    $ch_headers = [ 
      "Content-Type: application/x-www-form-urlencoded",
      "Accept: */*",
      "Connection: keep-alive",
      "Keep-Alive: 300",
      "User-Agent: goapp_digivent_testing",
    ];
    $ch_payload = [
      "username" => $username,
      "password" => $password,
      "clientKey" => $_ENV['DGV_CLIENTKEY'],
      "appKey" => $_ENV['DGV_APPKEY']
    ];

    curl_setopt_array( $ch, [
      CURLOPT_URL => "https://api.digivents.net/login",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER => $ch_headers,
      CURLOPT_FOLLOWLOCATION => 1,
      CURLOPT_SSL_VERIFYHOST => false,
      CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => http_build_query( $ch_payload ) 
    ]);

    $response = curl_exec($ch);

    curl_close( $ch );
    /*=========================  curl /  =========================*/
    

    /*=========================  response  =========================*/
    if( $response != 'logged' ){
      ResponseController::FastReponse( false, 401, $response );
    }
    else{
      $arr_response = array();
      $arr_response['goapp_jwt_token'] = $jwt->encode( $JWT_payload );
      #-----------------------------------------------------------
      $response = new ResponseController;
      $response->setSuccess( true );
      $response->setHttpStatusCode( 200 );
      $response->setData( $arr_response );
      $response->addMessage( 'logged' );
      $response->send();
    }
    /*=========================  response /  =========================*/
    
  }
}
?>