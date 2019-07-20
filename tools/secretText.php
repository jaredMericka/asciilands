<html>
	<head>
		<script>
			function secrify(element)
			{
				var secretText = element.innerHTML.split('');
				element.innerHTML = '';

				for (var index in secretText)
				{
					var word = secretText[index];
					var span = document.createElement('span');
					span.id = index;
					span.class = secret;
					span.innerHTML = word;
				}
			}

			function reveal(wordSpan)
			{
				var id = parseInt(wordSpan.id);
			}
		</script>

		<style>
			body
			{
				width:1000px;
			}

			secret
			{
				color:transparent;
				text-shadow: 10 10 10 black;
			}

			reveal
			{
				color:black;
				text-shadow:none;
			}
		</style>

	</head>
	<body>
		<div onload="secrify(this);">
			The quick brown fox jumps over the lazy dog. Jackdaws love my big sphynx of quarts. The quick brown fox jumps over the lazy dog. Jackdaws love my big sphynx of quarts. The quick brown fox jumps over the lazy dog. Jackdaws love my big sphynx of quarts. The quick brown fox jumps over the lazy dog. Jackdaws love my big sphynx of quarts. The quick brown fox jumps over the lazy dog. Jackdaws love my big sphynx of quarts. The quick brown fox jumps over the lazy dog. Jackdaws love my big sphynx of quarts. The quick brown fox jumps over the lazy dog. Jackdaws love my big sphynx of quarts. The quick brown fox jumps over the lazy dog. Jackdaws love my big sphynx of quarts. The quick brown fox jumps over the lazy dog. Jackdaws love my big sphynx of quarts. The quick brown fox jumps over the lazy dog. Jackdaws love my big sphynx of quarts. The quick brown fox jumps over the lazy dog. Jackdaws love my big sphynx of quarts. The quick brown fox jumps over the lazy dog. Jackdaws love my big sphynx of quarts.
		</div>
	</body>
</html>