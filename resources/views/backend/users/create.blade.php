@extends('backend.main')
@section('title', 'Create User - FDD')

@section('styles')
@endsection

@push('css')
@endpush



@section('content')
<div id="content-page" class="content-page">
    <div class="container-fluid">
       <div class="row">
          <div class="col-sm-12">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                   <div class="iq-header-title">
                      <h4 class="card-title">Create User</h4>
                   </div>
                </div>
                <div class="iq-card-body px-4">
                   <form action="{{route('users.store')}}" method="POST">
                    @csrf
                    {{@method_field('POST')}}
                    <div class="row">
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="first_name" class="required">First Name</label>
                            <input type="text" class="form-control" name="first_name" placeholder="e.g. Ali">
                        </div>
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="last_name" class="required">Last Name</label>
                            <input type="text" class="form-control" name="last_name" placeholder="e.g. Raza">
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="username" class="required">Username:</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="e.g. aliraza12">
                        </div>
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="email" class="required">Email address:</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="e.g. abc@email.com">
                        </div>
                     </div>
           
                     <div class="row">
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="password" class="required">Password:</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="********" value="{{old('password')}}">
                        </div>
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="password_confirmation" class="required">Confirm Password:</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                            placeholder="********" value="{{old('password_confirmation')}}">
                        </div>
                    </div>

                      <div class="mt-5">
                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-12">
                                <div class="mt-2 mb-3">
                                    <h4 class="required">
                                        Access Level:
                                    </h4>
                                </div>
                            </div>
                            <div class="col-gl-10 col-md-10 col-sm-12">
                                <div class="form-group">
                                    {{-- <label for="exampleFormControlSelect1">Select Input</label> --}}
                                    <select class="form-control" id="exampleFormControlSelect1" required name="role">
                                       <option selected="" disabled="">Select Role</option>
                                       @foreach ($roles as $role)
                                       <option  value="{{$role->name}}">{{$role->name}}</option>
                                      
                                        @endforeach
                                    </select>
                                 </div>
                                 
                              
                            </div>
                        </div>
                      </div>
                      <div class="mt-5 mb-4">
                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-12">
                                <div class="mt-2 mb-3">
                                    <h4 class="required">
                                        User Status
                                    </h4>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    {{-- <label for="exampleFormControlSelect1">Select Input</label> --}}
                                    <select class="form-control" id="exampleFormControlSelect1" required name="status">
                                       <option selected="" disabled="">Select Status</option>
                                       <option  value="1">Active</option>
                                        <option value="0">In Active</option>
                                    </select>
                                 </div>
                            </div>
                        </div>
                      </div>
                      <button type="submit" class="btn btn-primary mr-3">Submit</button>
                      <a href="{{route('users.index')}}" class="btn iq-bg-danger">Cancel</a>
                   </form>
                </div>
             </div>
          </div>
       </div>
    </div>
 </div>
@endsection


@section('scripts')

@endsection

@push('js')
@endpush
