<main class="container">
  <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="alertModalLabel">Aviso</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

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
      <h4 class="mb-3 text-primary">Publicação</h4>
      <div class="card p-2">
        <form id="form-post" class="row g-3" action="<?= base_url(['post', 'update', $post->id]) ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
          <div class="col-sm-12">
            <label for="title" class="form-label">Título</label>
            <input type="text" class="form-control" name="title" id="title" value="<?= $post->title ?>" required>
            <div class="invalid-feedback">
            </div>
          </div>
          <div class="col-sm-12">
            <div class="p-2 ">
              <img class="preview-img" src="<?= base_url(['img', 'capa', $post->cover]) ?>">
            </div>
          </div>
          <div class="col-sm-12 d-flex  justify-content-center align-items-center">
            <div>
              <input class="file-chooser item-image" name="arquivo" type="file" accept="image/*" hidden>
              <div class="file-button p-2 btnImage"><i class="fas fa-plus-circle"></i> Alterar Imagem</div>
            </div>
          </div>
          <div class="col-sm-12">
            <label for="preview" class="form-label">Prévia</label>
            <textarea rows="6" class="form-control mb-2" id="preview" name="preview" maxlength="300" required><?= $post->preview; ?></textarea>
            <small class="text-muted">Total de letras: <span class="caracteres"></span></small>
            <div class="invalid-feedback">
            </div>
          </div>
          <input type="hidden" name="imagens">
          <div class="col-sm-12">
            <input name="editor" type="hidden">
            <div class="capsula">
              <div id="editorcontainer">
                <div id="quill-editor">
                  <?php
                  if (!empty($post->text) && !empty($status->data)) {

                    $dt_post = new DateTime($post->date_post);
                    $dt_status = new DateTime($status->date);

                    if ($dt_post > $dt_status) {
                      echo $post->text;
                    } else if ($dt_post < $dt_status) {
                      echo $status->data;
                    }
                  } else if (!empty($status->data)) {
                    echo $status->data;
                  } else {
                    echo $post->text;
                  }
                  ?>
                </div>
              </div>
            </div>
            <div class="box-status my-2">
              <span class="aviso-status"></span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <div class="aviso-update">
                <?php
                if (!empty($post->date_post) && !empty($status->date)) {
                  $dt_post = new DateTime($post->date_post);
                  $dt_status = new DateTime($status->date);
                  if ($dt_post < $dt_status) {
                    echo "* Falta <b>enviar</b> as atualizões";
                  }
                }
                ?>
              </div>
              <button class="btn btn-outline-success my-4" type="submit" id="enviarTudo">Enviar</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>
<script>
  let url = "<?= base_url(); ?>",
    arqId = "<?= $post->id; ?>",
    caminho = "<?= $post->title; ?>";
</script>
<!-- criar botão "voltar histórico" -->