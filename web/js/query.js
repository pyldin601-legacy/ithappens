function findText() {
	var textBox = document.getElementById('query');
	if(textBox.value == '') {
		alert("Необходимо заполнить поле для поиска!");
	} else {
		parent.location = "/search/all/" + textBox.value + "/1";
	}
}

function findEnter(ev) {
	if(ev.keyCode == 13) {
		findText();
	}
}

function showCompose() {
	var kh = document.getElementById('compose');
	if(kh) {
		if(kh.style.display == 'block')
			kh.style.display = 'none';
		else
			kh.style.display = 'block';
	}
}