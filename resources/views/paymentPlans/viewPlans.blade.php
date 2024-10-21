@extends('template')

@section('main')
<style>
    .content {
        margin: 0 auto;
        max-width: 144rem;
    }

    .card-plan {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 16px;
        background-color: #fff;
        width: 250px;
        min-height: 400px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        /* margin-bottom: -300px;  */
        /* Ajusta a largura do card */
    }

    .card-plan:hover {
        border: 1px solid #ed3615;
        box-shadow: 0 0 10px rgba(237, 54, 21, 0.5);

        .title-plan {
            color: #ed3615;
        }
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e0e0e0;
        padding: 8px 16px;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }

    .card-body {
        padding: 16px 0;
    }

    

    .pricing-card-title {
        font-size: 24px;
        margin-bottom: 16px;
    }

    .btn-primary {
        background-color: #004d40;
        border-color: #004d40;
    }

    .btn-primary:hover {
        background-color: #00332e;
        border-color: #00332e;
    }

    .list-unstyled {
        padding-left: 0;
        list-style: none;
    }

    .list-unstyled li {
        margin-bottom: 8px;
    }

    .list-unstyled li i {
        margin-right: 8px;
    }

    .content-wrapper {
        margin-top: 80px;
        /* Adiciona uma margem superior para que o conteúdo fique abaixo do header */
    }

    /* .card-container {
        display: flex;
        justify-content: space-between;
    }

    .card-wrapper {
        flex: 1;
        margin: 0 -20px;
    } */

    .card-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }

    .card-wrapper {
        flex: 1 1 250px; /* Ajusta a largura mínima dos cards */
        margin: 10px; /* Espaçamento entre os cards */
        max-width: 300px; /* Largura máxima dos cards */
    }

    .price {
        font-size: 4rem;
        font-weight: bold;
        color: #333;
    }

    .price small {
        font-size: 1rem;
        font-weight: normal;
        color: #666;
    }
</style>
<div class="content-wrapper">
    <section class="content">
        <div class="row card-container">
            @foreach($paymentPlans as $plan)
            @if($plan->active === 'Active')
            <div class="card-wrapper">
                <div class="card-plan mb-4 shadow-sm">
                    <div class="text-left">
                        <h4 style="font-size: 25px;" class="my-0 font-weight-900 title-plan">{{ $plan->name }}</h4>
                        <p style="font-size: 15px; padding-top: 10px; color: #d3d3d3;" class="my-0 font-weight-100">{{ $plan->description }}</p>
                    </div>
                    <div class="card-body text-left">
                        <h1 class="card-title pricing-card-title">
                            <span style="font-size: 15px; position: relative; top: -12px;">R$</span>
                            <span style="position: relative; right: 5px;" class="price">{{ number_format($plan->value, 0, ',', '.') }}</span>
                            <small style="font-size: 15px; position: relative; right: 12px; top: -3px;">/mês</small>
                        </h1>
                        <hr>
                        <ul class="list-unstyled mt-3 mb-4 text-left">
                            <li style="font-size: 15px; font-weight: 600; color: #ed3615"><strong>Recursos:</strong></li>
                            <ul class="list-unstyled mt-3 mb-4 text-left">
                                @foreach($plan->payment_plan_attributes as $attribute)
                                <li style="font-size: 15px; font-weight: 400; color: #333"><i style="color: #d3d3d3" class="fa fa-check"></i> {{ $attribute->attribute }}</li>
                                @endforeach
                            </ul>
                        </ul>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </section>
</div>
@endsection