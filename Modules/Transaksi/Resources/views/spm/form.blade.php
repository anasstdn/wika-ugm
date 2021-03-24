@extends('layouts.app')

@section('content')
    <div class="bg-primary-dark">
        <div class="content content-top">
            <div class="row push">
                <div class="col-md py-10 d-md-flex align-items-md-center text-center">
                    <h1 class="text-white mb-0">
                        <span class="font-w300">SPM</span>
                        <span class="font-w400 font-size-lg text-white-op d-none d-md-inline-block">Pengajuan SPM</span>
                    </h1>
                </div>
            </div>
        </div>
    </div>


    <div class="content">
        <div class="block block-themed">
            <div class="block-header bg-info">
                <h3 class="block-title"><i class="fa fa-user-circle mr-5 text-muted"></i> Pengajuan Surat Pengadaan Material
                </h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle"
                        data-action-mode="demo">
                        <i class="si si-refresh"></i>
                    </button>
                    <button type="button" class="btn-block-option" data-toggle="block-option"
                        data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <div class="row items-push">


                    <div class="col-lg-12">
                        <!-- Progress Wizard -->
                        <div class="js-wizard-validation-classic block">
                            <!-- Step Tabs -->
                            <ul class="nav nav-tabs nav-tabs-block nav-fill" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#wizard-progress-data-pribadi" data-toggle="tab">1.
                                        Data Pengajuan SPM</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#wizard-progress-alamat" data-toggle="tab">2. Daftar
                                        Material</a>
                                </li>
                                {{-- <li class="nav-item">
								<a class="nav-link" href="#wizard-progress-kontak" data-toggle="tab">3. Kontak</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#wizard-progress-foto" data-toggle="tab">4. Foto Profil</a>
							</li> --}}
                            </ul>
                            <!-- END Step Tabs -->

                            <!-- Form -->
                            @if (isset($data) && !empty($data))
                                {!! Form::model($data, ['method' => 'put', 'route' => ['spm.update', $data->id], 'class' => 'js-wizard-validation-classic-form', 'id' => 'form', 'files' => true]) !!}
                            @else
                                {!! Form::open(['route' => 'spm.store', 'method' => 'POST', 'class' => 'js-wizard-validation-classic-form', 'id' => 'form', 'files' => true]) !!}
                            @endif
                            {{-- <form class="js-wizard-validation-classic-form" id="form" action="{{ url('profile/update') }}" method="post" enctype='multipart/form-data'>
							{{ csrf_field() }} --}}
                            <!-- Wizard Progress Bar -->
                            <div class="block-content block-content-sm">
                                <div class="progress" data-wizard="progress" style="height: 8px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                                        role="progressbar" style="width: 30%;" aria-valuenow="30" aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>
                            </div>
                            <!-- END Wizard Progress Bar -->

                            <!-- Steps Content -->
                            <div class="block-content block-content-full tab-content" style="min-height: 265px;">
                                <!-- Step 1 -->
                                <div class="tab-pane active" id="wizard-progress-data-pribadi" role="tabpanel">
                                    @include('transaksi::spm.pengajuan')
                                </div>
                                <!-- END Step 1 -->

                                <!-- Step 2 -->
                                <div class="tab-pane" id="wizard-progress-alamat" role="tabpanel">
                                    @include('transaksi::spm.item-barang')
                                </div>
                                <!-- END Step 2 -->
                                <!-- END Step 3 -->
                            </div>
                            <!-- END Steps Content -->

                            <!-- Steps Navigation -->
                            <div class="block-content block-content-sm block-content-full bg-body-light">
                                <div class="row">
                                    {{-- <div class="col-6">
											<button type="button" class="btn btn-alt-secondary" data-wizard="prev">
												<i class="fa fa-angle-left mr-5"></i> Previous
											</button>
										</div> --}}
                                    <div class="col-12 text-right">
                                        <a href="{{ url('/spm') }}" class="btn btn-alt-success">Kembali</a>
                                        <button type="button" class="btn btn-alt-secondary" data-wizard="prev">
                                            <i class="fa fa-angle-left mr-5"></i> Sebelumnya
                                        </button>
                                        <button type="button" class="btn btn-alt-secondary" data-wizard="next">
                                            Selanjutnya <i class="fa fa-angle-right ml-5"></i>
                                        </button>
                                        <button type="submit" id="simpan" class="btn btn-alt-primary d-none"
                                            data-wizard="finish">
                                            <i class="fa fa-check mr-5"></i> Simpan
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- END Steps Navigation -->
                            </form>
                            <!-- END Form -->
                        </div>
                        <!-- END Progress Wizard -->
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('js')
    <script>
        initWizardSimple = () => {
            $('.js-wizard-validation-classic').bootstrapWizard({
                onTabShow: (tab, navigation, index) => {
                    let percent = ((index + 1) / navigation.find('li').length) * 100;

                    // Get progress bar
                    let progress = navigation.parents('.block').find(
                        '[data-wizard="progress"] > .progress-bar');

                    // Update progress bar if there is one
                    if (progress.length) {
                        progress.css({
                            width: percent + 1 + '%'
                        });
                    }
                },
                onNext: (tab, navigation, index) => {
                    if (!formClassic.valid()) {
                        validatorClassic.focusInvalid();
                        return false;
                    }
                },
                onTabClick: (tab, navigation, index) => {
                    jQuery('a', navigation).blur();
                    return false;
                }
            });

            let formClassic = jQuery('.js-wizard-validation-classic-form');

            // Prevent forms from submitting on enter key press
            formClassic.on('keyup keypress', e => {
                let code = e.keyCode || e.which;

                if (code === 13) {
                    e.preventDefault();
                    return false;
                }
            });

            $.validator.addMethod("validDate", function(value, element) {
                return this.optional(element) || moment(value, "DD-MM-YYYY").isValid();
            }, "Format tanggal yang diperbolehkan, exp: DD-MM-YYYY");

            let validatorClassic = formClassic.validate({
                errorClass: 'invalid-feedback animated fadeInDown',
                errorElement: 'div',
                errorPlacement: (error, e) => {
                    $(e).parents('.form-group').append(error);
                },
                highlight: e => {
                    $(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
                },
                success: e => {
                    $(e).closest('.form-group').removeClass('is-invalid');
                    $(e).remove();
                },
            });

            $('.pengajuan').each(function(index, element) {
                $(this).rules('add', {
                    required: true,
                    messages: {
                        required: "Form Wajib Diisi"
                    },
                });

                if ($(this).attr('id') == 'keterangan_spm') {
                    $(this).rules('add', {
                        required: false,
                        messages: {
                            required: "Form Wajib Diisi"
                        },
                    });
                }

                if ($(this).attr('id') == 'tgl_spm') {
                    $(this).rules('add', {
                        validDate: true,
                    });
                }
            })

            $('input').on('focus focusout keyup', function() {
                $(this).valid();
            });

            $("select").on("select2:close", function(e) {
                $(this).valid();
            });

            $(".datepicker").on("change", function(e) {
                $(this).valid();
            });

        }

        initWizardDefaults = () => {
            jQuery.fn.bootstrapWizard.defaults.tabClass = 'nav nav-tabs';
            jQuery.fn.bootstrapWizard.defaults.nextSelector = '[data-wizard="next"]';
            jQuery.fn.bootstrapWizard.defaults.previousSelector = '[data-wizard="prev"]';
            jQuery.fn.bootstrapWizard.defaults.firstSelector = '[data-wizard="first"]';
            jQuery.fn.bootstrapWizard.defaults.lastSelector = '[data-wizard="lsat"]';
            jQuery.fn.bootstrapWizard.defaults.finishSelector = '[data-wizard="finish"]';
            jQuery.fn.bootstrapWizard.defaults.backSelector = '[data-wizard="back"]';
        }



        $(function() {
            initWizardDefaults();
            initWizardSimple();

            $('.datepicker').datepicker({
                format: "dd-mm-yyyy",
                locale: 'id',
                autoclose: true
            });

            $('#form').submit('#simpan', function(e) {
                var err = 0;
                var rowCount = $('#table-list-material >tbody >tr').length;
                if (rowCount < 1) {
                    $('#add_material').click();
                    notification('Silahkan pilih material terlebih dahulu', 'gagal');
                    $('#simpan').html('<i class="fa fa-circle-o-notch fa-spin"><i>').prop('disabled', true);
                    err += 1;
                } else {
                    $('.valid-material').each(function(index, element) {
                        if ($(element).hasClass('valid-material')) {
                            if ($(this).val() == '') {
                                notification('Silahkan periksa kelengkapan data terlebih dahulu',
                                    'gagal');
                                $('#simpan').html('<i class="fa fa-circle-o-notch fa-spin"><i>')
                                    .prop('disabled', true);
                                err += 1;
                            }
                        }
                    })
                    // clicked(e);
                }

                if (err > 0) {
                    e.preventDefault();
                } else {
                    clicked(e);
                }
            })
        })

    </script>
@endpush
