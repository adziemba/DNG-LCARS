/**
 * 
 */
document.observe('dom:loaded', function () {
	Element.hide($('bodyframe'));
	
	new Effect.Appear('bodyframe', {duration: 0.3});

});

function test() {
	node = $("first");
	node.innerHTML = "NODE.JS Test function";
}

//Open an overlayFrame
function callOverlay(url) {
	
	//var url = '/ajax/s';
	var pars = '';
	var target = 'overlayFrame';
	$(target).hide();
	
	var myAjax = new Ajax.Updater(target, url, 
	           {asynchronous:false, 
		        evalScripts:true, 
		        method:'post', 
		        parameters:pars,           
		        onSuccess: function(e){
		        	$$('#mainframe input[type=button]').each(function(button){ button.disable(); });
		        	new Effect.Appear(target, {duration: 0.2});
		        	$$('#bodyframe div').each(function (divs) { divs.addClassName('overlayBlur'); });
		        }
        })	
}

// close Overlay Frame
function closeOverlay() {
	var target = 'overlayFrame';
	var myJS = new Effect.Fade(target, {duration: 0.2, afterFinish: function() {
		$$('#bodyframe div').each(function (divs) { divs.removeClassName('overlayBlur'); });
		$('overlayFrame').update();
		$$('#mainframe input[type=button]').each(function(button){ button.enable(); });
	}});
	
}

//Calls a PHP function over a URL and received a HTML code to display in the target frame
function callFrame(url, target, pars) {
	var myAjax = new Effect.Fade(target, {duration: 0.2, afterFinish: function(){
	  new Ajax.Updater(target, url, 
	           {asynchronous:false, 
	            evalScripts:true, 
	            method:'post', 
	            parameters:pars,           
	            onSuccess: function(e){new Effect.Appear(target, {duration: 0.2})}
	            })
	}});
}

function loadCSS(filename){ 

    var file = document.createElement("link");
    file.setAttribute("rel", "stylesheet");
    file.setAttribute("type", "text/css");
    file.setAttribute("href", filename);
    document.head.appendChild(file);

 }