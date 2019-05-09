
if (window.location.hash === '#register') {
	document.getElementById('login').style.display = 'none';
} else {
	document.getElementById('register').style.display = 'none';
}
//------------------------------
    
 
  

document.getElementById('regclick').onclick = function() {
	var element = document.getElementsByClassName('error');
	var loginname = document.getElementsByName('username');
	var password = document.getElementsByName('password');
	document.getElementById('login').style.display = 'none';
	if(element[0] || element[1]){
		element[0].style.display = 'none';
		element[1].style.display = 'none';
	}
	if(loginname[1] || password[1]){
		loginname[1].value = '';
		password[1].value = '';
	}


	document.getElementById('register').style.display = 'block';

};

document.getElementById('logclick').onclick = function() {
	var element = document.getElementsByClassName('error');
	var loginname = document.getElementsByName('username');
	var password = document.getElementsByName('password');
	if(element[0] || element[1]){
		element[1].style.display = 'none';
		element[0].style.display = 'none';
	}
	if(loginname[1] || password[1]){

		loginname[1].value = '';
		password[1].value = '';
	}

	document.getElementById('login').style.display = 'block';
	document.getElementById('register').style.display = 'none';

};


