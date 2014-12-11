<?php

class Item extends Eloquent {
	protected $fillable = [];
	protected $table = 'payment_item';

	public function payments()
    {
        return $this->belongsTo('Payment');
    }

}