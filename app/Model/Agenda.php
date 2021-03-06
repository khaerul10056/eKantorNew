<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Reference\Employee;

class Agenda extends Model
{
    //
    protected $guarded = [];
    protected $dates = ['tanggal_awal','tanggal_akhir'];


    function employee()
    {
        return $this->hasOne(Employee::class,'id','employee_id');
    }

    function employeeFor()
    {
        return $this->hasOne(Employee::class,'id','employee_for_id');
    }
}
