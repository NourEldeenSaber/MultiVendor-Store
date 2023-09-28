<?php

namespace App\Models;

use App\Rules\Filter;
use App\Models\Product;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory , SoftDeletes;
    
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'description',
        'image',
        'status',
    ];
    // public function scopeActive(Builder $builder){
    //     $builder->where('status' , '=' , 'active');
    // }


    public function products(){
        return $this->hasMany(Product::class,'category_id','id');
    }

    public function parent(){
        return $this->belongsTo(Category::class , 'parent_id' , 'id')
        ->withDefault([
            'name' => '-'
        ]);
    }

    public function children(){
        return $this->hasMany(Category::class , 'parent_id' , 'id');
    }

    public function scopeFilter(Builder $builder , $filters){
        // When Way
        $builder->when($filters['name'] ?? false , function($builder , $value){
            $builder->where('categories.name' , 'LIKE' , "%{$value}%");
        });
        $builder->when($filters['status'] ?? false , function($builder , $value){
            $builder->where('categories.status' , '=' , $value );
        });

        // IF Way
        // if($filters['name'] ?? false){
        //     $builder->where('name'  , 'LIKE' , "%{$filters['name']}%");
        // }
        // if($filters['status'] ?? false){
        //     $builder->where('status' , '=' , $filters['status']);
        // }
    }
    public static function rules($id=0){
      return  [
            'name' =>[
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('categories','name')->ignore($id),

                function($attribute,$value,$fails){
                    if(strtolower($value) == 'laravel'){
                        $fails('this name is forbidden!');
                    }
                }
         
            ],
            'parent_id' => [
                'nullable', 'int' , 'exists:categories,id',
            ],
            'image' => [
                'image' , 'max:1048576' ,'dimensions:min_width=100,min_height=100',
            ],
            'status' => 'in:active,archived',
        ];
    }

    // Black List $guarded   منع
 }
