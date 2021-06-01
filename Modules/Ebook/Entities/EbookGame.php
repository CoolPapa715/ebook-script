<?php

namespace Modules\Ebook\Entities;

use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Eloquent\Model;


class EbookGame extends Model
{
    protected $table = "games";

    protected $fillable = [
        'hero_id',
        'user_id',
        'ebook_id',
        'status_id'
    ];

     /**
     * Get table data for the resource
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
   

 
    
}