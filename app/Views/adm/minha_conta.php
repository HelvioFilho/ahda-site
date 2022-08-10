<main class="container">
<div class="row">
	<div class="col-md-2 col-lg-2"></div>
	<div class="col-md-8 col-lg-8">
	<form id="uploadimage" class="ups" action='<?=base_url(['minha_conta','atualizar_img']) ?>' method="post" enctype="multipart/form-data">
	    <div class="d-flex">
	    	<input class="file-chooser item-image" name="arquivo" type="file" accept="image/*" hidden>
	        <div class="p-2 ">
	        	<img 
		        	class="preview-img" 
		        	src="<?=base_url(['img','user',($data->img !== "" ? $data->img : 'generic.jpeg')])?>" 
		        	alt="avatar do usuário">
	        </div>  
	    	<div class="p-2 d-flex align-items-end">
	        	<div class="file-button p-2 btnImage"><i class="fas fa-plus-circle"></i> <span class="Avatar_error">*</span> Selecionar Imagem</div>
	        	<input type="hidden" name="id" value="<?=$data->user_id;?>">
	        	<input type="hidden" name="img" value="<?=$data->img;?>">
	        	<div class="p-2">
	        		<input id="changeAvatar" class="btn btn-outline-success" type="submit" value="Enviar">
	        	</div>
	        </div>
	    </div>
	    <span class="Avatar_error">* Imagem não pode ser vazia.</span>
	</form>
    <?php if($session->getFlashdata('error')): ?>
    <?=($session->getFlashdata('error') === "success")? '<div class="alert alert-success alert-dismissible fade show" role="alert">
  Imagem atualizada com sucesso!
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>' :  '<div class="alert alert-danger alert-dismissible fade show" role="alert">
  '.$session->getFlashdata('error').'
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>'; ?>
	<?php endif; ?>
        <h4 class="mb-3 mt-5 text-primary">Minha Conta</h4>
        <form class="row g-3">
            <div class="col-sm-6">
              <label for="name" class="form-label">Nome</label>
              <input type="text" class="form-control" id="name" value="<?=$data->username; ?>" required>
              <div class="invalid-feedback">
              </div>
            </div>
            <div class="col-sm-6">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" value="<?=$data->email;?>" required>
              <div class="invalid-feedback">
              </div>
            </div>
			<div class="col-12">
            	<label for="sobre" class="form-label">Sobre</label>
              	<textarea 
                	rows="3" 
                	class="form-control mb-2" 
                	id="sobre"
                	maxlength="500"
                	required
               	><?=$data->about;?></textarea>
                <p>Total de letras: <span class="caracteres">20</span></p>
			</div>
			<div class="update-erro">
			</div>
			<button id="infor-update" type="button" class="btn btn-outline-success ms-auto">Atualizar</button>
			<hr>
			<h4 class="mb-3 text-primary">Alterar Senha</h4>
			<div class="col-sm-6">
				<label for="password" class="form-label">Nova senha</label>
				<input type="password" autocomplete="off" class="form-control" id="password">
				<div class="invalid-feedback">
				</div>
			</div>
			<div class="col-sm-6">
				<input id="hidden" type="hidden" name="id" value="<?=$data->user_id?>">
				<label for="repassword" class="form-label">Repetir senha</label>
				<input type="password" autocomplete="off" class="form-control" id="repassword" >
				<div class="invalid-feedback">
				</div>
			</div>
			<div class="pss-erro">
			</div>
			<button id="pss-update" type="button" class="btn btn-outline-success ms-auto">Atualizar</button>
	    </form>	
    </div>
</div>
</main>
<script>
let url = "<?=base_url();?>";
let session_email = "<?=$_SESSION['email']?>";
</script>