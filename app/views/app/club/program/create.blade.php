@extends('layouts.club')
@section('content')
<div class="container container-last">
    <div id="same-height-wrapper">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="col-md-4 signup-col same-height">
                    <h3>Create Program</h3>
                    <p></p>
                </div>
                <div class="col-md-7 same-height col-md-offset-1">
                    <h3>Create New Program</h3>
                    <p></p>
                    {{Form::open(array('action' => array('ProgramController@store'), 'class'=>'form-horizontal', 'method' => 'post')) }}
                    @if($errors->has())
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="alert alert-dismissable">
                                    <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
                                    <ul>
                                        @foreach ($errors->all() as $error) 
                                        <li class="text-danger">{{$error}}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-xs-12">
                            <h4>General Information</h4>
                            <p>All fields required</p>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Name</label>
                                <div class="col-sm-9">
                                    {{ Form::text('name','', array('class' => 'form-control')) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Description</label>
                                <div class="col-sm-9">
                                    {{Form::textarea('description', null, array('size' => '30x4','class'=>'form-control','placeholder'=>'Program Description')) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <hr />
                            <div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <a href="{{URL::action('EventoController@index')}}" class="btn btn-default">Cancel</a>
                                    <button type="submit" class="btn btn-primary btn-outline">Create</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>
</div>
@stop

