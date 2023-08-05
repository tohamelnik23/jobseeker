<div id="jobhistoryresponce" class="alert alert-success alert-block hide">
	<button type="button" class="close" data-dismiss="alert">×</button>	
	<strong></strong>
</div>
<form id="addjobhistoryForm" method="post" class="form-horizontal" action="{{{ route('employee.add.jobhistory') }}}">
    @csrf
    <div class="row">
		<div class="form-group col-lg-12">
			<label class="col-sm-12 control-label text-left" for="job_title"><star>*</star> Gig Title</label>
			<div class="col-sm-12  @if ($errors->has('job_title')) has-error @endif">
				<input type = "text" class="form-control" id="job_title" name="job_title" placeholder="Ex: Main Developer" value="{{old('job_title','')}}" maxlength = "512" />
				<em id="job_title-error" class="error help-block hide">
				</em>
			</div>
		</div>
	</div>

    <div class="row">
		<div class="form-group col-lg-12">
			<label class="col-sm-12 control-label text-left" for="job_company" ><star>*</star> Company </label>
			<div class="col-sm-12  @if ($errors->has('job_company')) has-error @endif">
				<input type = "text" class="form-control" id="job_company" name="job_company" placeholder="Ex: Microsoft" value="{{old('job_company','')}}" maxlength = "512"/>
				<em id="job_company-error" class="error help-block hide">
				</em>
			</div>
		</div>
	</div>

    <div class="row">
        <div class="form-group col-lg-12">
            <label class="col-sm-12 control-label text-left" for="job_start_date_year"> Start Date of Employment  </label>
            <div class="col-sm-3">
                <select class = "form-control" name = "job_start_date_year">
                    @for($i = 1980 ; $i <= date('Y'); $i++)
                        <option value = "{!! $i !!}"  @if($i == date('Y')) selected @endif >{!! $i !!}</option>
                    @endfor
                </select>
            </div>  
            <div class="col-sm-3">
                <select class = "form-control" name = "job_start_date_month">
                    @php
                        $months_array = Mainhelper::getMonthArray(); 
                    @endphp

                    @foreach($months_array as $month_key => $month)
                        <option value = "{!! $month_key !!}">{!! $month !!}</option>
                    @endforeach
                </select>
            </div> 
        </div>

        <div class="form-group col-lg-12">
            <label class="col-sm-12 control-label text-left" for="job_company"> End Date of Employment  </label>
            <div class="col-sm-3">
                <select class = "form-control" name = "job_end_date_year">
                    @for($i = 1980 ; $i <= date('Y'); $i++)
                        <option value = "{!! $i !!}"  @if($i == date('Y')) selected @endif >{!! $i !!}</option>
                    @endfor
                </select>
            </div>  
            <div class="col-sm-3">
                <select class = "form-control" name = "job_end_date_month">
                    @php
                        $months_array = Mainhelper::getMonthArray(); 
                    @endphp

                    @foreach($months_array as $month_key => $month)
                        <option value = "{!! $month_key !!}">{!! $month !!}</option>
                    @endforeach
                </select>
            </div> 
        </div>

        <div class="form-group col-lg-12">
            <label class="col-sm-12 control-label text-left" for="job_description"> Description  </label>
            <div class="col-sm-12  @if ($errors->has('job_description')) has-error @endif">
				<textarea rows = "5"  maxlength = "65535" type = "text" class="form-control" id="job_description" name="job_description">{{old('job_description','')}}</textarea>
				<em id="job_description-error" class="error help-block hide">
				</em>
			</div> 
        </div>
    </div>


    <div class="row my-lg-3">
		<div class="form-group col-lg-12">
			<div class="col-sm-12">
				<button type="submit" class="btn btn-primary" name="save" value="Save Changes">Save Changes</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</form>