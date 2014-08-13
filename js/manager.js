function build_assets(){
	var assets = [];
	$.each(window.showtime.get_assets(), function( key, val ) {
		var link = '';
		var endlink = '';
		if(val['mimetype'] == 'image' || val['mimetype'] == 'video'){
			link = '<a href="/file/get/'+val['uri']+'">';
			endlink = '</a>';
		} else {
			link = '<a href="'+val['uri']+'">';
			endlink = '</a>';
		}

		assets.push( 
				'<ul id="asset_'+ val['id'] + '">'
				+'<li class="mime_'+val['mimetype']+'">'+link+val['name']+endlink+'</a></li>'
				+'<li><a href="/settings/delete_asset/'+ val['id'] + '" class="img_remove_asset icon" title="Ta bort">&#8854;</a></li>'
				+'<li><a href="#" class="icon img_add_asset_playlist" title="Lägg till">&#59226;</a></li>'
				+'</ul>'
				);
		
	});
	$('#assets_container').html('').append(assets.join(''));

	$('.img_add_asset_playlist').click(function(){
		if($('#playlists ul.now').length == 1){
			asset_id = $(this).parent().parent().attr('id').replace('asset_', '');
			playlist_id = $('#playlists ul.now').attr('id').replace('playlist_', '');
			window.showtime.playlist_add_asset(playlist_id, asset_id);
			build_playlist_assets(playlist_id);
			popup('green', 'Success')
		}
	})
}
function get_active_playlist_id(){
	return $('#playlists ul.now').attr('id').replace('playlist_', '');
}
function build_playlists(){
	var playlists = [];
	$.each(window.showtime.get_playlists(), function( key, val ) {
		playlists.push(
			'<ul id="playlist_'+ val['idplaylist']+'">'
			+'<li><a href="#">'+val['name']+'</a></li>'
			+'<li><a href="#" class="icon playlist_settings">&#9881;</a></li>'
			+'</ul>'
			);
	});
	$('#playlists').html(playlists.join( "" ));
	
	$.each($('#playlists ul'), function(){
		$(this).click(function(){
			$('#playlists ul').removeClass('now');
			$(this).addClass('now');
			build_playlist_assets($(this).attr('id').replace('playlist_', ''));
			
		});
	});

	$('.playlist_settings').click(function(){
		playlist_id = $(this).parents('ul').attr('id').replace('playlist_', '');
		data = showtime.get_playlists(playlist_id)[0];

		document.getElementById('playlist_id').value = playlist_id;
		document.getElementById('playlist_name').value = data.name;
		
		var schedule = showtime.playlist_get_schedule(playlist_id);
		var list = [];
		$.each(schedule, function(i, s){
			console.log(s);
			list.push(
                '<ul id="schedule_'+ s.playlist_schedule_id+'">'
                +'<li>'
                    +'<select name="startdayofweek" class="playlist_schedule" style="width: 100px; margin: 0; margin-left: -5px; border: none;">'
                        +'<option value="1" '+((s.startdayofweek == 1) ? 'selected': '')+'>Måndag</option>'
                        +'<option value="2" '+((s.startdayofweek == 2) ? 'selected': '')+'>Tisdag</option>'
                        +'<option value="3" '+((s.startdayofweek == 3) ? 'selected': '')+'>Onsdag</option>'
                        +'<option value="4" '+((s.startdayofweek == 4) ? 'selected': '')+'>Torsdag</option>'
                        +'<option value="5" '+((s.startdayofweek == 5) ? 'selected': '')+'>Fredag</option>'
                        +'<option value="6" '+((s.startdayofweek == 6) ? 'selected': '')+'>Lördag</option>'
                        +'<option value="7" '+((s.startdayofweek == 7) ? 'selected': '')+'>Söndag</option>'
                    +'</select>'
                +'</li>'
                +'<li><input type="text" value="'+s.starttime+'" style="width: 100px; margin: 0; border: none;" class="playlist_schedule" name="starttime" /></li>'
                +'<li>'
                    +'<select name="enddayofweek" class="playlist_schedule" style="width: 100px; margin: 0; margin-left: -5px; border: none;">'
                        +'<option value="1" '+((s.enddayofweek == 1) ? 'selected': '')+'>Måndag</option>'
                        +'<option value="2" '+((s.enddayofweek == 2) ? 'selected': '')+'>Tisdag</option>'
                        +'<option value="3" '+((s.enddayofweek == 3) ? 'selected': '')+'>Onsdag</option>'
                        +'<option value="4" '+((s.enddayofweek == 4) ? 'selected': '')+'>Torsdag</option>'
                        +'<option value="5" '+((s.enddayofweek == 5) ? 'selected': '')+'>Fredag</option>'
                        +'<option value="6" '+((s.enddayofweek == 6) ? 'selected': '')+'>Lördag</option>'
                        +'<option value="7" '+((s.enddayofweek == 7) ? 'selected': '')+'>Söndag</option>'
                    +'</select>'
                +'</li>'
                +'<li><input type="text" value="'+s.endtime+'" style="width: 100px; margin: 0; border: none;" class="playlist_schedule" name="endtime" /></li>'
                +'<li><input type="text" value="'+s.priority+'" style="width: 100px; margin: 0; border: none;" class="playlist_schedule" name="priority" /></li>'
                +'<li style="width:20px"><a class="icon playlist_schedule_remove" href="#">&#8854;</a></li>'
                +'</ul>'
            );
		});


        
		$('#add_playlist').show();
		$('#playlist_schedule').html(list.join(''));
        $('.playlist_schedule').change(function(){
            schedule_id = $(this).parents('ul').attr('id').split('_')[1];
            attribute = $(this).attr('name');
            value = $(this).attr('value');
            console.log('Update schedule. ID: '+schedule_id+', Attribute: '+attribute+', Value '+value);
            console.log($(this));
            window.debug = $(this);
            showtime.playlist_set_schedule_attribute(schedule_id, attribute, value)
            popup('green', 'Success')
        });
        $('.playlist_schedule_remove').click(function(){
            schedule_id = $(this).parents('ul').attr('id').split('_')[1];
            showtime.playlist_schedule_remove(schedule_id);
            $('#playlists ul.now').children()[1].children[0].click();

        });

	});
}

