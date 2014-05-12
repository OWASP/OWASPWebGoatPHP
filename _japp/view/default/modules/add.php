
<form method="post" onsubmit="return false;">
New jFramework Module: <input type='text' id='ModulePath' />
<input type="checkbox" id="system" value="1" /> System Module
</form>
<div id="filepath" style="padding:6px;background-color:gray;border:3px ridge;font-weight:bold;">
File: <span id="module"></span>
</div>
<div id="code" style="background-color:#FdFDFD;border:3px ridge;padding:10px;font-weight:bold;display:none;">&lt;?php
<br/>
class <span id="class"></span> extends BaseControllerClass
<br/>{
<br/>&nbsp;&nbsp;&nbsp;function Start()
<br/>&nbsp;&nbsp;&nbsp;{
<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$App=$this->App;
<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$View=$this->View;
<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;# Put your logic here
<br/>
<br/>
<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$this->Present("Page Title Here");
<br/>&nbsp;&nbsp;&nbsp;}
<br/>}
<br/>
?&gt;</div>
<input type="button" onclick="fnSelect('code');" value="Select All"/>
<br/>
<form method="post" id="saveform" style="display:none;">
<input type='hidden' id="save_module" name="module" />
<input type='hidden' id="save_file" name="file" />
<br/>
<div id="sudo" style="font-size:small;">
Supply your system username and password if webserver does not have access writing files:
<br/>
Username:<input type="text" name="save_username" value="<?php echo $_POST['save_username'];?>"/>
Password: <input type='password' name="save_password" value="<?php echo $_POST['save_password'];?>"/>
</div>
<input type='submit' value='Save File' />
</form>

<script>
$(function(){
$("#ModulePath").bind("change",getModuleName);
$("#ModulePath").bind("change",getModuleClass);
$("#system").bind("click",getModuleName);
});

function getModuleName()
{
	data=$("#ModulePath").val();
	system=$("#system").attr("checked");
	$("#save_module").val($("#ModulePath").val());
	$.get("backend/module2path",{"issystem":system,"module":data},getModuleNameCallback);
}
function getModuleNameCallback(data)
{
	$("#module").html(data);
	$("#save_file").val(data);
}
function getModuleClass()
{
	data=$("#ModulePath").val();
	$.get("backend/module2class",{"module":data},getModuleClassCallback);
	$("#code,#saveform").fadeOut();
}
function getModuleClassCallback(data)
{
	$("#code").html(data);
	$("#code,#saveform").fadeIn();
}

</script>
<script type="text/javascript">
	function fnSelect(objId) {
		fnDeSelect();
		if (document.selection) {
		var range = document.body.createTextRange();
 	        range.moveToElementText(document.getElementById(objId));
		range.select();
		}
		else if (window.getSelection) {
		var range = document.createRange();
		range.selectNode(document.getElementById(objId));
		window.getSelection().addRange(range);
		}
	}
		
	function fnDeSelect() {
		if (document.selection) document.selection.empty(); 
		else if (window.getSelection)
                window.getSelection().removeAllRanges();
	}
	</script>
