@extends('layouts.app')

@section('content')
<style>
  .ajax-loader{
    position:fixed;
    top:0px;
    right:0px;
    width:100%;
    height:auto;
    background-color:#A9A9A9;
    background-repeat:no-repeat;
    background-position:center;
    z-index:10000000;
    opacity: 0.4;
    filter: alpha(opacity=40); /* For IE8 and earlier */
  }
        img.rounded2 {
  object-fit: cover;
  border-radius: 10%;
  height: auto;
  width: 100px;
}
</style>
<div class="ajax-loader text-center" style="display:none">
  <div class="progress">
    <div class="progress-bar progress-bar-striped active" aria-valuenow="100" aria-valuemin="1000"
    aria-valuemax="100" style="width: 100%;" id="loader" role="progressbar">
  </div>
</div>
<div id="" style="font-size:11pt;font-family: sans-serif;color: white">Silahkan Menunggu</div>
</div>

<div class="bg-image bg-image-bottom" style="background-image: url('{{asset('codebase/')}}/src/assets/media/photos/photo12@2x.jpg');">
	<div class="bg-black-op-75 py-30">
		<div class="content content-full text-center">
			<!-- Avatar -->
			<div class="mb-15">
				<a class="img-link" href="{{url('profil')}}">
          @if(isset($foto_profile) && $foto_profile->photo!==null)
          <img class="rounded2 img-avatar" src="{{asset('images/')}}/profile/{{$foto_profile->photo}}" alt="">
          @else
          <img class="img-avatar img-avatar96 img-avatar-thumb" src="{{asset('codebase/')}}/src/assets/media/avatars/avatar15.jpg" alt="">
          @endif
        </a>
			</div>
			<!-- END Avatar -->

			<!-- Personal -->
			<h1 class="h3 text-white font-w700 mb-10">
        <?php 
        $id=\Auth::user()->profile_id!==null?\Auth::user()->profile_id:\Auth::user()->id;
         ?>
				{{isset(\Auth::user()->profile->first_name)?\Auth::user()->profile->first_name:''}}&nbsp{{isset(\Auth::user()->profile->last_name)?\Auth::user()->profile->last_name:''}}
			</h1>
			<h2 class="h5 text-white-op">
				Senior Web Developer&nbsp&nbsp<i class="si si-calendar mb-5"></i> 01 September 2019<br/><br/><a class="btn btn-sm btn-alt-secondary mb-5 px-20" href="{{url('profil/edit/')}}/{{$id}}">
				<i class="fa fa-pencil"></i> Edit Profile
			</a>
			</h2>
			<!-- END Personal -->

			<!-- Actions -->
		{{-- 	<button type="button" class="btn btn-rounded btn-hero btn-sm btn-alt-success mb-5">
				<i class="fa fa-plus mr-5"></i> Add Friend
			</button>
			<button type="button" class="btn btn-rounded btn-hero btn-sm btn-alt-primary mb-5">
				<i class="fa fa-envelope-o mr-5"></i> Message
			</button>
			<a class="btn btn-rounded btn-hero btn-sm btn-alt-secondary mb-5 px-20" href="be_pages_generic_profile_edit.html">
				<i class="fa fa-pencil"></i>
			</a> --}}
			<!-- END Actions -->
		</div>
	</div>
