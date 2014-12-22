<?php

class Program extends Eloquent {
	protected $fillable = array('user_id','club_id','name','description');
    /**
     * Validation rules
     */
    public static $rules = array(
        'name'    => 'required'
    );

    public function Club()
    {
        return $this->belongsTo('club');
    }

    public function Teams()
    {
        return $this->hasMany('team', "program_id");
    }

    public function getTeams($user){
        $stats  = DB::table('programs AS p')
                ->where('p.user_id', '=', $user)
                ->orderBy('p.created_at', 'ASC')
                ->get();
        return $stats;

    }

}