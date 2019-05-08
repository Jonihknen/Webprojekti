
document.getElementById('delform').style.display = 'none';


document.getElementById('delnappi').onclick = function() {
	document.getElementById('delnappi').style.display = 'none';
	document.getElementById('delform').style.display = 'block';
};

document.getElementById('peruuta').onclick = function() {
	document.getElementById('delnappi').style.display = 'block';
	document.getElementById('delnappi').style.textAlign = 'center';
	document.getElementById('delform').style.display = 'none';
};


