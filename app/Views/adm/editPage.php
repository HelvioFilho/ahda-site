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
      <h4 class="mb-3 text-primary">Carrossel de Imagens</h4>
      <div class="card p-2">
      <style>
          .image_error {
            color: red;
            visibility: hidden
          }

          .carousel-item img {
            margin: auto;
            width: 400px;
            height: 350px;
          }

          .delete {
            position: absolute;
            top: 0px;
            right: 20%;
            text-decoration: none;
          }
        </style>
        <div class="col-sm-12">
          <br>
          <form id="form-img" class="ups" action='<?= base_url(['painel', 'add_carousel']) ?>' method="post" enctype="multipart/form-data">
            <div class="d-flex justify-content-center align-items-center">
              <input class="file-carousel-chooser item-image" name="arquivoCarousel" type="file" accept="image/*" hidden>
              <div class="p-2 d-flex align-items-end">
                <div class="file-carousel-button p-2 btnImage"><i class="fas fa-plus-circle"></i> <span class="image_error">*</span> <span class="image-value-carousel">Selecionar Imagem</span></div>
                <input type="hidden" name="id" value="<?= $post->id; ?>">
                <div class="p-2">
                  <input id="addImageToCarousel" class="btn btn-outline-success" type="button" value="Enviar Imagem">
                </div>
              </div>
            </div>
            <span class="image_error">* Imagem não pode ser vazia.</span>
          </form>
          <br><br>
          <div id="carouselExampleControls" class="carousel slide carousel-dark d-flex justify-content-center align-items-center" data-bs-interval="false" data-bs-ride="carousel">
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img src="<?= base_url(['img', 'capa', 'capaoutravezagoraaultimaserio2557.jpg']) ?>" class="d-block" alt="...">
                <button class="btn btn-danger delete" title="Deletar Imagem" data-id="1" type="button">
                  <i class="far fa-trash-alt"></i>
                </button>
              </div>
              <div class="carousel-item">
                <img src="<?= base_url(['img', 'capa', 'capaoutrotituloparanaoficarrepetitivo8811.jpeg']) ?>" class="d-block" alt="...">
                <button class="btn btn-danger delete" title="Deletar Imagem" data-id="2" type="button">
                  <i class="far fa-trash-alt"></i>
                </button>
              </div>
              <div class="carousel-item">
                <img src="<?= base_url(['img', 'capa', 'TituloAcaiemangairmao6008.jpg']) ?>" class="d-block" alt="...">
                <button class="btn btn-danger delete" title="Deletar Imagem" data-id="3" type="button">
                  <i class="far fa-trash-alt"></i>
                </button>
              </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>
        </div>
      </div>
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
          <div class="col-sm-12 d-flex justify-content-center align-items-center">
            <div>
              <input class="file-chooser item-image" name="arquivo" type="file" accept="image/*" hidden>
              <div class="file-button p-2 btnImage"><i class="fas fa-plus-circle"></i> Alterar Imagem de capa</div>
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
            <br>
            <label for="preview" class="form-label">Conteúdo</label>
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
              <button class="btn btn-outline-success my-4" type="submit" id="enviarTudo">Enviar Atualizações</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>
<script>
  let url = "<?= base_url(); ?>/",
    arqId = "<?= $post->id; ?>",
    caminho = "<?= $post->title; ?>";

  let classImage = document.getElementsByClassName('delete')

  Array.from(classImage).forEach(function(item) {
    item.addEventListener('click', function(e) {
      e.preventDefault();
      let n = this.dataset.id;
      console.log(n);
    });
    // this.addEventListener('click', function(e){
    //   let n = this.dataset.id;
    //   console.log(n);
    // });
  });
</script>