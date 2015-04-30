<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Media extends Model {

	protected $table = 'media';
	protected $fillable = ['id', 'filename', 'embed_code'];

	public function scopeReadIdFilename($query, $id = NULL)
	{
		return $query->select(['filename'])->WhereId($id)->get();
	}
	
	public function scopeReadIdEmbed($query, $id = NULL)
	{
		return $query->select(['embed_code'])->WhereId($id)->get();
	}
	
}
