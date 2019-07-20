<?php

$clientIP = $_SERVER['REMOTE_ADDR'];

$allowed = (array_search($clientIP, $allowedIPs) !== false);

if (!$allowed) { ?>

		<style>
			html{ padding:0px; margin:0px;overflow:hidden}
			* { font-family:lucida console, monospace; font-size:13px; cursor:default; line-height:<?php echo CHAR_HEIGHT; ?>px; }
			pre { font-family:inherit; font-size:inherit; }
			body
			{
				background-color:#333;
			}
			pre
			{
				color:#aaa;
			}
			input
			{
				border:none;
				height:13px;
				background-color:inherit;
				padding:0px;
				margin:0px;
				color:#afa;
			}

            #submit
            {
                background-color:#afa;
                color:#000;
				padding:<?php echo '0px ' . CHAR_WIDTH . 'px 0px ' . CHAR_WIDTH . 'px'; ?>;
            }
		</style>
	</head>
	<body>
		<pre>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2584;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;
&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;&nbsp;&nbsp;&#x2588;&nbsp;&nbsp;&nbsp;&#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2580;&nbsp;&nbsp;&#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2580;&nbsp;&#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2580;&nbsp;&nbsp;&nbsp;&nbsp;&#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2580;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;&nbsp;&nbsp;&#x2588;
&nbsp;&nbsp;&nbsp;&nbsp;&#x2584;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2584;&nbsp;&nbsp;&#x2588;&#x2588;&#x2580;&nbsp;&#x258c;&#x2580;&#x2588;&#x2588;&#x2588;&#x2580;&nbsp;&nbsp;&#x2590;&#x2588;&#x2580;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&#x2584;&#x2588;&#x258c;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2584;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2584;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2590;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2584;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&#x2580;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2580;&nbsp;&#x258c;&#x2580;&#x2588;&#x2588;&#x2588;&#x2580;
&nbsp;&nbsp;&#x2584;&#x2588;&#x2580;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2584;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2584;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x258c;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2584;&#x2588;&#x2580;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2590;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2580;&#x2588;&#x2584;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&nbsp;&#x258c;
&nbsp;&#x2590;&#x2588;&#x258c;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&#x2588;&#x2588;&#x2584;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x258c;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2590;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&#x2588;&#x2588;&#x2584;&nbsp;&#x258c;
&nbsp;&#x2588;&#x2588;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;&nbsp;&nbsp;&nbsp;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2590;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;
&#x2590;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x258c;&nbsp;&#x2580;&#x2588;&#x2588;&#x2584;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2584;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2590;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x258c;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x258c;&nbsp;&#x2580;&#x2588;&#x2588;&#x2584;
&#x2588;&#x2588;&#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x258c;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2590;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x258c;
&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&#x2584;&#x258c;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2590;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x258c;&nbsp;&nbsp;&#x2584;&#x258c;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;
&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&#x2588;&#x2580;&nbsp;&#x2590;&#x2588;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x258c;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2590;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&nbsp;&#x2588;&#x2580;&nbsp;&#x2590;&#x2588;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;
&#x2588;&#x2588;&#x2588;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&#x2588;&#x2584;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&#x2588;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&nbsp;&#x2590;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2588;&#x2584;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&nbsp;&nbsp;&#x2588;&#x2588;&#x2588;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2590;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&nbsp;&nbsp;&nbsp;&#x2588;&#x2584;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&#x2588;&#x2588;&#x2588;&#x258c;
&#x2590;&#x2588;&#x2588;&#x258c;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x2584;&#x258c;&#x2584;&#x2588;&#x2588;&#x2588;&#x2580;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2588;&#x2584;&#x2584;&#x2584;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&nbsp;&#x258c;&nbsp;&nbsp;&#x2590;&#x2588;&#x2580;&#x2584;&#x2584;&#x2588;&#x2584;&#x2584;&#x2584;&#x2584;&#x2584;&#x2584;&#x2588;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&#x258c;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2590;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2588;&#x2588;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&#x258c;&nbsp;&nbsp;&nbsp;&#x2580;&nbsp;&nbsp;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x2584;&#x258c;&#x2584;&#x2588;&#x2588;&#x2588;&#x2580;
&nbsp;&#x2588;&#x2588;&#x2588;&#x2584;&#x258c;&nbsp;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2580;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x2588;&#x2580;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2580;&nbsp;&nbsp;&#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2580;&nbsp;&nbsp;&#x2580;&#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2580;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2588;&#x2584;&#x258c;&nbsp;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;&nbsp;&#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;&nbsp;&nbsp;&nbsp;&#x2584;&#x2588;&#x2588;&#x2588;&#x2588;&#x258c;&nbsp;&#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;&#x2580;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2580;
&nbsp;&#x2590;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2590;&#x2588;&#x2588;&#x2588;&#x2588;&#x2584;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x2588;&#x2588;&#x2588;
&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2580;&#x2588;&#x2588;&#x2588;&#x258c;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2588;&#x2588;&#x258c;
&nbsp;&nbsp;&nbsp;&nbsp;&#x2580;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2580;&#x2588;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2588;&#x2580;

		</pre>
		<?php if (!isset($_POST['name'])) { ?>
		<span style="color:#faa;">Sorry, Asciilands is currently in closed alpha testing.</span>
		<br><br><span style="color:#ffa;">Please enter your name or email or something that will identify you to our admin.<br>
			Then press enter or click submit to request access from your IP to be enabled.</span>
		<br><br><form action="play.php" method="post">
			<span style="color:#afa">></span> <input id="name" name="name"/><br>
			<input type="submit" id="submit"/>
		</form>
	</body>
	<script type="text/javascript">
		document.getElementById('name').focus();
	</script>
</html>
	<head>
    </head>
	<body>
		<?php } else {

		$accessAttempts = file_get_contents("{$rootPath}accessAttempts.txt");
		$accessAttempts .= "\r\n{$_POST['name']} - {$clientIP}";
		file_put_contents('accessAttempts.txt', $accessAttempts);
		?>
		<span style="color:#afa;">Thankyou for your interest!</span><br><br>
		<span style="color:#aff;">Your IP and details have been submitted and should be enabled soon.</span>
	</body>
</html>
	<?php
	}
    EXIT();
}