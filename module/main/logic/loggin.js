function formhash() {
	form 	= document.getElementById("logForm");
	password= form.password;
    // Erstelle ein neues Feld für das gehashte Passwort. 
    var ph = document.createElement("input");
 
    // Füge es dem Formular hinzu. 
    form.appendChild(ph);
    ph.name = "ph";
    ph.type = "hidden";
    ph.value = hex_sha512(password.value);
 
    // Sorge dafür, dass kein Text-Passwort geschickt wird. 
    password.value = "";
 
    if(form.checkValidity()) {
    	//form.submit();
    	 return true;
    }
}

function regformhash() {
	form 	= document.getElementById("regForm");
	uid 	= form.username
	email	= form.email;
	password= form.password;
	conf	= form.confirmPassword
	
     // Überprüfe, ob jedes Feld einen Wert hat
    if (uid.value == ''         || 
          email.value == ''     || 
          password.value == ''  || 
          conf.value == '') {
 
        alert('You must provide all the requested details. Please try again');
        return false;
    }
    // Überprüfe den Benutzernamen
 
    re = /^\w+$/; 
    if(!re.test(form.username.value)) { 
        alert("Username must contain only letters, numbers and underscores. Please try again"); 
        form.username.focus();
        return false; 
    }

    // Überprüfe, dass Passwort lang genug ist (min 6 Zeichen)
    // Die Überprüfung wird unten noch einmal wiederholt, aber so kann man dem 
    // Benutzer mehr Anleitung geben
    if (password.value.length < 8) {
        alert('Passwords must be at least 8 characters long.  Please try again');
        form.password.focus();
        return false;
    }

    // Mindestens eine Ziffer, ein Kleinbuchstabe und ein Großbuchstabe
    // Mindestens sechs Zeichen 
 
    var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/; 
    if (!re.test(password.value)) {
        alert('Passwords must contain at least one number, one lowercase and one uppercase letter.  Please try again');
        return false;
    }

    // Überprüfe die Passwörter und bestätige, dass sie gleich sind
    if (password.value != conf.value) {
        alert('Your password and confirmation do not match. Please try again');
        form.password.focus();
        return false;
    }

    // Erstelle ein neues Feld für das gehashte Passwort.
    var ph = document.createElement("input");

    // Füge es dem Formular hinzu. 
    form.appendChild(ph);
    ph.name = "ph";
    ph.type = "hidden";
    ph.value = hex_sha512(password.value);

    
    
    // Sorge dafür, dass kein Text-Passwort geschickt wird. 
    password.value = "";
    conf.value = "";
 
   
    // Reiche das Formular ein.  
    if(form.checkValidity()) {
    	form.submit();
    	 return true;
    }

}