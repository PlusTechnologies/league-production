@extends('layouts.club')
@section('content')
<div class="container">
   <div class="row">
      <div class="col-xs-10 col-sm-offset-1">
         <div id="same-height-wrapper">
            <div class="row">
               <div class="col-xs-12">
                  <div class="col-xs-4 signup-col same-height">
                     <h3>New Team</h3>
                     <p>Create teams for regular season.</p>
                  </div>
                  <div class="col-xs-7 same-height col-xs-offset-1">
                     <h3 class="">Create New Team.</h3>
                     <p>All fields are required</p>
                     <br>
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
                     @if(Session::has('warning'))
                     <div class="row">
                        <div class="col-sm-12">
                           <div class="form-group">
                              <div class="alert alert-dismissable">
                                 <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
                                 <p class="text-danger">{{Session::get('warning')}}</p>
                              </div>
                           </div>
                        </div>
                     </div>
                     @endif
                     @if(Session::has('notice'))
                     <div class="row">
                        <div class="col-sm-12">
                           <div class="form-group">
                              <div class="alert alert-dismissable">
                                 <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
                                 <p class="text-success">{{Session::get('notice')}}</p>
                              </div>
                           </div>
                        </div>
                     </div>
                     @endif
                     {{ Form::open(array('action' => array('TeamController@store'),"class"=>"form-horizontal",'id'=>'new_team','method' => 'post')) }}
                     <div class="row">
                        <div class="col-xs-12">
                           <h4>Team details</h4>
                           <p>Enter Team information.</p>
                           <div class="form-group">
                              <label class="col-sm-3 control-label">Team's Name</label>
                              <div class="col-sm-9">
                                 {{Form::text('name', '', array('class'=>'form-control','placeholder'=>'Name', 'tabindex'=>'1')) }}
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="col-sm-3 control-label">Season</label>
                              <div class="col-sm-9">
                                 {{ Form::select('season_id', $seasons, '', array('class' => 'form-control', 'tabindex'=>'2') ) }}
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="col-sm-3 control-label">Program</label>
                              <div class="col-sm-9">
                                 {{ Form::select('program_id', $club->programs->lists('name', 'id'), '', array('class' => 'form-control', 'tabindex'=>'3') ) }}
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-xs-12">

                           <h4>About the Team</h4>
                           <p>Add helpful information that descrives this team profiles.</p>
                           <div class="form-group">

                              <label class="col-sm-3 control-label">Description</label>
                              <div class="col-sm-9">
                                 {{Form::textarea('description', null, array('id'=>'description','size' => '30x4','class'=>'form-control','placeholder'=>'Team Description', 'tabindex'=>'4')) }}
                              </div>

                           </div>
                        </div>
                     </div>

                     <div class="row">
                        <div class="col-xs-12">
                           <h4>Membership details</h4>
                           <p>Team Membership default information.</p>
                           <div class="form-group">
                              <label class="col-sm-3 control-label">Early Bird Dues</label>
                              <div class="col-sm-9">
                                 {{Form::text('early_due', '', array('class'=>'','id'=>'ebdues','placeholder'=>'Early Bird Dues', 'tabindex'=>'5')) }}
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="col-sm-3 control-label">Deadline</label>
                              <div class="col-sm-9">
                                 {{Form::text('early_due_deadline', '', array('id'=>'deadline','class'=>'form-control kendo-datepicker','placeholder'=>'MM/DD/YYYY', 'tabindex'=>'6')) }}
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="col-sm-3 control-label">Dues</label>
                              <div class="col-sm-9">
                                 {{Form::text('due', '', array('id'=>'dues','placeholder'=>'Team Dues','tabindex'=>'7')) }}
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="col-sm-3 control-label">Payment Plan</label>
                              <div class="col-sm-9">
                                 {{ Form::select('plan_id', [null =>'Select Plan'] + $plan,'', array('class' => 'form-control', 'tabindex'=>'8') ) }}
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-xs-12">
                           <h4>Registration Information</h4>
                           <p>All fields required</p>
                           <div class="form-group">
                              <label class="col-sm-3 control-label">Open</label>
                              <div class="col-sm-9">
                                 {{ Form::text('open',null, array('class' => 'form-control datepicker', 'tabindex'=>'9', 'placeholder'=>'MM/DD/YYYY')) }}
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="col-sm-3 control-label">Close</label>
                              <div class="col-sm-9">
                                 {{ Form::text('close',null, array('class' => 'form-control datepicker','tabindex'=>'10','placeholder'=>'MM/DD/YYYY')) }}
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="col-sm-3 control-label">Max. Players</label>
                              <div class="col-sm-9">
                                 {{ Form::text('max',null, array('class' => 'form-control','tabindex'=>'11')) }}
                                 <span id="helpBlock" class="help-block">Max. number of players</span>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-xs-12">
                           <h4>Status</h4>
                           <p>All fields required</p>
                           <div class="form-group">
                              <label class="col-sm-3 control-label">Status</label>
                              <div class="col-sm-9">
                                 {{ Form::select('status', ['Unavailable','Available'],'', array('class' => 'form-control') ) }}
                              </div>
                           </div>
                        </div>
                     </div>

                     <div class="row">
                        <div class="col-xs-12">
                           <hr />
                           <div class="form-group">
                              <div class="col-sm-12 text-right">
                                 <button class="btn btn-primary btn-outline" type="submit" id="add-team">Create Team</button>
                                 <a href="{{ URL::action('TeamController@index') }}" class="btn btn-default btn-outline" >Cancel</a>
                              </div>
                           </div>
                        </div>
                     </div>
                     {{ Form::close() }}
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@stop
@section('script')
<script type="text/javascript">

