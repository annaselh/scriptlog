<?php
/**
 * UserToken Class extends Dao Class
 * 
 * @package  SCRIPTLOG/LIB/DAO/UserToken
 * @category Dao Class
 * @author   M.Noermoehammad
 * @license  MIT
 * @version  1.0
 * @since    Since Release 1.0
 * 
 */
class UserToken extends Dao
{
    
  public function __construct()
  {
    parent::__construct();
  }

  public function getTokenByUserEmail($user_email, $expired, $fetchMode = null)
  {
      $sql = "SELECT t.ID, t.user_id, t.pwd_hash, t.selector_hash, 
                     t.is_expired, t.expired_date,
                     u.user_email 
             FROM user_token AS t
             INNER JOIN users AS u ON t.user_id = u.ID 
             WHERE u.user_email = :user_email AND t.is_expired = :expired";

      $this->setSQL($sql);

      if (is_null($fetchMode)) {

        $userToken = $this->findRow([':user_email' => $user_email, ':expired' => $expired]);

      } else {

        $userToken = $this->findRow([':user_email' => $user_email, ':expired' => $expired], $fetchMode);

      }
      
      if (empty($userToken)) return false;

      return $userToken;

  }

  public function updateTokenExpired($userTokenId)
  {
    $bind = ['is_expired' => 1];
    $stmt = $this->modify("user_token", $bind, "ID = {$userTokenId}");
  }

  public function createUserToken($bind)
  {

    $stmt = $this->create("user_token", [

      'user_id' => $bind['user_id'],
      'pwd_hash' => $bind['pwd_hash'],
      'selector_hash' => $bind['selector_hash'],
      'expired_date' => $bind['expired_date']

    ]);

  }

}