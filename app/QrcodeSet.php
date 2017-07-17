<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\QrcodeSet
 *
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Qrcode[] $qrcodes
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QrcodeSet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QrcodeSet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QrcodeSet whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class QrcodeSet extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\Illuminate\Database\Eloquent\Builder
     */
    public function qrcodes()
    {
        return $this->hasMany(Qrcode::class);
    }
}