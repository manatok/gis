<table border="1" cellpadding="5">
	<tr>
		<th>&nbsp;</th>
		<th>type</th>
		<th>minx</th>
		<th>miny</th>
		<th>maxx</th>
		<th>maxy</th>
		<th>meta</th>
	</tr>
<?php

if(isset($data)) {
	foreach($data as $i => $record) {
		echo "<tr>";
		echo "<td>".$i."</td>";
		echo "<td>".$record['type']."</td>";
		echo "<td>".$record['minx']."</td>";
		echo "<td>".$record['miny']."</td>";
		echo "<td>".$record['maxx']."</td>";
		echo "<td>".$record['maxy']."</td>";
		echo "<td><pre>".var_export($record['meta'],1)."</pre></td>";
		echo "</tr>";
	}
}
?>
</table>