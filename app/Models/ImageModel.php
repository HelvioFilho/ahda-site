<?php

namespace App\Models;

use CodeIgniter\Model;

class ImageModel extends Model
{
  protected $table = 'image';
  protected $primaryKey = 'id';
  protected $returnType = 'object';
  protected $allowedFields = ['path', 'post_id'];
}
