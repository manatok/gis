<?php
echo "<ul>";

foreach($data['files'] as $file) {
	echo "<li>".$file;
	if(strpos($file,".shp")) {
		echo "&nbsp;<a href='index.php?action=preview&fname=$file'>preview</a>&nbsp;|&nbsp;";
		echo "<a href='index.php?action=load&fname=$file'>load</a>";
	}
	echo "</li>";
}

echo "<ul>";