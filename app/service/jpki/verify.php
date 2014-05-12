<?php
class JpkiVerifyService extends BaseServiceClass
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
		if (isset($Params['signature']))
			$Signature=$Params['signature'];
		else
			$out['Error'][]="Supply 'signature' please.";
		
			
		$Res=j::SQL("SELECT X509 FROM jpki_certificates WHERE ID=?",$ID);
		if ($Res)
		{
			$PublicKey=$pki->X509_ExtractPublicKey($Res[0]['X509']);
			$Res=$pki->VerifyDataSignature($Data,$Signature,$PublicKey);
			
		}
		else
			$Res['Error']='X509 Not Found with spplied id.';
		$out['Verification']=$Res;
		return $out;
			}
	
}

?>