<?php

class Team extends Eloquent {
	protected $fillable = array ('name','description', 'club_id', 'season_id', 'program_id', 'due','early_due' ,'early_due_deadline');
    
    /**
     * Validation rules
     */
    public static $rules = array(
        'name'    		        => 'required',
        'season_id'	            => 'required',
        'program_id'            => 'required',
        'due'			        => 'required',
        'early_due'             => 'required',
        'early_due_deadline'    => 'required|date',
    );

    // public function Program()
    // {
    //     return $this->hasOne('program', "id", "program_id");
    // }
    public function Members() {
        return $this->belongsToMany('player','members', 'team_id', 'player_id')->select('*')->withTimestamps();    
    }
    public function Season() {
        return $this->hasOne('seasons', "id", "season_id");    
    }
    public function Club()
    {
        return $this->hasOne('club', "id", "club_id");
    }

    public function setEarlyDueDeadlineAttribute($value)
    {
        $this->attributes['early_due_deadline'] =   date('Y-m-d', strtotime($value));
    }

    public function getEarlyDueDeadlineAttribute($value) 
    {
       return Carbon::createFromFormat('Y-m-d', $value)->format('m/d/Y');
    }
    public function getDueAttribute($value) 
    {
       return money_format('%.2n',$value);
    }

    public function getEarlyDueAttribute($value) 
    {
       return money_format('%.2n',$value);
    }

}