$(document).ready(function() {

		function GetURLParameter(sParam){

    	var sPageURL = window.location.search.substring(1);
    	var sURLVariables = sPageURL.split('&');
    	for (var i = 0; i < sURLVariables.length; i++){
        	
    		var sParameterName = sURLVariables[i].split('=');
    		if (sParameterName[0] == sParam) {
        
        		return sParameterName[1];
    	
    		}    		
    	}
	}

	var id = GetURLParameter('id');

	var url = "api/topics/" + id + "/decks";

	$.ajax({ 
	   type: "GET",
	   dataType: "json",
	   url: url,
	   success: function(data){        
	     
	     var build = "";

	     $.each(data.decks, function(index,value) {

	     	build += "<div class='col-md-4'>";
          	build += "<h2>" + value.deck_name + "</h2>";
          	build += "<p><a class='btn btn-default' href='view-topic.html?id=" + value.deck_id + "' role='button'>Start »</a></p>";
        	build += "</div>";

	     });

	     var html = $("div#decks").html();

	     $("div#decks").html(build + html);

	   }
	});

	url = "api/topics/" + id;

	$.ajax({ 
	   type: "GET",
	   dataType: "json",
	   url: url,
	   success: function(data){        

	   	$("h1").html(data.topic_name + " Decks");

	   }
	});


});