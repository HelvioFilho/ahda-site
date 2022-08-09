<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>A Hora do Anjo</title>
<link rel="shortcut icon" type="image/x-icon" href="<?=base_url(['icone','angel.svg']) ?>">
<link rel="stylesheet" type="text/css" href="<?=base_url(['assets','css','bootstrap.css']) ?>">
<?=$uri->getSegment(1) == "publicacao" ? '<link href="'.base_url(['assets','css','quill.css']).'" rel="stylesheet">' : '' ?>
<link rel="stylesheet" type="text/css" href="<?=base_url(['assets','css','style.css']) ?>">
<link rel="stylesheet" type="text/css" href="<?=base_url(['assets','css',$uri->getSegment(1).'.css']) ?>">