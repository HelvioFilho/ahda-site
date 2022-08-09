window.onload = function () {
	let contador = document.querySelector('#sobre').value, 
		limite = 500;
	document.querySelector('.caracteres').innerHTML = limite - contador.length;
	document.querySelector('#sobre').addEventListener('input', function() {
		var caracteresDigitados = this.value.length;
	    var caracteresRestantes = limite - caracteresDigitados;
	    document.querySelector('.caracteres').innerHTML = caracteresRestantes;
	})
	function null_or_empty(str) {
	    var v = str.value;
	    return v == null || v == "";
	}
	document.querySelector('#name').addEventListener('keydown', function (){
		this.nextSibling.nextSibling.innerHTML = "";
	});
	document.querySelector('#infor-update').addEventListener('click', function(event) {
		let name = document.getElementById('name'),
			email = document.getElementById('email'),
			sobre = document.getElementById('sobre'),
			count = 0;
		if(null_or_empty(name)){
			name.nextSibling.nextSibling.innerHTML = "* Nome não pode ser vazio!";
			count++;
		}
		if(null_or_empty(email)){
			email.nextSibling.nextSibling.innerHTML = "* Email não pode ser vazio!";
			count++;
		}
		if(count === 0){
			var ajax = new XMLHttpRequest();
			ajax.open("POST", `${url}/minha_conta/atualizar/contato`, true);
			ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			ajax.send(`name=${name.value}&email=${email.value}&sobre=${sobre.value}`);
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 4 && ajax.status == 200) {
					var data = JSON.parse(ajax.responseText);
					if(data.erro){
						email.nextSibling.nextSibling.innerHTML = data.msg;
					}else{
						document.querySelector('.update-erro').innerHTML = '<div class="alert alert-success alert-dismissible fade show" role="alert">Dados alterados com sucesso!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
					}
				}
			}
		}	
	})
	//alterar senha
	document.querySelector('#password').addEventListener('keydown', function (){
		this.nextSibling.nextSibling.innerHTML = "";
	});
	document.querySelector('#repassword').addEventListener('keydown', function (){
		this.nextSibling.nextSibling.innerHTML = "";
	});
	document.querySelector('#pss-update').addEventListener('click', function(event) {
		let pss = document.getElementById('password'),
			repss = document.getElementById('repassword'),
			count = 0;
		if(null_or_empty(pss)){
			pss.nextSibling.nextSibling.innerHTML = "* Senha não pode se vazia!";
			count++;
		}
		if(null_or_empty(repss)){
			repss.nextSibling.nextSibling.innerHTML = "* Repetição da senha não pode ser vazia!";
			count++;
		}
		if(count === 0){
			if(document.getElementById('password').value === document.getElementById('repassword').value){
				var ajax = new XMLHttpRequest();
				ajax.open("POST", `${url}/adm/recuperar_senha/holdinster`, true);
				ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				ajax.send(`senha=${document.getElementById('password').value}&id=${document.getElementById('hidden').value}`);
				ajax.onreadystatechange = function() {
					if (ajax.readyState == 4 && ajax.status == 200) {
						var data = ajax.responseText;
					    if(data == true){
							document.querySelector('.pss-erro').innerHTML = '<div class="alert alert-success alert-dismissible fade show" role="alert">Dados alterados com sucesso!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
							pss.value = "";
							repss.value = "";
						}
					}
				}
			}else{
				repss.nextSibling.nextSibling.innerHTML = "* As senhas precisam ser iguais!";
			}
		}	
	})
	// checar e-mail
	let checkEmail = document.getElementById('email'),
		myTimeout;
	checkEmail.addEventListener('keyup', function(){
		clearTimeout(myTimeout);
		checkEmail.nextSibling.nextSibling.innerHTML = "";
		if(this.value !== '' && this.value !== session_email){
			myTimeout = setTimeout(() => {
				var ajax = new XMLHttpRequest();
				ajax.open("POST", `${url}/adm/verificar`, true);
				ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				ajax.send(`login=${this.value}`);
				ajax.onreadystatechange = function() {
					if (ajax.readyState == 4 && ajax.status == 200) {
						var data = ajax.responseText;
					  	if(data == "true"){
					  		checkEmail.nextSibling.nextSibling.innerHTML = "* Email já existe!"
						}
					}
				}
			}, 1000);
		}
	});
	
	const $ = document.querySelector.bind(document);
	const previewImg = $('.preview-img');
	const fileChooser = $('.file-chooser');
	const fileButton = $('.file-button');
	fileButton.onclick = () => fileChooser.click();
	fileChooser.onchange = e => {
		const fileToUpload = e.target.files.item(0);
		const reader = new FileReader();
		reader.onload = e => previewImg.src = e.target.result;
		reader.readAsDataURL(fileToUpload);
	};
	document.getElementById('changeAvatar').addEventListener('click', (e) => {
		e.preventDefault();
		document.getElementById("uploadimage").submit();
	});
}