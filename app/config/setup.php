<?php

if (isset($_POST['submit'])) {
    $username = $_POST['db_username'];
    $password = $_POST['db_password'];
    $dbName = $_POST['db_name'];
    $deployURL = $_POST['deploy_url'];
    $developURL = $_POST['develop_url'];

    $crlf = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')? "\r\n": "\n";

    $output = '<?php'.$crlf.$crlf
            .'/**'.$crlf
            .' * Auto-generated configuration file'.$crlf
            .' * Generate by OWASP WebGoatPHP setup script'.$crlf
            .' * Date:'.date(DATE_RFC1123).$crlf
            .' */'.$crlf
            .$crlf
            .'$cfg["DatabaseUsername"] = "'.$username.'";'.$crlf
            .'$cfg["DatabasePassword"] = "'.$password.'";'.$crlf
            .'$cfg["DatabaseName"] = "'.$dbName.'";'.$crlf
            .'$cfg["DeployURL"] = "'.$deployURL.'";'.$crlf
            .'$cfg["DevelopURL"] = "'.$developURL.'";'.$crlf;

    file_put_contents(__DIR__."/config.inc.php", $output);
    header('Location: '.$_SERVER['REQUEST_URI']);
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title><?php echo jf_Application_Title." Setup"; ?></title>
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
        </style>
    </head>
    <body>
        <h1 class="center">WebGoatPHP Setup</h1>
        <br>
        <div style="width: 15%; margin: 0 auto;">
            <form method="POST">
                <input type="text" placeholder="Enter MySQL Username" name="db_username" required>
                <input type="text" placeholder="Enter MySQL Password" name="db_password" required>
                <input type="text" placeholder="Enter Database name" name="db_name" required>
                <input type="text" placeholder="Develop URL ex: localhost" name="develop_url" required>
                <input type="text" placeholder="Deploy URL ex: webgoatphp.com" name="deploy_url" required><br>
                <input type="submit" value="Submit" name="submit" style="width: 50%">
            </form>
        </div>
    </body>
</html>

<?php die();?>