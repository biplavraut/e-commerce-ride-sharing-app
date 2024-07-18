<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Faq extends Model
{
    protected $guarded = ['id'];

    public $columnsWithTypes = [
		'faq_title' => 'string',
		'faq_description' => 'string',
		'order' => 'string'
	];
}
