<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos'; 
    
    public function comentarios(){
        return $this->hasMany('app\Modelos\Comentario');
    }
}