function build_playlist_assets(id){
	assets = showtime.get_playlist_assets(id);
	var list = [];
	$.each(assets, function(i, asset){
		list.push(
		'<div class="list clear" id="playlist_asset_'+id+'_'+asset.id+'">'
			+'<div class="del"><a href="#" class="icon remove_playlist_asset">&#8854;</a></div>'
			+'<div class="order">'+ asset.sortorder +'</div>'
			+'<div class="name">'+ asset.name +'</div>'
			+'<div class="type">'+ asset.mimetype +'</div>'
			+'<div class="duration"><input type="text" value="'+asset.duration+'" style="width: 100px; margin: 0; border: none;" class="playlist_asset_duration" /></div>'
		+'</div>'
		);
	});
	$('#playlist_assets').html(list.join(''));
	$('#playlist_assets').sortable({helper: fixHelper, beforeStop: beforestop }).disableSelection();
	$('.playlist_asset_duration').change(function(){
		playlist_id = $(this).parents('div.list').attr('id').split('_')[2];
		asset_id = $(this).parents('div.list').attr('id').split('_')[3];
		console.log('Update duration. Asset: '+asset_id+', Playlist: '+playlist_id);
		showtime.update_playlist_asset_duration(playlist_id, asset_id, this.value);
		popup('green', 'Success')
		
	});
	$('.remove_playlist_asset').click(function(){
		playlist_id = $(this).parents('div.list').attr('id').split('_')[2];
		asset_id = $(this).parents('div.list').attr('id').split('_')[3];
		showtime.playlist_remove_asset(playlist_id, asset_id);
		build_playlist_assets(playlist_id);
		popup('green', 'Success')
	});
	
	
}
function beforestop(event, ui) {
	var playlist_assets = {};
	$.each($(ui.item.parent()[0].children).not('.ui-sortable-placeholder'), function(i, child){
		id = child.id.split('_');
		playlist = id[2];
		asset = id[3];
		playlist_assets[i] = {"playlist":playlist, "asset":asset, "sortorder":i+1, "duration":$(child).find('input').attr('value')};
		popup('green', 'Success')
	});
	showtime.playlist_update_assets(playlist_assets);
	$('#playlists .now').click();
}
function delete_current_playlist(){
	id = $('#playlists ul.now').attr('id').replace('playlist_', '');
	name = $('#playlist_'+id).children('li').children('a').first().html();

	if(confirm('Vill du verkligen ta bort "'+name+'"?')) {
		showtime.playlist_remove(id);
		build_playlists();
		select_first_playlist();
		popup('green', 'Success');
		$('#add_playlist').hide();
		document.getElementById('form_add_playlist').reset();
	}
}

