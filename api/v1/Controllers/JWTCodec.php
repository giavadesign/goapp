<?php 
class JWTCodec{
  
  public function encode( array $payload ): string{

    /*=========================  header  =========================*/
    $header = json_encode([
      "typ" => "JWT",
      "alg" => "HS256",
    ]);
    $header = $this->base64urlEncode( $header );
    #-----------------------------------------------------------
    
    /*=========================  payload  =========================*/
    $payload = json_encode( $payload );
    $payload = $this->base64urlEncode( $payload );
    #-----------------------------------------------------------

    /*=========================  signature  =========================*/
    // serve una chiave di signature di 256 bits come la dimensone dell'hash
    // possimao generarlo da https://www.allkeysgenerator.com/Random/Security-Encryption-Key-Generator.aspx
    // usando e flaggando lhwx character per non avere problemi di codifica
    #-----------------------------------------------------------
    $signature = hash_hmac( 
      "sha256", 
      $header . '.' . $payload,
      "7134743777217A25432646294A404E635266556A586E3272357538782F413F44",
      true
    );
    $signature = $this->base64urlEncode( $signature ); 
    #-----------------------------------------------------------

    return $header . '.' . $payload . '.' . $signature ;

  }

  public function decode( string $token ): array{

    if( preg_match( "/^(?<header>.+)\.(?<payload>.+)\.(?<signature>.+)$/" , $token , $matches ) !== 1 ){
      throw new Exception( "Invalid Token format" );
    }
    $signature = hash_hmac( 
      "sha256", 
      $matches["header"] . '.' . $matches["payload"],
      "7134743777217A25432646294A404E635266556A586E3272357538782F413F44",
      true
    );
    $signature_from_token = $this->base64urlDecode( $matches["signature"] ); 

    if( !hash_equals( $signature , $signature_from_token ) ){
      throw new Exception( "Signature doesn't match" );
    }

    $payload = json_decode( $this->base64urlDecode( $matches["payload"] ) , true );

    return $payload;

  }

  public function base64urlEncode( string $text ) :string {
    return str_replace(
      ["+" , "/" , "="],
      ["-", "_", ""],
      base64_encode( $text )
    );
  }
  public function base64urlDecode( string $text ) :string {
    return base64_decode( str_replace(
      ["-" , "_"],
      ["+", "/"],
      $text) 
    );
  }

}
?>