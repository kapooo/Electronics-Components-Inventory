/**
 *
 * Advice Banner
 *
 * Copyright 2013-2015 Alessio Carpini - acarpini.it
 *
 * acarpini.it
 *
 */
 
function createDiv(){
    var bodytag = document.getElementsByTagName('body')[0];
    var div = document.createElement('div');
    div.setAttribute('id','cookie-law');
    div.innerHTML = '<p>Delete install.php !!! <a class="close-cookie-banner" href="javascript:void(0);" onclick="removeMe();"><span>Close</span></a></p>';    


    bodytag.insertBefore(div,bodytag.firstChild); // Adds the Cookie Law Banner just after the opening <body> tag
     
 //   document.getElementsByTagName('body')[0].className+=' cookiebanner'; //Adds a class tothe <body> tag when the banner is visible
     
}
 
window.onload = function(){
    if(check_install == true)
    {
        createDiv(); 
    }
}

function removeMe(){
	var element = document.getElementById('cookie-law');
	element.parentNode.removeChild(element);
}