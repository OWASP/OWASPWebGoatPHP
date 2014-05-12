<?php

/**
* @Entity @Table(name="jf_xuser")
* @InheritanceType("JOINED")
*/
class Xuser extends User
{
	/**
	 * @Column(type="string")
	 * @var string
	 */
	public $Email;	
	/**
	 * @Column(type="integer")
	 * @var integer
	 */
	public $PasswordChangeTimestamp;	
	/**
	 * @Column(type="string")
	 * @var string
	 */
	public $TemporaryResetPassword;
	/**
	 * @Column(type="integer")
	 * @var timestamp
	 */
	public $TemporaryResetPasswordTimeout;
	/**
	 * @Column(type="integer")
	 * @var timestamp
	 */
	public $LastLoginTimestamp;
	/**
	 * @Column(type="integer")
	 * @var integer
	 */
	public $FailedLoginAttempts;
	/**
	 * @Column(type="integer")
	 * @var timestamp
	 */
	public $LockTimeout;
	/**
	 * @Column(type="boolean")
	 * @var boolean
	 */
	public $Activated;
	/**
	 * @Column(type="integer")
	 * @var timestamp
	 */
	public $CreateTimestamp;
	
	
	public function __construct($Username=null,$Password=null,$Email="")
	{
		if ($Username)
		{
			parent::__construct($Username,$Password);
			$this->Email=$Email;
			$this->PasswordChangeTimestamp=0;
			$this->TemporaryResetPassword=0;
			$this->TemporaryResetPasswordTimeout=0;
			$this->LastLoginTimestamp=0;
			$this->FailedLoginAttempts=0;
			$this->LockTimeout=0;
			$this->Activated=true;
			$this->CreateTimestamp=time();
		}
	}
}
