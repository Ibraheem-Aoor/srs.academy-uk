@extends('admin.layouts.master')
@section('title', $title)
@section('content')

    <!-- Start Content-->
    <div class="main-body">
        <div class="page-wrapper">
            <!-- [ Main Content ] start -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ $title }} {{ __('btn_import') }}</h5>
                        </div>
                        <div class="card-block">
                            <a href="{{ route($route . '.index') }}" class="btn btn-primary"><i class="fas fa-arrow-left"></i>
                                {{ __('btn_back') }}</a>

                            <a href="{{ route($route . '.import') }}" class="btn btn-info"><i class="fas fa-sync-alt"></i>
                                {{ __('btn_refresh') }}</a>
                        </div>

                        <div class="card-block">
                            <p>1. Your Excel data should be in the format of the download file. The first line of your Excel
                                file should be the column headers as in the table example. Also make sure that your file is
                                UTF-8 to avoid unnecessary encoding problems. <a
                                    href="{{ asset('dashboard/sample/exam.xlsx') }}" class="text-primary" download>Download
                                    Sample File</a></p>
                            <hr />
                            <p>2. For "Attendance" use ( P=Present, A=Absent ).</p>
                            <hr />
                            <p>3. "Achieve Marks" must contain a numeric value.</p>
                            <hr />
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-block">
                            <form class="needs-validation" novalidate action="{{ route($route . '.import.store') }}"
                                method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row gx-2">
                                    @include('common.inc.subject_search_filter')



                                    <div class="form-group col-md-3">
                                        <label for="type">{{ __('field_type') }} <span>*</span></label>
                                        <select class="form-control" name="type" id="type" required>
                                            <option value="">{{ __('select') }}</option>
                                            @isset($types)
                                                @foreach ($types as $type)
                                                    <option value="{{ $type->id }}"
                                                        @if (old('type') == $type->id) selected @endif>{{ $type->title }}
                                                    </option>
                                                @endforeach
                                            @endisset
                                        </select>

                                        <div class="invalid-feedback">
                                            {{ __('required_field') }} {{ __('field_type') }}
                                        </div>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="date">{{ __('field_date') }} <span>*</span></label>
                                        <input type="date" class="form-control date" name="date" id="date"
                                            value="{{ date('Y-m-d') }}" required>

                                        <div class="invalid-feedback">
                                            {{ __('required_field') }} {{ __('field_date') }}
                                        </div>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="import">{{ __('File xlsx') }} <span>*</span></label>
                                        <input type="file" class="form-control" name="import" id="import"
                                            value="{{ old('import') }}" accept=".xlsx" required>

                                        <div class="invalid-feedback">
                                            {{ __('required_field') }} {{ __('File xlsx') }}
                                        </div>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <button type="submit" class="btn btn-info btn-filter"><i class="fas fa-upload"></i>
                                            {{ __('btn_upload') }}</button>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <a onclick="downloadImportTemplate();" class="btn btn-primary btn-filter"><i
                                                class="fas fa-download"></i>
                                            {{ __('btn_download_fom') }}</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
    <!-- End Content-->

@endsection

@section('page_js')
    <script type="text/javascript">
        "use strict";
        $(".session").on('change', function(e) {
            e.preventDefault(e);
            var subject = $(".subject");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: "{{ route('filter-techer-subject') }}",
                data: {
                    _token: $('input[name=_token]').val(),
                    session: $(this).val()
                },
                success: function(response) {
                    // var jsonData=JSON.parse(response);
                    $('option', subject).remove();
                    $('.subject').append('<option value="">{{ __('select') }}</option>');
                    $.each(response, function() {
                        $('<option/>', {
                            'value': this.id,
                            'text': this.code + ' - ' + this.title
                        }).appendTo('.subject');
                    });
                }

            });
        });


        $("#subject").on('change', function(e) {
            e.preventDefault(e);
            var type = $("#type");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: "{{ route('filter-mark-types-by-subject-and-program') }}",
                data: {
                    _token: $('input[name=_token]').val(),
                    subject_id: $(this).val(),
                    program_id: $('#program').val()
                },
                success: function(response) {
                    // var jsonData=JSON.parse(response);
                    $('option', type).remove();
                    $('#type').append('<option value="">{{ __('select') }}</option>');
                    $.each(response, function() {
                        $('<option/>', {
                            'value': this.id,
                            'text': this.title + ` (${this.marks})`
                        }).appendTo('#type');
                    });
                }

            });
        });

        function downloadImportTemplate() {
            // Collect form inputs
            var session = document.getElementById('session').value;
            var subject = document.getElementById('subject').value;
            var program = document.getElementById('program').value;
            var type = document.getElementById('type').value;

            // Construct URL with query parameters
            var url = "{{ route('admin.exam-attendance.import.download_form') }}";
            url += "?session=" + session + "&subject=" + subject + "&type=" +
                type + "&program=" + program;
            // Redirect user to the download route with query parameters
            window.location.href = url;
        }
    </script>
@endsection
