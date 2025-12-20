<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageContent extends Model
{
    protected $fillable = [
        'hero_title',
        'hero_subtitle',
        'hero_button_text',
        'hero_image_url',
        'products_description',
        'about_description',
        'about_image_url',
        'feature_1_title',
        'feature_1_desc',
        'feature_2_title',
        'feature_2_desc',
        'feature_3_title',
        'feature_3_desc',
        'contact_description',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'phone',
        'email',
        'address',
    ];
}
