<?php


class JpkiSealService extends BaseServiceClass
{

	function Execute($Params)
	{
		$pki = new JpkiCorePlugin ( $this->App );
		$Params['id']=array(19,20);
		if (isset ( $Params ['id'] ) && is_array ( $Params ['id'] ))
			$ID = $Params ['id'];
		else
			$out ['Error'] [] = "Supply 'id' array please.";
		if (isset ( $Params ['content'] ))
			$Data = $Params ['content'];
		else
			$out ['Error'] [] = "Supply 'content' please.";

		if (is_array($ID))
		{
		foreach ( $ID as $i )
		{
			$Res = j::SQL ( "SELECT X509 FROM jpki_certificates WHERE ID=?", $i );
			if ($Res) $PKs [] = $pki->X509_ExtractPublicKey($Res [0] ['X509']);
		}
		$Res = $pki->SealData ( $Data, $PKs );
		$out ['SealedResult'] = $Res;
		}
		return $out;
	}
}

?>