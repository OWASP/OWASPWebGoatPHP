<?php
class JpkiX509ListService extends BaseServiceClass
{
	function Execute($Params)
	{
		$pki=new JpkiCorePlugin($this->App);
		$Res=j::SQL("SELECT ID,Title,CommonName,EmailAddress FROM jpki_certificates");
		if (!$Res)
			$Res['Error']='Empty.';
		$out['Result']=$Res;
		return $out;
	}
}

?>