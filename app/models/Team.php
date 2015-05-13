<?php

class Team extends Eloquent {
	protected $fillable = array ('id','name','description', 'club_id', 'season_id', 'program_id', 'due','early_due' ,'early_due_deadline');
    
    /**
     * Validation rules
     */
    public static $rules = array(
        'name'          => 'required',
        'season_id'     => 'required',
        'program_id'    => 'required',
        'description'   => 'required',
        'early_due'     => 'required',
        'early_due_deadline' => 'required|date',
        'due'           => 'required',
        'open'          => 'required|date',
        'close'         => 'required|date',
        'max'           => 'required|integer',
        'status'        => 'required'
    );

    public function program(){
        return $this->hasOne('Program', "id", "program_id");
    }
    public function members() {
        return $this->belongsToMany('Player','members')->withPivot('accepted_on','created_at','id');    
    }

    public function coaches() {
        return $this->hasMany('Coach','team_id','id');    
    }

    public function season() {
        return $this->hasOne('Seasons', "id", "season_id");    
    }
    public function plan() {
        return $this->hasOne('Plan', "id", "plan_id");    
    }

    public function club()
    {
        return $this->hasOne('Club', "id", "club_id");
    }

    public function setEarlyDueDeadlineAttribute($value)
    {
        if($value){
            $this->attributes['early_due_deadline'] =   date('Y-m-d', strtotime($value));
        }
    }

    public function setPlanIdAttribute($value)
    {
        if($value == ""){
           return  $this->attributes['plan_id'] =   null;
        }

        $this->attributes['plan_id'] =   $value;
    }


    public function getEarlyDueDeadlineAttribute($value) 
    {
        if($value){
            return Carbon::createFromFormat('Y-m-d', $value)->format('m/d/Y');
        }
    }
    public function getDueAttribute($value) 
    {
       return "$".number_format($value, 2);
    }

    public function getEarlyDueAttribute($value) 
    {
       return "$".number_format($value, 2);
    }

    public function setOpenAttribute($value)
    {
        $this->attributes['open'] =   date('Y-m-d', strtotime($value));
    }

    public function getOpenAttribute($value) 
    {
        return Carbon::createFromFormat('Y-m-d', $value)->format('m/d/Y');

    }

    public function setCloseAttribute($value)
    {
        $this->attributes['close'] =   date('Y-m-d', strtotime($value));
    }

    public function getCloseAttribute($value) 
    {
        return Carbon::createFromFormat('Y-m-d', $value)->format('m/d/Y');

    }

    public function getStatusAttribute($value){
        if($value){ return array('name'=>'Available', 'id'=>1);};
        return array('name'=>'Unavailable', 'id'=>0);
    }


}