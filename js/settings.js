$(document).ready(function(){
	$('#settings_content input[type=checkbox]').change(function(){
            device_id = $(this).attr('id').split('_')[2];
            playlist_id = $(this).attr('id').split('_')[3];
            console.log('Device id: ' + device_id);
            console.log('Playlist id: ' + playlist_id);
            if (this.checked){
                showtime.device_add_playlist(device_id, playlist_id);
            } else {
                showtime.device_remove_playlist(device_id, playlist_id);
            }
            popup('green', 'Success')
        });
});