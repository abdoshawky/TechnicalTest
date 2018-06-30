@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    <div id="closingReason" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Why do you want to close your account ?</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <form action="{!! url('users/account') !!}" method="post">
                                        {!! csrf_field() !!}
                                        {!! method_field('delete') !!}

                                        <div class="form-group">
                                            <textarea name="reason" class="form-control" cols="30" rows="10"></textarea>
                                        </div>

                                        <input type="submit" class="btn btn-danger btn-block btn-embossed" value="Close">
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if(auth()->user()->verified == 1)
                        You are logged in!
                    @else
                        Please Verify your email
                    @endif


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
