<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Ticket</title>
    <style>
        body{
            background-color: #F6F6F6;
            margin: 0;
            padding: 0;
        }
        h1,h2,h3,h4,h5,h6{
            margin: 0;
            padding: 0;
        }
        p{
            margin: 0;
            padding: 0;
        }
        .container{
            width: 80%;
            margin-right: auto;
            margin-left: auto;
        }
        .brand-section{
           background-color: #0d1033;
           padding: 10px 40px;
        }
        .logo{
            width: 50%;
        }

        .row{
            display: flex;
            flex-wrap: wrap;
        }
        .col-6{
            width: 50%;
            flex: 0 0 auto;
        }
        .text-white{
            color: #fff;
        }
        .company-details{
            float: right;
            text-align: right;
        }
        .body-section{
            padding: 16px;
            border: 1px solid gray;
        }
        .heading{
            font-size: 20px;
            margin-bottom: 08px;
        }
        .sub-heading{
            color: #262626;
            margin-bottom: 05px;
        }
        table{
            background-color: #fff;
            width: 100%;
            border-collapse: collapse;
        }
        table thead tr{
            border: 1px solid #111;
            background-color: #f2f2f2;
        }
        table td {
            vertical-align: middle !important;
            text-align: center;
        }
        table th, table td {
            padding-top: 08px;
            padding-bottom: 08px;
        }
        .table-bordered{
            box-shadow: 0px 0px 5px 0.5px gray;
        }
        .table-bordered td, .table-bordered th {
            border: 1px solid #dee2e6;
        }
        .text-right{
            text-align: end;
        }
        .w-20{
            width: 20%;
        }
        .float-right{
            float: right;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="brand-section">
            <div class="row">
                <div class="col-6">
                    <h1 class="text-white">Your Ticket</h1>
                </div>

            </div>
        </div>



        <div class="body-section">
            <h3 class="heading">Ticket Info</h3>
            <br>
            <table class="table-bordered">
                <thead>

                    <tr>


                        <th class="w-20">Seat Number</th>
                        <th class="w-20">Date</th>
                        <th class="w-20">Source </th>
                        <th class="w-20">Destination</th>
                        <th class="w-20">Departure Time</th>
                        <th class="w-20">Price</th>
                        <th class="w-20">QR Code</th>

                    </tr>


                </thead>
                <tbody>
                <td class="w-20"> {{$seat->seat_name}}</td>
                <td class="w-20">{{$booked_ticket->stops_stations->date}}</td>
                <td class="w-20">{{$booked_ticket->stops_stations->source_station}}</td>
                <td class="w-20"> {{$booked_ticket->stops_stations->destination_station}}</td>
                <td class="w-20"> {{$booked_ticket->stops_stations->scheduled_departure_time}}</td>
                <td class="w-20"> {{$booked_ticket->stops_stations->price}}</td>
                <td class="w-20"><img src=""></td>

                </tbody>
            </table>
            <br>
            <h3 class="heading">Payment Status: Paid</h3>
            <h3 class="heading">Payment Mode: Visa</h3>
        </div>

        <div class="body-section">
            <p>&copy; Copyright 2022 - Egypt Railway. All rights reserved.
            </p>
        </div>
    </div>

</body>
</html>
