<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Livro extends Model
{

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'categoria',
        'autor',
        'codigo_autor',
        'ano',
    ];
}