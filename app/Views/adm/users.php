<?php $tipo = [1 => "Administrador", 2 => "Moderador", 3 => "Usuário"] ?>
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
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
          <button id="confirmar" data-tipo="" data-id="" data-desc="" type="button" class="btn btn-success">Confirmar</button>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-2 col-lg-2">
    </div>
    <div class="col-md-8 col-lg-8">
      <?php if ($_SESSION['tipouser'] == 1 || $_SESSION['tipouser'] == 2) : ?>
        <div class="accordion my-4 mb-4" id="accordionUser">
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingUser">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUser" aria-expanded="false" aria-controls="collapseUser">
                Adicionar Usuário
              </button>
            </h2>
            <div id="collapseUser" class="accordion-collapse collapse" aria-labelledby="headingUser" data-bs-parent="#accordionUser">
              <div class="accordion-body">
                <form id="form-user" class="row g-3" action="<?= base_url(['user', 'add']) ?>" method="post" accept-charset="utf-8">
                  <div class="col-sm-6">
                    <label for="name" class="form-label">Nome</label>
                    <input type="text" class="form-control" name="name" id="name" required>
                    <div class="invalid-feedback">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" class="form-control" name="email" id="email" required>
                    <div class="invalid-feedback">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <select id="select-user" name="select" class="form-select" required>
                      <option value="" selected>Tipo de Usuário</option>
                      <option value="2">Moderador</option>
                      <option value="3">Usuário</option>
                    </select>
                    <div class="invalid-feedback">
                    </div>
                  </div>
                  <div class="col-sm-6">

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
      <?php endif; ?>
      <br>
      <h4 class="mb-3 text-primary">Usuários</h4>
      <?php foreach ($users as $user) : ?>
        <div id="d<?= $user->user_id ?>" class="card p-2">
          <h6 class="my-1 mb-1"><?= $user->username ?></h6>
          <?php if ($_SESSION['tipouser'] == 1 || $_SESSION['tipouser'] == 2) : ?>
            <?php if ($user->access == 1) : ?>
              <small class="text-muted mb-1"><?= $tipo[$user->access]; ?></small>
            <?php else : ?>
              <div class="col-sm-4 col-6">
                <select id="sel<?= $user->user_id; ?>" data-id="<?= $user->user_id; ?>" data-old="<?= $user->access ?>" class="form-select user-select mb-1 my-1">
                  <?php if ($user->access == 2) : ?>
                    <option value="2" selected>Moderador</option>
                    <option value="3">Usuário</option>
                  <?php else : ?>
                    <option value="2">Moderador</option>
                    <option value="3" selected>Usuário</option>
                  <?php endif; ?>
                </select>
              </div>
            <?php endif; ?>
          <?php else : ?>
            <small class="text-muted mb-1"><?= $tipo[$user->access]; ?></small>
          <?php endif; ?>
          <small class="mb-2"><b>Numero de postagens:</b> <?= $user->count_post; ?> </small>
          <?php switch ($_SESSION['tipouser']) {
            case 1:
              if ($_SESSION['user_id'] !== $user->user_id) {
                echo '<div class="my-2 mb-2">
								<button data-id="' . $user->user_id . '" class="btn btn-outline-danger btn-delete" type="button">Excluir</button>
				            	<button id="b' . $user->user_id . '" data-id="' . $user->user_id . '" data-des="' . $user->is_disabled . '" class="btn btn-outline-info btn-disabled" type="button">' . ($user->is_disabled ? 'Desativar' : 'ativar') . '</button>
							</div>';
              }
              break;
            case 2:
              if ($user->access !== "1") {
                echo '<div class="my-2 mb-2">
								<button data-id="' . $user->user_id . '" class="btn btn-outline-danger btn-delete" type="button">Excluir</button>
				            	<button id="b' . $user->user_id . '" data-id="' . $user->user_id . '" data-des="' . $user->is_disabled . '" class="btn btn-outline-info btn-disabled" type="button">' . ($user->is_disabled ? 'Desativar' : 'ativar') . '</button>
							</div>';
              }
              break;
          } ?>
          <span id="e<?= $user->user_id ?>"></span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</main>
<script>
  let url = "<?= base_url() ?>";
</script>