				<form class="languages" action="#" method="post">
				<p>
				<select name="languages_entry">
				<option value="-1">[default]</option>
				<loop languages>
				<option value="<id>"<if selected> selected</if selected>><title></option>
				</loop languages>
				</select>
				<input type="submit" name="languages_update" value="[update]" class="btnUpdate" />
				</p>
				</form>