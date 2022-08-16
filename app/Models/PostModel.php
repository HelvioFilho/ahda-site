<?php

namespace App\Models;

use CodeIgniter\Model;

class PostModel extends Model
{
  protected $table = 'post';
  protected $primaryKey = 'id';
  protected $returnType = 'object';
  protected $allowedFields = ['title', 'text', 'preview', 'date', 'user', 'cover', 'is_published', 'date_post'];

  public function uploadImg($arquivo, $path)
  {
    require_once APPPATH . '/Libraries/Image_moo.php';

    $imageEdited = new \Image_moo();

    $newName = $arquivo->getRandomName();
    $arquivo->move('./img/' . $path, $newName);

    $imageEdited
      ->load('./img/' . $path . $newName)
      ->save('./img/' . $path . $newName, true);

    return $newName;
  }

  public function equal($equal, $compare){
    foreach($equal as $value){
      if($value == $compare){
        return 1;
      }
    }
    return 0;
  }

}
