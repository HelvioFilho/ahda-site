window.onload = function () {
	function clear(target){
		document.querySelector(target).innerHTML = '';
		document.querySelector('.alert').innerHTML = '';
	}
	document.querySelector('#password').addEventListener('input', function() {
		clear('.erroPss');
		if(this.value !== '' && document.querySelector("#repassword").value !== ''){
			document.querySelector("#cm-btn").removeAttribute("disabled");
		}else{
			document.querySelector("#cm-btn").setAttribute("disabled", "");
		}
	});
	document.querySelector('#repassword').addEventListener('input', function() {
		clear('.erroPss');
		if(this.value !== '' && document.querySelector("#password").value !== ''){
			document.querySelector("#cm-btn").removeAttribute("disabled");
		}else{
			document.querySelector("#cm-btn").setAttribute("disabled", "");
		}
	});
	document.querySelector('form').addEventListener('submit', function (e){
		e.preventDefault();
		if(document.getElementById('password').value === document.getElementById('repassword').value){
			var ajax = new XMLHttpRequest();
			ajax.open("POST", `${url}/adm/recuperar_senha/holdinster`, true);
			ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			ajax.send(`senha=${document.getElementById('password').value}&id=${document.getElementById('hidden').value}`);
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 4 && ajax.status == 200) {
					var data = ajax.responseText;
				    if(data == true){
						document.querySelector('.alert').innerHTML = '<p class="text-center text-muted" style="color:black"><b>Senha alterada com sucesso!</b></p>';
						let part = document.querySelectorAll('.form-floating')
						part.forEach((item) => {
						  item.remove();
						})
						document.querySelector("#cm-btn").outerHTML = `<a class="w-100 btn btn-lg btn-primary" href="${url}">Entrar</a>`;
					}
				}
			}
		}else{
			document.querySelector('.erroPss').innerHTML = '*';
            document.querySelector('.alert').innerHTML = '* As senhas precisam ser iguais!';
		}
	});
}