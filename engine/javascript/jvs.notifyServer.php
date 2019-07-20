<?php if (false) { ?> <script> <?php }
header("content-type: application/javascript");

$rootPath = '../../';
require "{$rootPath}engine/core/include.php";

?>

var notifyWait = false;

function notifyServer(key, type, content)
{
    if (notifyWait) return 0;
    notifyWait = true;

	var rightClick = (rightClick !== undefined && rightClick ? 1 : 0);

    UIEventRequest = new XMLHttpRequest();

    UIEventRequest.open("POST","ajax/notifyServer.php","true");
    UIEventRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    UIEventRequest.send('k='+key+'&c='+content+'&t='+type);

    UIEventRequest.onreadystatechange = function()
    {
        if (UIEventRequest.readyState === 4 && UIEventRequest.status === 200)
        {
			<?php if ($player instanceof EditorPlayer || $player instanceof EditorPlayer2) { ?>getNewView(-1);<?php } ?>
		}
		notifyWait = false;
	};
	return false;
}