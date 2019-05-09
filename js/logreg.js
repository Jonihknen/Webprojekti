
if (window.location.hash === '#register') {
	document.getElementById('login').style.display = 'none';
} else {
	document.getElementById('register').style.display = 'none';
}
//------------------------------
    
 
  

document.getElementById('regclick').onclick = function() {
	var element = document.getElementsByClassName('error');
	document.getElementById('login').style.display = 'none';
	if(element){
		element[0].style.display = 'none';
		element[1].style.display = 'none';
	}
	document.getElementById('register').style.display = 'block';

};

document.getElementById('logclick').onclick = function() {
	var element = document.getElementsByClassName('error');
	if(element){
		element[1].style.display = 'none';
		element[0].style.display = 'none';
	}
	document.getElementById('login').style.display = 'block';
	document.getElementById('register').style.display = 'none';
};


