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
          <button type="button" class="btn btn-danger clean-close" data-bs-dismiss="modal"></button>
          <button id="confirmar" data-id="" data-tipo="" data-mod="" type="button" class="btn btn-success hide">Apagar</button>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-2">
    </div>
    <div class="col-md-8 col-lg-8">
      <div class="accordion my-4 mb-4" id="accordionUser">
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingUser">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUser" aria-expanded="false" aria-controls="collapseUser">
              Adicionar Publicação
            </button>
          </h2>
          <div id="collapseUser" class="accordion-collapse collapse" aria-labelledby="headingUser" data-bs-parent="#accordionUser">
            <div class="accordion-body">
              <form id="form-user" class="row g-3" action="<?= base_url(['post', 'add']) ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="col-sm-6">
                  <label for="title" class="form-label">Título</label>
                  <input type="text" class="form-control" name="title" id="title" required>
                  <div class="invalid-feedback">
                  </div>
                </div>
                <div class="col-sm-6 d-flex  justify-content-center align-items-center">
                  <input class="file-chooser item-image" name="arquivo" type="file" accept="image/*" hidden>
                  <div class="file-button p-2 btnImage"><i class="fas fa-plus-circle"></i> Selecionar Imagem</div>

                </div>
                <div class="col-sm-6">
                  <label for="preview" class="form-label">Prévia</label>
                  <textarea rows="6" class="form-control mb-2" id="preview" name="preview" maxlength="300" required></textarea>
                  <small class="text-muted">Total de letras: <span class="caracteres"></span></small>
                  <div class="invalid-feedback">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="p-2 ">
                    <img class="preview-img" src="<?= base_url(['img', 'capa', 'angel-default.jpg']) ?>">
                  </div>
                </div>
                <div class="col-sm-12 d-flex justify-content-end">
                  <button id="add-user" class="btn btn-outline-success" type="submit">Adicionar</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="error col-sm-12">
        <?php if ($session->getFlashdata('error')) : ?>
          <div class="alert 
		    			alert-<?= $session->getFlashdata('error') ?> 
		    			alert-dismissible 
		    			fade show" role="alert">
            <?= $session->getFlashdata('msg') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">

            </button>
          </div>
        <?php endif; ?>
      </div>
      <br>
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-4">
            <h4 class="mb-3 text-primary">Publicações</h4>
          </div>
          <div class="col-md-8">
            <form action="<?= base_url(['publicacoes', 'busca']) ?>" method="post" accept-charset="utf-8">
              <div class="d-flex justify-content-end">
                <input class="form-control search" name="busca" type="search" placeholder="Busca" aria-label="Search" value="<?= (isset($_SESSION['search'])) ? $_SESSION['search'] : "" ?>" required>
                <button class="btn btn-outline-success btn-search" type="submit">Procurar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <br>
      <!-- imprementar a busca -->
      <div id="search_field">
        <?php if ($uri->getSegment(2) == "busca" && empty($posts)) : ?>
          <div class="padding d-flex align-items-center justify-content-center">
            <h3>Nenhum resultado encontrado!</h3>
          </div>
        <?php endif; ?>
        <?php foreach ($posts as $post) : ?>
          <div id="c<?= $post->id ?>" class="card p-2">
            <div class="col-md-12 d-flex justify-content-center">
              <div class="card p-2 card-img" style="width: 100%;">
                <img class="preview-img" src="<?= base_url(['img', 'capa', $post->cover]) ?>">
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <b>Título: </b>
                <p class="text-muted"><?= $post->title; ?></p>
              </div>
              <div class="col-md-12">
                <b>Prévia: </b>
                <p class="text-muted"><?= mb_strimwidth($post->preview, 0, 50, "..."); ?></p>
              </div>
              <div class="col-md-12">
                <b>Criador por:</b>
                <p class="text-muted">
                  <?php foreach ($user as $us) : ?>
                    <?= $us->user_id == $post->user ? $us->username : '' ?>
                  <?php endforeach; ?>
                </p>
              </div>
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-5 data-padding">
                    <small><b>Data:</b> <span class="text-muted"><?= date('d/m/Y H:i', strtotime($post->date)) ?></span></small>
                  </div>
                  <div class="col-md-7 d-flex justify-content-end">
                    <div class="btn-control">
                      <button class="btn btn-outline-danger exc-pub" title="Deletar Postagem" data-id="<?= $post->id ?>" type="button">
                        <i class="far fa-trash-alt"></i>
                      </button>
                      <a href="<?= base_url(['publicacao', $post->id]) ?>" class="	btn 
											btn-outline-info 
											">
                        Editar
                      </a>

                      <?php
                      if (!empty($post->text) && $post->is_published == 0) {
                        $btn = "btn-outline-success";
                        $mod = "Esconder";
                        $text = "Publicar";
                      } elseif (!empty($post->text) && $post->is_published == 1) {
                        $btn = "btn-outline-danger";
                        $mod = "Publicar";
                        $text = "Esconder";
                      } else {
                        $btn = "hide";
                        $mod = "";
                        $text = "";
                      }
                      ?>
                      <button id="pub<?= $post->id ?>" class="	btn 
				            				<?= $btn; ?>
				            				alt-pub
				            				" data-id="<?= $post->id ?>" data-mod="<?= $mod; ?>" type="button">
                        <?= $text; ?>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <span id="e<?= $post->id ?>">
            </span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <br>
  <div class="d-flex justify-content-center">
    <?= $pager->links('default', 'boot_custom') ?>
  </div>
</main>
<script>
  let url = "<?= base_url(); ?>";
</script>