<?php 
#-----------------------------------------------------------
declare( strict_types = 1 );
require_once( dirname( __DIR__ ) .'/Models/UserModel.php' );
#-----------------------------------------------------------

class UsersController{


  public function processRequest( string $method, ?int $id = null ):void {
    
    if( $id === null ){
      if( $method == 'GET' ){ $this->Index(); }
      elseif( $method == 'POST' ){ $this->Create(); }
      else{ ResponseController::FastReponse( false, 405, "Request Method Not Allowed", "GET, POST" ); }
    }
    else{
      if( $method == 'GET' ){ $this->Read( $id ); }
      elseif( $method == 'POST' ){ if( isset( $_POST['_method'] ) && $_POST['_method'] == 'PATCH' ){ $this->Update( $id ); } }
      elseif( $method == 'PATCH' ){ $this->Update( $id ); }
      elseif( $method == 'DELETE' ){ $this->Delete( $id ); }
      else{ ResponseController::FastReponse( false, 405, "Request Method Not Allowed", "GET, PATCH, DELETE" ); }
    }
  }


  ###############################################
  // index
  #----------------------------------------------
  private function index():void {

    /*=========================  curl  =========================*/
    $ch = curl_init();
    $ch_headers = [ 
      "Content-Type: application/json",
      "Content-Length: 0",
      "Accept: */*",
      "Connection: keep-alive",
      "Keep-Alive: 300",
      "User-Agent: goapp_digivent_testing",
      "Cookie: ASP.NET_SessionId=bekwzkxxew0ioetx2rgrgc5q; .AUTHAPIDIGIVENTS=5270435D272125603212A8E75FAC71489F37B904B2E5BEC074868983711A30A38A824531047EED290BA4D5B72730F91FF1320CF2512A444D499EDC561FF3D2431B2399DAB9411ACDDD2E549DF67CB1DDC23E2D6159D3571D6E1DA764F7903CA5538CC55C489E7DA4FCCE8571F1658B507CEA322EE6A40113A7CAD426ADBE71F0",
    ];

    curl_setopt_array( $ch, [
      CURLOPT_URL => "https://api.digivents.net/EvtUser/getAll",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER => $ch_headers,
      CURLOPT_POST => true,
      CURLOPT_FOLLOWLOCATION => 1,
      CURLOPT_SSL_VERIFYHOST => false,
    ]);
 
    $response = curl_exec($ch);
    curl_close( $ch );
    #-----------------------------------------------------------
    $response_payload = json_decode($response, TRUE);
    /*=========================  curl /  =========================*/

    $arr_response = array();
    
    $response = new ResponseController;
    $response->setSuccess( true );
    $response->setHttpStatusCode( 200 );
    $response->setData( $response_payload );
    $response->send();
    
  }
  #----------------------------------------------
  # index  /
  ###############################################