function get_form_data(form_id){
	var data = {};
	elements = document.getElementById(form_id).elements
	for(i=0; i<elements.length; i++) {
		var data;
		if(elements[i].type == 'checkbox'){
			data[elements[i].name] = (elements[i].checked == true) ? 1 : 0;
		}
		else {
			data[elements[i].name] = elements[i].value;
		}

	}
	return data;
}
function select_first_playlist(){
	$('#playlists ul').first().click();
} 
var fixHelper = function(e, ui) {
	ui.children().each(function() {
		$(this).width($(this).width());
	});
	return ui;
};
$(document).ready(function(){
	build_assets();
	build_playlists();
	select_first_playlist();
	
	$('.img_add_playlist').click(function(){
		console.log('hi');
		document.getElementById('form_add_playlist').reset();
		document.getElementById('playlist_id').value = '';
		$('#add_playlist').show();
	});
	// Connect all click handlers to their functions.
	$('#asset_mimetype').change(function(){
		if(this.value == 'webpage' || this.value == 'rtmp' || this.value == 'livestream'){
			$('#asset_file_div').hide();
			$('#asset_uri_div').show();
		} else {
			$('#asset_file_div').show();
			$('#asset_uri_div').hide();
		}
	});
	
	$('#playlist_save').click(function(){
		window.showtime.update_playlist(get_form_data('form_add_playlist'));
		build_playlists();
		$('#add_playlist').hide();
		document.getElementById('form_add_playlist').reset();
		popup('green', 'Success')
	});
	$('#img_add_asset').click(function(){
		$('#add_asset').show();
	});
	$('#form_add_asset').ajaxForm(function(){
		popup('green', 'Success')
	})

	$('#asset_save').click(function(){
		var	oData = new FormData(document.forms.namedItem("form_add_asset"));
		var oReq = new XMLHttpRequest();
		oReq.open('post', '/json/save_asset');
		oReq.onload = function(oEvent) {
			if (oReq.status == 200) {
				console.log("Uploaded!");
				$("#add_asset").hide();
				document.getElementById('form_add_asset').reset();
				build_assets();
				popup('green', 'Success')
			} else {
			    console.log("Error " + oReq.status + " occurred uploading your file");
			}
		};
		oReq.send(oData);
		
	});
	$('.close_popup').click(function(){
		$(this).parents('.popup').hide();
	});
	$('#device_list').click(function(){
		var list = [];
		var playlists = showtime.get_playlists();
		$.each(showtime.get_devices(), function(){
			var device_id = this.device_id;
			var device_playlists = showtime.device_get_playlists(device_id);
			var str = '<ul>'
						+'<li><input type="text" id="device_'+device_id+'" class="device_name" value="'+this.name+'" /></li>'
						+'<li>'+this.address+'</li>'
					  +'</ul>';
			str = str+'</td></tr>';
			list.push(str);
		});
		$('#devices_list_list').html(list.join(''));
		$('#devices').show();
		$('.device_name').change(function(){
			device_id = $(this).attr('id').replace('device_', '');
			showtime.device_update_name(device_id, this.value);
			popup('green', 'Success')
		});
	});
    $('#add_playlist_schedule_time').click(function(){
        playlist_id = $('#playlist_id').attr('value');
        console.log('Adding new schedule time to: '+playlist_id);
        showtime.playlist_schedule_add_new_time(playlist_id);
        console.trace();
        $('#playlists ul.now').children()[1].children[0].click();
        
    });


});
