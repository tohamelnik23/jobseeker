<div class = "shift-time-item mar-btm clearfix">
    <div class="row">
        <div class  =   "form-group col-lg-3">  
            <div class="col-sm-12 input-group  eb input-daterange">
                <input type="text" class="form-control edit job_date job_start_date"  @if(isset($shift_time_type)) name = "job_date"  @else  name = "job_date[]"  @endif placeholder = "Start Date..."   data-content = "The start date is required"  @if(isset($item_job)) value = "{!!  date('Y-m-d', strtotime($item_job->getJobDate())) !!}" @else value = "{{ date('Y-m-d') }}"  @endif autocomplete="off"  />
                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span> 
            </div>
            <div class="col-sm-12">
                <span class="help-block">
                    <strong></strong>  
                </span> 
            </div> 
        </div>
        <div class  =   "form-group col-lg-3">
            <div class="col-sm-12 input-group bootstrap-timepicker timepicker eb">
                <input type="text" class="form-control edit job_time" @if(isset($shift_time_type)) name="job_time" @else name="job_time[]" @endif placeholder="Start Time..."  data-content = "The start time is required" @if(isset($item_job)) value = "{!! $item_job->getJobTime() !!}"  @endif autocomplete="off" />
                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span> 
            </div>
            <div class="col-sm-12">
                <span class="help-block">             
                    <strong></strong>  
                </span> 
            </div>
        </div>
        <div class  =   "form-group col-lg-3">
            <div class="col-sm-12 input-group bootstrap-timepicker timepicker eb">
                <input type = "text" class = "form-control edit job_time job_end_time"  @if(isset($shift_time_type)) name = "job_end_time" @else name = "job_end_time[]"  @endif placeholder = "End Time..."  data-content = "The time is required" @if(isset($item_job)) value = "{!! $item_job->getJobTime('end') !!}"  @endif autocomplete = "off" />
                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span> 
            </div>
            <div class="col-sm-12">
                <span class="help-block">
                    <strong></strong>
                </span>
            </div>
        </div>

        @if(!isset($shift_time_type))
        <div class  =   "form-group col-lg-3">
            <button class="btn btn-danger btn-icon btn-circle remove-shift-time-button" type = "button">
                <i class="fa fa-minus "></i>
            </button>
        </div>
        @endif

    </div>
    <div class = "form-group clearfix">
        <div class = "col-lg-12  after_end_start_error has-error hidden">
            <span class="help-block">             
                <strong>Please select the before and end time correctly </strong>  
            </span>
        </div>
    </div>  
    <div class="form-group expire_date_group hidden clearfix"> 
        <div class = "col-lg-12">
            <span class = "text-dark h5"> Remove shift from DiscoverGigs when: </span>  
            <span class = "text-dark h5">
                <input type="hidden"  @if(isset($shift_time_type))  name = "duration_picker"  @else   name = "duration_picker[]" @endif class = "duration_picker" @if(isset($item_job)) value = "{!! $item_job->duration !!}"  @endif  />
            </span>
            <select class = "inline-form-control"  @if(isset($shift_time_type)) name = "duration_type"  @else name = "duration_type[]"  @endif>
                <option value = "after"  @if(isset($item_job) && ($item_job->duration_type == 'after'))  selected @endif  >After start</option>
                <option value = "before" @if(isset($item_job) && ($item_job->duration_type == 'before')) selected @endif >Before end</option>
            </select>
            <span class = "text-dark h5"> of Shift  </span>
        </div>
    </div>
    <input type = "hidden" @if(isset($shift_time_type)) name = "duration" @else name = "duration[]"   @endif   class = "duration" /> 
</div>