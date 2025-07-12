@extends('admin.layout.default')

@section('settings','active menu-item-open')
@section('content')
<div class="card card-custom">

    <div class="card-body">
        <div class="mb-7">
            <div class="row align-items-center">

                <form method="POST" action="" class="w-100">
                    {{ csrf_field() }}
                    <div class="col-lg-9 col-xl-12">
                        <div class="row align-items-center">
                            <div class="form-group col-md-6">
                                <label>Registered Office Address</label>
                                <input type="text" name="registered_office_address" value="{{$details->registered_office_address ?? $details->registered_office_address}}" isrequired="required" class="form-control" placeholder="Enter Registered Office Address"> <br />
                              <input type="text" name="registered_office_address2" value="{{$details->registered_office_address2 ?? $details->registered_office_address2}}"  class="form-control" placeholder="Enter Registered Office Address2">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Office Address</label>
                                <input type="text" name="office_address" value="{{$details->office_address ?? $details->office_address}}" isrequired="required" class="form-control" placeholder="Enter Office Address"> <br />
                                <input type="text" name="office_address2" value="{{$details->office_address2 ?? $details->office_address2}}" class="form-control" placeholder="Enter Office Address2">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Phone Number</label>
                                <div><input type="text" name="phone_number" value="{{$details->phone_number ?? $details->phone_number}}" isrequired="required" class="form-control" placeholder="Enter Phone Number"></div>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Email Address</label>
                                <div><input type="email" name="email_id" value="{{$details->email_id ?? $details->email_id}}" isrequired="required" class="form-control" placeholder="Enter Email Address"></div>
                            </div>
                            <div class="form-group col-md-12">
                                <label>WhatsApp No</label>
                                <div>
                                    <input type="text" name="whatsapp" value="{{$details->whatsapp ?? $details->whatsapp}}" isrequired="required" class="form-control" placeholder="Enter WhatsApp No">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Customer Care</label>
                                <div>
                                    <input type="text" name="customer_care" value="{{$details->customer_care ?? $details->customer_care}}" isrequired="required" class="form-control" placeholder="Enter Customer Care">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label>GST No.</label>
                                <div>
                                    <input type="hidden" name="setting_id" value="{{$details->id ?? $details->id}}" class="form-control">
                                    <input type="text" name="gst_number" value="{{$details->gst_number ?? $details->gst_number}}" class="form-control" placeholder="Enter GST No.">
                                </div>
                            </div>
                            <!-- <div class="form-group col-md-12">
                                <label>GST No.</label>
                                <div>
                                    <input type="text" name="gst_number" value="{{$details->gst_number ?? $details->gst_number}}"  class="form-control" placeholder="Enter GST No.">
                                </div>
                            </div> -->


                            <div class="form-group col-md-12">
                                <center><button class="btn btn-success">Update</button></center>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

{{-- Styles Section --}}
@section('styles')

@endsection

{{-- Scripts Section --}}
@section('scripts')
@endsection