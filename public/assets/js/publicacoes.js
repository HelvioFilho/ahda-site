window.onload = function () {
	let contador = document.querySelector('#preview').value,
		myModal = new bootstrap.Modal(document.getElementById("alertModal"), {}),
		limite = 300;
	document.querySelector('.caracteres').innerHTML = limite - contador.length;
	document.querySelector('#preview').addEventListener('input', function() {
		var caracteresDigitados = this.value.length;
	    var caracteresRestantes = limite - caracteresDigitados;
	    document.querySelector('.caracteres').innerHTML = caracteresRestantes;
	})
	const $ = document.querySelector.bind(document);
	const previewImg = $('.preview-img');
	const fileChooser = $('.file-chooser');
	const fileButton = $('.file-button');
	fileButton.onclick = () => fileChooser.click();
	fileChooser.onchange = e => {
		const fileToUpload = e.target.files.item(0);
		extArquivo = fileToUpload.type.split('/').pop(),
		extPermitidas = ['jpg', 'png', 'jpeg', 'jpe'];
		if(typeof extPermitidas.find(function(ext){ return extArquivo == ext; }) == 'undefined'){
			document.querySelector('.modal-body').innerHTML = "<p class='error'>Tipo de arquivo inválido, só são aceitas imagens do tipo: <br> <b>jpg</b>, <b>jpe</b>, <b>jpeg</b> e <b>png</b>!</p>";
			document.querySelector('.clean-close').innerHTML = "Fechar";
			document.getElementById('confirmar').classList.add('hide');
			myModal.show();
		}else{
			const reader = new FileReader();
			reader.onload = e => previewImg.src = e.target.result;
			reader.readAsDataURL(fileToUpload);
		}
	};
	let del = document.getElementsByClassName("exc-pub"),
		pub = document.getElementsByClassName("alt-pub");
	Array.prototype.forEach.call(del, function(el) {
    	el.addEventListener('click', function(e){
			let confirmar = document.getElementById("confirmar");
			document.getElementById('confirmar').classList.remove('hide');
			document.querySelector('.clean-close').innerHTML = "Cancelar";
			document.querySelector('#confirmar').innerHTML = "Apagar";
			document.querySelector('.modal-body').innerHTML = '<b class="error-default">Essa ação não pode ser desfeita.</b> <br> Tem certeza que deseja excluir a publicação?';
			confirmar.dataset.id = this.dataset.id;
			myModal.show();
    	});
	});
	Array.prototype.forEach.call(pub, function(el) {
    	el.addEventListener('click', function(e){
			let confirmar = document.getElementById("confirmar"),
				msg = "";
			document.getElementById('confirmar').classList.remove('hide');
			document.querySelector('.clean-close').innerHTML = "Cancelar";
			document.querySelector('#confirmar').innerHTML = "Sim";
			if(this.dataset.mod == "Esconder"){
				msg = "Você esta prestes a tornar a publicação visível, dessa forma ela vai aparecer no aplicativo. Tem certeza que deseja fazer isso?";
			}else{
				msg = "Você esta prestes a tornar a publicação invisível, dessa forma ela não vai aparecer no aplicativo. Tem certeza que deseja fazer isso?";
			}
			document.querySelector('.modal-body').innerHTML = msg;
			confirmar.dataset.id = this.dataset.id;
			confirmar.dataset.tipo = 'pub';
			confirmar.dataset.mod = this.dataset.mod;
			myModal.show();
    	});
	});
	document.getElementById("confirmar").addEventListener('click', function(e){
		myModal.hide();
		let id = this.dataset.id,
			tipo = this.dataset.tipo,
			mod = this.dataset.mod;
		if(tipo == 'pub' ){
			var ajax = new XMLHttpRequest();
			ajax.open("POST", `${url}/publicar`, true);
			ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			ajax.send(`id=${id}&mod=${mod}`)
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 4 && ajax.status == 200) {
					var data = ajax.responseText;
					
					if(data == "Publicar"){
						document.querySelector(`#e${id}`).innerHTML = '<div class="alert alert-success alert-dismissible fade show" role="alert">Publicação agora esta visível para todos!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
							document.getElementById(`pub${id}`).classList.remove('btn-outline-success');
							document.getElementById(`pub${id}`).classList.add('btn-outline-danger');
					}else{
						document.querySelector(`#e${id}`).innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Publicação agora esta invisível para todos!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
						document.getElementById(`pub${id}`).classList.remove('btn-outline-danger');
						document.getElementById(`pub${id}`).classList.add('btn-outline-success');
					}
					document.getElementById(`pub${id}`).dataset.mod = data;
					document.getElementById(`pub${id}`).innerHTML = mod;
				}
			}
		}else{
			var ajax = new XMLHttpRequest();
			ajax.open("GET", `${url}/delete_publicacao/${id}`, true);
			ajax.send();
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 4 && ajax.status == 200) {
					var data = ajax.responseText;
					if(data){
						document.querySelector(`#c${id}`).outerHTML = '<div class="alert alert-success alert-dismissible fade show" role="alert">Publicação excluída com sucesso!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
					}else{
						document.querySelector(`#e${id}`).innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Não foi possível excluir a publicação!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
					}	
				}
			}	
		}
	});
	if ( window.history.replaceState ) {
		window.history.replaceState( null, null, window.location.href );
	}
}