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
        return $this->belongsTo('Club');
    }

    public function teams()
    {
        return $this->hasMany('Team','program_id', 'id');
    }

    public function getTeams($user){
        $stats  = DB::table('programs AS p')
                ->where('p.user_id', '=', $user)
                ->orderBy('p.created_at', 'ASC')
                ->get();
        return $stats;

    }

}