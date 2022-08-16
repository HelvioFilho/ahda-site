<?php

namespace App\Models;

use CodeIgniter\Model;

class StatusModel extends Model
{
  protected $table = 'status';
  protected $primaryKey = 'id';
  protected $returnType = 'object';
  protected $allowedFields = ['post_id', 'data', 'date'];
}
