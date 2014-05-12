<?php
/**
 * Autoform Generator Class
 * Enter description here ...
 * @author abiusx
 * @version 1.03
 */
class AutoformPlugin extends JPlugin
{
	public $formElements=array();
	public $labelNames=array();
	public $formAttribs;
	function __construct($method='get',$action='')
	{
		$this->formAttribs['Method']=$method;
		$this->formAttribs['Action']=$action;
		$this->formAttribs['Id']=$this->GenerateRandomID();
	}
	function GenerateRandomID()
	{
		$id="";
		$l=rand(2,10);
		for ($i=0;$i<$l;++$i)
		{
			$id.=chr(ord('a')+rand(0,25));
		}
		return $id;
	}
	function PresentCSS()
	{
	?>
	label {
		width:200px;
		float:right;
		height:1em;
	}
	input {
		text-align:center;
	}
	.autoFormContainer {
		margin:2px;
	}
	<?php 
	}
	function PresentScript()
	{
	?>
function showValidation_<?php echo $this->formAttribs['Id'];?>(name,validation)
{
	var icon=String();
	if (validation==true)
		icon='tick';
	else if (validation==false)
		icon='wrong';
		
	var bgimg="url('<?php echo SiteRoot;?>/img/plugin/autoform/icon/"+icon+"16.png')";
	$("form.autoform#<?php echo $this->formAttribs['Id'];?> *[name='"+name+"']").css("background-image",bgimg);
	return validation;
}
function validateForm_<?php echo $this->formAttribs['Id'];?>(e)
{
	var name=e.target.tagName;
	name=(name.toLowerCase());
	var all=false;
	if (name=='form')
		all=true;
	else
		name=e.target.name;
	<?php 
		foreach ($this->formElements as $E)
		{
			if ($E['Validation'])
			{
			?>
	idx="<?php echo $E['Name'];?>";
	if (all || name==idx)
	{
		var item=$("form.autoform#<?php echo $this->formAttribs['Id'];?> *[name='"+idx+"']");
		if (item.is(":visible") && !item.val().match(<?php echo $E['Validation'];?>))
			return showValidation_<?php echo $this->formAttribs['Id'];?>(idx,false);
		else
			showValidation_<?php echo $this->formAttribs['Id'];?>(idx,true);
	}
			<?php
			} 	
		}
		?>
	return true;
}
function checkDependency_<?php echo $this->formAttribs['Id'];?>(e)
{
<?php 
	foreach ($this->formElements as $E)
	{
		if ($E['Dependency'])
		{
			$v=$E['DependencyValue'];
			?>
			container=$("form.autoform#<?php echo $this->formAttribs['Id'];?> #container_<?php echo $this->formAttribs['Id'];?>_<?php echo $E['Name'];?>");
			item=$("form.autoform#<?php echo $this->formAttribs['Id'];?> *[name='<?php echo $E['Name'];?>']");
			if (item.attr("name")==undefined)
				item=$("form.autoform#<?php echo $this->formAttribs['Id'];?> *[nameBackup='<?php echo $E['Name'];?>']");
			
			<?php 
			if ($this->formElements[$E['Dependency']]['Type']=='radio')
			{
				?>
			dependency=$("form.autoform#<?php echo $this->formAttribs['Id'];?> *[name='<?php echo $E['Dependency'];?>']:checked");
				<?php 
			}
			else 
			{
				?>
			dependency=$("form.autoform#<?php echo $this->formAttribs['Id'];?> *[name='<?php echo $E['Dependency'];?>']");
			<?php 
			}
			?>
				data=dependency.val();
				if (data<?php echo $v;?>)
				{
					if (!container.is(":visible"))
					{
						item.attr("name",item.attr("nameBackup"));
						container.show();
					}
				}
				else
				{
					if (container.is(":visible"))
					{
						item.attr("nameBackup",item.attr("name"));
						item.attr("name","");
						container.hide();
					}
				}	
		<?php 
		}
	}

?>
}
$(function(){
	$("form.autoform#<?php echo $this->formAttribs['Id'];?> *").live("change",validateForm_<?php echo $this->formAttribs['Id'];?>);
	$("form.autoform#<?php echo $this->formAttribs['Id'];?> *").live("change",checkDependency_<?php echo $this->formAttribs['Id'];?>);
	$("form.autoform#<?php echo $this->formAttribs['Id'];?>").live("submit",function(e) {
		if (!validateForm_<?php echo $this->formAttribs['Id'];?>(e))
		{
			alert("لطفا اطلاعات را اصلاح کنید");
			return false;
		}
		});
	checkDependency_<?php echo $this->formAttribs['Id'];?>();
	$("form.autoform input:first").focus();
});
		<?php 
		
	}
	function PresentHTML()
	{
		?>
		<form class='autoform' id='<?php echo $this->formAttribs['Id'];?>' method='<?php echo $this->formAttribs['Method'];?>' action='<?php echo $this->formAttribs['Action'];?>'>
		<?php 
		foreach ($this->formElements as $E)
		{
			if ($E['Type']=="check")
				$E['Type']="checkbox";
//			if ($E['Dependency'])
			{
				?>
				<div id='container_<?php echo $this->formAttribs['Id'];?>_<?php echo $E['Name'];?>' class='autoFormContainer'>
				<?php 	
			}
			if ($E['Type']=='text' or $E['Type']=='password') :
			?>
				<label><?php echo $E['Label'];?></label>
				<input type='<?php echo $E['Type'];?>' name='<?php echo $E['Name'];?>' 
				<?php if ($E['Direction']) echo " dir='{$E['Direction']}' ";?> 
				<?php if ($E['Disabled']) echo " disabled='disabled' ";?> 
				<?php if ($E['Style']) echo " style='{$E['Style']}' ";?> 
				value='<?php 
				if (array_key_exists("Value",$E))
					echo $E['Value'];
				else 
					echo $E['Default'];
				?>'
				
				/><?php echo $E['Unit'];?>
			<?php 	
			elseif ($E['Type']=='submit') :
			?>
			<input type='submit' value='<?php echo $E['Value']?$E['Value']:"ارسال";?>' />
			<?php 
			elseif ($E['Type']=='textarea') :
			?>
				<label><?php echo $E['Label'];?></label>
			<textarea name='<?php echo $E['Name'];?>'><?php 
				if (array_key_exists("Value",$E))
					echo $E['Value'];
				else 
					echo $E['Default'];
				?></textarea>
			<?php 
			elseif ($E['Type']=='radio' or $E['Type']=="checkbox") :
			?>
			<label><?php echo $E['Label'];?></label>
			<?php 
				if (is_array($E['Options']))
				foreach ($E['Options'] as $k=>$o)
				{
			?>
				<input type='<?php echo $E['Type'];?>' name='<?php echo $E['Name'];?>' value='<?php echo $k;?>' <?php 
				if ($E['Value']==$k 
					or (!$E['Value'] AND $E['Default']==$k)) echo " checked='checked' ";?>
				/><?php echo $o;?>
			<?php
				} ?>
			<?php 
			elseif ($E['Type']=='select') :
			?>
						<label><?php echo $E['Label'];?></label>
						<select name='<?php echo $E['Name'];?>' 
							style="<?php
							if(!$E['width'])
								echo 'width:auto;'; 
							else 
								echo 'width:'.$E['width'].';';?>">
						<?php 
							if (is_array($E['Options']))
							foreach ($E['Options'] as $k=>$o)
							{
						?>
							<option value='<?php echo $k;?>' <?php 
							if ($E['Value']==$k 
								or (!$E['Value'] AND $E['Default']==$k)) echo " selected ";?>
							><?php echo $o;?></option>
						<?php
							} ?></select>
							<?php
						endif ;
						
//			if ($E['Dependency'])
			{
				?>
				</div>
				<?php 	
			}
		}
		?>
		</form>
		<?php 
	}

