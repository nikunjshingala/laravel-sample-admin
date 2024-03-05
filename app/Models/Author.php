<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    use HasFactory, SoftDeletes;
    protected $table="authors";
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'birthdate',
        'country',
        'type',
        'status'
    ];    
    /**
     * Get related author files detail
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function authorFiles(){
        return $this->hasMany('App\Models\AuthorFiles', 'author_id');
    }
}
