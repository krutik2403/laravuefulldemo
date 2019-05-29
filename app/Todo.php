<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Storage;

class Todo extends Model
{
    protected $table = 'todos';
    protected $fillable = [
    	'user_id',
    	'title',
    	'image',
    	'description'
	];
	
	protected $appends = ['image_url'];

	public function getImageUrlAttribute() {
		if($this->image != '' && Storage::disk('public')->exists('todo_images/' . $this->image)) {
			return Storage::disk('public')->url('todo_images/' . $this->image);
		}else {
			return null;
		}
	}
}
