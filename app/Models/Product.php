<?php

namespace App\Models;

use App\Models\Store;
use App\Models\Category;
use App\Models\Scopes\StoreScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'category_id',
        'name',
        'slug',
        'description',
        'image',
        'price',
        'compare_price',
        'options',
        'rating',
        'featured',
        'status',
    ];


    protected static function booted()
    {
        static::addGlobalScope('store' , new StoreScope() );

    }

    public function category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }

    public function store(){
        return $this->belongsTo(Store::class,'store_id','id');
    }

    public function tags(){

        return $this->belongsToMany(
            Tag::class,      // Related Model
            'product_tag',   // Pivot table name
            'product_id',    // FK in pivot table for the current model
            'tag_id' ,       // FK in pivot table for the related model
            'id' ,           // PK for the current model
            'id'             // PK for the related model
        );

    }

    public function scopeActive(Builder $builder){
        $builder->where('status' , '=' , 'active');
    }


    //Accessors
    public function getImageUrlAttribute(){
    if(!$this->image) {
        return "https://www.esom.so/public/web/images/defaultProduct.jpg";
    }
    if(Str::startsWith($this->image, ['http://' , 'https://'])){
        return $this->image;
    }
    return asset('storage/' . $this->image);
    }
    
    public function getSalePercentAttribute(){
        if(!$this->compare_price){
            return  0;
        }
        return round(100 - (100 * $this->price / $this->compare_price),0);
    }
}
