<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $table = 'notes';

    protected $fillable = [
    	'name', 'content'
    ];

    public function tags() {
    	return $this->belongsToMany(Tag::class, 'notes_tags');
    }
}
