@extends('admin.layouts.master')
@section('title', $title)
@section('content')
    @php
        $max_mark = null;
    @endphp
    <!-- Start Content-->
    <div class="main-body">
        <div class="page-wrapper">
            <!-- [ Main Content ] start -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ $title }}</h5>

                            @can($access . '-import')
                                <a href="{{ route('admin.exam-attendance.import') }}" class="btn btn-dark btn-sm float-right"><i
                                        class="fas fa-upload"></i> {{ __('btn_import') }}</a>
                            @endcan
                        </div>
                        <div class="card-block">
                            <form class="needs-validation" novalidate method="get"
                                action="{{ route($route . '.index') }}">
                                <div class="row gx-2">
                                    @include('common.inc.subject_search_filter')

                                    <div class="form-group col-md-3">
                                        <label for="type">{{ __('field_type') }} <span>*</span></label>
                                        <select class="form-control" name="type" id="type" required>
                                            <option value="">{{ __('select') }}</option>
                                            @isset($types)
                                                @foreach ($types as $type)
                                                    <option value="{{ $type->id }}" @selected($type->id == $selected_type)>{{ $type->title }}
                                                        ({{ $type->marks }})</option>
                                                @endforeach
                                            @endisset
                                        </select>

                                        <div class="invalid-feedback">
                                            {{ __('required_field') }} {{ __('field_type') }}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <button type="submit" class="btn btn-info btn-filter"><i class="fas fa-search"></i>
                                            {{ __('btn_search') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="card">
                        <form class="needs-validation" novalidate action="{{ route($route . '.store') }}" method="post"
                            enctype="multipart/form-data" id="update-form">
                            @csrf

                            @if (isset($rows))
                                <div class="card-block">
                                    <!-- [ Data table ] start -->
                                    <div class="table-responsive">
                                        <table class="display table nowrap table-striped table-hover printable">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('field_student_id') }}</th>
                                                    <th>{{ __('field_name') }}</th>
                                                    @if (isset($rows))
                                                        @foreach ($rows as $row)
                                                            @if ($loop->first)
                                                                @php
                                                                    $max_mark = $max_marks = $row->type->marks;
                                                                @endphp
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    <th>
                                                        {{ __('field_max_marks') }}
                                                        @if (isset($max_marks))
                                                            ({{ round($max_marks, 2) }})
                                                        @endif
                                                    </th>
                                                    <th>{{ __('field_note') }}</th>
                                                    <th>{{ __('field_subject') }}</th>
                                                    <th>{{ __('field_type') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($rows as $key => $row)
                                                    <input type="hidden" name="exams[{{ $key }}]"
                                                        value="{{ $row->id }}">
                                                    <tr>
                                                        <td>
                                                            @isset($row->studentEnroll->student->student_id)
                                                                <a
                                                                    href="{{ route('admin.student.show', $row->studentEnroll->student->id) }}">
                                                                    #{{ $row->studentEnroll->student->student_id ?? '' }}
                                                                </a>
                                                            @endisset
                                                        </td>
                                                        <td>{{ $row->studentEnroll->student->first_name ?? '' }}
                                                            {{ $row->studentEnroll->student->last_name ?? '' }}</td>
                                                        <td>
                                                            <input type="text" class="form-control"
                                                                name="marks[{{ $key }}]" id="marks"
                                                                value="{{ $row->achieve_marks ? round($row->achieve_marks, 2) : '' }}"
                                                                style="width: 100px;" data-v-max="{{ $max_marks }}"
                                                                data-v-min="0">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control"
                                                                name="notes[{{ $key }}]" id="notes"
                                                                value="{{ $row->note }}" style="width: 100px;">
                                                        </td>
                                                        <td>{{ $row->subject->code ?? '' }}</td>
                                                        <td>{{ $row->type->title ?? '' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- [ Data table ] end -->
                                </div>

                                @if (count($rows) > 0)
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-success update"><i class="fas fa-check"></i>
                                            {{ __('btn_update') }}</button>
                                    </div>
                                @endif

                                @if (count($rows) < 1)
                                    <div class="card-block">
                                        <h5>{{ __('no_result_found') }}</h5>
                                    </div>
                                @endif
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
    <!-- End Content-->

@endsection

@section('page_js')
    @isset($max_mark)
        <script>
            function checkMaxMarkAndSubmit() {
                // Get all marks input fields
                var marksInputs = document.querySelectorAll('input[name^="marks"]');

                // Flag to track if any mark is greater than 100
                var hasInvalidMark = false;

                // Iterate over marks input fields
                marksInputs.forEach(function(input) {
                    var mark = parseFloat(input.value);
                    if (mark > 100) {
                        hasInvalidMark = true;
                        // Display error message or handle as needed
                        // For example, you can display an alert:
                        toastr.error('MAX MARK IS ' + "{{ $max_mark }}");

                        // Stop iteration
                        return false;
                    }
                });

                // If any mark is greater than 100, prevent form submission
                if (hasInvalidMark) {
                    return false;
                }

                // If all marks are valid, allow form submission
                return true;
            }

            // Attach event listener to form submission event
            document.getElementById('update-form').addEventListener('submit', function(event) {
                // Call checkMaxMarkAndSubmit function before form submission
                if (!checkMaxMarkAndSubmit()) {
                    // If checkMaxMarkAndSubmit returns false, prevent form submission
                    event.preventDefault();
                }
            });
        </script>
    @endisset
    <script>
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
    </script>
@endsection
