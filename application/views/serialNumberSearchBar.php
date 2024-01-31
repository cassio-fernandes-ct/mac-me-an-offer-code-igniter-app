<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
* {
  box-sizing: border-box;
}

body {
  font: 16px Arial;  
}

/*the container must be positioned relative:*/
.autocomplete {
  position: relative;
  display: inline-block;
}

input {
  border: 1px solid transparent;
  background-color: #f1f1f1;
  padding: 10px;
  font-size: 16px;
}

input[type=text] {
  background-color: #f1f1f1;
  width: 100%;
}

input[type=submit] {
  background-color: DodgerBlue;
  color: #fff;
  cursor: pointer;
}

.autocomplete-items {
  position: absolute;
  border: 1px solid #d4d4d4;
  border-bottom: none;
  border-top: none;
  z-index: 99;
  /*position the autocomplete items to be the same width as the container:*/
  top: 100%;
  left: 0;
  right: 0;
}

.autocomplete-items div {
  padding: 10px;
  cursor: pointer;
  background-color: #fff; 
  border-bottom: 1px solid #d4d4d4; 
}

/*when hovering an item:*/
.autocomplete-items div:hover {
  background-color: #e9e9e9; 
}

/*when navigating through the items using the arrow keys:*/
.autocomplete-active {
  background-color: DodgerBlue !important; 
  color: #ffffff; 
}
</style>
</head>     
<body>


<!--Make sure the form has the autocomplete function switched off:-->
<form autocomplete="off" action="#">
  <div class="autocomplete" ID = "autocomplete" style="width:300px;">
    <input id="serial_search_bar" type="text" name="serial" placeholder="search" id = "serial_search_bar" class = "serial_search_bar">
  </div>
  <input style = "display: none;" type="submit">
</form>
<script src="https://app.macmeanoffer.com/assets/js/jquery.min.js" type="text/javascript"></script>
<script>
var base_url = "https://app.macmeanoffer.com/";

var typingTimer; 
	var doneTypingInterval = 1000; 
	var input_n = $('#serial_search_bar');
	input_n.on('keyup', function () { 
		clearTimeout(typingTimer);
		var search_val 	   = $(this).val();
	  	typingTimer = setTimeout(doneTyping, doneTypingInterval);
	});

	input_n.on('keydown', function () {
	  clearTimeout(typingTimer);
	});

	function doneTyping(){
		var serial = '';
		var serial = $("#serial_search_bar").val();

		var data = 'search='+serial;
		
		if(serial != '' && serial.length > 2)
		{
			$.ajax({
				url: base_url+'SerialNumberSearchBar/getserialproduct',
				type: 'post',
				data: data,
				success: function( response ){
					$(".autocomplete-items").remove();
					var responseObj = jQuery.parseJSON(response);
					a = document.createElement("DIV");
					a.setAttribute("id", this.id + "autocomplete-list");
					a.setAttribute("class", "autocomplete-items");
					document.getElementById("serial_search_bar").parentNode.appendChild(a);
					$(".autocomplete-items").append( responseObj.html );

				},  
			});
		}else{
			$(".autocomplete-items").remove();
		}
	}

</script>

</body>
</html>