<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
//هو مودل لجدول وسيط فالافضل ننخليه extend pivot
class ClassworkUser extends Pivot
{
    use HasFactory;

    public function getUpdatedAtColumn()
    {
    }

    // public function setUpdatedAt($value)
    // {
    //     // $this->{$this->getUpdatedAtColumn()} = $value;// هنا رح يرجع اسم العمود تبع الابديت
    //     return $this;
    // }
}
