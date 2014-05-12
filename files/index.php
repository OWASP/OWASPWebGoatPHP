<?php
/*	Report errors. Ignore pesky notices.
 *	There are a few places this script assumes information is
 *	available for folders (size, for example) that does not
 *	exist. Let's just make sure you're not reporting notices.
 */
error_reporting(1);

/**********************************************************************************************************************************/
/*******************************************************************************************************************[ SETTINGS ]***/

/*
 * Current translations: english, german
 */
$language = "english";

/*
 * The directory you want to list.
 * You can leave it blank for default option or fill in something like "downloads/"
 *
 */
$this_folder = "";

/*
 * The domain for the breadcrumb navigation.
 * Leave it blank for the default option or fill in something like "files.yoursite.org"
 */
$this_host = "";

/*
 * Files pdirl should ignore
 */
$ignore = array(
	"index.php",
	// "folder"
);

$sort = array(
	array('key'=>'name',	'sort'=>'asc'), // ... this sets the initial sort "column" and order ...
);

/**********************************************************************************************************************************/
/****************************************************************************************************************[ TRANSLATION ]***/
/*
 * Send translations to <theingeniouswiz@newroots.de>
 */

$translation["english"] = array(
	"periods" => array("second(s)", "minute(s)", "hour(s)", "day(s)", "week(s)", "month(s)", "year(s)", "decade(s)"),
	"number_of_files" => "This folder contains %s file(s) totaling %s %s in size.",
	"no_files" => "This folder contains no files.",
	"time" => "%d %s old",
	"directory_listing_for" => "Directory listing for ",
	"parent_directory" => "parent directory",
	"search" => "Search...",
	"search_for_in" => "Search for \"%s\" in %s",
	"found" => "Found %s file(s) totaling %s %s in size.",
	"not_found" => "No search result."
);

$translation["german"] = array(
	"periods" => array("Sek.", "Min.", "Stunde(n)", "Tag(e)", "Woche(n)", "Mon.", "J.", "Jahrzehnt(e)"),
	"number_of_files" => "Dieser Ordner beinhaltet %s Datei(en) mit der Gesamtgr&ouml;&szlig;e von %s %s.",
	"no_files" => "Dieser Ordner enth&auml;lt keine Dateien.",
	"time" => "%d %s alt",
	"directory_listing_for" => "Ordnerinhalt von ",
	"parent_directory" => "zur&uuml;ck",
	"search" => "Suchen...",
	"search_for_in" => "Suche nach \"%s\" in %s",
	"found" => "%s Datei(en) mit der Gr&ouml;&szlig;e von %s %s gefunden.",
	"not_found" => "Keine Suchergebnisse."
);

if (!isset($translation[$language]))
	$language = "english";
$_ = $translation[$language];


/**********************************************************************************************************************************/
/*********************************************************************************************************************[ IMAGES ]***/


