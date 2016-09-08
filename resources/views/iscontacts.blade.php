@extends('layouts.app')

@section('htmlheader_title')
    Home
@endsection


@section('main-content')
    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Infusionsoft Settings:
                    </div>

                    <div class="panel-body">
                        {{ trans('adminlte_lang::message.logged') }}
                    </div>
                    <?php if(!empty($contacts)): ?>
                    <div class="panel-body">
                        <h3 style="margin-bottom: 30px;">Contacts</h3>
                        @if(Session::has('success'))
                            <p class="alert alert-success">
                                {!! Session::get('success') !!}
                            </p>
                        @endif
                        <table id="example2" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>{{ trans('adminlte_lang::message.id') }}</th>
                                <th>{{ trans('adminlte_lang::message.FirstName') }}</th>
                                <th>{{ trans('adminlte_lang::message.LastName') }}</th>
                                <th>{{ trans('adminlte_lang::message.email') }}</th>
                                <th>{{ trans('adminlte_lang::message.action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($contacts as $contact): ?>
                            <tr>
                                <td><?php echo $contact['Id']; ?></td>
                                <td><?php echo $contact['FirstName']; ?></td>
                                <td><?php echo $contact['LastName']; ?></td>
                                <td><?php echo $contact['Email']; ?></td>
                                <td>
                                    {!! Form::open(array('url'=>'/deletecontact','method'=>'POST', 'id'=>'myform')) !!}
                                        <input type="hidden" name="contactid" id="contactid" value="{{$contact['Id']}}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="button" id="<?php echo $contact['Id']; ?>" class="btn btn-danger send-btn" value="Delete">

                                    {!! Form::close() !!}
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            </tbody>
                            <tfoot></tfoot>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div id="dialog-confirm" title="Delete contact?" style="display: none;">
        <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>This contact will be permanently deleted and cannot be recovered. Are you sure?</p>
    </div>
    @endsection

    @section('page_specific_styles')
        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
    @endsection
    @section('page_specific_scripts')
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
        <!-- DataTables -->
        <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
        <!-- SlimScroll -->
        <script src="{{ asset('plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
        <!-- FastClick -->
        <script src="{{ asset('plugins/fastclick/fastclick.js') }}"></script>
    @endsection
    @section('page_specific_inline_scripts')
        <script>
            jQuery( document ).ready( function( $ ) {

                $('.send-btn').click(function(){
                    //var contactid = $('#contactid').val();
                    var contactid = this.id;

                    $( "#dialog-confirm" ).dialog({
                        resizable: false,
                        height: "auto",
                        width: 400,
                        modal: true,
                        buttons: {
                            "Delete Contact": function() {
                               // $( this ).dialog( "close" );

                                $.ajax({
                                    url: 'deletecontact',
                                    type: "post",
                                    data: {'contactid':contactid, '_token': $('input[name=_token]').val()},
                                    success: function(data){

                                        if(data=='success'){

                                            window.location.reload(true);
                                        }

                                    }
                                });

                            },
                            Cancel: function() {
                                $( this ).dialog( "close" );
                            }
                        }
                    });
                });
                // CSRF protection
                $.ajaxSetup({  headers: {'X-CSRF-Token': $('input[name="_token"]').val() } });

                $('#modal-save').click(function(){
                    $.get('createcomment',function(data){
                        $('#createCommentData').append(data);
                        console.log(data);
                    });
                });

            } );
            $(function () {

                $('#example2').DataTable();
            });
        </script>
    @endsection
<!-- i have add this comments -->