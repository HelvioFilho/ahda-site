<?php namespace App\Models;

use CodeIgniter\Model;

class PostModel extends Model{
  protected $table = 'post';
  protected $primaryKey = 'id';
  protected $returnType = 'object';
  protected $allowedFields = ['title', 'text', 'preview', 'date', 'user', 'cover', 'is_published', 'date_post'];

}