if(isset($_GET['image']))
{
	$image = $_GET['image'];

	header("Content-type: image/png");
	// Cache icons for a week
	header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 60 * 60 *24 * 7) . ' GMT');
	switch ($image) {
		case "..":			die(base64_decode('iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAYAAADEtGw7AAAABHNCSVQICAgIfAhkiAAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAANNSURBVDiNtZVNaFxVGIaf75xz782fUvsjGI00BY2IoILGmJks2igRqcH6H4pdSOmmK2sToS6iiBsRhIouTDetYnVRjSJCLbYbFSKdKiiIQZRY04ihNmnoZGbuufdzMXeSMaZjXPTAyzmLj+d7v5d77hFV5Uosc0WogPs/xb37gwERDoRtvv/UqPpGtWt23DcS7r1+U+eHRmzfWgz9J1heEpMbdm/e3HH7q3sGD7RY49K1GGnYeWBYWnPixrtv3Zq/r/vRpsCFa2E2Buefl3bBneq/e0fnnV33Bt9Of07P5kGMo1JecIu5/cE/6sUwG7T69lr2q4JzI+Ed1jSdGNy285rrNt1gJ6Y+BZQkTXhh11vNRgyCILKc5OjY7vXgHbA6uPc5t721ue3ow1ufbjORMjH1CUYs1gR8MXkYIw4jBmssgkFEyG95vHEUueFg37qrN7y8o39Xy/nFKX4+ewZrApwJSDUlTROscRix+NRgMsdJGqMosho4P+IOtm/seGYg/1jL5PlvmJ77aRmoCYGkpLZCRTyJxqDgCInsVXiNWXmDlx0b2jGIkhD7EmVfJBaLotgQwGNQjBXEVL1pqmgJiumFf0WxlP5XzckT5/78/dBHJ9+9dNOGbrquvYeyL5KaEtgKLoKw2RK1WJpaLVFr9Rw0G1KpXB6so5p+/Zrf99fsheFjxw8X14cd9HQ+RBAE2MDgQkMQmSp8CWpxkcFYQYGFc8u8pShEJACiiTf8B7c9VZwZT947cn/v9ra+zielMPsxaio8uOVZnHPYwKCqpF5JfHVHYOYMLSISq2rsMqgDwkzRD+8n392Y55Hjfvyd3F3bNuZv2elOzx5DU3j90CvlcjGOVo4uMDd/FgOEIpLUHNtMrqbfvkz+mP+VocSfPDh/cb4r1zMUaiLEZQ0KY/6B8kUWgHKmUrbXWK7hT2h+moXC28neQuH0yc9OjJcXi6VG5fUrrTlOMvk69xVAfAktjKUvVoZ+mbxUPLKHNI3EUMxcxlmdX6FqFKrqRSSLqtqxrpED3PdHk7HK4MyP6zbL7iRmLhvd18FjYBEoqapK/Y2RKj0Aomx3dRNIJs1UP2UZKKsuvypyucdURCzV79zWqTZNbaIESHQVyN9x5li6vCTOrQAAAABJRU5ErkJggg==')); break;
		case "cc":			die(base64_decode('iVBORw0KGgoAAAANSUhEUgAAACcAAAAMCAYAAADhyKTAAAAAAXNSR0IArs4c6QAAAAZiS0dEAP8A/wD/oL2nkwAAAAlwSFlzAAALEwAACxMBAJqcGAAAAAd0SU1FB9kHCwkgGBX9Oy0AAALjSURBVDjLfdVNaF5VEAbgZ74kNVGhUg1NqUZNpQWlosb6g3+INYpSN4JSEboSgn+gdmNEVwr+IARBEERECtWFdaOCVItYtEERFZQSN63R2FaKoiZYaL92XHSuXD5SD1zOnXvO3HfOzDvvicwEEbEMN2MDVjo5DuIr7M7Mo5YYETGGCVyEM/A3fsTOzJw/hc8gbsUVGMYJ/IoZfJ6ZJyAyU0SchUdxBJ9gDoELsBGn4ZXM/LMH5I4KbBe+wwKW40rchHczc3ePz0o8gt/L7xf0YQy34ShezcxFGMDTuL8Jtv2gU2vPYKD1/Xq8hJGyRzGFs8s+H9O4rOVzOp7F3Q1WHWao3vvxALYiOgVyHNuzqXFrVIq3o1t7RcRQAbyRmYdqaxeLNcvMOWzDvRHRqT0TOIz3MBgRW/EipiNishL1ZgW8oYOrsPMkZmyJiKmIuCEi1tf7ZJ14V5ULLsHhzJytYFfgweLqE8Vf+KbmsZqvLi4m7sQqPI8XsBabMrOLT5vgzsV+XI7VeLl4sBk78FHx4CeMFMiq8mnGUBH7nAqwr7KXmMdIRAxgBfZHRD/WFLfnMnMffsCl9b+fMdLf8LTmTnXOYNl9tbZsqaZrVx/HqixLdnUPVboR8Tq6TWe28P4bnTrZGL6tjD1eNX8bm0pejlfnNvw6UHYDdgAflLktM49UuaMqczAzj+EPXFj77sKTEXFmRAwXVWZrbRSH+kvHJvB1Zr7Vc8jvC6SDW0qHYC/ui4iLM3Pv/yRpvLLaUGAGt0fELD7EuqIR7MOOlt6+37TvFLagbwkp6au1p3qk5Nr68Xll34jnGukoTk1jfctnqKTknqLFQO1b3bIn8RiiEeHleKgCaURYS4S7JYx/9QjqxirPZ9WZC0X68Qr+nczc0+MzjIfxT2HNF73W1K2xgNcyczFa11c/rsM11Y3K8Ut80UPcNthoHWBdSc5Clf3jzPztFD5N6cYLq1vX1x7MNHr7L3sYPXKw/UdVAAAAAElFTkSuQmCC')); break;
		case "search":		die(base64_decode('iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAYAAADEtGw7AAAACXBIWXMAAA3XAAAN1wFCKJt4AAAAB3RJTUUH1QwQERAYX4Te4gAAAthJREFUOMvVlc1LVFEYxn/3zozp0NSggxOm4UIjcmwRQdQm6R9oUYsQN1EKEvaxidokbqtVEaNT7kKychFBiyIxaFcZ5ScOlaSF1jQzaU33nnPnnhZz5zozYiDSogMv97zvec5zHs773HvhfxsawLnzZ3uytnW5dFEIAYCdtY/fivUPrpu46+xpdfJE+5qgaN9NksnkvvuDQ6/XTXxg/0EWFhbWBL55O4qUEqWUGwBVlaELvdG+q6V4b36ye3cTuu5Zk7imZntRrus6Ho+Hu/cGrnR2dvZGo9HlVcRCCCYnJ9g21k9wbnhdTbrkA5ZZam9zS0N773DMVRyJNGM9HmbPyVMbcsO7/ttHi65ifHyMXfkkPVMEXpz+xOLErJuHm+oJ79qxmjW4s/gqDMNwFAOWBZZEOYCvM/MkrAYC3U/dTYmBDso+zhOsrUJDAxQK0KRc6UGhYgBlSWwhQAgQJotTc/hbYxgjN1juacQYuYG/NcbcaBwlBEqYKGGCMFFWCXEmkyESac5VLAuk6WwQLlA+v07kcBPy+fWCN2gFZ4ssSPEXxdJEmY5iabpA36EzjA9P4Dt0xq2l5hMoYeTIszq28Xs1satYSpQwsEXugOrarWQGOihv6SLQHae8pQuAQHeczzOLpL6kHQGbkEtpgGS+eVqhK5QlQQqUAqVBqLoCtfCSbz2NRQYIdMep6HjIl9gRtm7xopV7ySTTKIUB6C5xJNKM9SyAEjLXDGcoIFTpI1QZBKUhLZt0yiRReJA0weslk/5BVhEHvF7ABzA2OU3d5h0oS6CkyDHmTITm2AkFPg1CQZ1Q0E/ekzlXSDKpFN8NXgFlXsAPUDceo6HejOskGglvx7HnOkYC4+cvHr1nCqjQgHIgPNrG7EY/7k9muXbxBQ+ADxrgccgDQIUzL3Mco7Fae2FNAVknhBO/gaU8yOM4JH/neVJKiLWSp+2s2YBVEPY/++f9AdcxYLCMX92lAAAAAElFTkSuQmCC')); break;
		case "directory":	die(base64_decode('iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAYAAADEtGw7AAAABmJLR0QAAAAAAAD5Q7t/AAAACXBIWXMAAA3XAAAN1wFCKJt4AAAAB3RJTUUH1QsKFAImbV4amgAAAjVJREFUOMvVlc1rE0EYh5+Z3dSmGJFaBCvovaYnBVGQ+md4VSkSRKIXL36A4ElRT5LWIgiKWMWzN/UgCIpamyJpe/CgttHatE2aZJPNzushu5tNpId+eHDgNzuz8/LMb96dmYX/rSiA8xfS1zzTuNo5WK/XATCeOTF27/74usHn0mfl9MnhNYMyo3cpFAqHno0//7Bu8JHDR8nn82sGfvr8Edd1EZFQALt6+y6OZEZvdsbbQWNg4ABaW2uC+/v3tvW11liWxZOnj2+kUqmRTCZT+svxmdSwDB07zssZYeLH5j5azF28/vZB+kroOJkc5ParSS6dGsJxvQ2Dbz18cxlogaemsgAslessFGsbgu7r62mlCsBxHJLJwU3v3WrdawdHHW9V0QCVSmVrHFerALNb7nhlZQXdKK62gQPH8a7WXhbwDwMYEYwBYwRjBM9XI6KKU0OLNxMcEBV13PAMtYYBCcCgEET5HQRBNQeVgCgEYXu3za+lJVd7lTKgQ3AyOcjB3Du0pam5pslQhBN0gvDrYGm9Cc3P30WlnYXXgG0DMYDslxw7du4mv1zD8R0HvBAerYVw5kTcpuYaFpZLnr34HqDLBnoAuvcMsD9hkZsvowATTXTY9tPQtI/QNLAtZvg+Oy2lUrn0LTezCsRtoDo5mR3++mhibLN3++r0izvzc/NzwVosoBtIAHG/3eXvGNXKdNvFFbwTwPNV91UFiioCt33FIlA6wKrjafwxAzQiMv/sn/cH5SsZQC/sNk8AAAAASUVORK5CYII=')); break;
		case "package":		die(base64_decode('iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAYAAADEtGw7AAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAA3XAAAN1wFCKJt4AAAAB3RJTUUH1gEREy4g8394FQAAAklJREFUOMutlE1rE1EUhp87k5nMJGqrxhZEaHEj+AcUXLgUBBE/UAp1W92IlK7E7hRFWuhOUNyIglQQUagLF26LoG5KwSp+ECjG2HyZxExmmrku5k4cJ6mmtRfuzHDu4TnveQ9zBetcMyPaayADXB9/6N9ZK09j/Wvf2StzQ5Zl354Z0cY2E4zu5Dh8+jJ/g28IDJBbeBqF3980sOd51D6/DOGjcXgiNpirwGSvYNdtohffcnz8EbM3jp0CznUFA5Njt7Jrwn7k3vPhzRxOKcvAwCCu28RxHPrqWQB7TcXh+rnyqSOWsPspLL1gaX6Wd77PkZPnKX95BUCjXvm/4Xm1PPmVMrl8gQd3p+gfPoBlWdjpvk4h3dSmMnu7gquFZZa/u4Eiw2dq+iYTly52ze0A5xee8XFxHkELKVukrcC6dDrF0MFRJg5dwHcb7fxKZRkzmfk3GGB4zyBICYLgHUIWH3f1stz41hu41SgBEkUOgiI8Fb/PpAQh0HuxAkA3jDZHhg+hgGG9aIFewJoGJMyO9Dgi1k+8nT/AJoCZTOEZJqKbGCmRQoAEodCO42CbySjDA/wQrANbA7CNTBiqdOcz8JZ2RDdWMZJWCN4BFAE3BBsh2LDT1LxWfEztluOxhG6SMNqK+4A64HV4bG7bza79R3v8F4MSTV+LGqdFPfaAatGxnty7dubEBm5R8bXkPwcqQAPwRWx4W4CdwHZ1WyWVEk3t8LsFrAKOar0KrAA1wAVkfO5CdWEqgK62FrFVRiz3VAFXxVoh6BcscNFF02vRlwAAAABJRU5ErkJggg==')); break;
		case "program":		die(base64_decode('iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAYAAADEtGw7AAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAA3XAAAN1wFCKJt4AAAAB3RJTUUH1QQWFAcAWrsU1AAAA6FJREFUOMu1lctvG0Ucx78zO+tdO36WKrFjO27cQlOSoChtKUFUqQRpWgnBoeLUG23gAC2vA/wJXEDiyg0ipHKseLVCQkikjRKSSnm3W6d26mec2BvHzm782uXg3RCC20RIjPTT7I52PvuZn+Y3A/xPjRzkoxcvfOYDkDJe2ydvfZ7ebw49KPT9kfMbb7x+WgGQMsb+O9iEvvf2kNzZHnAPnuy1XRzuPxCc7mt6ZUgOB4OepcQqFuMZ/DGXsJ09+3xpPzh9GvTa1SG5MxD0SKksGKXgGYdrb53DTGTd/vLA8afC6ZOg16+el4/4g57lzDoYx4FxFIzj8Nu0BIHnsZzesr96rveJcNoM+sHIsBzyBzyxbB6MUjBKwXGN/sJLJyDwDALPYHe67ddHLqrN4HQv9MN3huWOdr8nvr6xY2n2lBB8f2saA31hnDjqQ7laQ8DbZn1tsLe4F053Qz96d1gO+vyeVL7QgBmmpm2b2w6P0wZd03GqOwTvMy7c+HkKckl39HV3FHbDTePU5Tf7CwGv37O6UWxYUvp3TxvWv44twXfYhdZDDug1DUtSGgJjEHkedudhV6jVopqFxMxq+u7mvVSNiOWerrDAUQKlXAMhjeK0WTjEknnU6hpO93Rgq1zFnT8jEC0MlBBQQrCaiuor2YoVeu0MAHAAkIyMlfzHXvl64X7801w+r4RDAV6KZhH0urESz6FW0zD3IAULY3A6rNA1YHWtCJ7jwHMcspmYPiutEaJXOidvf7Hwr7PCzHXPc15l4Ey/bXllHQQApQRW0YKBU2FQQjCzkECxtA1KCNLJR/rkfJIQvdI5cfvLWNPtZhwu7fNSxjY5MaV0hdt2thY0HfelDBil0GoaRJ4hm442hTYtEBM+I2VtE+MTyrNHWiHyPFpEAUGfB4zjYLeJyKai+vhsoil0J8d7WzIyprS4fKOyKnxSV3Jq3wtdfK1ah9Nhg8MuYmlxXv/l7kOST82enLv7bQKADkDbD+wAcCiXXrSU1cJNlfNfqW+tbfd0H2e6BjyORjD6wzSJzf90Kf7wjgzACkAwWHXzB3vBLQCcANwAPEoxK27mH/9eFo5eqhbTlWpF5UZ/vIcHUzc+zmekogFku1KqAdhuBmaGgc0IR0UtCGopJ6l8aHApuobF8W++KsqJTWP5dQAVAGUDqBrPTa8mwTA2w2WkpwUAb1gpAIoANgFs7IrSQe88014EYDGWrAOoGmYKgC1j7B/tLwk4imB4boo5AAAAAElFTkSuQmCC')); break;
		case "web":			die(base64_decode('iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAYAAADEtGw7AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAN1wAADdcBQiibeAAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAAQDSURBVDiNlZVZbFVVFIa/tc8d2nKF0lIF2oahFJBisBNDKqKJIwloTUBExOHZR33QN19NSHw0QkSG0jgECnEgQBOJ0YBDQwKlSAsG0EIHaG+5A/eec/by4d7TgV5MXDk75yRn51v//vfaa4uqAvDlN4cWOY7Z5Xv+ZgUHADR4EJGUEbmiql2+tadB27a/ujPLA0IC8NeH2w831je3LlpYg4hMm+j7PslUgmQywdDwUKa7+3zcWvu+wsEdr71pHwhu/+qAt23LDmckfhvP81BVrLVYtai1iAihUJhwOIzvWmbEYvx5uefexZ4L16xvW954/e3bk8Em+LBWHRHBdd0c0FpUbS6BKp7vkUonGY2PcKP/Gpcu9VC/qrFo3eqWWjGmc9/Bz4sLggPlgcpgqCrk/wVRMqOE0bERABYvXmIeXV5XZ0SOfLF/jykAztmUW3oeFvAkeAkCREJh1E7YWr+qITRvXtUGq7p2OthqkAHNj1xJKKjkSwMQAYRQOEJb+0E+2/0pAwMDVM2viorwbMALjXucX66qjgtFYXA0xd5j3Vy8Okw661JbXcbWZ2qpmT8HxxFuXL/BkY7DtL78ihgxLwEfTQEHHo8rQznbfYvdR7+naWUp2zaepSS2iX9uRdn3w3FWLGjgnU11VFdVk3WzWGtxXfexB1oR6B2NZ9nT8R1bnu+jZuEQNxPvcWtsAUuX/MFbrSfpv32a33oGMcahKFpEZWUlvrWh6eD85iG5Dfr256s82dyLapZzV58m44eYGRsmkZ7FWKqWDc2/s/fYeRzH4DjO1H0q5LFjDCCc6xuiYUU5v17eiC9RRDKUx05R9tBF7ow4pJjBcHwYEYfgoOqkspxmhRGDMQZBGIqvYW7pCWYVdxFPpLkTdxiJF2Ekzt1UPfeyRTjGwTHO1FVT4IAYkwM3Lp/L9ZvFPDzzEo2L9zO75AzHz77ILxeeIJPx+HtwGZUVMwmFHEwebAsqzmcTMRgxtD61jL6/bjI4tplMxmVwJMbdZBrPLSGRLOVMV5x3tzZjjIPjmGkeT/SKfDYRQUQom1XMBzubOPXTGNcG1jO3tI76mjLSqWqO/dhC09JHqK+dg2NyQu73eKKO7VSwiNCwrIIPt6/m0InZ9PZ3cc+1VJVHeKFpJRuaa4hEIogYkOBw2QLg+xoNCuVl5axfN4eWNY/jeR4A4VCYcCQ8PkeZaFK2ULmB+qq51qmqqOSECIoxQjQSIagr6/uT8yNIIMyfAhaR6K5PPu7su9L73JKa2oI3yH+FqtJ3pRfP8zpFJKqqmZCIGKCi7UB7WyqZjsViJWtVJ1VLcOkVtExRBRGxiUTyTEfH0TagQkT6RVURkSgwHwj/L6nTwwX6VTXzL7a9CPnuPUapAAAAAElFTkSuQmCC')); break;
		case "text":		die(base64_decode('iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAYAAADEtGw7AAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAA3XAAAN1wFCKJt4AAAAB3RJTUUH1QYYDQkr4Wx8tgAAAZhJREFUOMu1ld1qGkEYhp9vVo+anCriDSgq/lGStb2bXkQJpD1IYjD2wvpHQQRRPBHaHiwoFAIhB2X360Hczbgb3AmmHww7s7s888477zDwn0rizmg8vAA+HAJT1cuz9+cfd16OxkM9tEbjoca8QnrW1WqF53moKiKCyMOi7H48tpRSrVZ3OBmwDZjNZhlIujqdDqqaeb8X3Gw2KRQefwnDcGf8lPpcsIgwnU5zN6zb7WKMcQMbY1BV2u02AMVi8dkJyYC///iGfzJARJhMJrkA3/cJwzAffPL6lEqlQhAE9Hq9p9VsfbYT42RFEASICMvlMldxq9Vy9zhutVptr2LP85Ic54LTS4w3054wvXQnK758/cybwVtEhPl87hQ3p1T4pwNKpRKbzSaxwhiTqLYtiPtRFLl5vF6vEREWi8XLHRC76vV65nCkfXdWbG/GPo/7/f7zcmxXo9FI1MUK009VdYtbuVx+kaspAUdRdHXz6fr8ENivn7/fZe681GRHwPG2HQGvAAN4gAJ/gXvgD3AL3G3H4fY7/wAc1bAz9NMAcQAAAABJRU5ErkJggg==')); break;
		case "audio":		die(base64_decode('iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAYAAADEtGw7AAAABHNCSVQICAgIfAhkiAAAAihJREFUOI3t0r9rU1EUwPHvfT/y0rRNsI21TVX8UUG0W6liXQSx0ojg4iAIruLs0EXpoBVtd3H2HxCkexERVOjkoBXUtGry2qaNTfKSvHfvuy5RYihq0i6CBw7c4dzPPedwBS3EgfQ9DSCEkKZlvgrC6tXM01sft6o1WoEBrl+b4MTYiLVv/+BJWzjP9ozPdO4IHLEEUoX07O41k33JZDyqZ3cElirEDyR+IEn290UxjCuHL9wd3jaspCLwJYEvkYFiYDAVx7CfpC5OxbbZsWJ1dVUuvnlbXcm5geU4oisR3xsNo6/3T0yfPn55KtIeLEPyuTVToc8W8oV32aUvXkdnt9Pd23PUEOJ5uezU2oQVoEVmbvJFWPJGq7XKw9zn5fXN/HpRmGLOVBwBsFqFtdY/z5/mp6rAzXr+Ei13/LfxH/6H4S2/28H0/XMIPQPimGkJW6BRkiDUIRvFWnvwofSDyVjMnk6fGWZ4aEA4EYuqr1grlO3FzBoZd7M9WBvhnUvjI2JXIsbXvEcuX8bd8ChVfHriURKdzo/SOOAB8k+wALoB8t8qlGsKd8Oj6PkUPR8hBADVio8wCIF+oFTPIqAbYbPpkY744Kix7BbHwlATdWxhWQaWYeAHPivuus5mXfxC9lFx6eVCvVsJBEDYCIumCSJAV+rUjfORROq2MK0hBJbWIEBq5X+orLyfdRcezwOV+ipKgN+8ima4Mex6Wg2TqYYOg9/c5TvS8eNfJDIr0gAAAABJRU5ErkJggg==')); break;
		case "image":		die(base64_decode('iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAYAAADEtGw7AAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAA3XAAAN1wFCKJt4AAAAB3RJTUUH1QsKEAYBqzHCKQAAAxFJREFUOMvVlU+IHEUUh7/q6prent6dzc5mFxcUMQZEI4qI6EESwYsHzdGjomchRwmYDZJcPCt4WYIHg+QUTIgg63V1RSNuFmOMJuqazIQ9ZDM7f3u7qp6H6WlnMmiU5GJDUd393vv61e9VvYb/26WWTiy9CyzeY+6xEFg8+PJBsdaqf/IUEf6N3RjD2XNnj4QAzjn1wUdn+HbtZwZ0Afq+gnhBkMIgInikuBcEEXh07wMcfutVAEIApRQr3/zInieeJwgCjHbEseNmQ2OdxzqPcx4A6wTrHNb6EZu1nvXLF1FK/QUGCLWiOp1w/30prxyYpFqp8tnKVb5eSwpAH5yD3G1g52k2dSFNAQ6CgKkk4pl9KXsWXkApw/6nm1zZ2KHbUzjfBzsnOJ/Deg2cTvJ3Qq+mC63DgfCBUlTKEdstTWprGD3NVqNJEu0iCsEPwL4PcTaj0ajTbmwy+eABrPMEAeMZK2AyLlHfnOOL1UtUp3f4ZaPKZLmE91Jk4r3gvfDHT9/hrOVm/QrJ7EPMLTzCb6Ee11gFUEkiUIp2J6HdFkohmCSv/AAswmbtd65eXOXhx/f3ZbRN5mYSTBiMZiwi2Cyju705ujfzLeVFSNMMgF6asr76OcZEZN1bhKbE7vkFdpo3sFlKkOtRZLzdbHHi5BmGN7L3nlanS6vVLYoXhY5ymIEIv9bP47xw/uQ5PIrZSmlUiqKSpQgRcM6ROYe1Qmgm2DUzMboS7+i1b9FqdzBRgqZDZXoeE/pxsAgYExfB8Z26we55bJbS62zz1GP7iCbKTJU19Ru1USm01sxWZ/9Tp9na2qJUinnpxeeYmYoxxrB24QJaB4cK8LNP7uX7S5fv3A4Z71VGC9571n9YB2TpjdfffD8EiOOYtw+9Nhw9ClF/DxYRvvxqhWvXNqhdv/7h4pGjhwFC7/3x05+efuduG/Dy8vJ7pz459THgAFF5PgaI8ppN5M8hoIdGmAfZodkCGdABekA6DB4sVg/BBkfID7Vnlc/Dgkk+fP4RP/BRjClbQG//ZchYHUdtI/Y/AcKMnti9YBGeAAAAAElFTkSuQmCC')); break;
		case "video":		die(base64_decode('iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAYAAADEtGw7AAAABmJLR0QAVQBXAFOSIcO/AAAACXBIWXMAAA3XAAAN1wFCKJt4AAAAB3RJTUUH1gkPCigUF9DYtAAABNRJREFUOMutlVlslFUUx3/fNvu0M6ULnXYsMCyFFhPTBAJUQpXIYkSWEHzQoLz7YGKQSGI0oqJBE31TXwAVY5RoSFDAlpmyhFJaELHS0pa2FGbaMtDO6nzffIsvU5gIPric5OScnHvP/56c/JIr8De294N3qsbH4287nfbVdrutWpZluyiKAoBpmpauGznLsgbzun7M63Hv27Vz93hxv/AQwTJd10/IstzkK/URCAQIVAcoLy/H7fYAkMmkicfjRGNRotEoU4kpkqn0hRKvZ+2unbvvPiC85923HpNluTNYG5RbVrUwfGOYVCpFNBalrS1CIpFEEMDtdrN+/Rpqa2rw+fxUVVQRjoS5PjSkKYq8dNfO3b/IxcKmaZ2sr18ou1wuXn1tJ5qmgWXR/PgKNm3aQCRymrq6IHV1QW7H77Dvw4+xKTJOl5MXt2/H7fHYurq7jwNVxRML772/x9z+wnZ+u3qFf2ONCxez/+B+jhxtDciFdQiApOtGqrPrgtflcrH/wAE0TUPT8qxd9xS1NQGGR0bp6OxmzeoWxsbGOdF6EruiUOL1sGPHS3R2XUDVtLF8Pi8KgAjYAfeGTc9+vnzZko3B2iAtq1oYGR0hk0kTjcX47vAR4nfuomp5yvw+tmx+hrmhOfhKfQRmBghHwgwND1mnz5x/PRob+1oGJMAFVIiC9aTT7aXvWh+jN0eZpmLRgkV88tHKh1IxcG2AqcQUqVSGcx1dU2dPtXepqpqdFrYBJYZh5K78ellZ2NBgb4+cFbxue6amJuAqK/M/gKUoilgWyLJCLDZGNpujvn6uX5CEz27euPmKUFiDD6gBGjdu2vCpzW6zLVu2nFuxMbOr+1Iyl/2jdGoyLum6bimKDUWRBVEUsdkUAGbPnkWgOkBlZRXRsSidXZfPSMVDAGZvb98ZC8x0OhlKpZKyr8TjWLpsiblu/dNWU1OT2bj4UcorZ5qirOg2u8Nwur2qzWbTZEWRKysqhOYVzZxoDVdOEyECTqAMqABmAP6GxkXN1YHqJ2RFmaHrel5VtZwAFgjZVDrVI1jWyMXuS63BR+pK54RCz4mS0mC3K6HJZDImbN22ZbWm5Z+XJGmzJIle/oOl0pmMIsnu2MTduAj87PP5t6qq6pUkmY5z5wGIhNsBCLdFAPj2m8MPRMMw6P29l3QqTU/PVbSc6nY4HPj9XlXYum2LlU5l8M/wIwoC3hIvDruDZDLJrFl1KDaF/msDhOaGUFWV/mv9zJs/j9sTtxkcvE4oNIeBgUFKfaWkkmlcLgeZbO68DLCgfj6maTA5OQWAYRgIglCE1v28uF6MXl7LI0ki6Uw2eys6+bI4fZhOZ7FME0mSkCQJw9BRCjjlcuo9EVW9nxuGwcDAIKZpEo3GkCRJjcYm3ui5fKFHamhc9KbdbqevtxeH00l7+BQer4cfjx7H5XJaBw98JQQfCVpffnFIcLqcZrgtIrjcLqO766JYUVFuJqZSVklpyV2PxxM/c+rsypGhodOAJmzdtsUqvJ6TJMnxTygwDCNqmuYtVdU6fjp6bC+QBXJAXgbQdSOh5nKHxscnjicSidsD/YMT072AVeAcwCxyo8h1IA9ohTNLtiwr9/3hH+Y85LJQEDULwlZRbToWu1mI9/486S8iFv+D/QnVfTUabg8fNgAAAABJRU5ErkJggg==')); break;
		default:			die(base64_decode('iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAYAAADEtGw7AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAN1wAADdcBQiibeAAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAALPSURBVDiNlZVNT1NBFIbfd3proVRxISRAiXwmGmIIEg2GhStdalwYv1Djj/BPmJC4NGqMCrXxI4A/gIU7V24UQYEFMSEhSADTFuHeO8fFnemdfpE47WSmt81z3vPOOVOKCADg7Yc3vYmEmgyD8IoACQAAxL5BsqTIVRH5Emr9CZDc7Rv3DtBg0ILfz+RnRkfOXevt6QfJmh+GYYhiqYBisYDN35v7Cwtfd7XWDwWYnrh1XzcE599NBTevTyS2d7cQBAFEBFpraNEQrUESnpdEMplE6Gu0ZDL48XPx7/fFb2s61ON37zzYcsHKbrSWBEn4vh8BtYaIjgKIIAgDlPaK2Nndxq/1NSwtLWJkeLTpwvnxQSo1/2r6RXNdsFVuVdopIoD5zo50Sxo7f7YBAH19A+r0qaEhRc6+fP1c1QFHNkWpG5jl0S4EARzxkhAd2zoyfNbr6Mhe1CJjtWAtNgLEzKgkBBCa0gBAAiC85BHk8tN4+uwJNjY2kO3MpkhcqvXYpCsiiF6uaAEZ6SUJKqK9/QRO9nTj6LEMZudm0Np6nIrqquV51R6XlZkNGQUxxQwKQBLp5jTS2TS6s9048A+gtYbv+2dqwdpqM2QhQAM3Pkerin03WppSTejq6kKotVcLNocHWhBNo0TEyp6xHyqrpXxOLth6nFDKURmv1cgyzN1LHbCNpqgM0Ch113p0h1zOGnUOT6kYbO+M6rV6WKG6rmITjVRRSTke01Huao8xUpF1BdhGc6HladU7FsU8KWd7qMeNoNFUTiaIO5QWfIjHVdlBWPVQbK0j3pcvsDqKAQlFoqtTRMr9QadhxB5iVXyCVlhYASaZmnz8aH5ldfnyQP9gw9NvNEQEK6vLCIJgnmRKRPY9kgpAW24qnysV9zKZTHpMxLn17J+eA3H1SnR36EKh+Hlu7mMOQBvJdYoISKYAdAJI/pfU2uEDWBeR/X8JhI/HEPUHOgAAAABJRU5ErkJggg==')); break;
	}

	exit();

}


