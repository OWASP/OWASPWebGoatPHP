<?php


class JpkiPublicDecryptService extends BaseServiceClass
{

	function Execute($Params)
	{
		$pki = new JpkiCorePlugin ( $this->App );
		if (isset ( $Params ['id'] ))
			$ID = $Params ['id'];
		else
			$out ['Error'] [] = "Supply 'id' please.";
		if (isset ( $Params ['content'] ))
			$Data = $Params ['content'];
		else
			$out ['Error'] [] = "Supply 'content' please.";
		
		$Res = j::SQL ( "SELECT X509 FROM jpki_certificates WHERE ID=?", $ID );
		if ($Res)
			$Res = $pki->Decrypt_Public ( $Data, $pki->X509_ExtractPublicKey ( $Res [0] ['X509'] ) );
		else
			$Res ['Error'] = 'X509 Not Found with spplied id.';
		$out ['DecryptedData'] = $Res;
		return $out;
	}
}

?>