    <!-- Edit modal content -->
    <div id="editModal-{{ $row->id }}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="needs-validation" novalidate action="{{ route($route . '.update', $row->id) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">{{ __('modal_edit') }} {{ $title }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form Start -->
                        <div class="form-group">
                            <label for="faculty">{{ __('field_faculty') }} <span>*</span></label>
                            <select class="form-control" name="faculty" id="faculty" required>
                                <option value="">{{ __('select') }}</option>
                                @foreach ($faculties as $faculty)
                                    <option value="{{ $faculty->id }}"
                                        @if ($row->faculty_id == $faculty->id) selected @endif>{{ $faculty->title }}</option>
                                @endforeach
                            </select>

                            <div class="invalid-feedback">
                                {{ __('required_field') }} {{ __('field_faculty') }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="title" class="form-label">{{ __('field_title') }} <span>*</span></label>
                            <input type="text" class="form-control" name="title" id="title"
                                value="{{ $row->title }}" required>

                            <div class="invalid-feedback">
                                {{ __('required_field') }} {{ __('field_title') }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="shortcode" class="form-label">{{ __('field_shortcode') }}
                                <span>*</span></label>
                            <input type="text" class="form-control" name="shortcode" id="shortcode"
                                value="{{ $row->shortcode }}" required>

                            <div class="invalid-feedback">
                                {{ __('required_field') }} {{ __('field_shortcode') }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="total_fees" class="form-label">{{ __('field_program_total') }}
                                <span>*</span></label>
                            <input type="text" class="form-control" name="total_fees" id="total_fees"
                                value="{{ $row->default_total_fees }}" required>

                            <div class="invalid-feedback">
                                {{ __('required_field') }} {{ __('field_program_total') }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="total_hours" class="form-label">{{ __('field_program_total_hours') }}
                                <span>*</span></label>
                            <input type="text" class="form-control" name="total_hours" id="total_hours"
                                value="{{ $row->default_total_hours }}" required>

                            <div class="invalid-feedback">
                                {{ __('required_field') }} {{ __('field_program_total_hours') }}
                            </div>
                        </div>
                        {{-- <div class="form-group">
                        <div class="switch d-inline m-r-10">
                            <input type="checkbox" id="switch-{{ $row->id }}" name="registration" value="1" @if ($row->registration == 1) checked @endif>
                            <label for="switch-{{ $row->id }}" class="cr"></label>
                        </div>
                        <label>{{ __('field_registration') }}</label>
                    </div> --}}

                        <div class="form-group">
                            <label for="status" class="form-label">{{ __('select_status') }}</label>
                            <select class="form-control" name="status" id="status">
                                <option value="1" @if ($row->status == 1) selected @endif>
                                    {{ __('status_active') }}</option>
                                <option value="0" @if ($row->status == 0) selected @endif>
                                    {{ __('status_inactive') }}</option>
                            </select>
                        </div>
                        <!-- Form End -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                                class="fas fa-times"></i> {{ __('btn_close') }}</button>
                        <button type="submit" class="btn btn-success"><i class="fas fa-check"></i>
                            {{ __('btn_update') }}</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
