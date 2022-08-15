<main class="container">
  <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="alertModalLabel">Aviso</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <b class="error-default">Essa ação não pode ser desfeita.</b> <br> Tem certeza que deseja excluir a mensagem?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
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
  <div class="row">
    <div class="col-md-2">
    </div>
    <div class="col-md-8 col-lg-8">
      <br>
      <div class="d-flex justify-content-between">
        <h4 class="mb-3 text-primary">Mensagens</h4>
        <div>
          <a href="<?= base_url(['mensagens']) ?>" class="btn btn-secondary">Todos</a>
          <button type="button" class="btn btn-primary">Novos</button>
        </div>
      </div>
      <br>
      <?php if (empty($mensagens)) : ?>
        <div class="padding d-flex align-items-center justify-content-center">
          <h3>Não há novas mensagens!</h3>
        </div>
      <?php endif; ?>
      <?php foreach ($mensagens as $msg) : ?>
        <div id="c<?= $msg->id ?>" class="card p-2 <?= ($msg->is_read) ? "" : "bg-light"; ?>">
          <div class="col-md-12 d-flex justify-content-between">
            <div>
              <h6 class="my-2 mb-1"><?= $msg->name; ?></h6>
              <?php if ($msg->email) : ?>
                <small class="text-muted"><b>Email: </b><?= $msg->email; ?> </small>
              <?php endif; ?>
            </div>
            <div>
              <span id="b<?= $msg->id ?>" class="	badge 
								bg-info 
								rounded-pill
								<?= ($msg->is_read) ? "bhide" : ""; ?>
								">
                novo
              </span>
            </div>
          </div>
          <div class="row">
            <div class="col-md-7 my-2">
              <p class="text-muted"><b>Prévia: </b><br><?= mb_strimwidth($msg->message, 0, 50, "..."); ?></p>
            </div>
            <div class="col-md-5 my-2 d-flex justify-content-end align-items-end">
              <div>
                <button class="btn btn-outline-danger exc-msg" title="Deletar Mensagem" data-id="<?= $msg->id ?>" type="button">
                  <i class="far fa-trash-alt"></i>
                </button>
                <button id="v<?= $msg->id ?>" class="	btn 
									btn-outline-info 
									vis-msg
									<?= (!$msg->is_read) ? "bhide" : ""; ?>
									" title="Marcar mensagem como 'não visualizada'" data-id="<?= $msg->id ?>" type="button">
                  <i class="fas fa-eye-slash"></i>
                </button>
                <?php
                $email = "";
                if ($msg->email) {
                  $email = "<span class='text-muted'><b>Email: </b>" . $msg->email . "</span>";
                }
                $message = "<b>Nome: </b>" . $msg->name . "<br>" . $email . "<div class='card p-2 my-4'>" . $msg->message . "</div>";
                ?>
                <button class="	btn 
		            				btn-outline-success 
		            				ver-msg" data-id="<?= $msg->id ?>" data-msg="<?= $message; ?>" type="button">
                  Ver
                </button>
              </div>
            </div>
          </div>
          <span id="e<?= $msg->id ?>">
          </span>
        </div>
      <?php endforeach ?>
      <br>
      <div class="d-flex justify-content-center">
        <?= $pager->links('default', 'boot_custom') ?>
      </div>
    </div>
  </div>
</main>
<script>
  let url = "<?= base_url() ?>";
</script>