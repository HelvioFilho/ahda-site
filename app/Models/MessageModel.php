<?php namespace App\Models;

use CodeIgniter\Model;

class MessageModel extends Model{
  protected $table = 'message';
  protected $primaryKey = 'id';
  protected $returnType = 'object';
  protected $allowedFields = ['name', 'email', 'message', 'is_read', 'date'];

}