function digitalcodeInit(selector, digit, digit_type = "number"){
	var digit_html =  '<div  class="digit-group" data-length = "'+ digit +'" data-type = "'+ digit_type +'">';
		for(var i = 0; i < digit; i++){
			if(i == digit/2)
				digit_html += '<span class="splitter">&ndash;</span>'; 
			if(i == 0)
				digit_html += '<input type="text" autocomplete="off" class="digit-1" name="code_digit" data-next="digit-2" />';
			else if(i == (digit-1))
				digit_html += '<input type="text" autocomplete="off" class="digit-'+ (i+1) +'" name="code_digit" data-previous="digit-' + i + '" />';
			else
				digit_html += '<input type="text" autocomplete="off" class="digit-'+ (i+1) +'" name="code_digit" data-next="digit-'+ (i + 2) +'" data-previous="digit-' + i + '"  />';			
		}
	digit_html += '</div>'; 
	$(selector).html(digit_html); 
	$('.digit-group').find('input').each(function() {
		$(this).attr('maxlength', 1); 
		var input_type = $(this).closest(".digit-group").attr('data-type'); 
		$(this).on('keypress', function(e) {
			var flag  = 1; 
			if(input_type == "number"){
				if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39 || e.keyCode === 8 || e.keyCode === 37)
					flag = 0;
			}
			else{
				if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39 || e.keyCode === 8 || e.keyCode === 37)
					flag = 0;
			} 
			if(flag)
				return false; 
		}); 
		$(this).on('keyup', function(e) {
			var parent = $($(this).parent()); 
			if(e.keyCode === 8 || e.keyCode === 37) {
				var prev = parent.find('input.' + $(this).attr('data-previous')); 
				if(prev.length) {
					$(prev).select();
				}
			}
			else{
				var flag  = 1; 
				if(input_type == "number"){
					if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39)
						flag = 0;
				}
				else{
					if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39)
						flag = 0;
				}
				if( !flag ) {
					var next = parent.find('input.' + $(this).attr('data-next'));  
					if(next.length) {
						$(next).select();
					} else {
						if(parent.data('autosubmit')) {
							parent.submit();
						}
					}
				}
			} 
		}); 
	});
}

function getDigitCodeValue(selector, need_splitter = 0){ 
	var result_string = "";
	var digit_length = $(selector).find(".digit-group").attr("data-length"); 
	var digit_count = 0;	
	$(selector).find('.digit-group input').each(function(){
		digit_count++;
		result_string += $(this).val(); 
		if(need_splitter)
			if(digit_count == Math.floor(digit_length/2))
				result_string += "-";
	});
	return result_string;
} 
function validateDigitalCodeValue(selector){
	var digit_length = $(selector).find(".digit-group").attr("data-length");
	var flag = 1;
	$(selector).find('.digit-group input').each(function(){
		if($(this).val() == "")
			flag = 0;
	});
	return flag;
}