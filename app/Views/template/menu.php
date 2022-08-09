<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark" aria-label="Main navigation">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?=base_url()?>">
      <img class="anjo-icon" src="<?=base_url(['img','anjo-branco.png']) ?>" alt="">
      A Hora do Anjo
    </a>
    <button 
      class="navbar-toggler p-0 border-0"
      type="button" 
      data-bs-toggle="collapse" 
      data-bs-target="#navbarSupportedContent" 
      aria-controls="navbarSupportedContent" 
      aria-expanded="false" 
      aria-label="Toggle navigation"
    >
      <span class="navbar-toggler-icon"></span>
    </button>
    <?php 
      function active($uri, $target){
        $menu = $uri->getSegment(1);
        if($menu == "publicacao"){
          $menu = "publicacoes";
        }
        
        return $target === $menu ? 'class="nav-link active" aria-current="page"' : 'class="nav-link"';
      }
    ?>
    <div class="control-nav collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a <?=active($uri,'home')?> href="<?=base_url(['home'])?>">Home</a>
        </li>
        <li class="nav-item">
          <a <?=active($uri,'minha_conta')?> href="<?=base_url(['minha_conta'])?>">Minha Conta</a>
        </li>
        <li class="nav-item">
          <a <?=active($uri,'usuarios')?> href="<?=base_url(['usuarios'])?>">Usuários</a>
        </li>
        <li class="nav-item">
          <a <?=active($uri,'mensagens')?> href="<?=base_url(['mensagens'])?>">Mensagens <span class="badge bg-primary menu-b"><?=$countMsg;?></span></a>
        </li>
        <li class="nav-item">
          <a <?=active($uri,'publicacoes')?> href="<?=base_url(['publicacoes'])?>">Publicações</a>
        </li>
      </ul>
      <a class="btn btn-outline-danger" href="<?=base_url(['logoff']) ?>" >Sair</a>
    </div>
  </div>
</nav>