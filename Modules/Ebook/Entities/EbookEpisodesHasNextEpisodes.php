<?php

namespace Modules\Ebook\Entities;

use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Eloquent\Model;
use Modules\Ebook\Admin\Table\EbookTable;

class EbookEpisodesHasNextEpisodes extends Model
{
    protected $table = "episodes_has_next_episodes";

    protected $fillable = [
        'episode_id',
        'next_episode_id',
        'text',
        'is_even',
        'ci_auth'
    ];

     /**
     * Get table data for the resource
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function table($request)
    {
        $query = $this->newQuery()
            ->where('ci_auth', $request->session()->get('ci_auth'));
            
        return new EbookTable($query);
    }

        
}