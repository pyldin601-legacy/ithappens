// document.body.onload = setInterval("navLink('/presence.php')", 60000);


function navLink(link) {  

         
    var http = createRequestObject();  
    if( http ) {  
        http.open('get', link);  
        http.onreadystatechange = function () {  
            if(http.readyState == 4) {
				
            }  
        }  
        http.send(null);      
    } else {  
        document.location = link;  
    }  
}  

function createRequestObject() {  
    try 
	{ return new XMLHttpRequest() 
	} catch(e) {
        try {
			return new ActiveXObject('Msxml2.XMLHTTP');
		} catch(e) {  
            try {
				return new ActiveXObject('Microsoft.XMLHTTP');
			} catch(e) { 
				return null;
			}  
        }  
    }  
}