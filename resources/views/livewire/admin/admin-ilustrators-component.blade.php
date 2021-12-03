<div>
    <style>
        nav svg {
            height: 20px;
        }
        nav .hidden {
            display: block !important;
        }
        .sclist {
            list-style: none;
        }
        .sclist li {
            line-height: 33px;
            border-bottom: 1px solid #ccc;
        }
        .slist i {
            font-size: 16px;
            margin-left: 12px; 
        }
    </style>
    <div class="container" style="padding:30px 0;">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-6">
                                All Ilustrators
                            </div>
                            <div class="col-md-6">
                                <a href="{{route('admin.addilustrator')}}" class="btn btn-success pull-right">Add New</a>
                            </div>
                        </div>
                    <div class="panel-body">
                        @if(Session::has('message'))
                            <div class="alert alert-success" role="alert">{{Session::get('message')}}</div>
                        @endif
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ilustrators as $ilustrator)
                                <tr>
                                    <td>{{$ilustrator->id}}</td>
                                    <td>{{$ilustrator->name}}</td>
                                    <td>{{$ilustrator->slug}}</td>
                                    <td>
                                        <a href="{{route('admin.editilustrator',['ilustrator_slug'=>$ilustrator->slug])}}"><i class="fa fa-edit fa-2x"></i></a>
                                        <a href="#" onclick="confirm('Are you sure, You want to delete this ilustrator?') || event.stopImmediatePropagation()" wire:click.prevent="deleteIlustrator({{$ilustrator->id}})" style="margin-left:10px;"><i class="fa fa-times fa-2x text-danger"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{$ilustrators->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
