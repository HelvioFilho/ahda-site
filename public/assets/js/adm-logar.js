window.onload = function () {
	function clear(target){
		document.querySelector(target).innerHTML = '';
		document.querySelector('.alert').innerHTML = '';
	}
	document.querySelector('#email').addEventListener('input', function() {
		clear('.erroMail');
		if(this.value !== '' && document.querySelector("#password").value !== ''){
			document.querySelector("#cm-btn").removeAttribute("disabled");
		}else{
			document.querySelector("#cm-btn").setAttribute("disabled", "");
		}
	});
	document.querySelector('#password').addEventListener('input', function() {
		clear('.erroPss');
		if(this.value !== '' && document.querySelector("#email").value !== ''){
			document.querySelector("#cm-btn").removeAttribute("disabled");
		}else{
			document.querySelector("#cm-btn").setAttribute("disabled", "");
		}
	});
	if(document.querySelector("#password").value !== '' && document.querySelector("#email").value !== ''){
		document.querySelector("#cm-btn").removeAttribute("disabled");
	}
	let checkEmail = document.getElementById('email'),
		myTimeout;
	checkEmail.addEventListener('keyup', function(){
		clearTimeout(myTimeout);
		if(this.value !== ''){
			myTimeout = setTimeout(() => {
				if(this.value !== '' && document.querySelector("#password").value !== ''){
					document.querySelector("#cm-btn").removeAttribute("disabled");
				}else{
					document.querySelector("#cm-btn").setAttribute("disabled", "");
				}				
				var ajax = new XMLHttpRequest();
				ajax.open("POST", `${url}/adm/verificar`, true);
				ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				ajax.send(`login=${this.value}`);
				ajax.onreadystatechange = function() {
					if (ajax.readyState == 4 && ajax.status == 200) {
						var data = ajax.responseText;
					    if(data == "false"){
							document.querySelector('.erroMail').innerHTML = '*';
		            		document.querySelector('.alert').innerHTML = '* Usuário não existe!';
		            	}
					}
				}
			}, 1000);
		}	
	});
	document.querySelector('form').addEventListener('submit', function (e){
		e.preventDefault();
		document.getElementById('cm-btn').setAttribute("disabled", "");
		document.getElementById('cm-btn').innerHTML = 'Entrando...';
		let pss = document.querySelector("#password").value, 
			us = document.querySelector("#email").value,
			box = document.querySelector("#box").value;

		var ajax = new XMLHttpRequest();
		ajax.open("POST", `${url}/adm/logar`, true);
		ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajax.send(`login=${us}&pss=${pss}&box=${box}`);
		ajax.onreadystatechange = function() {
			if (ajax.readyState == 4 && ajax.status == 200) {
				var data = ajax.responseText;
				if(data == "login"){
					document.querySelector('.erroMail').innerHTML = '*';
            		document.querySelector('.alert').innerHTML = '* Usuário não existe!';
            	}
            	if(data == "senha"){
            		document.querySelector('.erroPss').innerHTML = '*';
            		document.querySelector('.alert').innerHTML = '* Senha incorreta!';
            	}
            	if(data == "login" || data == "senha"){
            		document.getElementById('cm-btn').removeAttribute("disabled");
					document.getElementById('cm-btn').innerHTML = 'Entrar';
            	}
            	if(data == "logado"){
            		document.location.reload(true);
            	}
            	if(data == "block"){
            		document.querySelector('.alert').innerHTML = '* Você perdeu o acesso ao sistema, entre em contato com o administrador para saber o motivo!';
            		document.getElementById('cm-btn').removeAttribute("disabled");
					document.getElementById('cm-btn').innerHTML = 'Entrar';
            	}
			}
		}
	})
	let checkEmailForgot = document.getElementById('rec-pass'),
		newTimeout;
	checkEmailForgot.addEventListener('keyup', function(){
		document.querySelector('.forgot-erro').innerHTML = '';
		clearTimeout(newTimeout);
		if(this.value !== ''){
			newTimeout = setTimeout(() => {				
				var ajax = new XMLHttpRequest();
				ajax.open("POST", `${url}/adm/verificar`, true);
				ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				ajax.send(`login=${this.value}`);
				ajax.onreadystatechange = function() {
					if (ajax.readyState == 4 && ajax.status == 200) {
						var data = ajax.responseText;
					    if(data == "false"){
							document.querySelector('.forgot-erro').innerHTML = '* Email não encontrado!';
							document.querySelector("#b-rec").setAttribute("disabled", "");
		            	}else{
		            		document.querySelector("#b-rec").removeAttribute("disabled");
		            	}
					}
				}
			}, 1000);
		}	
	});
	document.getElementById('b-rec').addEventListener('click', function (){
		this.setAttribute("disabled", "");
		this.innerHTML = 'Enviando...';
		var ajax = new XMLHttpRequest();
		ajax.open("POST", `${url}/adm/recuperar`, true);
		ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajax.send(`email=${document.getElementById('rec-pass').value}`);
		ajax.onreadystatechange = function() {
			if (ajax.readyState == 4 && ajax.status == 200) {
				var data = ajax.responseText;
			    if(data == "false"){
					document.querySelector('.forgot-erro').innerHTML = 'Ocorreu um erro e não foi possível enviar o email, caso o problema persistir entre em contato com o administrador do sistema!';
					document.getElementById('b-rec').innerHTML = 'Enviar';
					document.getElementById('b-rec').removeAttribute("disabled");
				}else{
            		document.querySelector('.modal-body').innerHTML = '<p>Foi enviado um e-mail com instruções de como alterar a sua senha. Caso não encontre o e-mail, verifique a sua caixa de spam.</p>';
            		document.getElementById('b-rec').remove();
            	}
            }
		}
	})
}