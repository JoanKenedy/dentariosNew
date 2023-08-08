<?php

namespace App\Models\Course;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Course\Categorie;
use App\Models\Course\CourseSection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        "title",
        "subtitle",
        "slug",
        "imagen",
        "precio_usd",
        "precio_mex",
        "categorie_id",
        "sub_categorie_id",
        "user_id",
        "level",
        "idioma",
        "vimeo_id",
        "time",
        "description",
        "requirements",
        "who_is_it_for",
        "state",

    ];

    public function setCreatedAtAttributte($value){
     date_default_timezone_set("America/Mexico_City");
     $this->attributes["created_at"] = Carbon::now();

    }

    public function setUpdatedAtAttributte($value){
     date_default_timezone_set("America/Mexico_City");
     $this->attributes["updated_at"] = Carbon::now();

    }
    public function instructor(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function categorie(){
        return $this->belongsTo(Categorie::class);
    }
    public function sub_categorie(){
        return $this->belongsTo(Categorie::class);
    }
    public function sections(){
        return $this->hasMany(CourseSection::class);
    }
}
