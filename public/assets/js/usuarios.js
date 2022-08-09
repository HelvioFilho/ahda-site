window.onload = function () {
	var myModal = new bootstrap.Modal(document.getElementById("alertModal"), {});
	let dels = document.getElementsByClassName("btn-delete"),
		diss = document.getElementsByClassName("btn-disabled");
	Array.prototype.forEach.call(dels, function(del) {
	    del.addEventListener('click', function(){
			document.querySelector(".modal-body").innerHTML = "<b style='color:red'>Essa ação não pode ser desfeita.</b> <br> Excluir um usuário não vai apagar as suas postagens, porém as informações do mesmo não vão mais aparecer em suas postagens!";
			let confirmar = document.getElementById("confirmar");
			confirmar.dataset.id = this.dataset.id;
			confirmar.dataset.tipo = confirmar.dataset.desc = "del";
			myModal.show();
			
		});
	});
	Array.prototype.forEach.call(diss, function(dis) {
	    dis.addEventListener('click', function(){
			let confirmar = document.getElementById("confirmar"),
				msg;
			if(this.dataset.des == 1){
				msg = "Usuários desativados não podem entrar no sistema até serem ativados novamente.";
			}else{
				msg = "Ao ativar um usuário ele poderá voltar a acessar o sistema.";
			}
			document.querySelector(".modal-body").innerHTML = msg;
			
			confirmar.dataset.id = this.dataset.id;
			confirmar.dataset.tipo = this.dataset.des;
			confirmar.dataset.desc = "dis"
			myModal.show();
		});
	});
	document.getElementById("confirmar").addEventListener('click', function(e){
		myModal.hide();
		let id = this.dataset.id;
		if(this.dataset.desc === "del"){
			var ajax = new XMLHttpRequest();
			ajax.open("GET", `${url}/delete_user/${id}`, true);
			ajax.send();
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 4 && ajax.status == 200) {
					var data = ajax.responseText;
					if(data){
						document.querySelector(`#d${id}`).outerHTML = '<div class="alert alert-success alert-dismissible fade show" role="alert">Usuário excluído com sucesso!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
					}else{
						document.querySelector(`#e${id}`).innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Não foi possível excluir o usuário!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
					}	
				}
			}
		}else if(this.dataset.desc === "dis"){
			var ajax = new XMLHttpRequest();
			ajax.open("POST", `${url}/desabilitar_user`, true);
			ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			ajax.send(`id=${this.dataset.id}&des=${this.dataset.tipo}`);
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 4 && ajax.status == 200) {
					var data = JSON.parse(ajax.responseText);
					if(data.error){
						document.querySelector(`#e${id}`).innerHTML = `<div class="alert alert-success alert-dismissible fade show" role="alert">Usuário ${data.padrao} com sucesso!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
						document.querySelector(`#b${id}`).dataset.des = data.des;
						document.querySelector(`#b${id}`).innerHTML = data.msg;
					}else{
						document.querySelector(`#e${id}`).innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Não foi possível desativar o usuário!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
					}	
				}
			}
		}else{
			var ajax = new XMLHttpRequest();
			ajax.open("POST", `${url}/alterar_acesso`, true);
			ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			ajax.send(`id=${this.dataset.id}&acesso=${this.dataset.tipo}`);
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 4 && ajax.status == 200) {
					var data = JSON.parse(ajax.responseText);
					if(data.error){
						document.querySelector(`#e${id}`).innerHTML = `<div class="alert alert-success alert-dismissible fade show" role="alert">Acesso do usuário alterado com sucesso! ${data.padrao}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
						document.querySelector(`#sel${id}`).value = data.acesso;
					}else{
						document.querySelector(`#e${id}`).innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Não foi possível alterar o acesso do usuário!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
					}	
				}
			}
		}
	})
	let checkEmail = document.getElementById('email'),
		myTimeout;
	checkEmail.addEventListener('keyup', function(){
		clearTimeout(myTimeout);
		checkEmail.nextSibling.nextSibling.innerHTML = "";
		if(this.value !== ''){
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
	function null_or_empty(str) {
	    var v = str.value;
	    return v == null || v == "";
	}
	document.getElementById('name').addEventListener('keydown', function (){
		this.nextSibling.nextSibling.innerHTML = "";
	});
	document.getElementById('select-user').addEventListener('click', function (){
		this.nextSibling.nextSibling.innerHTML = "";
	});
	document.getElementById('add-user').addEventListener('click', function(event) {
		event.preventDefault();
		let name = document.getElementById('name'),
			email = document.getElementById('email'),
			select = document.getElementById('select-user'),
			count = 0;
		if(null_or_empty(name)){
			name.nextSibling.nextSibling.innerHTML = "* Nome não pode ser vazio!";
			count++;
		}
		if(null_or_empty(email)){
			email.nextSibling.nextSibling.innerHTML = "* Email não pode ser vazio!";
			count++;
		}
		if(null_or_empty(select)){
			select.nextSibling.nextSibling.innerHTML = "* Escolha o tipo de usuário!";
			count++;
		}
		if(count === 0){
			var ajax = new XMLHttpRequest();
			ajax.open("POST", `${url}/adm/verificar`, true);
			ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			ajax.send(`login=${email.value}`);
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 4 && ajax.status == 200) {
					var data = ajax.responseText;
				  	if(data == "true"){
				  		checkEmail.nextSibling.nextSibling.innerHTML = "* Você não pode criar um novo usuário com um e-mail já cadastrado!";
					}else{
						document.getElementById('form-user').submit();
					}
				}
			}
		}	
	});
	let selects = document.getElementsByClassName("user-select");
	Array.prototype.forEach.call(selects, function(sel) {
	    sel.addEventListener('change', function(){
			document.querySelector(".modal-body").innerHTML = "Você está alterando os privilégios de um usuário. Tem certeza que deseja confirmar essa ação?";
			let confirmar = document.getElementById("confirmar");
			confirmar.dataset.id = sel.dataset.id;
			confirmar.dataset.tipo = sel.value;
			confirmar.dataset.desc = "sel";
			sel.value = sel.dataset.old;
			myModal.show();
		});
	});
}


	