/**********************************************************************************************************************************/
/************************************************************************************************************[ DIRECTORY LOGIC ]***/


// Get this folder and files name.
$this_script = $_SERVER['PHP_SELF'];
if (!$this_host)
	$this_host = $_SERVER['HTTP_HOST'];
if (!$this_folder)
	if (isset($_GET['folder']))
		$this_folder = htmlspecialchars($_GET['folder']);

// Declare vars used beyond this point.
$file_list = array();
$folder_list = array();
$total_size = 0;
$file_array = array();

// This fixes a vulnerability listing parent directories with "?folder=../".
if (count(explode("../", $this_folder."/")) > 1)
	$this_folder = "";

if (empty($_POST['search'])) {
	/*
	 * Maybe you will ask me why I use glob() instead of readdir().
	 * glob() hides files with a "."-prefix automaticly.
	 * I think its more secure when you hide files,
	 * which should be hidden - especially in a directory listing, hm?
	*/
	$file_array = glob($this_folder."*");
} else {
	// Search for term in current directory
	$search = htmlspecialchars($_POST['search']);
	if (isset($_GET['search_in']))
		$search_in = htmlspecialchars($_GET['in']);
	$file_array = rglob("*".$search."*", 0, $this_folder);
}

foreach ($file_array as $file) {
    if (array_search($file, $ignore) === false) {
    	// If not in the folder where pdirl is
    	if (!empty($this_folder)) {
	    	if (strpos($file, $this_folder) === 0) {
	    			/*
	    			 * If you search with glob e.g. in "examples" you will get paths like "examples/foobar.mp3"
	    			 * The path should be shortened.
	    			 */
		    		$item['path'] 	= substr_replace($file, "", 0, strlen($this_folder));
		    	} else {
		    		/*
				     * Okay, you will probably ask why I save the path of the file/folder
				     * Because of the sorting options, files and folders should be sorted according to their name and not
				     * their path. In version 0.9.2beta files and folders were sorted according to their path (only in search mode).
				     */
				    $item['path'] = $file;
		    }
    	} else {
    		$item['path'] = $file;
    	}
    	$path_array = explode("/", $item['path']);
    	// This should give us the filename
		$item['name'] = array_pop($path_array);
	    $item['location'] = implode("/", $path_array);
		if (!is_dir($file)) {
			$stat = stat($file); // ... slow, but faster than using filemtime() & filesize() instead.
			$item['bytes'] = $stat['size'];
			// converting 348298 to array(340.13, "KB")
			$item['size'] = byteunit($stat['size'], 2);
			$item['mtime'] = $stat['mtime'];
			$item['type'] = get_type($file);
			// Add this items file size to this folders total size
			$total_size += $item['bytes'];
			$file_list[] = $item;
		} else {
			// ...and folders to the folder list.
			array_push($folder_list, $item);
		}
    }
}

