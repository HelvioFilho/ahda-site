window.onload = function () {
  let vis = document.getElementsByClassName("vis-msg"),
  myModal = new bootstrap.Modal(document.getElementById("alertModal"), {}),
  msgModal = new bootstrap.Modal(document.getElementById("msgModal"), {}),
  exc = document.getElementsByClassName("exc-msg"),
  ver = document.getElementsByClassName("ver-msg"),
  del = document.getElementsByClassName("exc-pub"),
  pub = document.getElementsByClassName("alt-pub");

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
          document.querySelector('.message-modal').innerHTML = msg;
          document.querySelector('.menu-b').innerHTML = data.count;
          document.querySelector('#pill-count').innerHTML = data.count;
          msgModal.show();
        }else{
          document.querySelector(`#e${id}`).innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Erro desconhecido, não foi possível abrir a mensagem!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        } 
      }
    }
    });
  });
  Array.prototype.forEach.call(del, function(el) {
      el.addEventListener('click', function(e){
      let confirmar = document.getElementById("confirmar");
      document.getElementById('confirmar').classList.remove('hide');
      document.querySelector('.clean-close').innerHTML = "Cancelar";
      document.querySelector('#confirmar').innerHTML = "Apagar";
      document.querySelector('.modal-aviso').innerHTML = '<b class="error-default">Essa ação não pode ser desfeita.</b> <br> Tem certeza que deseja excluir a publicação?';
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
      document.querySelector('.modal-aviso').innerHTML = msg;
      confirmar.dataset.id = this.dataset.id;
      confirmar.dataset.tipo = 'pub';
      confirmar.dataset.mod = this.dataset.mod;
      myModal.show();
      });
  });
  document.getElementById("at-radio").addEventListener('click', function(e){
    let link = document.querySelector(`input[name="radio"]`).value;
    var ajax = new XMLHttpRequest();
      ajax.open("POST", `${url}/alterar_radio`, true);
      ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      ajax.send(`link=${link}`)
      ajax.onreadystatechange = function() {
        if (ajax.readyState == 4 && ajax.status == 200) {
          var data = ajax.responseText;
          if(data){
            msg = "<p>Rádio atualizada com sucesso!</p>";
          }else{
            msg = "<p>Não foi possível atualizar a rádio!</p>";
          }
          document.querySelector('.modal-aviso').innerHTML = msg;
          document.getElementById('confirmar').classList.add('hide');
          document.querySelector('.clean-close').innerHTML = "ok";
          myModal.show();
        }
      }
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
            document.querySelector(`#mm${id}`).outerHTML = '<div class="alert alert-success alert-dismissible fade show" role="alert">Publicação excluída com sucesso!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
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
function alterC(){
  var windowWidth = window.innerWidth;
  
  if(windowWidth < 825){
    document.getElementById('remove_cl').classList.remove('py-5', 'g-5');
  }else{
    document.getElementById('remove_cl').classList.add('py-5', 'g-5');
  }
}
alterC();
window.addEventListener('resize', function(event){
  alterC();
});