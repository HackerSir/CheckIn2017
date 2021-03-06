<?php

namespace App;

/**
 * App\DataUpdateRequest
 *
 * @property int $id
 * @property int|null $user_id 申請者
 * @property int $club_id 社團
 * @property string $reason 申請理由
 * @property string|null $submit_at 申請提交時間
 * @property int|null $reviewer_id 審核者
 * @property string|null $review_at 審核時間
 * @property bool|null $review_result 審核通過
 * @property string|null $review_comment 審核評語
 * @property string|null $original_description 原簡介
 * @property string|null $original_url 原網址
 * @property string|null $description 簡介
 * @property string|null $url 網址
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $original_extra_info 原額外資訊
 * @property string|null $extra_info 額外資訊
 * @property string|null $original_custom_question 原自訂問題
 * @property string|null $custom_question 自訂問題
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Club $club
 * @property-read string $show_result
 * @property-read \App\ImgurImage|null $imgurImage
 * @property-read \App\ImgurImage|null $originalImgurImage
 * @property-read \App\User|null $reviewer
 * @property-read \App\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|DataUpdateRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DataUpdateRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DataUpdateRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|DataUpdateRequest whereClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataUpdateRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataUpdateRequest whereCustomQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataUpdateRequest whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataUpdateRequest whereExtraInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataUpdateRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataUpdateRequest whereOriginalCustomQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataUpdateRequest whereOriginalDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataUpdateRequest whereOriginalExtraInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataUpdateRequest whereOriginalUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataUpdateRequest whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataUpdateRequest whereReviewAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataUpdateRequest whereReviewComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataUpdateRequest whereReviewResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataUpdateRequest whereReviewerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataUpdateRequest whereSubmitAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataUpdateRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataUpdateRequest whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataUpdateRequest whereUserId($value)
 * @mixin \Eloquent
 */
class DataUpdateRequest extends LoggableModel
{
    protected static $logName = 'data-update-request';
    protected $fillable = [
        'user_id',
        'club_id',
        'reason',
        'submit_at',
        'reviewer_id',
        'review_at',
        'review_result',
        'review_comment',
        'original_description',
        'original_extra_info',
        'original_url',
        'original_custom_question',
        'description',
        'extra_info',
        'url',
        'custom_question',
    ];

    protected $casts = [
        'review_result' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function imgurImage()
    {
        return $this->morphOne(ImgurImage::class, 'club')->where('memo', 'new');
    }

    public function originalImgurImage()
    {
        return $this->morphOne(ImgurImage::class, 'club')->where('memo', 'original');
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     * @return string
     */
    public function getShowResultAttribute()
    {
        if (is_null($this->review_result)) {
            return '<span class="text-info"><i class="fas fa-fw fa-question mr-2"></i>等待審核</span>';
        }
        if ($this->review_result) {
            return '<span class="text-success"><i class="fas fa-fw fa-check mr-2"></i>通過</span>';
        }

        return '<span class="text-danger"><i class="fas fa-fw fa-times mr-2"></i>不通過</span>';
    }

    protected function getNameForActivityLog(): string
    {
        return $this->club->name . ' 的資料更新申請';
    }
}