// Calculate the total folder size
if($file_list)
	$total_size = byteunit($total_size, 2);

// Sort folder list.
if($folder_list)
	$folder_list = filesort($folder_list, $sort);
// Sort file list.
if($file_list)
	$file_list = filesort($file_list, $sort);

/**********************************************************************************************************************************/
/******************************************************************************************************************[ FUNCTIONS ]***/

/**
 *	http://us.php.net/manual/en/function.array-multisort.php#83117
 *	http://us.php.net/manual/en/function.array-multisort.php#85166
 */

function filesort($data, $keys) {
	if (!$data)
		return false;
	foreach ($data as $key => $row)
	{
		foreach ($keys as $k)
		{
			$cols[$k['key']][$key] = $row[$k['key']];
		}
	}
	$idkeys = array_keys($data);
	$i=0;$sort='';
	foreach ($keys as $k)
	{
		if($i>0){$sort.=',';}
		$sort.='$cols['.$k['key'].']';
		if(isset($k['sort'])){$sort.=',SORT_'.strtoupper($k['sort']);}
		if(isset($k['type'])){$sort.=',SORT_'.strtoupper($k['type']);}
		$i++;
	}
	$sort .= ',$idkeys';
	$sort = 'array_multisort('.$sort.');';
	@eval($sort);
	$result = false;
	foreach($idkeys as $idkey)
	{
		$result[$idkey]=$data[$idkey];
	}
	return $result;
}

