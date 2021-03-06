<?php

namespace App;

/**
 * App\Booth
 *
 * @property int $id
 * @property string|null $zone 區域
 * @property int|null $club_id 對應社團
 * @property string $name 名稱
 * @property float|null $longitude 經度
 * @property float|null $latitude 緯度
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Club|null $club
 * @property-read string $embed_map_url
 * @method static \Illuminate\Database\Eloquent\Builder|Booth newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Booth newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Booth query()
 * @method static \Illuminate\Database\Eloquent\Builder|Booth whereClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booth whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booth whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booth whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booth whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booth whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booth whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booth whereZone($value)
 * @mixin \Eloquent
 */
class Booth extends LoggableModel
{
    protected static $logName = 'booth';

    protected $fillable = [
        'zone',
        'club_id',
        'name',
        'longitude',
        'latitude',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|\Illuminate\Database\Eloquent\Builder
     */
    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    /**
     * 內嵌地圖網址
     *
     * @return string
     */
    public function getEmbedMapUrlAttribute()
    {
        $url = 'https://www.google.com/maps/embed/v1/place';
        $queryParameters = [
            'key'  => config('services.google.map.embed_key'),
            'q'    => $this->latitude . ',' . $this->longitude,
            'zoom' => 18,
        ];
        $fullUrl = $url . '?' . urldecode(http_build_query($queryParameters));

        return $fullUrl;
    }

    protected function getNameForActivityLog(): string
    {
        return $this->name;
    }
}
