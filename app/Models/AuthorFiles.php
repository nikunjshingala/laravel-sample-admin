<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorFiles extends Model
{
    use HasFactory;
    protected $table = "author_files";

    /**
     * Get related author detail
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function author(){
        return $this->belongsTo('App\Models\Author', 'author_id', 'id');
    }
}
