<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateInputField extends Model
{
    protected $guarded = [];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }
}
