<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $table = 'comentarios';
    
    public function users(){
        return $this->belongsTo('app\User');
    }
    public function productos(){
        return $this->belongsTo('app\Modelos\Producto');
    }
}