$(document).ready(function () {
   $("#deadline, .datepicker").kendoDatePicker();
   $("#deadline, .datepicker").bind("focus", function () {
      $(this).data("kendoDatePicker").open();
   });
   $("#ebdues, #dues").kendoNumericTextBox({
      format: "c",
      decimals: 2
   });
});

// $(window).load(function() {

//     $("#dues, #ebdues").maskMoney({thousands:',', decimal:'.', allowZero:true, prefix:'USD$ ',});
//     $("#dues, #ebdues").maskMoney('mask');

//     $('.hasDatepicker').each(function() { 

//         var dateFormat = $(this).val();
//         if(dateFormat){
//             var date = new Date(dateFormat); 
//             date.setDate(date.getDate() + 1); 
//             var dateFormat = $.datepicker.formatDate('MM dd, yy',date);
//             $(this).val(dateFormat);
//         }

//     });
// });

// $(function() {
//     $('#add-team').click(function(event){
//         event.preventDefault();
//         //clean dates
//         var dateTypeVar_start = $('#deadline').datepicker('getDate');
//         $('#deadline').val($.datepicker.formatDate('yy-mm-dd', dateTypeVar_start));
//         //clean amount 
//         $("#dues").val($("#dues").maskMoney('unmasked')[0]);
//         $("#ebdues").val($("#ebdues").maskMoney('unmasked')[0]);

//         //submit form
//         $( "#new_team" ).submit()
//     });

//     $("#dues, #ebdues").maskMoney({thousands:',', decimal:'.', allowZero:true, prefix:'USD$ ',});
//     // jQuery UI Datepicker JS init
//     var datepickerSelector = '#deadline';
//     $(datepickerSelector).datepicker({
//         showOtherMonths: true,
//         selectOtherMonths: true,
//         dateFormat: "MM d, yy",
//         yearRange: '-3:+3'
//     }).prev('.btn').on('click', function (e) {
//         e && e.preventDefault();
//         $(datepickerSelector).focus();
//     });
//     // Now let's align datepicker with the prepend button
//     $(datepickerSelector).datepicker('widget').css({'margin-left': -$(datepickerSelector).prev('.btn').outerWidth() - 49});
// });

</script>
@stop