/**
 *	@ http://us3.php.net/manual/en/function.filesize.php#84652
 */

function byteunit ($size, $precision = 0) {
	$sizes = array('YB', 'ZB', 'EB', 'PB', 'TB', 'GB', 'MB', 'KB', 'Bytes');
	$total = count($sizes);
	while($total-- && $size > 1024) $size /= 1024;
	$return['number'] = round($size, $precision);
	$return['unit'] = $sizes[$total];
	return $return;
}
/**
 *	@ http://us.php.net/manual/en/function.time.php#71342
 */
function time_ago($timestamp, $recursive = 0)
{
	global $_;
	$current_time = time();
	$difference = $current_time - $timestamp;
	$periods = $_['periods'];
	$lengths = array(1, 60, 3600, 86400, 604800, 2630880, 31570560, 315705600);
	for ($val = sizeof($lengths) - 1; ($val >= 0) && (($number = $difference / $lengths[$val]) <= 1); $val--);
	if ($val < 0) $val = 0;
	$new_time = $current_time - ($difference % $lengths[$val]);
	$number = floor($number);
	$text = sprintf($_['time'], $number, $_['periods'][$val]);

	if (($recursive == 1) && ($val >= 1) && (($current_time - $new_time) > 0)) {
		$text .= time_ago($new_time);
	}
	return $text;
}

