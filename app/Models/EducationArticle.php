<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class EducationArticle extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'education_articles';

    protected $fillable = [
        'title',
        'category',
        'preview',
        'content',
    ];
}
