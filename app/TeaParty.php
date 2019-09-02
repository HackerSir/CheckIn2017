<?php

namespace App;

/**
 * App\TeaParty
 *
 * @property int $club_id 對應社團
 * @property string $name 茶會名稱
 * @property \Illuminate\Support\Carbon|null $start_at 開始時間
 * @property \Illuminate\Support\Carbon|null $end_at 結束時間
 * @property string $location 地點
 * @property string|null $url 網址
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read \App\Club $club
 * @property-read bool $is_ended
 * @property-read bool $is_started
 * @property-read string $state
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeaParty newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeaParty newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeaParty query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeaParty whereClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeaParty whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeaParty whereEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeaParty whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeaParty whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeaParty whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeaParty whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeaParty whereUrl($value)
 * @mixin \Eloquent
 */
class TeaParty extends LoggableModel
{
    protected $primaryKey = 'club_id';
    public $incrementing = false;
    protected static $logName = 'club';

    protected $fillable = [
        'club_id',
        'name',
        'start_at',
        'end_at',
        'location',
        'url',
    ];

    protected $dates = [
        'start_at',
        'end_at',
    ];

    protected $appends = [
        'state',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    /**
     * @return bool
     */
    public function getIsStartedAttribute()
    {
        return $this->start_at->isPast();
    }

    /**
     * @return bool
     */
    public function getIsEndedAttribute()
    {
        return $this->end_at->isPast();
    }

    /**
     * @return string
     */
    public function getStateAttribute()
    {
        if ($this->is_ended) {
            return 'ended';
        }
        if ($this->is_started) {
            return 'in_process';
        }

        return 'not_started';
    }

    protected function getNameForActivityLog(): string
    {
        return $this->club->name . ' 的茶會資訊';
    }
}
