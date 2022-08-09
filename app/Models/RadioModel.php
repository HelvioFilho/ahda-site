<?php namespace App\Models;

use CodeIgniter\Model;

class RadioModel extends Model{
  protected $table = 'radio';
  protected $primaryKey = 'id';
  protected $returnType = 'object';
  protected $allowedFields = ['link'];

}