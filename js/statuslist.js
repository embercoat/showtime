$(document).ready(function(){
    console.log('Statuslist: Loaded');
    $('tr.device').each(function(){
        id = $(this).children('.status').attr('id');
        device_id = id.split('_')[2];
        $.getJSON('/json/get_device_status/'+device_id, function(data){
            if (data['Status'] == "OK") {
                $('#device_status_'+data['Device_id']).html(data['Status']);
                
            } else if (data['Status'] == "Problem"){
                // Device Problem
                $('#device_status_'+data['Device_id']).html(data['Status']+': '+data['processes']);
                
            } else if (data['Status'] == "Error"){
                // Device Error
                $('#device_status_'+data['Device_id']).html(data['Status']+': '+data['Error']);
            } else {
                //Unkown result
                
            }
            console.log("Inside: "+data['Device_id']);
            console.log(data['Status']);
        });
    });
});