  ###############################################
  // Create
  #----------------------------------------------
  public function Create(){
    #-----------------------------------------------------------
    if( !isset( $_SERVER['CONTENT_TYPE'] ) || ( isset( $_SERVER['CONTENT_TYPE'] ) && $_SERVER['CONTENT_TYPE'] !== 'application/json' ) )
      ResponseController::FastReponse( false, 400,  "Content type is not set to json" );
    #-----------------------------------------------------------
    $raw_post_data = file_get_contents('php://input');
    if( !$json_data = json_decode( $raw_post_data ) ) ResponseController::FastReponse( false, 400,  "Request body is not valid JSON" );
    #-----------------------------------------------------------
    if( empty( $json_data->username ) ) ResponseController::FastReponse( false, 400, "Username id is mandatory");
    if( empty( $json_data->password ) ) ResponseController::FastReponse( false, 400, "Pssword Code is mandatory");
    if( empty( $json_data->name ) ) ResponseController::FastReponse( false, 400, "Name name is mandatory");
    if( empty( $json_data->surname ) ) ResponseController::FastReponse( false, 400, "Surname is mandatory");
    #-----------------------------------------------------------
    try{
      $user = new UserModel();
      $user->check_user_data( 0, $json_data->name, $json_data->surname, $json_data->email, $json_data->company, $json_data->role, '' );
      $user->check_user_login_data( $json_data->username, $json_data->password );
    }
    catch( UserException $e ){ ResponseController::FastReponse( false, 500, $e->getMessage() ); }


    /*=========================  curl  =========================*/
    $ch = curl_init();
    
    $ch_payload = array(
      "User" => $json_data->username,
      "Password" => $json_data->password,
      "Name" => $json_data->name,
      "Surname" => $json_data->surname,
      "Email" => $json_data->email,
      "Company" => $json_data->company,
      "Role" => $json_data->role,
    );

    $ch_headers = [ 
      "Content-Type: application/json",
      "Accept: */*",
      "Connection: keep-alive",
      "Keep-Alive: 300",
      "User-Agent: goapp_digivent_testing",
      "Cookie: ASP.NET_SessionId=bekwzkxxew0ioetx2rgrgc5q; .AUTHAPIDIGIVENTS=5270435D272125603212A8E75FAC71489F37B904B2E5BEC074868983711A30A38A824531047EED290BA4D5B72730F91FF1320CF2512A444D499EDC561FF3D2431B2399DAB9411ACDDD2E549DF67CB1DDC23E2D6159D3571D6E1DA764F7903CA5538CC55C489E7DA4FCCE8571F1658B507CEA322EE6A40113A7CAD426ADBE71F0",
    ];   
    
    curl_setopt_array( $ch, [
      CURLOPT_URL => "https://api.digivents.net/EvtUser/insert",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER => $ch_headers,
      CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => json_encode( $ch_payload ),
      CURLOPT_FOLLOWLOCATION => 1,
      CURLOPT_SSL_VERIFYHOST => false,
    ]);


    $response = curl_exec($ch);
    $response_payload = json_decode($response, TRUE);

    if( $response_payload['status'] == 'ko' ){ ResponseController::FastReponse( false, 400, $response_payload['error'] ); }
    else{
      $arr_response = array();
      
      $response = new ResponseController;
      $response->setSuccess( true );
      $response->setHttpStatusCode( 200 );
      $response->setData( $response_payload );
      $response->addMessage( "Record inserito con successo" );
      $response->send();
    }

    curl_close( $ch );
    /*=========================  curl /  =========================*/
  }
  #----------------------------------------------
  # create  /
  ###############################################

  ###############################################
  // Read
  #----------------------------------------------
  public function Read( $id ){
    ResponseController::FastReponse( false, 404, 'Work in progress' );
  }
  #----------------------------------------------
  # Read  /
  ###############################################

  ###############################################
  // Update
  #----------------------------------------------
  public function Update( $id ){
    ResponseController::FastReponse( false, 404, 'Work in progress' );
  }
  #----------------------------------------------
  # Update  /
  ###############################################

  ###############################################
  // Delete
  #----------------------------------------------
  public function Delete( $id ){
    /*=========================  curl  =========================*/
    $ch = curl_init();

    $ch_headers = [ 
      "Content-Type: application/x-www-form-urlencoded",
      "Content-Length: 10",
      "Accept: */*",
      "Connection: keep-alive",
      "Keep-Alive: 300",
      "User-Agent: goapp_digivent_testing",
      "Cookie: ASP.NET_SessionId=bekwzkxxew0ioetx2rgrgc5q; .AUTHAPIDIGIVENTS=5270435D272125603212A8E75FAC71489F37B904B2E5BEC074868983711A30A38A824531047EED290BA4D5B72730F91FF1320CF2512A444D499EDC561FF3D2431B2399DAB9411ACDDD2E549DF67CB1DDC23E2D6159D3571D6E1DA764F7903CA5538CC55C489E7DA4FCCE8571F1658B507CEA322EE6A40113A7CAD426ADBE71F0",
    ];
    $ch_payload = array(
      'id' => $id
    );

    curl_setopt_array( $ch, [
      CURLOPT_URL => "https://api.digivents.net/EvtUser/delete",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER => $ch_headers,
      CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => http_build_query( $ch_payload ),
      CURLOPT_FOLLOWLOCATION => 1,
      CURLOPT_SSL_VERIFYHOST => false,
    ]);
    $response = curl_exec( $ch );

    curl_close( $ch );
    #-----------------------------------------------------------
    $response_payload = json_decode($response, TRUE);
    /*=========================  curl /  =========================*/

    if( $response_payload['status'] == 'ko' ){
      ResponseController::FastReponse( false, 400, $response_payload['error'] );
    }
    else{
      $arr_response = array();
      
      $response = new ResponseController;
      $response->setSuccess( true );
      $response->setHttpStatusCode( 200 );
      $response->setData( $response_payload );
      $response->addMessage( "Record eliminato con successo" );
      $response->send();
    }
    /*=========================  curl /  =========================*/
  }
  #----------------------------------------------
  # Delete  /
  ###############################################
}

?>