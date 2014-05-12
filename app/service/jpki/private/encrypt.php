<?php


class JpkiPrivateEncryptService extends BaseServiceClass
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
		
		$Res = j::SQL ( "SELECT PrivateKey FROM jpki_certificates WHERE ID=?", $ID );
		if ($Res)
			$Res = $pki->Encrypt_Private ( $Data,  ( $Res [0] ['PrivateKey'] ) );
		else
			$Res ['Error'] = 'PrivateKey Not Found with spplied id.';
		$out ['EncryptedData'] = $Res;
		return $out;
	}
}

?>