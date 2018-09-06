@extends('layout')

@section('title', 'Dashboard')

@section('body')
    <div class="wrapper spacing-top-large spacing-bottom-large">
        <div style="color: black; margin-bottom: 20px;">{{ __('calendar.months.' . $month) }}</div>
        <div class="row row--gutter mb-4">
            <div class="row__column">
                <div class="box">
                    <div class="box__section row">
                        <div class="row__column">
                            <div class="mb-1" style="color: #A7AEBB;">{{ __('general.total_spent') }}</div>
                            <h2 style="font-size: 20px;">{!! $currency->symbol !!} {{ number_format($totalSpendings / 100, 2) }}</h2>
                        </div>
                        <div class="row__column row__column--compact ml-2">
                            <i class="fal fa-wallet" style="color: #A7AEBB; font-size: 20px;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row__column">
                <div class="box">
                    <div class="box__section row">
                        <div class="row__column">
                            <div class="mb-1" style="color: #A7AEBB;">{{ __('general.most_expensive_tag') }}</div>
                            <h2 style="font-size: 20px;">{{ count($mostExpensiveTag) ? $mostExpensiveTag[0]->name : '-' }}</h2>
                        </div>
                        <div class="row__column row__column--compact ml-2">
                            <i class="fal fa-tag" style="color: #A7AEBB; font-size: 20px;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row__column">
                <div class="box">
                    <div class="box__section row">
                        <div class="row__column">
                            <div class="mb-1" style="color: #A7AEBB;">{{ __('general.most_expensive_day') }}</div>
                            <h2 style="font-size: 20px;">{{ count($mostExpensiveWeekday) ? __('calendar.weekdays.' . $mostExpensiveWeekday[0]->weekday) : '-' }}</h2>
                        </div>
                        <div class="row__column row__column--compact ml-2">
                            <i class="fal fa-calendar" style="color: #A7AEBB; font-size: 20px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row row--gutter mb-4">
            <div class="row__column">
                <div style="color: black; margin-bottom: 20px;">{{ __('general.recent') }} {{ __('general.spendings') }}</div>
                <div class="box">
                    @if (count($recentSpendings))
                        @foreach ($recentSpendings as $spending)
                            <div class="box__section row row--seperate">
                                <div class="row__column">{{ $spending->description }}</div>
                                <div class="row__column row__column--compact">{!! $currency->symbol !!} {{ $spending->formatted_amount }}</div>
                            </div>
                        @endforeach
                    @else
                        <div class="box__section">You don't have any spendings</div>
                    @endif
                </div>
            </div>
            <div class="row__column">
                <div class="box">
                    <div class="box__section">
                        <div class="mb-2">{{ __('general.analysis') }}</div>
                        @if (count($tagsBreakdown))
                            <div class="ct-chart ct-perfect-fifth" style="max-width: 500px; margin-left: auto; margin-right: auto;"></div>
                        @else
                            Not enough data
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row gutter spacing-bottom-large">
            <div class="column">
                <div class="box">
                    <div class="box__section text-center" style="padding: 15px;">
                        <a href="/earnings">{{ __('general.earnings') }} ({{ $earningsCount }}) <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="box">
                    <div class="box__section text-center" style="padding: 15px;">
                        <a href="/spendings">{{ __('general.spendings') }} ({{ $spendingsCount }}) <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var labels = [{!! implode(',', array_map(function ($entry) { return '\'' . $entry->name . '\''; }, $tagsBreakdown)) !!}];

        var data = {
            series: [{!! implode(',', array_map(function ($entry) { return $entry->amount; }, $tagsBreakdown)) !!}]
        };

        var sum = function(a, b) { return a + b };

        new Chartist.Pie('.ct-chart', data, {
            donut: true,
            donutWidth: 10,
            donutSolid: true,
            startAngle: 270,
            showLabel: true,

            labelInterpolationFnc: function (value, x) {
                return labels[x] + ' (' + Math.round(value / data.series.reduce(sum) * 100) + '%)';
            }
        });
    </script>
@endsection
