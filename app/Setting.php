<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Setting extends Model
{
    protected $fillable = [
        'ck','cs','shortcode','vURL','aURL'
    ];
}
