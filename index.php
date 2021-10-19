<?php
define('HOSTNAME_FORMAT', 'local');
$query = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_SPECIAL_CHARS);

if ($query === 'phpinfo')
{
	phpinfo();
	exit;
}

/**
 * Test if the folder isn't a file or a masked dir (with '.' detection)
 *
 * @param string $folder Foldername
 * @return boolean
 */
function isFolder(string $folder): bool
{
	return !str_contains($folder, '.') && !empty($folder) && !is_file($folder);
}

/**
 * Echo the link that correspond the query
 *
 * @param string $folder Foldername
 * @param string|null $query "q" GET variable
 * @return void
 */
function displayLink(string $folder, ?string $query)
{	
	switch ($query)
	{
		case 'notVhosts':
			// Without virtual hosts
			echo "<a class=\"website\" target=\"_blank\" href=\"$folder\"><h2>" . 'localhost/' . $folder . '</h2></a>' . PHP_EOL;
			break;
		case null:
			// Default display
			echo "<a class=\"website\" target=\"_blank\" href=\"http://$folder." . HOSTNAME_FORMAT . '/"><h2>' . "$folder." . HOSTNAME_FORMAT . '</h2></a>' . PHP_EOL;
			break;
		default:
			// Redirect to the default localhost page if the query is not correct
			header('Location: http://localhost');
			exit;
			break;
	}
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="author" content="Alexandre Guillaume">
		<meta name="description" content="Laragon personalized homepage">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet"> 
		<title>Largon homepage</title>
		<style>
			body {
				background-color: #212121;
			}
			h1, h2, h3, p, a {
				font-family: 'Righteous', cursive;
				text-align: center;
				color: white;
			}
			a {
				color: #75ff4c;
				text-decoration: none;
			}
			.button :hover {
				color: #32ff00;
			}
			.website h2:hover {
				background-color: gray;
			}
			.error {
				color: red;
			}
			fieldset {
				width: 25%;
				margin-left: auto;
				margin-right: auto;
				margin-bottom: 30px;
			}
			legend {
				color: white;
			}
		</style>
	</head>
	<body>
		<header>
			<h1>LARAGON HOMEPAGE</h1>
		</header>
		<main>
			<fieldset>
			<legend><h1>Websites</h1></legend>
			<?php
			$handle = opendir('.');

			do
			{
				// File/Folder name
				$folder = readdir($handle);

				if(isFolder($folder))
				{				
					// Link to the project
					displayLink($folder, $query);
				}
			}
			while(!empty($folder));

			closedir($handle);
			?>
			</fieldset>
			<h3>Server Version: <?php echo $_SERVER['SERVER_SOFTWARE']; ?></h3>
			<h3>PHP Version: <?php echo phpversion(); ?></h3>
			<h3>Document Root: <?php echo $_SERVER['DOCUMENT_ROOT']; ?></h3>
			<h3 class="button"><a href="/?q=phpinfo" target="_blank">phpinfo()</a></h3>
			<h3 class="button"><a href="/phpmyadmin" target="_blank">phpmyadmin</a></h3>
			<?php
			if ($query === 'notVhosts')
			{
				echo '<h3 class="button"><a href="/">Enable Vhosts</a></h3>' . PHP_EOL;
			}
			else
			{
				echo '<h3 class="button"><a href="/?q=notVhosts">Disable Vhosts</a></h3>' . PHP_EOL;
			}
			?>
		</main>
		<footer>
			<h2>Copyright &copy; - <a target="_blank" href="https://www.github.com/zayoxy">@Zayoxy</a></h2>
		</footer>
	</body>
</html>