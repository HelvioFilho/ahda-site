<footer class="my-5 pt-5 text-muted text-center text-small">
    <p class="mb-1">&copy; <?=date('Y'); ?> A Hora do Anjo - Todos os direitos reservados.</p>
    <ul class="list-inline">
      <li class="list-inline-item">Desenvolvido por <a class="hsvf" target="_blank" href="https://hsvf.com.br">hsvf</a></li>
      
    </ul>
</footer>
<script src="<?=base_url(['assets','js','fontawesome.js']) ?>" type="text/javascript" charset="utf-8"></script>
<script src="<?=base_url(['assets','js','bootstrap.js']) ?>" type="text/javascript" charset="utf-8"></script>
<?=$uri->getSegment(1) == "publicacao" ? '<script src="'.base_url(['assets','js','quill','quill.js']).'"></script>' : '' ?>
<script src="<?=base_url(['assets','js',$uri->getSegment(1).'.js']) ?>" type="text/javascript" charset="utf-8"></script>