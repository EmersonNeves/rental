@extends('admin.template')

@section('main')
<div class="content-wrapper">
    <section class="content">
        <div class="row">
                <div class="col-md-3 settings_bar_gap">
                    @include('admin.common.settings_bar')
                </div>
                <div class="col-md-9">

                        <div class="box box_info">
                            <div class="box-header">
                                <h3 class="box-title">Planos de Pagamento</h3>
                                <div class="pull-right"><a class="btn btn-primary" href="{{ url('admin/settings/add-payment-plan') }}">Adicionar Plano de Pagamento</a></div>
                            </div>
                        
                            <div class="box-body">
                                <div class="table-responsive">
                                {!! $dataTable->table() !!}
                                </div>
                            </div>
                        </div>
                </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="{{ asset('public/backend/plugins/DataTables-1.10.18/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/backend/plugins/Responsive-2.2.2/js/dataTables.responsive.min.js') }}"></script>
{!! $dataTable->scripts() !!}
@endpush
