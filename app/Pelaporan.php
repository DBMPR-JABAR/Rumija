<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pelaporan extends Model
{
    //
    protected $table = 'rumija_report';
    protected $fillable = ['pelapor_names','pelapor_ktps','pelapor_uptd','pelapor_numbers','pelapor_emails','pelapor_address'];
}
