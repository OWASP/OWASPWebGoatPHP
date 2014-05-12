<?php
	if (\jf\HttpRequest::File()=="sys/login" or \jf\HttpRequest::File()=="sys/logout") return;

	if (!j::UserID())
		header("location: ".SiteRoot."/sys/login?return=/{\jf\HttpRequest::File()}");
	else 
		if (!j::$RBAC->Check("panel")) 
			j::$RBAC->Enforce("root");
?>