function breadcrumb ($path, $link=true) {
	global $this_script;
	if ($path == $this_script)
		$path = dirname($path);
	$path = explode ("?", $path);
	$path = $path[0];
	$dir_array = explode ("/", $path);
	$counter = 1;
	$breadcrumb = "";
	foreach ($dir_array as $dir) {
		if ($dir != "") {
			$dir = urldecode($dir);
			if ($link) {
				$breadcrumb .= '<a href="'.implode("/", array_slice($dir_array, 0, $counter )).'/">'.$dir.'</a>/';
			} else {
				$breadcrumb .= $dir."/";
			}
		}
		$counter++;
	}
	return $breadcrumb;
}

function get_type ($file) {
	$typelist = array (
			'text'		=> array('ppt', 'pptx', 'doc', 'docx', 'txt', 'rtf', 'odf', 'odt', 'ods', 'odp', 'odg', 'odc', 'odi', 'nfo', 'xml', 'pdf', 'x-office-document'),
			'package'	=> array('7z', 'dmg', 'rar', 'sit', 'zip', 'bzip', 'gz', 'tar', 'deb', 'bz2', 'bz', 'x-compressed-tar', 'iso'),
			'program'	=> array('exe', 'msi', 'mse', 'sh', 'bat', 'x-executable', 'x-ms-dos-executable'),
			'web'		=> array('js', 'html', 'htm', 'xhtml', 'tpl', 'jsp', 'asp', 'aspx', 'php', 'css'),
			'video'		=> array('x-shockwave-flash', 'mv4', 'bup', 'mkv', 'ifo', 'flv', 'vob', '3g2', 'bik', 'xvid', 'divx', 'wmv', 'avi', '3gp', 'mp4', 'mov', '3gpp', '3gp2', 'swf', 'ogv'),
			'audio'		=> array('spx', 'ogg', 'oga', 'mp3', 'wav', 'midi', 'mid', 'aac', 'wma'),
			'image'		=> array('ai', 'bmp', 'eps', 'gif', 'ico', 'jpg', 'jpeg', 'png', 'psd', 'psp', 'raw', 'tga', 'tif', 'tiff')
	);

	$ext = pathinfo($file);
	if (isset($ext["extension"]))
		$ext = strtolower($ext["extension"]);
	if (function_exists("mime_content_type") and $mime_type = mime_content_type($file)) {
		$mime_type = explode("/", $mime_type);
	} else {
		$mime_type = array("","");
	}

	foreach ($typelist as $key => $value) {
		if (in_array($mime_type[1], $value)) {
			return $key;
			break;
		} elseif ($mime_type[0] == $key) {
			return $key;
			break;
		} elseif (in_array($ext, $value)) {
			return $key;
			break;
		}
	}
}

