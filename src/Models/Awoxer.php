<?php

namespace Vo1\Seat\AwoxFinder\Models;

use Illuminate\Database\Eloquent\Model;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Universe\UniverseName;

class Awoxer extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $hidden = ['created_at', 'updated_at'];
    protected $fillable = [
        'id',
        'name',
        'description',
        'added_by',
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function character()
    {
        return $this->belongsTo(CharacterInfo::class, 'id', 'character_id');
    }

    public function universe_name()
    {
        return $this->hasOne(UniverseName::class, 'entity_id', 'id')
            ->withDefault([
                'category'  => 'character',
                'name'      => trans('web::seat.unknown'),
            ]);
    }
}
