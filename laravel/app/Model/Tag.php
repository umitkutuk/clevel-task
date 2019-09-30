<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';

    protected $fillable = [
    	'name'
    ];

    protected $hidden = [
		'pivot',
        'created_at',
        'updated_at'
    ];

    public function notes() {
    	return $this->belongsToMany(Note::class, 'notes_tags');
    }
}
