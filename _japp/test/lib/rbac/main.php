<?php
class LibRbacMainTest extends JDbTest
{
	function setUp()
	{
		parent::setUp ();
	}
	function __construct()
	{
		$this->add ( "jf/test/lib/rbac/roles" );
		$this->add ( "jf/test/lib/rbac/permissions" );
		$this->add ( "jf/test/lib/rbac/users" );
	}
	function testAssign()
	{
		$RID = jf::$RBAC->Roles->AddPath ( "/CEO/CIO/Admin" );
		jf::$RBAC->Permissions->AddPath ( "/Users/add" );
		jf::$RBAC->Permissions->AddPath ( "/Users/edit" );
		jf::$RBAC->Permissions->AddPath ( "/Users/remove" );
		$PID = jf::$RBAC->Permissions->AddPath ( "/Users/changepass" );
		
		$this->assertTrue ( jf::$RBAC->Assign ( $RID, $PID ) );
		$this->assertTrue ( jf::$RBAC->Assign ( $RID, "/Users/edit" ) );
		$this->assertTrue ( jf::$RBAC->Assign ( $RID, "add" ) );
		$this->assertTrue ( jf::$RBAC->Assign ( "/CEO/CIO", "/Users/remove" ) );
		$this->assertTrue ( jf::$RBAC->Assign ( "CEO", "Users" ) );
		$this->assertTrue ( jf::$RBAC->Assign ( "CEO", $PID ) );
		$this->assertTrue ( jf::$RBAC->Assign ( "/CEO/CIO", $PID ) );
		$this->assertTrue ( jf::$RBAC->Assign ( "/CEO", "/Users/add" ) );
		$this->assertTrue ( jf::$RBAC->Assign ( "/CEO/CIO/Admin", "remove" ) );
	}
	function testCheck()
	{
		// adding users
		jf::$User->CreateUser ( "boss", "" );
		jf::$User->CreateUser ( "admin", "" );
		jf::$User->CreateUser ( "secretary", "" );
		
		// adding roles
		jf::$RBAC->Roles->AddPath ( "/CEO/CIO/Admin" );
		jf::$RBAC->Roles->AddPath ( "/CEO/CIO/Networking" );
		jf::$RBAC->Roles->AddPath ( "/CEO/CIO/CISO" );
		jf::$RBAC->Roles->AddPath ( "/CEO/Financial" );
		jf::$RBAC->Roles->AddPath ( "/CEO/Secretary" );
		
		// assingning roles to users
		$res = jf::$RBAC->Users->Assign ( "/CEO", jf::$User->UserID ( "boss" ) );
		$res = $res and jf::$RBAC->Users->Assign ( "/CEO/Financial", jf::$User->UserID ( "boss" ) );
		
		$res = $res and jf::$RBAC->Users->Assign ( "/CEO/CIO/Admin", jf::$User->UserID ( "admin" ) );
		$res = $res and jf::$RBAC->Users->Assign ( "/CEO/CIO/Networking", jf::$User->UserID ( "admin" ) );
		$res = $res and jf::$RBAC->Users->Assign ( "/CEO/CIO/CISO", jf::$User->UserID ( "admin" ) );
		
		$res = $res and jf::$RBAC->Users->Assign ( "/CEO/Secretary", jf::$User->UserID ( "secretary" ) );
		$this->assertTrue ( $res );
		
		// adding permissions
		jf::$RBAC->Permissions->AddPath ( "/Users/add" );
		jf::$RBAC->Permissions->AddPath ( "/Users/edit" );
		jf::$RBAC->Permissions->AddPath ( "/Users/remove" );
		jf::$RBAC->Permissions->AddPath ( "/Users/changepass" );
		jf::$RBAC->Permissions->AddPath ( "/Signature/financial" );
		jf::$RBAC->Permissions->AddPath ( "/Signature/office" );
		jf::$RBAC->Permissions->AddPath ( "/Signature/order" );
		jf::$RBAC->Permissions->AddPath ( "/Signature/network" );
		jf::$RBAC->Permissions->AddPath ( "/reports/IT/network" );
		jf::$RBAC->Permissions->AddPath ( "/reports/IT/security" );
		jf::$RBAC->Permissions->AddPath ( "/reports/financial" );
		jf::$RBAC->Permissions->AddPath ( "/reports/general" );
		
		// assigning permissions to roles
		$res = jf::$RBAC->Assign ( "CEO", "Users" );
		$res = $res and jf::$RBAC->Assign ( "CEO", "Signature" );
		$res = $res and jf::$RBAC->Assign ( "CEO", "/reports" );
		$this->assertTrue ( $res );
		
		$res = $res and jf::$RBAC->Assign ( "CIO", "/reports/IT" );
		$res = $res and jf::$RBAC->Assign ( "CIO", "/Users" );
		
		$res = $res and jf::$RBAC->Assign ( "Admin", "/Users" );
		$res = $res and jf::$RBAC->Assign ( "Admin", "/reports/IT" );
		
		$res = $res and jf::$RBAC->Assign ( "Networking", "/reports/network" );
		$res = $res and jf::$RBAC->Assign ( "Networking", "/Signature/network" );
		
		$res = $res and jf::$RBAC->Assign ( "CISO", "/reports/security" );
		$res = $res and jf::$RBAC->Assign ( "CISO", "/Users/changepass" );
		$this->assertTrue ( $res );
		
		$res = $res and jf::$RBAC->Assign ( "Financial", "/Signature/order" );
		$res = $res and jf::$RBAC->Assign ( "Financial", "/Signature/financial" );
		$res = $res and jf::$RBAC->Assign ( "Financial", "/reports/financial" );
		
		$res = $res and jf::$RBAC->Assign ( "Secretary", "/reports/financial" );
		$res = $res and jf::$RBAC->Assign ( "Secretary", "/Signature/office" );
		$this->assertTrue ( $res );
		

		// now checking
		
		$this->assertTrue ( jf::$RBAC->Users->HasRole ( "/CEO/Financial", jf::$User->UserID ( "boss" ) ) );
		$this->assertTrue ( jf::$RBAC->Check ( "/Signature/financial", "boss" ) );
		$this->assertTrue ( jf::$RBAC->Check ( "/reports/general", "boss" ) );
		$this->assertTrue ( jf::$RBAC->Check ( "/reports/IT/security", "boss" ) );
		
		$this->assertTrue ( jf::$RBAC->Check ( "/reports/IT/security", "admin" ) );
		$this->assertTrue ( jf::$RBAC->Check ( "/reports/IT/network", "admin" ) );
		$this->assertTrue ( jf::$RBAC->Check ( "/Users", "admin" ) );
		
		$this->assertTrue ( jf::$RBAC->Check ( "/Signature/office", "secretary" ) );
		$this->assertFalse ( jf::$RBAC->Check ( "/Signature/order", "secretary" ) );
		$this->assertTrue ( jf::$RBAC->Check ( "/reports/financial", "secretary" ) );
		$this->assertFalse ( jf::$RBAC->Check ( "/reports/general", "secretary" ) );
		
		try
		{
			$this->assertFalse ( jf::$RBAC->Check ( "/reports/general", "secretary2" ) );
			$this->fail ( "No error on unknown user" );
		} catch ( \jf\RBACUserNotFoundException $e )
		{
		}
		
		try
		{
			$this->assertFalse ( jf::$RBAC->Check ( "/reports/generalz", "secretary" ) );
			$this->fail ( "No error on unknown permission" );
		} catch ( \jf\RBACPermissionNotFoundException $e )
		{
		}
	}
	function testEnforce()
	{
		try
		{
			$this->assertFalse ( jf::$RBAC->Check ( "root", "secretary2" ) );
			$this->fail ( "No error on unknown user" );
		} catch ( \jf\RBACUserNotFoundException $e )
		{
		}
		
		try
		{
			$this->assertFalse ( jf::$RBAC->Check ( "/reports/generalz", "root" ) );
			$this->fail ( "No error on unknown permission" );
		} catch ( \jf\RBACPermissionNotFoundException $e )
		{
		}
	}
}