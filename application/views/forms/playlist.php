<div id="add_playlist" class="popup">
	<div class="settings">
		<a href="#" class="end icon close_popup">&#10006;</a>
		<fieldset>
			<form id="form_add_playlist" action="#">
				<input id="playlist_id" type="hidden" name="playlist_id" value="" />
				<label for="playlist_name">Namn</label>
				<input id="playlist_name" name="playlist_name" />
				<br />
				<button id="playlist_save" onclick="javascript: return false">Spara</button>
			</form>
            <ul>
                <li style="width: 105px">Start Veckodag</li>
                <li style="width: 105px">Starttid</li>
                <li style="width: 105px">Slut Veckodag</li>
                <li style="width: 105px">Sluttid</li>
                <li style="width: 105px">Prioritet</li>
            </ul>
			<div id="playlist_schedule"></div>
			<button id="add_playlist_schedule_time">Lägg till tid</button>
		</fieldset>
		<button id="playlist_delete_button" onclick="delete_current_playlist();">Radera Spellista</button>
	</div>
</div>