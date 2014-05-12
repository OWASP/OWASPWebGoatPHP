<?php


class JpkiUnsealService extends BaseServiceClass
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
		if (isset ( $Params ['envelopekey'] ))
			$EnvelopeKey = $Params ['envelopekey'];
		else
			$out ['Error'] [] = "Supply 'envelopekey' please.";

		$Res = j::SQL ( "SELECT PrivateKey FROM jpki_certificates WHERE ID=?", $ID );
		if ($Res)
			$Res = $pki->UnsealData ( $Data, $EnvelopeKey,$Res[0]['PrivateKey'] );
		else
			$Res['Error']="X509 not found with spplied id.";		
		$out ['UnsealedResult'] = $Res;
		return $out;
	}
}

?>