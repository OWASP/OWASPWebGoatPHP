<?php
class JpkiSignService extends BaseServiceClass
{
	function Execute($Params)
	{
		$pki=new JpkiCorePlugin($this->App);
		if (isset($Params['id']))
			$ID=$Params['id'];
		else
			$out['Error'][]="Supply 'id' please.";
		if (isset($Params['content']))
			$Data=$Params['content'];
		else
			$out['Error'][]="Supply 'content' please.";
		
			
		$Res=j::SQL("SELECT PrivateKey FROM jpki_certificates WHERE ID=?",$ID);
		if ($Res)
			$Res=$pki->SignData($Data,$Res[0]['PrivateKey']);
		else
			$Res['Error']='X509 Not Found with spplied id.';
		$out['SignedData']=$Res;
		return $out;
			}}

?>