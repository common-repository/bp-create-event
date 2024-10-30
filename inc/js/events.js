jQuery(document).ready(function () {
jQuery("#event-date").datepicker({
       minDate: 0,
       maxDate: "+60D",
        numberOfMonths: 2,
	        onSelect: function(selected) {
          jQuery("#end-date").datepicker("option","minDate", selected)
	        }
	    });
	    jQuery("#end-date").datepicker({
	        minDate: 0,
	        maxDate:"+60D",
	        numberOfMonths: 2,
	        onSelect: function(selected) {
	           jQuery("#event-date").datepicker("option","maxDate", selected)
	        }
    });  
	  

jQuery('#event-time').timepicker({
		showPeriod: true,
		showLeadingZero: false

});
jQuery("#single_event").click(function () {
            if (jQuery(this).is(":checked")) {
                jQuery("#end_part").hide();
            } else {
                jQuery("#end_part").show();
            }
        });

jQuery('.img-wrap .close').on('click', function() {
    var id = jQuery(this).closest('.img-wrap').find('a').data('id');
   
   // alert(id);
    jQuery.ajax({
    	type: 'get',
        url: ajaxurl,
        data: {
            'action':'request',
            'id' : id
        },
        success:function(data) {
            // This outputs the result of the ajax request
            //alert(data);
             jQuery('#img-'+id).fadeOut();   
           // console.log(data);
        },
       
    });   
});
});
function initialize() {
	var input = document.getElementById('event-location');
	var autocomplete = new google.maps.places.Autocomplete(input);
	google.maps.event.addListener(autocomplete, 'place_changed', function () {
		var place = autocomplete.getPlace();
		console.log(place);
		var lat = place.geometry.location.lat();
		var lng = place.geometry.location.lng();
		var latlng = lat + ',' + lng;
		//document.getElementById('event-place').value = JSON.stringify(place);
		if( place.formatted_address.indexOf( place.name ) > -1 )
			document.getElementById('event-address').value = place.formatted_address;
		else
			document.getElementById('event-address').value = place.name + ', ' + place.formatted_address;
		document.getElementById('event-latlng').value = latlng;
	});
}

google.maps.event.addDomListener(window, 'load', initialize);

