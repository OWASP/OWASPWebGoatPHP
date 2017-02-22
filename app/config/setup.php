<?php
$setup=false;
if (isset($_POST['submit'])) {
    $config['DBUSER']= $_POST['db_username'];
    $config['DBPASS']= $_POST['db_password'];
    $config['DBNAME']= $_POST['db_name'];
    $config['LOCALHOSTURL'] = $_POST['develop_url'];
    #TODO: check if these DB settings actually work!


    $configFile=__DIR__."/application.php";
    $configData=file_get_contents($configFile);
    $configData=str_replace(array_keys($config), $config, $configData);
    $configData=preg_replace('/jf::import.*?INITIALSETUP\n/', '', $configData);


    if (is_writable($configFile)) {
    	file_put_contents($configFile, $configData);
	    $setup=true;
    }


    #TODO: populate the DB (ask for admin credentials and create here)
    #TODO: if db exists, populate, else create and populate
    #TODO: if does not exist and creating fails, error and ask to be created
    #can use DB connections Initialize method to populate the data.
}

if (!$setup) {
?>

<!DOCTYPE html>
<html>
    <head>
        <title>WebGoatPHP Setup</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="author" content="Shivam Dixit">
        <style type="text/css">
            html {
                font-family: sans-serif;
                -webkit-text-size-adjust: 100%;
                -ms-text-size-adjust: 100%;
            }

            body {
                font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
                font-size: 14px;
                line-height: 1.42857143;
                color: #333;
                background-color: #fff;
            }

            .center {
                text-align: center;
            }

            h1 {
                font-size: 36px;
                margin-top: 20px;
                margin-bottom: 10px;
                font-family: inherit;
                font-weight: 500;
                line-height: 1.1;
                color: inherit;
            }
            input {
                display: block;
                width: 100%;
                height: 34px;
                padding: 6px 12px;
                font-size: 14px;
                line-height: 1.428571429;
                color: #555555;
                vertical-align: middle;
                background-color: #ffffff;
                border: 1px solid #cccccc;
                border-radius: 4px;
                -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
                box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
                -webkit-transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
                transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
            }

            input:focus {
                border-color: #66afe9;
                outline: 0;
                -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(102, 175, 233, 0.6);
                box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(102, 175, 233, 0.6);
            }
            #config {
            	border:3px double gray;
            	padding:10px;
            	background-color:#DDD;
            }
        </style>
    </head>
    <body>
        <h1 class="center">WebGoatPHP Setup</h1>
        <br>
        <?php if (isset($configData)) {
		      //file was not writable, ask user to modify manually
        ?>
		<p>The configuration file <strong><?php echo realpath($configFile)?></strong> was not writable. Please copy the following configurations into it, overriding everything it already has:</p>
		<div id='config'>
		<pre onclick='selectText(this);'><?php echo htmlspecialchars($configData); ?></pre>
		</div>
<script type="text/javascript">
	    function selectText(obj) {
	        if (document.selection) {
	            var range = document.body.createTextRange();
	            range.moveToElementText(obj);
	            range.select();
	        } else if (window.getSelection) {
	            var range = document.createRange();
	            range.selectNode(obj);
	            window.getSelection().addRange(range);
	        }
	    }
</script>

		<?php
		} else {
        ?>
        <div style="width: 15%; margin: 0 auto;">
            <form method="POST">
                <input type="text" placeholder="Enter MySQL Username" name="db_username" required>
                <input type="text" placeholder="Enter MySQL Password" name="db_password">
                <input type="text" placeholder="Enter Database name" name="db_name" required>
                <input type="text" placeholder="Develop URL ex: localhost" name="develop_url" value="<?php echo $_SERVER['HTTP_HOST'];?>" required>
                <input type="submit" value="Submit" name="submit" style="width: 50%">
            </form>
        </div>
        <?php
        }
        ?>
    </body>
</html>

<?php
exit(0);
}

?>