<?php if (false) { ?> <script> <?php }
header("content-type: application/javascript");

$rootPath = '../../';
require "{$rootPath}engine/core/include.php";

?>

var frameTimer;

var framesSinceLoad	= 1;
var viewWait		= false;

var tileString		= "<?php echo $view->tileString; ?>";
var spriteString	= "<?php echo $view->spriteString; ?>";
var updateString	= "";

var viewHeight		= <?php echo $view->height; ?>;
var viewWidth		= <?php echo $view->width; ?>;

var mapName			= "<?php echo $map->MAP; ?>";

var mapContainer = document.getElementById('mapContainer');

// Pending direction saves direction press that happens while input is locked out.
// This way, if you happen to try to move between frames, it'll happen late and not never.
var pendingDirection;


function getNewView(direction)
{
	if (viewWait)
	{
		if (pendingDirection === undefined) pendingDirection = direction;
		return;
	}

	viewWait = true;

	var forceFastFrame = false;

//	if (direction === -1 && pendingDirection !== undefined)
	if (pendingDirection !== undefined)
	{
		direction = pendingDirection;
		forceFastFrame = true;
	}

	pendingDirection = undefined;

    request = new XMLHttpRequest();

    request.open("POST","ajax/getUpdates.php","true");
    request.setRequestHeader("Content-type","application/x-www-form-urlencoded");

    request.send("d="+direction+"&m="+mapName);

    request.onreadystatechange = function()
    {
        if (request.readyState === 4 && request.status === 200)
        {
			try
			{
				if (frameTimer !== undefined) clearTimeout(frameTimer);
				<?php if (!($player instanceof EditorPlayer || $player instanceof EditorPlayer2)) { ?>
				if (allowIdle) frameTimer = setTimeout(idle, request.getResponseHeader('<?php echo HEADER_NEXTFRAME ?>'));
				<?php } ?>
				document.getElementById('frameRate').innerHTML = request.getResponseHeader('<?php echo HEADER_NEXTFRAME ?>')
					+ ' ' + framesSinceLoad
					+ ' ' + pressedDIRs.join();

                if (viewHeight !== request.getResponseHeader('<?php echo HEADER_VIEW_HEIGHT ?>'))
                {
                    viewHeight  = request.getResponseHeader('<?php echo HEADER_VIEW_HEIGHT ?>');
                    mapContainer.style.marginTop = (0 - (viewHeight * <?php echo CHAR_HEIGHT; ?> )) + "px";
                    mapContainer.style.height = (viewHeight * <?php echo CHAR_HEIGHT * 2; ?>) + "px";
                }

                if (viewWidth !== request.getResponseHeader('<?php echo HEADER_VIEW_WIDTH ?>'))
                {
                    viewWidth   = request.getResponseHeader('<?php echo HEADER_VIEW_WIDTH ?>');
                    mapContainer.style.marginLeft = (0 - (viewWidth * <?php echo 1.5 * CHAR_WIDTH; ?> )) + "px";
                    mapContainer.style.width = (viewWidth * <?php echo CHAR_WIDTH * 3; ?>) + "px";
                }

				newTileString		= request.getResponseHeader('<?php echo HEADER_TILES ?>');
				newSpriteString		= request.getResponseHeader('<?php echo HEADER_SPRITES ?>');

				viewWait = false;

				//updateString = request.responseText === 'REFRESH' ? undefined : request.responseText;
				updateString = request.responseText;

				if (newTileString)		tileString		= newTileString;
				if (newSpriteString)	spriteString	= newSpriteString;
				if (updateString) applyUpdates();

				map.innerHTML = getViewHTML();

				updateSpriteDivs();

				// Force fast frame happens when there's a direction pending. It shouldn't happen otherwise.
				if (forceFastFrame)
				{
					clearTimeout(frameTimer);
					frameTimer = setTimeout(idle, 200);
				}
			}
			catch (exception)
			{
				viewWait = false;
				<?php if (DEV_MODE) { ?>
				console_echo('Mep retrieval error: ' + request.responseText, '#faa');
				<?php } ?>
			}
        }
    };
}

function getViewHTML()
{
    var t = 0;
    var t_class = tileString.charAt(t);
    var t_frame;
    var html = '<span class="'+t_class+'">';
    var tile=tileKey[t_class]; //window['rt_'+t_class];
    var isStatic = tile.c.length === 6;
//	var prefixes = 'abcdefhijk';
	var spriteOffset = 0;
	var startOfLineSpriteOffset;
	var charIndex = 0;

    var center = (Math.floor(viewHeight/2) * viewWidth) + Math.floor(viewWidth/2);

    for (r=0;r<viewHeight;r++) // ROW
    {
        for (l=0;l<=1;l++)  // LINE
        {
			if (l === 0) startOfLineSpriteOffset = spriteOffset;
			else spriteOffset = startOfLineSpriteOffset;

            if (l === 1) t = t - viewWidth;

            for (c=0;c<viewWidth;c++)  // COLUMN
            {
                if (tileString.charAt(t) !== t_class)
                {
                    t_class = tileString.charAt(t);

                    html = html + '</span><span class="'+t_class+'">';
                    tile = tileKey[t_class];
                    isStatic = tile.c.length === 6;
                }

				sprite_key = spriteString.charAt(t + spriteOffset);

                if (sprite_key !== '-')
                {
					spriteOffset++;
					sprite_key = sprite_key + spriteString.charAt(t + spriteOffset);

                    t_sprite = spriteKey[sprite_key];
					if (t_sprite === undefined) t_sprite = spriteKey['notFound'];
                    t_frame = t_sprite['f'+(framesSinceLoad % t_sprite['f'])];
                }
//
				var mod = (l === 0 ? 0 : 3);

				for (e=mod;e<=2+mod;e++)  // ELEMENT
				{
					charIndex = isStatic ? e : Math.floor(Math.random()*tile.d);
					if (tile.c[charIndex] === undefined) charIndex = 0;
					html = html + (t_frame && t_frame['e'+e] ? t_frame['e'+e] : tile.c[charIndex]);
				}

                t_frame = undefined;
                t++;
            }
            html = html + '<br>';
        }
    }
    framesSinceLoad ++;
    return html;
}


