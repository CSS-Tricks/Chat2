$(function() {
    		 
    $("#sendie").keydown(function(event) {  
    
        var key = event.which;  
   
         // all keys including return 
         if (key >= 33) {  
         
             var maxLength = $(this).attr("maxlength");  
             var length = this.value.length;  
             
             if (length >= maxLength) {  
                 event.preventDefault();  
             }  
         }  
																																																			});
			 
        $('#sendie').keyup(function(e) {	
        					 
            if (e.keyCode == 13) { 
            
                var text = $(this).val();
                var maxLength = $(this).attr("maxlength");  
                var length = text.length; 
                 
                if (length <= maxLength + 1) {  
                    chat.send(text, name);	
                    $(this).val("");
                } else {
                    $(this).val(text.substring(0, maxLength));
                }	
            
            }
            
        });
            
});