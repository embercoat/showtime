showtime = {
	assets : new Array(),
	data : null,
	add_playlist: function(id){
	},
	get_assets: function(){
		$.ajaxSetup({async: false});
		var d;
		$.getJSON("/json/get_assets", function(data){
			d = data;
		});
		return d;
	},
	playlists : new Array(),
	update_playlist : function(data){
		$.post('/json/save_playlist', data);
	},
	get_playlists: function(id){
		id = typeof id !== 'undefined' ? id : '';
		
		$.ajaxSetup({async: false});
		var d;
		$.getJSON("/json/get_playlists/"+id, function(data){
			d = data;
		});
		return d;
	},
	get_playlist_assets : function(id){
		$.ajaxSetup({async: false});
		var d;
		$.getJSON("/json/get_playlist_assets/"+id, function(data){
			d = data;
		});
		return d;
	},
	playlist_add_asset : function(playlist, asset){
		$.post('/json/playlist_add_asset', { 'asset' : asset, 'playlist' : playlist});
	},
	playlist_remove_asset : function(playlist, asset){
        $.post('/json/playlist_remove_asset', { 'asset' : asset, 'playlist' : playlist});
	},
	playlist_update_assets : function(data){
		$.post('/json/playlist_update_assets', data);
	},
	playlist_get_schedule : function(id){
		var d;
		$.getJSON("/json/get_playlist_schedule/"+id, function(data){ d = data; });
		return d;
	},
    playlist_set_schedule_attribute : function(schedule_id, attribute, value){
	    $.post('/json/set_playlist_schedule_attribute', { 'schedule_id' : schedule_id, 'attribute' : attribute, 'value' : value});
	},
    playlist_schedule_add_new_time : function(playlist_id){
        console.log('Adding new schedule time to: '+playlist_id);
	    $.post('/json/add_playlist_schedule_time', { 'playlist_id' : playlist_id});
    },
	update_playlist_asset_duration : function(playlist_id, asset_id, duration) {
		$.post('/json/update_playlist_asset_duration', { 'asset' : asset_id, 'playlist' : playlist_id, 'duration' : duration });
	},
	playlist_remove : function(playlist){
		$.post('/json/playlist_remove/'+playlist, {});
	},
    playlist_schedule_remove : function(schedule_id){
		$.post('/json/playlist_schedule_remove/'+schedule_id, {});
	},

	get_devices : function(){
		$.ajaxSetup({async: false});
		var d;
		$.getJSON("/json/get_devices/", function(data){ d = data; });
		return d;
	},
	device_update_name : function(device, name){
		$.post('/json/device_update_name', { 'device' : device, 'name' : name});
	},
	device_add_playlist : function(device, playlist) {
		$.post('/json/device_add_playlist', { 'device' : device, 'playlist' : playlist});
	},
	device_remove_playlist : function(device, playlist) {
		$.post('/json/device_remove_playlist', { 'device' : device, 'playlist' : playlist});
	},
	device_get_playlists : function(device){
		$.ajaxSetup({async: false});
		var d;
		$.getJSON("/json/get_device_playlists/"+device, function(data){ d = data; });
		return d;
	}
};

window.showtime = showtime;