</div>
<div class="bg-white">
  {{--  <li><a href="{{ url('locale/en') }}" ><i class="fa fa-language"></i> EN</a></li>

  <li><a href="{{ url('locale/id') }}" ><i class="fa fa-language"></i> FR</a></li> --}}
  <!-- Breadcrumb -->

  <!-- END Breadcrumb -->

  <!-- Content -->
  <div class="content">
  	<!-- Projects -->
  	<!-- Articles -->
  	<h2 class="content-heading">
  		{{-- <button type="button" class="btn btn-sm btn-rounded btn-alt-secondary float-right">View More..</button> --}}
  		<i class="si si-note mr-5"></i> Personal Information
  	</h2>
  	<div class="form-group row col-md-12">
  		<div class="col-lg-2 text-right">
  			<span>Employee ID</span>
  		</div>
  		<div class="col-lg-6 text-left">
        <?php 
        $nip=isset(\Auth::user()->profile->employees[0]->employee_id)?\Auth::user()->profile->employees[0]->employee_id:null;
        
        ?>
  			<span>{{isset($nip) && $nip!==null?explode('/',$nip)[0].' / '.explode('/',$nip)[1].' / '.explode('/',$nip)[2]:''}}</span>
  		</div>
  	</div>
  	<div class="form-group row col-md-12">
  		<div class="col-lg-2 text-right">
  			<span>Full Name</span>
  		</div>
  		<div class="col-lg-6 text-left">
  			<span>{{isset(\Auth::user()->profile->first_name)?\Auth::user()->profile->first_name:''}}&nbsp{{isset(\Auth::user()->profile->last_name)?\Auth::user()->profile->last_name:''}}</span>
  		</div>
  	</div>
  	<div class="form-group row col-md-12">
  		<div class="col-lg-2 text-right">
  			<span>ID Card Number</span>
  		</div>
  		<div class="col-lg-6 text-left">
  			<span>{{isset(\Auth::user()->profile->nik)?\Auth::user()->profile->nik:''}}</span>
  		</div>
  	</div>
  	<div class="form-group row col-md-12">
  		<div class="col-lg-2 text-right">
  			<span>Place of Birth / DOB</span>
  		</div>
  		<div class="col-lg-6 text-left">
  			<span>{{isset(\Auth::user()->profile->pob)?\Auth::user()->profile->pob:''}}, {{\Auth::user()->profile_id!==null?date_indo(date('Y-m-d',strtotime(\App\Models\Profile::find(\Auth::user()->profile_id)->dob))):''}}</span>
  		</div>
  	</div>
    <div class="form-group row col-md-12">
      <div class="col-lg-2 text-right">
        <span>Sex</span>
      </div>
      <div class="col-lg-6 text-left">
        <span>{{isset(\Auth::user()->profile->sex)?\Auth::user()->profile->sex:''}}</span>
      </div>
    </div>
     <div class="form-group row col-md-12">
      <div class="col-lg-2 text-right">
        <span>Religion</span>
      </div>
      <div class="col-lg-6 text-left">
        <span>{{isset(\Auth::user()->profile->religion->name)?\Auth::user()->profile->religion->name:''}}</span>
      </div>
    </div>
     <div class="form-group row col-md-12">
      <div class="col-lg-2 text-right">
        <span>Blood Group</span>
      </div>
      <div class="col-lg-6 text-left">
        <span>{{isset(\Auth::user()->profile->blood->name)?\Auth::user()->profile->blood->name.''.\Auth::user()->profile->blood->rhesus:''}}</span>
      </div>
    </div>
  	<div class="form-group row col-md-12">
  		<div class="col-lg-2 text-right">
  			<span>Contact</span>
  		</div>
  		<div class="col-lg-6 text-left">
  			<address>
  				{{-- <strong>Twitter, Inc.</strong><br> --}}
  				{{isset(\Auth::user()->profile->address)?\Auth::user()->profile->address:''}}<br>
  				{{isset(\Auth::user()->profile->province->name)?\Auth::user()->profile->province->name:''}}, {{isset(\Auth::user()->profile->province->country->name)?\Auth::user()->profile->province->country->name:''}}&nbsp{{isset(\Auth::user()->profile->postal_code)?\Auth::user()->profile->postal_code:''}}<br>
  				<abbr title="Phone">Phone:</abbr> {{isset(\Auth::user()->profile->phone)?\Auth::user()->profile->phone:''}}
  			</address>
  			<address>
  				<strong>Email</strong><br>
  				<a href="mailto:#">{{isset(\Auth::user()->profile->email)?\Auth::user()->profile->email:''}}</a>
  			</address>
  		</div>
  	</div>
  

  	{{-- <h2 class="content-heading"> --}}
  		{{-- <button type="button" class="btn btn-sm btn-rounded btn-alt-secondary float-right">View More..</button> --}}
  		{{-- <i class="si si-note mr-5"></i> Departement / Position 
  	</h2>
  	<div class="form-group row col-md-12">
  		<div class="col-lg-2 text-right">
  			<span>Departement</span>
  		</div>
  		<div class="col-lg-6 text-left">
  			<span>Surveilance Group A</span>
  		</div>
  	</div>
  	<div class="form-group row col-md-12">
  		<div class="col-lg-2 text-right">
  			<span>Position</span>
  		</div>
  		<div class="col-lg-6 text-left">
  			<span>Polkovnik 1 Rank</span>
  		</div>
  	</div> --}}

  	{{-- <h2 class="content-heading"> --}}
  		{{-- <button type="button" class="btn btn-sm btn-rounded btn-alt-secondary float-right">View More..</button> --}}
  		{{-- <i class="si si-note mr-5"></i> Expertise
  	</h2>
  	<div class="block-content block-content-full">
  		<a class="btn btn-sm btn-alt-secondary mb-5" href="javascript:void(0)">
  			<i class="fa fa-tag text-muted mr-5"></i>Intellgence
  		</a>
  		<a class="btn btn-sm btn-alt-secondary mb-5" href="javascript:void(0)">
  			<i class="fa fa-tag text-muted mr-5"></i>Surveilance
  		</a>
  		<a class="btn btn-sm btn-alt-secondary mb-5" href="javascript:void(0)">
  			<i class="fa fa-tag text-muted mr-5"></i>Psy Operation
  		</a>
  		<a class="btn btn-sm btn-alt-secondary mb-5" href="javascript:void(0)">
  			<i class="fa fa-tag text-muted mr-5"></i>Interrogation
  		</a>
  		<a class="btn btn-sm btn-alt-secondary mb-5" href="javascript:void(0)">
  			<i class="fa fa-tag text-muted mr-5"></i>Torture
  		</a>
  		<a class="btn btn-sm btn-alt-secondary mb-5" href="javascript:void(0)">
  			<i class="fa fa-tag text-muted mr-5"></i>Agent Recruitment
  		</a>
  		<a class="btn btn-sm btn-alt-secondary mb-5" href="javascript:void(0)">
  			<i class="fa fa-tag text-muted mr-5"></i>Field Decision
  		</a>
  		<a class="btn btn-sm btn-alt-secondary mb-5" href="javascript:void(0)">
  			<i class="fa fa-tag text-muted mr-5"></i>Programming
  		</a>
  		<a class="btn btn-sm btn-alt-secondary mb-5" href="javascript:void(0)">
  			<i class="fa fa-tag text-muted mr-5"></i>Hacking & Phising
  		</a>
  		<a class="btn btn-sm btn-alt-secondary mb-5" href="javascript:void(0)">
  			<i class="fa fa-tag text-muted mr-5"></i>Military & Geopolitical Analyst
  		</a>
  		<a class="btn btn-sm btn-alt-secondary mb-5" href="javascript:void(0)">
  			<i class="fa fa-tag text-muted mr-5"></i>HUMINT
  		</a>
  		<a class="btn btn-sm btn-alt-secondary mb-5" href="javascript:void(0)">
  			<i class="fa fa-tag text-muted mr-5"></i>SIGINT
  		</a>
  		<a class="btn btn-sm btn-alt-secondary mb-5" href="javascript:void(0)">
  			<i class="fa fa-tag text-muted mr-5"></i>Radio Operator
  		</a>
  	</div> --}}
  	{{-- <h2 class="content-heading"> --}}
  		{{-- <button type="button" class="btn btn-sm btn-rounded btn-alt-secondary float-right">View More..</button> --}}
  		{{-- <i class="si si-note mr-5"></i> Professional Career
  	</h2>

  	<div class="col-lg-6">
  		<h5>Committee of the State Security of the Soviet Union</h5>
  		<ul>
  			<p><i class="mr-5"></i> Date Join &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp: 01/09/1979 - 31/12/1989</p>
  			<p><i class="mr-5"></i>Departement &nbsp: Directorate A Foreign Intelligence</p>
  			<p><i class="mr-5"></i>Position &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp: Political Dissident</p>
  		<h5 style="font-size:12pt;">Achievements</h5>
  		<ol>
  			<li>First item</li>
  			<li>Second item</li>
  		</ol>
  		<br/>
  		<p style="font-weight: bold"><i class="mr-5"></i> CP&nbsp&nbsp&nbsp&nbspAlexander Gorbunov / +7 992 123 0938</p>
  		</ul>
  	</div>
  	<br/>
  	<div class="col-lg-6">
  		<h5>Ministry of the State Security of the German Democratic Republic</h5>
  			<ul>
  			<p><i class="mr-5"></i> Date Join &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp: 01/09/1979 - 31/12/1989</p>
  			<p><i class="mr-5"></i>Departement &nbsp: Directorate A Foreign Intelligence</p>
  			<p><i class="mr-5"></i>Position &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp: Political Dissident</p>
  		<h5 style="font-size:12pt;">Achievements</h5>
  		<ol>
  			<li>First item</li>
  			<li>Second item</li>
  		</ol>
  		<br/>
  		<p style="font-weight: bold"><i class="mr-5"></i> CP&nbsp&nbsp&nbsp&nbspAlexander Gorbunov / +7 992 123 0938</p>
  		</ul>
  	</div>

  	<h2 class="content-heading">
  		<button type="button" class="btn btn-sm btn-rounded btn-alt-secondary float-right">View More..</button>
  		<i class="si si-note mr-5"></i> Biography
  	</h2>
  	<div class="form-group row col-md-12">
  		<div class="block-content nice-copy">
  			<p>Potenti elit lectus augue eget iaculis vitae etiam, ullamcorper etiam bibendum ad feugiat magna accumsan dolor, nibh molestie cras hac ac ad massa, fusce ante convallis ante urna molestie vulputate bibendum tempus ante justo arcu erat accumsan adipiscing risus, libero condimentum venenatis sit nisl nisi ultricies sed, fames aliquet consectetur consequat nostra molestie neque nullam scelerisque neque commodo turpis quisque etiam egestas vulputate massa, curabitur tellus massa venenatis congue dolor enim integer luctus, nisi suscipit gravida fames quis vulputate nisi viverra luctus id leo dictum lorem, inceptos nibh orci.</p>
  			<p>Potenti elit lectus augue eget iaculis vitae etiam, ullamcorper etiam bibendum ad feugiat magna accumsan dolor, nibh molestie cras hac ac ad massa, fusce ante convallis ante urna molestie vulputate bibendum tempus ante justo arcu erat accumsan adipiscing risus, libero condimentum venenatis sit nisl nisi ultricies sed, fames aliquet consectetur consequat nostra molestie neque nullam scelerisque neque commodo turpis quisque etiam egestas vulputate massa, curabitur tellus massa venenatis congue dolor enim integer luctus, nisi suscipit gravida fames quis vulputate nisi viverra luctus id leo dictum lorem, inceptos nibh orci.</p>
  		</div>
  	</div> --}}

  </div>

</div>
@endsection