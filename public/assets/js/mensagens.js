window.onload = function () {
	let vis = document.getElementsByClassName("vis-msg"),
		myModal = new bootstrap.Modal(document.getElementById("alertModal"), {}),
		msgModal = new bootstrap.Modal(document.getElementById("msgModal"), {}),
		exc = document.getElementsByClassName("exc-msg"),
		ver = document.getElementsByClassName("ver-msg");
	Array.prototype.forEach.call(ver, function(el) {
    	el.addEventListener('click', function(e){
			let id = this.dataset.id,
				msg = this.dataset.msg;
			var ajax = new XMLHttpRequest();
			ajax.open("POST", `${url}/abrir_mensagem`, true);
			ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			ajax.send(`id=${id}`);
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 4 && ajax.status == 200) {
					var data = JSON.parse(ajax.responseText);
					if(data.response){
						document.getElementById(`c${id}`).classList.remove('bg-light');
						document.getElementById(`b${id}`).classList.add('bhide');
						document.querySelector(`#v${id}`).classList.remove('bhide');
						document.querySelector('.message-modal').innerHTML = msg;
						document.querySelector('.menu-b').innerHTML = data.count;
						msgModal.show();
					}else{
						document.querySelector(`#e${id}`).innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Erro desconhecido, não foi possível abrir a mensagem!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
					}	
				}
			}
    	});
	});
	Array.prototype.forEach.call(vis, function(el) {
    	el.addEventListener('click', function(e){
			let id = this.dataset.id;
			var ajax = new XMLHttpRequest();
			ajax.open("POST", `${url}/marcar_novo`, true);
			ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			ajax.send(`id=${id}`);
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 4 && ajax.status == 200) {
					var data = JSON.parse(ajax.responseText);
					if(data.response){
						document.getElementById(`c${id}`).classList.add('bg-light');
						document.getElementById(`b${id}`).classList.remove('bhide');
						document.querySelector(`#v${id}`).classList.add('bhide');
						document.querySelector('.menu-b').innerHTML = data.count;
					}else{
						document.querySelector(`#e${id}`).innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Não foi possível marcar como não visto!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
					}	
				}
			}
    	});
	});
	Array.prototype.forEach.call(exc, function(el) {
    	el.addEventListener('click', function(e){
    	let confirmar = document.getElementById("confirmar");
    	  confirmar.dataset.id = this.dataset.id;
    	  myModal.show();
    	});
	});
	document.getElementById("confirmar").addEventListener('click', function(e){
		myModal.hide();
		let id = this.dataset.id;
		var ajax = new XMLHttpRequest();
		ajax.open("GET", `${url}/delete_mensagem/${id}`, true);
		ajax.send();
		ajax.onreadystatechange = function() {
			if (ajax.readyState == 4 && ajax.status == 200) {
				var data = JSON.parse(ajax.responseText);
				if(data.response){
					document.querySelector(`#c${id}`).outerHTML = '<div class="alert alert-success alert-dismissible fade show" role="alert">Mensagem excluída com sucesso!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
					document.querySelector('.menu-b').innerHTML = data.count;
				}else{
					document.querySelector(`#e${id}`).innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Não foi possível excluir a mensagem!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
				}	
			}
		}
	});
}