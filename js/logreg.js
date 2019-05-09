
if (window.location.hash === '#register') {
	document.getElementById('login').style.display = 'none';
} else {
	document.getElementById('register').style.display = 'none';
}

document.getElementById('regclick').onclick = function() {
	document.getElementById('login').style.display = 'none';
	document.getElementById('register').style.display = 'block';

};

document.getElementById('logclick').onclick = function() {
	document.getElementById('login').style.display = 'block';
	document.getElementById('register').style.display = 'none';
};


