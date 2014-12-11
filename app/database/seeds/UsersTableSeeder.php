<?php

// Composer: "fzaninotto/faker": "v1.3.0"
//use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder {

	public function run()
  {
    
    $user = new User;
    $user->username              = 'jd.hernandez@me.com';
    $user->email                 = 'jd.hernandez@me.com';
    $user->password              = '@Password1';
    $user->password_confirmation = '@Password1';
    $user->confirmed             = 1;
    $user->confirmation_code     = md5(uniqid(mt_rand(), true));
    $user->save();

    $profile = new Profile;
    $profile->user_id   =   $user->id;
    $profile->firstname = 'David';
    $profile->lastname  = 'Hernandez';
    $profile->mobile    = '(916)952-5736';
    $profile->dob       = '09/24/1986';
    $profile->avatar    = '/img/coach-avatar.jpg';
    $profile->save();


    if(!$user->id) {
      Log::info('Unable to create user '.$user->username, (array)$user->errors());
    } else {
      Log::info('Created user "'.$user->username.'" <'.$user->email.'>');
    }

    $user1 = new User;
    $user1->username              = 'david.hernandez@plusconsulting.co';
    $user1->email                 = 'david.hernandez@plusconsulting.co';
    $user1->password              = '@Password1';
    $user1->password_confirmation = '@Password1';
    $user1->confirmed             = 1;
    $user1->confirmation_code     = md5(uniqid(mt_rand(), true));
    $user1->save();

    $profile1 = new Profile;
    $profile1->user_id   =   $user1->id;
    $profile1->firstname = 'David';
    $profile1->lastname  = 'Hernandez';
    $profile1->mobile    = '(916)952-5736';
    $profile->dob        = '09/24/1986';
    $profile1->avatar    = '/img/coach-avatar.jpg';
    $profile1->save();

    if(!$user1->id) {
      Log::info('Unable to create user '.$user1->username, (array)$user1->errors());
    } else {
      Log::info('Created user "'.$user1->username.'" <'.$user1->email.'>');
    }

    
    $admin = new Role;
    $admin->name = 'administrator';
    $admin->save();

    $club = new Role;
    $club->name = 'club owner';
    $club->save();

    $club2 = new Role;
    $club2->name = 'club administrator';
    $club2->save();

    $default = new Role;
    $default->name = 'default';
    $default->save();

    $user->attachRole($club);
    $user1->attachRole($default);

  }

}