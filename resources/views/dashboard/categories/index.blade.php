@extends('dashboard.layouts.master')
@section('content')
            <div class="dashboard-content-one">

                <!-- Breadcubs Area Start Here -->
                <div class="breadcrumbs-area">
                    <h3>{{trans('dashboard.categories')}}</h3>
                    <ul>
                        <li>
                            <a href="/">{{trans('dashboard.home')}}</a>
                        </li>
                        <li>{{trans('dashboard.All_Categories')}}</li>
                    </ul>
                </div>
                <!-- Breadcubs Area End Here -->

                <div class="card height-auto">
                    <div class="card-body">
                        <div class="heading-layout1">
                            <div class="item-title">
                                <h3>{{trans('dashboard.All_Categories')}}</h3>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table display data-table text-nowrap" id="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{trans('dashboard.Name')}}</th>
                                        <th>{{trans('dashboard.Image')}}</th>
                                        <th>{{trans('dashboard.Parent')}}</th>
                                        <th>{{trans('dashboard.Action')}}</th>
                                    </tr>
                                </thead>

                            </table>
                        </div>
                    </div>
                </div>
                <!-- Student Table Area End Here -->
@endsection
@section('script')
    <script>
        @if(\Illuminate\Support\Facades\Session::has('success'))
        toastr.success("{{ \Illuminate\Support\Facades\Session::get('success')}}")
        @elseif(\Illuminate\Support\Facades\Session::has('error'))
        toastr.error("{{ \Illuminate\Support\Facades\Session::get('error')}}")
        @endif
    </script>
    <script>
            $(document).ready(function(){
            // DataTable
           let  t = $('#table').DataTable({
               dom: 'Bfrtip',
               buttons: [
                   {
                       extend: 'print',
                       text: '<i class="fa fa-print" aria-hidden="true"></i>',
                       exportOptions: {
                           columns: ':visible'
                       }
                   },
                   {
                       extend:    'excelHtml5',
                       text:      '<i class="fa fa-file-excel-o"></i>',
                       titleAttr: 'Excel'
                   },
                   {
                       extend:    'csvHtml5',
                       text:      '<i class="fa fa-file-text-o"></i>',
                       titleAttr: 'CSV'
                   },
                   {
                       extend:    'pdfHtml5',
                       text:      '<i class="fa fa-file-pdf-o"></i>',
                       titleAttr: 'PDF'
                   },
                   {
                       extend: 'colvis',
                       text: 'select items',
                   }

               ],
            processing: true,
            serverSide: true,
            pagingType: "full_numbers",
                order: [[1, 'asc']],
           bDestroy: true,
               iDisplayLength: 25,

            ajax: "{{route('categories.ajax')}}",
            columns: [
            { data: '#' ,search:false },
            { data: 'name' },
            { data: 'image' },
            { data: 'parent' },
            { data: 'action' ,search : false},
            ],
            });
                t.on('order.dt search.dt', function () {
                    let i = 1;

                    t.cells(null, 0, { search: 'applied', order: 'applied' })
                        .every(function (cell) {
                            this.data(i++);
                        });
                }).draw();

            });
    </script>


@endsection
