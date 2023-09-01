<!DOCTYPE html>
<html>
<head>
    <style>
        .container {
            display: flex;
            justify-content: space-between;
        }

        .square {
            width: 30%;
            border: 1px solid #ccc;
            padding: 20px;
        }

        .center-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .scroll-container {
            max-height: 300px;
            overflow-y: auto;
        }

        .scroll-list {
            list-style: none;
            border: 1px solid #ccc;
            padding: 20px;
            margin: 0;
        }
    </style>

</head>
<body>
<div class="container">
    <div class="square">
        <h2>Donations</h2>
        <h4>Total revenue from Donations in the past 30 days</h4>
        <p>{{$donation_revenue}}</p>
    </div>

    <div class="square">
        <h2>Subscriptions</h2>
        <h4>Total revenue from Subscriptions in the past 30 days</h4>
        <p>{{$subscriber_revenue}}</p>
    </div>

    <div class="square">
        <h2>Merch Sales</h2>
        <h4>Total revenue from Merch Sales in the past 30 days</h4>
        <p> {{$merch_sales['revenue']}} </p>
        <h4>Top 3 items that did the best sales wise in the past 30 days</h4>
        <p> <?php foreach ($merch_sales['top_result'] as $idx => $val) {
                echo $idx + 1 . ". Name: " . $val->name . "<br> Sales: " . $val->total_sales . "<br><br>";
            } ?> </p>
    </div>

    <div class="square">
        <h2>Followers</h2>
        <h4>Total amount of followers they have gained in the past 30 days</h4>
        <p> {{$total_followers}} </p>
    </div>
</div>

<div class="center-container">
    <h1>Combined List</h1>

    <div class="scroll-container">
        <ul class="scroll-list">
            @foreach ($list as $entry)
                <li>
                    @if ($entry->tier)
                        {{ $entry->name }} (Tier{{ $entry->tier }}) subscribed to you!
                    @elseif ($entry->donation_amount)
                        {{ $entry->name }} donated {{ $entry->donation_amount }} {{ $entry->currency }} to you!
                    @elseif ($entry->merch_name)
                        {{ $entry->name }} bought "{{ $entry->merch_name }}" from you
                        for {{ $entry->merch_price }} {{ $entry->currency }}! Thank you for being awesome!
                    @else
                        {{ $entry->name }} followed you!
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>

</body>
</html>
