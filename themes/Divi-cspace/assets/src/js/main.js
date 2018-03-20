jQuery(function($){
	$('#filter').change(function(){
		var filter = $('#filter');
		$.ajax({
			url:filter.attr('action'),
			//url : ajax_object.ajax_url,
			data:filter.serialize(), // form data
			type:filter.attr('method'), // POST
			beforeSend:function(xhr){
				//filter.find('button').text('Processing...'); // changing the button label
			},
			success:function(data){
				//filter.find('button').text('Apply filter'); // changing the button label back
                $('.auto-layout').html(data); // insert data
                //console.log(data);
			}
		});
		return false;
	});

//url parse
// var parser = document.createElement('a');
// parser.href = window.location;

//console.log(parser.pathname);

// parser.protocol; // => "http:"
// parser.hostname; // => "example.com"
// parser.port;     // => "3000"
// parser.pathname; // => "/pathname/"
// parser.search;   // => "?search=test"
// parser.hash;     // => "#hash"
// parser.host;     // => "example.com:3000"


});