/*
 * @ http://www.php.net/manual/de/function.glob.php#87221
 */
function rglob($pattern='*', $flags = 0, $path='')
{
	global $this_folder;
    $paths=glob($path.'*', GLOB_MARK|GLOB_ONLYDIR|GLOB_NOSORT);
    $files=glob($path.$pattern, $flags);
    foreach ($paths as $path) { $files=array_merge($files,rglob($pattern, $flags, $path)); }
    return $files;
}

function tpl_header() {
	global $this_host, $_, $this_folder, $search;
	$hostlink = "<a href=\"http://$this_host/\">$this_host</a>/";
	if (empty($search)) {
		$ret = $_["directory_listing_for"];
		$ret.= $hostlink;
		$ret.= breadcrumb($_SERVER['REQUEST_URI']);
	} else {
		$ret = sprintf($_["search_for_in"], $search, $hostlink.breadcrumb($_SERVER['REQUEST_URI']));
	}
	return $ret;
}

function tpl_footer() {
	global $file_list, $folder_list, $_, $total_size, $search;
	if ($file_list && empty($search)) {
		echo sprintf($_['number_of_files'], count($file_list), $total_size['number'], $total_size['unit']);
	} elseif ($file_list && !empty($search)) {
		echo $_['found'] = sprintf($_['found'], count($file_list), $total_size['number'], $total_size['unit']);
	} elseif (!$file_list && !$folder_list && !empty($search)) {
		echo $_['not_found'];
	} else {
		echo $_['no_files'];
	}
}