	function AddElement($Element,$Name=null,$Label=null,$Value=null,$Default=null,$Unit=null,$Validation=null,$Options=null,$Dependency=null,$Style=null)
	{
		if (is_array($Element))
		{
			$data=$Element;
		}
		else 
		{
			$data['Type']=$Element;
		}
		if ($Name!==null)
			$data['Name']=$Name;
		if ($Label!==null)
			$data['Label']=$Label;
		if ($Default!==null)
			$data['Default']=$Default;
		if ($Unit!==null)
			$data['Unit']=$Unit;
		if ($Validation!==null)
			$data['Validation']=$this->getValidation($Validation);
		if ($Options!==null)
			$data['Options']=$Options;
		if ($Dependency!==null)
			$data['Dependency']=$Dependency;
		if ($Style!==null)
			$data['Style']=$Style;
		if ($Value!==null)
			$data['Value']=$Value;

		
		if ($Element['Validation'] && !$Validation)
			$data['Validation']=$this->getValidation($Element['Validation']);	
			
		//updaing label names
		if ($Name===false)
			$Name=$Element['Name'];
		if ($Label===false)
			$Label=$Element['Label'];
		if ($Name)
			$this->labelNames[$Name]=($Label)?$Label:$Name;
		
		$this->formElements[$data['Name']]=$data;
		return $data;
	}
	function getValidation($Validation)
	{
		if ($Validation=="number" or $Validation=='numeric')
		{
			return "/^\d{1,}$/";
		}
		elseif ($Valudation=='alphanumeric')
		{
			return "/^[a-zA-Z0-9 ]{1,}$/";
		}
		elseif ($Validation=='alpha')
		{
			return "/^[a-zA-Z ]{1,}$/";
		}
		elseif ($Validation=='alpha_farsi')
		{
			return "/[a-z ا-ی آ-يA-Z]{1,}/";
		}
		elseif ($Validation=='alphanumeric_farsi')
		{
			return "/[a-zA-Z0-9ا-ی۰-۹آ-ی ]{1,}/";
		}
		elseif ($Validation=='numeric_farsi' or $Validation=='number_farsi')
		{
			return "/^[۰-۹0-9]{1,}$/";
		}
		elseif ($Validation=='*')
			return "/.*/";
		elseif ($Validation=='?')
			return "/^.{1,}$/";
		else 
			return $Validation;
	}
	
	function Validate($Data,&$Result,$ForceExistance=false)
	{
		$flag=true;
		foreach ($this->formElements as $E)
		{
			if ($E['Validation'])
			{
				if (!$ForceExistance and !array_key_exists($E['Name'], $Data)) continue;
				if (!preg_match($E['Validation'],$Data[$E['Name']]))
				{
					$Result[$E['Name']]=$flag=false;
//					echo "Validation failed on ".$E['Name'].BR;
				}
//				else 
//					$Result[$E['Name']]=true;
			}
			elseif ($E['Type']=='select' or $E['Type']=='radio')
			#TODO: only allow listed options
			;
		}
		return $flag;
	}
	
}