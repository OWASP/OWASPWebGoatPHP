<?php

/**
 * 
 * Autolist Plugin, Provides sortable list views of Objects and Arrays
 * Simplest scenario to use it to create a new instance and provide a SQL Tablename and an array of header/label maps.
 * 
 * @version 1.2
 * @author abiusx
 *
 */
class AutolistPlugin extends JPlugin
{
	/**
	 *
	 * Array or object access
	 * @var unknown_type
	 */
	public $ObjectAccess=false;
	
	
	
	public $Limit=100;
	public $Offset=0;
	public $Sort;
	public $Order;
	public $_LimitLabel="lim";
	public $_OffsetLabel="off";
	public $_SortLabel="sort";
	public $_OrderLabel="ord";
	
	
	
	/**
	 * 
	 * Contains the data SQL table
	 * @var unknown_type
	 */
	public $Table;
	/**
	 * Count of all elements. Is automatically evaluated when data provided via SetTable,
	 * otherwise should be set manually.
	 * @var unknown_type
	 */
	public $Count;
	
	public $HeaderArray;
	public $Data;
	public $MetaData;
	/**
	 *	Name of the list
	 */
	public $Name;
	
	public $FilterCallback=null;
	public function SetSortParams()
	{
		if (isset($_GET[$this->_SortLabel]))
			$this->SetSort($_GET[$this->_SortLabel],$_GET[$this->_OrderLabel]);
		if (isset($_GET[$this->_LimitLabel]))
			$this->SetLimit($_GET[$this->_LimitLabel],$_GET[$this->_OffsetLabel]);
	}
	private function GenerateRandomName()
	{
		$n="";
		for ($i=0;$i<10;++$i)
			$n.=chr(ord("a")+mt_rand(0,25));
		return $n;
	}
	public function __construct($DataOrTablename=null, $HeaderArray=null,$Name=null)
	{
		if ($Name==null)
			$this->Name=$this->GenerateRandomName();
		else 
			$this->Name=$Name;
		$this->SetHeaders($HeaderArray);
		if ($DataOrTablename)
			if (is_string($DataOrTablename))
			{
				$this->SetSortParams();
				$this->SetTable($DataOrTablename);
			}
			else
				$this->SetData($DataOrTablename);
	}
	function SetHeaders($HeaderArray)
	{
		$this->HeaderArray=$HeaderArray;
	}
	function SetData($Data)
	{
		$this->Data=$Data;
	}
	/**
	 * 
	 * Automatically retrieves the data from the table and sets data
	 * @param string $Table
	 */
	function SetTable($Table)
	{
		$Query="SELECT ";
		$fields=array();
		foreach ($this->HeaderArray as $k=>$h)
			$fields[]=$k;
		$Query.=implode(",",$fields);
		$Query.=" FROM {$Table} ";
		if ($this->Sort)
			$Query.=" ORDER BY {$this->Sort} {$this->Order} ";
		if ($this->Offset)
			$Query.=" LIMIT {$this->Offset},{$this->Limit}";
		elseif ($this->Limit)
			$Query.=" LIMIT {$this->Limit}";
		$r=j::SQL($Query);
		$this->SetData($r);
		$this->Table=$Table;
		
	}
	function SetLimit($Limit,$Offset=null)
	{
		$this->Limit=$Limit*1;
		$this->Offset=$Offset;
	}
	function SetSort($Sort,$Order="ASC")
	{
		$Order=strtoupper($Order);
		if ($Order!="ASC" AND $Order!="DESC")
			$Order="ASC";
		$flag=false;
		foreach ($this->HeaderArray as $k=>$h)
		{
			if (!$this->MetaData[$k]['Unsortable'])
				if ($Sort==$k) 
					$flag=true;
		}
		if (!$flag) 
		{
			reset($this->HeaderArray);
			do
			{
				$t=each($this->HeaderArray);
			} while ($this->MetaData[$t['key']]['Unsortable']);
			$Sort=$t['key'];
			
		}
		$this->Order=$Order;
		return $this->Sort=$Sort;
	}
	function SetMetadata($MetadataArray)
	{
		$this->MetaData=$MetadataArray;
	}
	function SetHeader($HeaderName,$HeaderLabel,$CData=false,$Unsortable=false)
	{
		$this->HeaderArray[$HeaderName]=$HeaderLabel;
		$this->MetaData[$HeaderName]['CData']=$CData;
		$this->MetaData[$HeaderName]['Unsortable']=$Unsortable;
		
	}
	function SetFilter($Callback)
	{
		$this->FilterCallback=$Callback;
	}
	protected function Filter($Key,$Value,$Object=null)
	{
		if ($this->FilterCallback)
			return call_user_func($this->FilterCallback,$Key,$Value,$Object);
		else
			return $Value;
	}
	
	public $Border=1;
	public $Padding=2;
	public $Spacing=0;
	public $Width="100%";
	function Present()
	{
		?>
		<table class='autolist' id='<?php echo $this->Name;?>' width='<?php echo $this->Width;?>' border='<?php echo $this->Border;?>' cellpadding='<?php echo $this->Padding;?>' cellspacing='<?php echo $this->Spacing;?>' >
		<thead>
		<tr>
			<?php 
			if (!is_array($this->HeaderArray)) throw new Exception("No header array provided!");
			foreach ($this->HeaderArray as $h)
			{
				?>
				<th><?php echo $h; ?></th>
				<?php 	
			}
			?>
		</tr>
		</thead>
		<tbody>
			<?php 
			if (is_array($this->Data))
			foreach ($this->Data as $D)
			{
				?>
		<tr align='center'>
				<?php 
				foreach ($this->HeaderArray as $k=>$h)
				{
					if ($this->ObjectAccess)
					{	
						if (isset($D->{$k}))
							$Value=($D->{$k});
						elseif (method_exists($D,$k))
							$Value=$D->{$k}();
						else 
							$Value="";
							
					}
					else
						$Value=($D[$k]);
					$Value=$this->Filter($k, $Value,$D);
					if (!$this->MetaData[$k]['CData'])
						$Value=htmlspecialchars($Value);
				?>
				<td><?php echo $Value;?></td>
				<?php
				}
				?>
		</tr>
				<?php  
			}
			?>
		</tbody>
		</table>
		<?php
	}
	function PresentSortbox()
	{
		
		?>
<form class='sortform sortbox' id="sortform_<?php echo $this->Name;?>" method='get' style='text-align:center;width:<?php echo $this->Width;?>'>
<input type='text' name='<?php echo $this->_OffsetLabel;?>' size='5' value='<?php echo $this->Offset;?>' />
به تعداد
<input type='text' name='<?php echo $this->_LimitLabel;?>' value='<?php echo $this->Limit;?>' size='5'/>
مرتب سازی بر اساس
<select name='<?php echo $this->_SortLabel;?>'>
<?php 
foreach ($this->HeaderArray as $k=>$v)
{
	if (!$this->MetaData[$k]['Unsortable'])
	{
		if ($this->Sort==$k)
			$sel=" selected='selected' ";
		else 
			$sel="";
		echo "<option {$sel} value='{$k}'>$v</option>\n";
	}
}
?>
</select>
<select name='<?php echo $this->_OrderLabel;?>'>
<option value='ASC'  >صعودی</option>
<option value='DESC' <?php if ($this->SortOrder=='DESC') echo " selected='selected' ";?>>نزولی</option>

</select>

<input type='submit' value='برو' />		
		</form>
		<?php 
	}
}