<div  class="alert alert-success alert-block hide">
	<button type="button" class="close" data-dismiss="alert">X</button>	
	<strong></strong>
</div>   
<form id="editeducationForm" method="post" class="form-horizontal" action="{{{ route('employee.update.education') }}}">
    @csrf
    <input type="hidden" name="education" value="{{$education->serial}}">
    <div class="row">
        <div class="form-group col-lg-12">
            <label class="col-sm-12 control-label text-left" for="school"><star>*</star> School</label>
            <div class="col-sm-12  @if ($errors->has('school')) has-error @endif">
                <input type="text" class="form-control school"   name="school" placeholder="Ex: Boston University" value="{{old('school',$education->school)}}"/>
                <em   class="error school-error help-block hide">
                </em>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-12">
            <label class="col-sm-12 control-label text-left" for="degree">Degree</label>
            <div class="col-sm-12  @if ($errors->has('degree')) has-error @endif">
                <input type="text" class="form-control degree"  name="degree" placeholder="Ex: Bachelor’s" value="{{old('degree',$education->degree)}}"/>
                <em   class="error degree-error  help-block hide">
                </em>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-12">
            <label class="col-sm-12 control-label text-left" for="fieldofstudy">Field of study</label>
            <div class="col-sm-12  @if ($errors->has('fieldofstudy')) has-error @endif">
                <input type="text" class="form-control fieldofstudy"   name="fieldofstudy" placeholder="Ex: Business" value="{{old('fieldofstudy',$education->fieldofstudy)}}"/>
                <em   class="error help-block fieldofstudy-error  hide">
                </em>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-6">
            <label class="col-sm-12 control-label text-left" for="startyear">Start Year</label>
            <div class="col-sm-12  @if ($errors->has('startyear')) has-error @endif">
                <input type="text" class="form-control startyear"   name="startyear" placeholder="Ex: 2007" value="{{old('startyear',$education->startyear)}}"/>
                <em   class="error help-block startyear-error hide">
                </em>
            </div>
        </div>
        <div class="form-group col-lg-6">
            <label class="col-sm-12 control-label text-left" for="endyear">End Year (or expected)</label>
            <div class="col-sm-12  @if ($errors->has('endyear')) has-error @endif">
                <input type="text" class="form-control endyear"  name="endyear" placeholder="Ex: 2011" value="{{old('endyear',$education->endyear)}}"/>
                <em   class="error help-block endyear-error hide">
                </em>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-12">
            <label class="col-sm-12 control-label text-left" for="grade">Grade</label>
            <div class="col-sm-12  @if ($errors->has('grade')) has-error @endif">
                <input type="text" class="form-control grade"  name="grade" placeholder="Grade" value="{{old('grade',$education->grade)}}"/>
                <em   class="error help-block grade-error hide">
                </em>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-12">
            <label class="col-sm-12 control-label text-left"  for="activities">Activities and societies</label>
            <div class="col-sm-12  @if ($errors->has('activities')) has-error @endif">
                <textarea rows="4"  name="activities" class="form-control activities" placeholder="Ex: Alpha Phi Omega, Marching Band, Volleyball">{{$education->activities}}</textarea>
                <em   class="error help-block activities-error">
                </em>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-12">
            <label class="col-sm-12 control-label text-left"  for="description">Description</label>
            <div class="col-sm-12  @if ($errors->has('description')) has-error @endif">
                <textarea rows="4"  name="description" class="form-control description" placeholder="Description">{{$education->description}}</textarea>
                <em   class="error description-error help-block">
                </em>
            </div>
        </div>
    </div>
    <div class="row my-lg-3">
        <div class="form-group col-lg-12">
            <div class="col-sm-12">
                <button id="editeducationsubmit" type="submit" class="btn btn-primary" name="save" value="Save Changes">Save Changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>	
</form>