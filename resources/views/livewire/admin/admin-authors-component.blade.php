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
                                All Authors
                            </div>
                            <div class="col-md-6">
                                <a href="{{route('admin.addauthor')}}" class="btn btn-success pull-right">Add New</a>
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
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($authors as $author)
                                <tr>
                                    <td>{{$author->id}}</td>
                                    <td>{{$author->name}}</td>
                                    <td>{{$author->slug}}</td>
                                    <td>
                                        <a href="{{route('admin.authors',['id'=>$author->id])}}"><i class="fa fa-edit fa-2x"></i></a>
                                        <a href="#" onclick="confirm('Are you sure, You want to delete this author?') || event.stopImmediatePropagation()" wire:click.prevent="deleteAuthor({{$author->id}})" style="margin-left:10px;"><i class="fa fa-times fa-2x text-danger"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{$authors->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
