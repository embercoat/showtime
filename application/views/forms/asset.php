<div id="add_asset" class="popup">
	<div class="settings">
		<a href="#" class="end icon close_popup">&#10006;</a>
		<fieldset>
			<form id="form_add_asset" enctype="multipart/form-data" method="post" action="#">
    			<input id="asset_id" type="hidden" name="asset_id" />
    			<label for="asset_name">Namn</label>
    			<input id="asset_name" name="asset_name" />
    			<br />
    			<label for="asset_mimetype">Typ</label>
    			<select id="asset_mimetype" name="asset_mimetype">
        			<option value="image">Bild</option>
        			<option value="webpage">Webbsida</option>
        			<option value="video">Video</option>
				<option value="livestream">Livestream</option>
    			</select>
    			<br />
    			<div id="asset_uri_div" style="display: none;">
        			<label for="asset_uri">Adress</label>
        			<input id="asset_uri" name="asset_uri" />
    			</div>
    			<div id="asset_file_div">
        			<label id="label_asset_file" for="asset_file">Fil</label>
        			<input id="asset_file" type="file" name="asset_file" />
    			</div>
    			<br />
    			<button id="asset_save">Spara</button>
			</form>
		</fieldset>
	</div>
</div>
