<?php
#####################################################################################
# Transition script. Used to make changes between versions, such as database schema #
# upgrades and similar things. 														#
#####################################################################################

$OldVersion=jf::LoadGeneralSetting("Version");
$Version=constant("jf_Application_Version");
if ($Version!=$OldVersion) # upgrade (or downgrade)
{
	jf::SaveGeneralSetting("Version", $Version); # save the new version first, so that concurrent requests do not run transition again


	if ($OldVersion=="1.0" and $Version=="2.0")
	{
		//upgrade the database schema from version 1 to version 2
	}




}
