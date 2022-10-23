<?php 

require_once( 'api/v1/vendor/autoload.php');

try{

  $test = new JWTCodec;
  $test->decode( 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MSwidXNlcm5hbWUiOiJqb2huZG9lIn0.MzgP7isIlxWTkcwF46nvfxCXPXSIlLA_HC9x-cbgtHg' );
}
catch( Exception $e ){
  echo '<pre>';
  print_r($e);
  echo '</pre>';
}



?>