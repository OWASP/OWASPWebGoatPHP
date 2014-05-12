<?php
class JpkiX509GetService extends BaseServiceClass
{
	function Execute($Params)
	{
		$pki=new JpkiCorePlugin($this->App);
		if (isset($Params['id']))
			$ID=$Params['id'];
		else
			$out['Error'][]="Supply 'id' please.";
		$Res=j::SQL("SELECT X509 FROM jpki_certificates WHERE ID=?",$ID);
		if ($Res)
			$Res=$pki->X509_Details($Res[0]['X509'],false);
		else
			$Res['Error']='X509 Not Found.';
		$out['Result']=$Res;
		return $out;
	}
}

?>