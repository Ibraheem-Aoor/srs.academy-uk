@extends('admin.layouts.master')
@section('title', $title)
@section('content')

    <!-- Start Content-->
    <div class="main-body">
        <div class="page-wrapper">
            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- [ Card ] start -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('modal_edit') }} {{ $title }}</h5>
                        </div>
                        <div class="card-block">
                            <a href="{{ route($route . '.index') }}" class="btn btn-primary"><i class="fas fa-arrow-left"></i>
                                {{ __('btn_back') }}</a>

                            <a href="{{ route($route . '.edit', $row->id) }}" class="btn btn-info"><i
                                    class="fas fa-sync-alt"></i> {{ __('btn_refresh') }}</a>
                        </div>

                        <form class="needs-validation" novalidate action="{{ route($route . '.update_plan', [$row->id]) }}"
                            method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-block">
                                <div class="row">
                                    {{-- Start Subject Programs --}}
                                    {{-- Each Enroll Is In A Session and has multi offered subjects --}}

                                    <div class="col-md-12">
                                        <label>{{ __('program_courses', ['program' => $row->title]) }}</label>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>{{ trans_choice('module_subject', 1) }}</th>
                                                    <th>{{ __('field_type') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($row->subjects as $program_subject)
                                                    {{-- @dd($program_subject->programs()->find($row->id)->pivot) --}}
                                                    <tr>
                                                        <td>{{ $loop->index+1 }}</td>
                                                        <td>
                                                            <label for="">{{ $program_subject->title }}</label>
                                                            <input required type="text" class="form-control"
                                                                name="enroll_subjects[{{ $program_subject->id }}][subject_id]"
                                                                value="{{ $program_subject->id }}" hidden>
                                                            {{-- <input required type="text" class="form-control"
                                                                name="enroll_subjects[{{ $program_subject->id }}][session_id]"
                                                                value="{{ $offered_enroll->session?->id }}" hidden> --}}
                                                            <input type="hidden" name="">
                                                        </td>
                                                        <td>
                                                            <select required class="form-control"
                                                                name="enroll_subjects[{{ $program_subject->id }}][subject_type_id]"
                                                                id="subject_type" required>
                                                                <option value="">{{ __('select') }}</option>
                                                                @foreach ($subject_types as $key => $name)
                                                                    <option value="{{ $key }}"
                                                                        @selected(@$program_subject->pivot['subject_type_id'] == $key)>
                                                                        {{ $name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    {{-- End Subject Programs --}}
                                    <div class="form-group col-md-4"></div>

                                    <!-- Form End -->
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success"><i class="fas fa-check"></i>
                                    {{ __('btn_update') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- [ Card ] end -->
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
    <!-- End Content-->

@endsection