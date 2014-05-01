<h3>You are going to load the following file:&nbsp<?php echo $data['fname'] ?>
<form method="POST">
	<table>
		<tr><td>Layer Name</td><td><input type="text" name="name" value="<?php echo $data['name'] ?>" /></td></tr>
		<tr><td>Set Reference</td><td>
			<select name="setref">
			<?php
			foreach($data['setReference'] as $reference) {
				echo "<option value='".$reference['name']."'>".$reference['name']." e.g ".$reference['val']."</option>";
			}

			?></select>
			</td>
		</tr>
		<tr><td colspan="2"><input type="submit" name="load" value="load" /></td></tr>
	</table>
</form>