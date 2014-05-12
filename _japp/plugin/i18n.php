<?php

/**
 * 
 * This class provides infrastructure for i18n alongside all appliaction outputs.
 * @author abiusx
 * @version 2.0
 */
class I18nPlugin extends BaseApplicationClass
{
	
	private $TranslatePreparedStatement;
	private $SavePreparedStatement;
	
	protected $Pivot=null;
	protected $Active=null;
	public $Languages;
	
	/**
	 * This is the utility function for translations,
	 * use it at ease!
	 * @param string $Phrase
	 * @param optional language $Language
	 * @param optional language $Target
	 */
	public function Translate($Phrase,$Language=null,$Target=null)
	{
		if ($Language===null)
			$Language=$this->GetPivot();
		if ($Target===null)
			$Target=$this->GetActive();
		
		$Res=$this->GetTranslation($Phrase,$Language,$Target);
		if ($Res===null)
		{
			$this->AddPhrase($Phrase, $Language);
			return $Phrase;
		}
		elseif ($Res===false)
		{
			return $Phrase;
		}
		else
			return $Res;
	}
	
	function __construct($Pivot=null,$Active=null)
	{
		if ($Pivot)
			$this->SetPivot($Pivot);
		else
			$this->SetPivot(reg("jf/i18n/pivot"));
		if ($Active)
			$this->SetActive($Active);
		else
			$this->SetActive(reg("jf/i18n/active"));
		$this->Languages=reg("jf/i18n/langs");
	}
	
	function AddPhrase($Phrase,$Language)
	{
		if ($this->PhraseID($Phrase, $Language))
			return false;
		$ID=j::SQL("INSERT INTO jfp_i18n (Language,Phrase) VALUES (?,?)",$Language,$Phrase);
		return $ID;
	}
	function PhraseID($Phrase,$Language)
	{
		$Res=j::SQL("SELECT * FROM jfp_i18n WHERE Language=? AND Phrase=?",$Language,$Phrase);
		if ($Res)
			return $Res[0]['ID'];
		else
			return false;
	}
	function PhraseInfo($PhraseID)
	{
		$Res=j::SQL("SELECT * FROM jfp_i18n WHERE ID=?",$PhraseID );
		if ($Res)
			return $Res[0];
		else
			return null;
	}
	function EditPhrase($ID,$NewPhrase,$NewLanguage=null)
	{
		if ($NewLanguage)
			j::SQL("UPDATE jfp_i18n SET Phrase=? AND Language=? WHERE ID=?",$NewPhrase,$NewLanguage,$ID);
		else
			j::SQL("UPDATE jfp_i18n SET Phrase=? WHERE ID=?",$NewPhrase,$ID);
		return j::AffectedRows();
	}
	function RemovePhrase($ID)
	{
		j::SQL("DELETE jfp_i18n_graph WHERE ID1=? OR ID2=?",$ID,$ID);
		j::SQL("DELETE jfp_i18n WHERE ID=? LIMIT 1",$ID);
	}
	
	
	private $_getTranslationPrepared=null;
	/**
	 * 
	 * Finds a translation for a particular LanguagePhrase, in the target language
	 * @param string $Phrase
	 * @param Language $Language
	 * @param Language $Target
	 * @return null on phrase not found, false on translation not found, string translation on found
	 */
	function GetTranslation($Phrase,$Language=null,$Target=null)
	{
		if ($Language===null)
			$Language=$this->GetPivot();
		if ($Target===null)
			$Target=$this->GetActive();
		
		if ($Target===$Language)
			return $Phrase;
			
		//if source or target is not pivot, do indirect translation
		if ($Language != $this->GetPivot() && $Target!=$this->GetPivot())
		{
			$t=$this->GetTranslation($Phrase,$Language,$this->GetPivot());
			return $this->GetTranslation($t,$this->GetPivot(),$Target);
		}
		//else do the direct pivot->target translation
			
		
		$ID=$this->PhraseID($Phrase,$Language);
		if (!$ID)
			return null;
		
			
		/**/
		if ($this->_getTranslationPrepared===null)
			$this->_getTranslationPrepared=j::$DB->Prepare("SELECT * FROM (SELECT ID1 AS gID FROM jfp_i18n_graph WHERE ID2=? 
			UNION ALL
				SELECT ID2 AS gID FROM jfp_i18n_graph WHERE ID1=?) AS TRs
				JOIN jfp_i18n ON (jfp_i18n.ID=TRs.gID)
				
				WHERE Language=?");
		$this->_getTranslationPrepared->Execute($ID,$ID,$Target);
		$Res=$this->_getTranslationPrepared->AllResult();
		/*
		$Res=j::SQL("
		SELECT * FROM (SELECT ID1 AS gID FROM jfp_i18n_graph WHERE ID2=? 
			UNION 
				SELECT ID2 AS gID FROM jfp_i18n_graph WHERE ID1=?) AS TRs
				JOIN jfp_i18n ON (jfp_i18n.ID=TRs.gID)
				
				WHERE Language=?",$ID,$ID,$Target);
		*/
		if (!$Res)
			return false;
		
		return $Res[0]['Phrase'];
	}
	
	/**
	 * 
	 * Links two LanguagePhrases as equal
	 * You can provide two i18n LanguagePhrase IDs to this function, or equivalent language phrase pairs.
	 * One of them should be in Pivot
	 * @param $Phrase1 or ID1
	 * @param $Language1 or ID2
	 * @param $Phrase2
	 * @param $Language2
	 * @return false on failure, 1 on sucess, 0 on link exists
	 */
	function Link($Phrase1,$Language1,$Phrase2=null,$Language2=null)
	{
		if ($Phrase2!==null)
		{	
			$Phrase1=$this->PhraseID($Phrase1,$Language1);
			$Language1=$this->PhraseID($Phrase2,$Language2);
		}
		
		$ID1=$Phrase1;
		$ID2=$Language1;
		$Info1=$this->PhraseInfo($ID1);
		$Info2=$this->PhraseInfo($ID2);
		if ($Info1['Language']!=$this->GetPivot() and
			$Info2['Language']!=$this->GetPivot())
			return false;
		
		j::SQL("REPLACE INTO jfp_i18n_graph (ID1,ID2) VALUES (?,?)",$ID1,$ID2);
		return j::$DB->AffectedRows();
	}
	/**
	 * Disconnects a phrase from its pivot. 
	 * One of them should be in Pivot
	 * @param $Phrase1 or ID1
	 * @param $Language1 or ID2
	 * @param $Phrase2
	 * @param $Language2
	 */
	function Unlink($Phrase1,$Language1,$Phrase2=null,$Language2=null)
	{
		if ($Phrase2!==null)
		{	
			$Phrase1=$this->PhraseID($Phrase1,$Language1);
			$Language1=$this->PhraseID($Phrase2,$Language2);
		}
		
		$ID1=$Phrase1;
		$ID2=$Language1;
		
		j::SQL("DELETE FROM jfp_i18n_graph WHERE ID1=? AND ID2=? LIMIT 1",$ID1,$ID2);
		return j::$DB->AffectedRows();
	}
	
	
	function SetActive($Lang)
	{
		$this->Language=$Lang;
	}
	function GetActive()
	{
		return $this->Language;
	}
	function SetPivot($Lang)
	{
		$this->Pivot=$Lang;
	}
	function GetPivot()
	{
		return $this->Pivot;	
	}
	

}