/**********************************************************************************************************************************/
/*******************************************************************************************************************[ TEMPLATE ]***/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Directory Listing for <?=$this_host."/".breadcrumb($_SERVER['REQUEST_URI'],0) ?></title>
<style type="text/css">
body{font-family: "Lucida Grande",Calibri,Arial;font-size: 9pt;color: #333;background: white;}
a{color: #b00;font-size: 11pt;font-weight: bold;text-decoration: none;}
a:hover{color: #000;}
img{vertical-align: bottom;padding: 0 3px 0 0;}
table{margin: 0 auto;padding: 0;width: 600px;}
table td{padding: 5px;vertical-align: middle;}
thead td{padding-left: 0;font-family: "Trebuchet MS";font-size: 11pt;font-weight: bold;}
tbody .folder td{border: solid 1px white;}
tbody .file td{background: #fff;border: solid 1px #ddd;}
tbody .file td.size, tbody .file td.time{white-space: nowrap;width: 1%;padding: 5px 10px;}
tbody .file td.size span{color: #999;font-size: 8pt;}
tbody .file td.time{color: #555;}
tbody .folder td small, tbody .file td small {display: block;color: #AAA;}
tbody .folder td small a, tbody .file td small a {font-size: 8pt; color: #777;font-weight: normal;}
tfoot td{padding: 5px 0;color: #777;font-size: 8pt;background: white;border-color: white;}
tfoot td.copy{text-align: right;white-space: nowrap;}
tfoot td.cc{margin-top: 20px;}
tfoot td.cc img{padding: 0;border: none;}
tfoot a {font-size: 8pt;font-weight: normal;text-decoration: underline;}
#s {padding: 0px;text-align: right;}
#s input{width: 65px;border: solid 1px #ddd;color: #999;padding: 5px 5px 5px 27px;background: url("<?=$this_script?>?image=search") no-repeat 3px 3px;}
</style>
</head>

<body>
<table cellpadding="0" cellspacing="1">
	<thead>
		<tr>
			<td colspan="2">
				<?=tpl_header()?>
			</td>
			<td id="s">
				<form action="" method="post">
					<input name="search" type="text" value="<?php if (!empty($search)) { echo $search; } else { echo $_["search"]; } ?>" onclick=" if(this.value == '<?=$_["search"]?>') { this.value = ''; }" />
				</form>
			</td>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td class="total" colspan="3"><?=tpl_footer()?></td>
		</tr>
		<tr>
			<td class="copyright" colspan="3" style="font-size:9px;">
			Powered by
			<a href="http://jframework.info" style="font-size:9px;color:black;text-decoration:none;outline:none;border:0px solid;"
			title="jFramework 2">jFramework
			<img src="http://jframework.info/img.jlogo.png" width="12" height="12" style="border:0px solid;"/></a>
			</td>
		</tr>
	</tfoot>
	<tbody>

<? if($this_folder): # If this folder is not the folder where pdirl is?>
		<tr class="folder">
			<td colspan="3" class="name"><img src="<?=$this_script?>?image=.." alt=".." /> <a href=".."><?=$_['parent_directory']?></a></td>
		</tr>
<? endif; ?>
<? if($folder_list): ?>
<? foreach($folder_list as $item) : ?>
		<tr class="folder">
			<td colspan="3" class="name">
			<img src="<?=$this_script?>?image=directory" alt="directory" /> <a href="<?=$item['path']?>/"><?=$item['name']?></a>
			<?php if (!empty($item['location'])) { ?>
			<small><?=breadcrumb($item['location'])?></small>
			<?php } ?>
			</td>
		</tr>
<? endforeach; ?>
<? endif; ?>
<? if($file_list): ?>
<? foreach($file_list as $item) : ?>
		<tr class="file">
			<td class="name"><img src="<?=$this_script?>?image=<?=$item['type']?>" alt="<?=$item['type']?>" /><a href="<?=$item['path']?>"><?=$item['name']?></a>
			<?php if (!empty($item['location'])) { ?>
			<small><?=breadcrumb($item['location'])?></small>
			<?php } ?>
			</td>
			<td class="size"><?=$item['size']['number']?><span><?=$item['size']['unit']?></span></td>
			<td class="time"><?=time_ago($item['mtime'])?></td>
		</tr>
<? endforeach; ?>
<? endif; ?>
	</tbody>
</table>
</body>
</html>
