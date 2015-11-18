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

    public static $rules_group =array(
        'name'          =>'required',
        'max'           =>'required|integer',
    );

    public function program(){
        return $this->hasOne('Program', "id", "program_id");
    }
    public function members() {
        return $this->hasMany('Member', 'team_id', 'id');
        //return $this->belongsToMany('Player','members')->withPivot('accepted_on','created_at','id');    
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

    public function children()
    {
        return $this->hasMany('Team', 'parent_id', 'id');
    }
    public function parent()
    {
        return $this->belongsTo('Team', 'parent_id');
    }

    public function waitlist() {
        return $this->hasMany('Waitlist', 'team_id', 'id');
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

    public function aggregateMembers()
    {   $count = 0;
        foreach ($this->children as $e) {
            foreach ($e->members()->where('status', '=', 1)->get() as $p) {
                $count++;
            }
        }
        return $count + $this->members()->where('status', '=', 1)->get()->count();
    }

    public function aggregateSales()
    {   $count = 0;

        foreach ($this->children as $e) {

           $count += Item::where('team_id',$e->id)->sum('price');

        }
        return $count + Item::where('team_id',$this->id)->sum('price');
    }

    public function aggregateReceivable()
    {   $count = 0;

        foreach ($this->children as $e) {

           $count += SchedulePayment::with('member')->whereHas('member', function ($query) use ($e) {$query->where('team_id', '=', $e->id);})->sum('subtotal');

        }
        return $count + SchedulePayment::with('member')->whereHas('member', function ($query) use ($e) {$query->where('team_id', '=', $this->id);})->sum('subtotal');
    }


}