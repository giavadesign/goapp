<?php 
#-----------------------------------------------------------
declare( strict_types = 1 );
ini_set("display_errors", 'on');
#-----------------------------------------------------------

class UserException extends Exception { }

class UserModel{

  private $_Id;
  private $_Name;
  private $_Surname;
  private $_Email;
  private $_Company;
  private $_Role;
  private $_Telefono;
  private $_User;
  private $_Password;

  public function check_user_data( int $Id , string $Name, string $Surname, string $Email, string $Company, string $Role, string $Telefono ){

    $this->setId( $Id );
    
    $this->setName( $Name );
    $this->setSurname( $Surname );
    $this->setEmail( $Email );
    $this->setCompany( $Company );
    $this->setRole( $Role );
    
  }
  
  public function check_user_login_data( string $User, string $Password ):void{
    $this->setUser( $User );
    $this->setPassword( $Password);
  }

  ###############################################
  // setter
  #----------------------------------------------
  /*=========================  setId  =========================*/
  public function setId( int $Id ):void {

    $methods_need_id = array( 'GET', 'PUT', 'PATCH' , 'DELETE' );

    if( in_array( $_SERVER['REQUEST_METHOD'], $methods_need_id ) ){
      if( ( $id !== null ) && ( !is_numeric( $id ) || $id <= 0 || $id > 9223372036854775807 || $this->_ID !== null ) ){
        throw new UserException( "Product ID Error" );
      } 
    }
    
    $this->_Id = $Id;

  }
  #-----------------------------------------------------------

  /*=========================  setUser  =========================*/
  public function setUser( string $User ){
    if( empty( $User ) || strlen( $User) > 255 ){
      throw new UserException( "Username format not valid. It could not be empty and it has been at most 255 chars");
    }

    $this->_User = $User;
  }
  #-----------------------------------------------------------
  
  /*=========================  setPassowrd  =========================*/
  public function setPassword( string $Password ){
    if( empty( $Password ) || strlen( $Password) > 255 ){
      throw new UserException( "Password format not valid. It could not be empty and it has been at most 255 chars");
    }

    $this->_Password = $Password;
  }
  #-----------------------------------------------------------
  
  /*=========================  setNome  =========================*/
  public function setName( string $Name ){
    if( empty( $Name ) || strlen( $Name) > 255 ){
      throw new UserException( "Name format not valid. It could not be empty and it has been at most 255 chars");
    }

    $this->_Name = $Name;
  }
  #-----------------------------------------------------------
  
  /*=========================  setSurname  =========================*/
  public function setSurname( string $Surname ){
    if( empty( $Surname ) || strlen( $Surname) > 255 ){
      throw new UserException( "Surname format not valid. It could not be empty and it has been at most 255 chars");
    }

    $this->_Surname = $Surname;
  }
  #-----------------------------------------------------------

  /*=========================  setEmail  =========================*/
  public function setEmail( string $Email ){
    if( empty( $Email ) || strlen( $Email) > 255 ){
      throw new UserException( "Email format not valid. It could not be empty and it has been at most 255 chars");
    }

    $this->_Email = $Email;
  }
  #-----------------------------------------------------------
  
  /*=========================  setRole  =========================*/
  public function setRole( string $Role ){
    if( empty( $Role ) || strlen( $Role) > 255 ){
      throw new UserException( "Role format not valid. It could not be empty and it has been at most 255 chars");
    }

    $this->_Role = $Role;
  }
  #-----------------------------------------------------------
  
  /*=========================  setCompany  =========================*/
  public function setCompany( string $Company ){
    if( empty( $Company ) || strlen( $Company) > 255 ){
      throw new UserException( "Company format not valid. It could not be empty and it has been at most 255 chars");
    }

    $this->_Company = $Company;
  }
  #-----------------------------------------------------------
  
  /*=========================  setCompany  =========================*/
  public function setTelefono( string $Company ){

    $this->_Telefono = $Telefono;
  }
  #-----------------------------------------------------------
  
  
  #----------------------------------------------
  # setter  /
  ###############################################


  ###############################################
  // getter
  #----------------------------------------------
  public function getId():int { return $this->_ID; }
  public function getName():string { return $this->_Name; }
  public function getSurname():string { return $this->_Surname; }
  public function getEmail():string { return $this->_Email; }
  public function getCompany():string { return $this->_Company; }
  public function getRole():string { return $this->_Role; }
  public function getTelefono():string { return $this->_Telefono; }
  #----------------------------------------------
  # getter  /
  ###############################################


  /*=============================================
  return
  =============================================*/
  public function return_data_array():array {
    
    $item_data = array();
    
    $item_data['Id'] = $this->getId();
    $item_data['Name'] = $this->getName();
    $item_data['Surname'] = $this->getSurname();
    $item_data['Email'] = $this->getEmail();
    $item_data['Company'] = $this->getCompany();
    $item_data['Role'] = $this->getRole();
    $item_data['Telefono'] = $this->getTelefono();

    return $item_data;
  }
  /* ===================  return  =================*/
}

?>