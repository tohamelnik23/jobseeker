<?php 
namespace App\Model; 
use Illuminate\Database\Eloquent\Model; 
class Media extends Model{
    protected $primaryKey 	= 'id';
	protected $table 		= 'media'; 
	protected $fillable 	= ['user_id', 'title', 'name', 'path', 'type', 'size', 'parent_id', 'status', 'is_default']; 
}