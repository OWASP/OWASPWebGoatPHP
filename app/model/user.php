<?php
/**
 * 
 * 
 * @Entity @Table(name="jf_users")
 * @entity(repositoryClass="UserRepository")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="discriminator", type="string")
 * @DiscriminatorMap({"User" = "User", "Xuser" = "Xuser", "AppUser"="AppUser"})
 * */
class User extends JModel
{
    /**
     * @GeneratedValue @Id @Column(type="integer")
     * @var string
     */
    protected $ID;
	public function ID()
	{
		return $this->ID;
	}
    /**
     * @Column(type="string",unique=true)
     * @var string
     */
    protected $Username;
	public function Username()
	{
		return $this->Username;
	}
    /**
     * @Column(type="string")
     * @var string
     */
    protected $Password;
    /**
     * 
     * Password hash of a user
     * @return string
     */
    public function Password()
    {
    	return $this->Password;
    }

    /**
     * @Column(type="string")
     * @var string
     */
    protected $Salt;
    
    /**
     * @Column(type="float")
     * @var float
     */
    protected $Protocol;
    
    
    /**
     * @param string $Username
     * @param string $Password
     */
    function __construct($Username=null,$Password=null)
    {
    	if ($Username)
    	{
    		$this->Username=$Username;
    	}
    		
    }
    
}


use \Doctrine\ORM\EntityRepository;
class UserRepository extends EntityRepository
{
	public function getAllUsersBelow100()
	{
		return $this->_em->createQuery('SELECT u FROM User u WHERE u.id < 100')
		->getResult();
	}
}