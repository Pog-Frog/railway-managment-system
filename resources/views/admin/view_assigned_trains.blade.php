<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.88.1">
    <title>Dashboard Template · Bootstrap v5.1</title>


    <!-- Bootstrap core CSS -->
    <link href="{{ url('styles/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>


    <!-- Custom styles for this template -->
    <link href="{{ url('styles/admin/dashboard.css') }}" rel="stylesheet">

    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0  shadow">
        <a class="navbar-brand col-md-3 position-sticky col-lg-2 me-0 px-3"
           href="#">Welcome {{$data->name}}</a>
        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="{{url("admin/logout")}}">Sign out</a>
            </li>
        </ul>
        <button style="margin-right: 80px;" class="navbar-toggler position-absolute d-md-none collapsed" type="button"
                data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </header>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{url("admin/trains")}}">
                                <span data-feather="airplay"></span>
                                Train management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{url("admin/stations")}}">
                                <span data-feather="airplay"></span>
                                Station management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{url("admin/lines")}}">
                                <span data-feather="airplay"></span>
                                Line management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{url("admin/employees")}}">
                                <span data-feather="user"></span>
                                Employee Management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{url("admin/trips")}}">
                                <span data-feather="briefcase"></span>
                                Trips
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span data-feather="users"></span>
                                Customer accounts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span data-feather="bar-chart-2"></span>
                                Reports
                            </a>
                        </li>
                    </ul>
                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>Management tools</span>
                    </h6>
                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                            <a class="nav-link" href="{{url("admin/lines?insert_line")}}">
                                <span data-feather="file-text"></span>
                                Add Lines
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route("view_lines")}}">
                                <span data-feather="file-text"></span>
                                View Lines
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</head>
<body>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div style="overflow-x: auto;" class="p-3 my-3 border rounded">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h5">Assinged Trains for Line No. {{$line_id}}</h1>
        </div>
        @if(Session::has('success'))
            <div class="alert-success">{{Session::get('success')}}

            </div>
        @else
            <div class="alert-danger">{{Session::get('fail')}}

            </div>
        @endif
        <table id="task-table" class="table table-r">
            <thead>
            <tr>
                <th scope="col" style="text-align: center">ID</th>
                <th scope="col" style="text-align: center">number/name</th>
                <th scope="col" style="text-align: center">model</th>
                <th scope="col" style="text-align: center">number of cars</th>
                <th scope="col" style="text-align: center">admin</th>
                <th scope="col" style="text-align: center">captain</th>
                <th scope="col" style="text-align: center">status</th>
                <th scope="col" style="text-align: center">updated at</th>
                <th scope="col" style="text-align: center">edit</th>
            </tr>
            </thead>

            <tbody>

            <tbody>
            @foreach($query as $record)
                <tr>
                    <td style="text-align: center">
                        <div style="max-width: 500px;max-height: 100px;overflow-y: auto;">
                            <input type="type" style="max-width: 50px; max-height: 100px;overflow-y: auto; text-align: center;" name="train_id" value="{{$record->trains->id}}" disabled>
                        </div>
                    </td>

                    <td style="text-align: center">
                        <div style="max-width: 500px;max-height: 100px;overflow-y: auto;">
                            {{$record->trains->train_no}}
                        </div>
                    </td>
                    <td style="text-align: center">
                        <div style="max-width: 500px;max-height: 100px;overflow-y: auto;">
                            {{$record->trains->train_model}}
                        </div>
                    </td>
                    <td style="text-align: center">
                        <div style="max-width: 300px; max-height: 100px;overflow-y: auto;">
                            {{$record->trains->no_of_cars}}
                        </div>
                    </td>
                    <td style="text-align: center">
                        <div style="max-width: 300px; max-height: 100px;overflow-y: auto;">
                            @if(is_null($record->trains->admins))
                                {{"not assigned"}}
                            @else
                                {{$record->trains->admins->name}}
                            @endif
                        </div>
                    </td>
                    <td style="text-align: center">
                        <div style="max-width: 300px; max-height: 100px;overflow-y: auto;">
                            @if(is_null($record->captains))
                                {{"not assigned"}}
                            @else
                                {{$record->captains->name}}
                            @endif
                        </div>
                    </td>
                    <td style="text-align: center">
                        <div style="max-width: 300px; max-height: 100px;overflow-y: auto;">
                            {{$record->trains->status}}
                        </div>
                    </td>
                    <td style="text-align: center">
                        <div style="max-width: 300px; max-height: 100px;overflow-y: auto;">
                            {{$record->trains->updated_at}}
                        </div>
                    </td>
                    <td style="text-align: center">
                        <a href="{{route('edit_train_index', ['train_id'=>($record->trains->id)])}}">Edit</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</main>
</body>

<script src="{{ url('/js/jquery.min.js') }}"></script>
<script src="{{ url('/js/bootstrap.min.js') }}"></script>
<script src="{{ url('/js/feather.min.js') }}"></script>
<script src="{{ url('/scripts/admin/dashboard.js') }}"></script>
</html>
