<main class="container">
<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="alertModalLabel">Aviso</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body modal-aviso">
        <b class="error-default">Essa ação não pode ser desfeita.</b> <br> Tem certeza que deseja excluir a mensagem?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger clean-close" data-bs-dismiss="modal">Cancelar</button>
        <button id="confirmar" data-id="" type="button" class="btn btn-success">Sim</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="msgModal" tabindex="-1" aria-labelledby="msgModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="msgModalLabel">Mensagem</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body message-modal">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
<br>
   <h4 class="mb-3">Bem vindo, <?=$_SESSION['usuario_logado']?>.</h4>
    <hr>
   <div id="remove_cl" class="py-5 row g-5">
      <div class="col-md-5 col-lg-4 order-md-last">
        <form id="radio" class="card p-2">
          <h6 class="my-1 ms-2 mb-2">Link da Rádio</h6>
          <div class="input-group">
            <input type="text" name="radio" class="form-control" placeholder="Link da Rádio" value="<?=(empty($url_link))? "" : $url_link->link;?>">
            <button id="at-radio" type="button" class="btn btn-outline-success">Atualizar</button>
          </div>
        </form>
        <h4 class="d-flex justify-content-between align-items-center mb-3">
          <span class="text-primary">Últimas Mensagens</span>
          <span id="pill-count" class="badge bg-primary rounded-pill bg-msg"><?=$countMsg;?></span>
        </h4>
        <?php if (count($mensagens) > 0) : ?>
        <ul class="list-group mb-3">
          <?php foreach ($mensagens as $msg):?>
          <li id="c<?=$msg->id?>"
              class="
                    real-li
                    list-group-item 
                    d-flex 
                    justify-content-between 
                    lh-sm 
                    <?=($msg->is_read) ? "": "bg-light";?>"
          >
            <div>
              <h6 class="my-2 mb-2"><?=$msg->name;?> </h6>
              <p class="text-muted"><b>Prévia: </b><br><?=mb_strimwidth($msg->message, 0, 30, "..."); ?></p>
            </div>
            <div>
            	<span
                id="b<?=$msg->id?>"
                class=" badge 
                    bg-info 
                    rounded-pill
                    <?=($msg->is_read) ? "bhide": "";?>
                    ">
                  novo
              </span>
            </div>
            <?php
              $email = "";
              if($msg->email){
                $email = "<span class='text-muted'><b>Email: </b>".$msg->email."</span>";
              }
                    $message = "<b>Nome: </b>".$msg->name."<br>".$email."<div class='card p-2 my-4'>".$msg->message."</div>";
                  ?>
            <div class="btn-see">
              <button 
                    class=" btn 
                        btn-outline-success 
                        ver-msg" 
                    data-id="<?=$msg->id ?>" 
                    data-msg="<?=$message;?>"
                    type="button"
                  >
                    Ver
                  </button>
            </div>
          </li>
          <?php endforeach; ?>
        </ul>
        <?php else : ?>
          <div class="padding d-flex align-items-center justify-content-center">
            <h4>Ainda não há mensagens!</h4>
          </div>
        <?php endif; ?>
      </div>
      <div class="col-md-7 col-lg-8">
        <h4 class="mb-3 text-primary">Últimas publicações</h4>
        <?php if (count($posts) > 0) : ?>
      <?php foreach ($posts as $post):?>
        <div id="mm<?=$post->id?>" class="card p-2" >
      <div class="col-md-12 d-flex justify-content-center">
        <div class="card p-2 card-img" style="width: 100%;">
          <img 
              class="preview-img" 
              src="<?=base_url(['img','capa',$post->cover])?>"
            >
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <b>Título: </b><p class="text-muted"><?=$post->title; ?></p>
        </div>
        <div class="col-md-12">
          <b>Prévia: </b><p class="text-muted"><?=mb_strimwidth($post->preview, 0, 50, "..."); ?></p>
        </div>
        <div class="col-md-12">
          <b>Criador por:</b> <p class="text-muted">
          <?php foreach ($user as $us):?>
            <?=$us->user_id == $post->user ? $us->username : '' ?>
              <?php endforeach; ?>
          </p>
        </div>
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-5 data-padding">
              <small><b>Data:</b> <span class="text-muted"><?=date('d/m/Y H:i', strtotime($post->date)) ?></span></small>
            </div>
            <div class="col-md-7 d-flex justify-content-end">
              <div class="btn-control">
                <button class="btn btn-outline-danger exc-pub" 
                    title="Deletar Postagem" 
                    data-id="<?=$post->id ?>" 
                    type="button"
                >
                  <i class="far fa-trash-alt"></i>
                </button>
                <a
                  href="<?=base_url(['publicacao',$post->id]) ?>"
                  class=" btn 
                          btn-outline-info" 
                >
                  Editar
                </a>
                <?php 
                  if(!empty($post->text) && $post->is_published == 0){
                    $btn = "btn-outline-success";
                    $mod = "Esconder";
                    $text = "Publicar";
                  }elseif(!empty($post->text) && $post->is_published == 1){
                    $btn = "btn-outline-danger";
                    $mod = "Publicar";
                    $text = "Esconder";
                  }else{
                    $btn = "hide";
                    $mod = "";
                    $text = "";
                  }
                ?>
                      <button
                        id="pub<?=$post->id?>"
                        class=" btn 
                            <?=$btn;?>
                            alt-pub
                            "
                        data-id="<?=$post->id ?>"
                        data-mod="<?=$mod;?>"
                        type="button"
                      >
                        <?=$text;?>
                      </button>
                    </div>
                  </div>
                </div>
        </div>
      </div>
      <br>
      <span id="e<?=$post->id?>">
      </span>
        </div>
      <?php endforeach; ?>
      <?php else : ?>
          <div class="padding d-flex align-items-center">
            <h4>Ainda não existe nenhuma publicação!</h4>
          </div>
        <?php endif; ?>
      </div>
    </div>
</main>
<script>
  let url = "<?=base_url();?>";
</script>