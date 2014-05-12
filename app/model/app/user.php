<?php
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity @Table(name="user")
 * @entity(repositoryClass="AppUserRepository")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="discriminator", type="string")
 * Application Specific User
 * @author abiusx
 *
 */
class AppUser extends Xuser
{
	/**
	* @Column(type="string")
	* @var string
	*/
	protected $Firstname;
	public function Firstname()
	{
		return $this->Firstname;
	}
	/**
	* @Column(type="string")
	* @var string
	*/
	protected $Lastname;
	public function Lastname()
	{
		return $this->Lastname;
	}
	
	
	
	
	public function __construct($Username=null,$Password=null,$Firstname="",$Lastname="",$isReviewer=false,$Email="")
	{
		if ($Username)
		{
			parent::__construct($Username,$Password,$Email);
			$this->Firstname=$Firstname;
			$this->Lastname=$Lastname;
		}
	}	
}

use \Doctrine\ORM\EntityRepository;
class AppUserRepository extends EntityRepository
{
	public function getAllUsersBelow100()
	{
		$r=j::DQL("SELECT COUNT(U) AS Result FROM MyUser AS U");
		return $r[0]['Result'];
	}
}