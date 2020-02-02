@extends('layouts.app')
@section('title',"Meeting Operation Status.")
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Meeting Operation Status.</div>
                <div class="panel-body">
                    @if(isset($status) && !empty($status) )
                        {{$message}}
                    @else
                        {{$message}}
                    @endif
                    <br />
                    <hr>
                    <a href="{{ route('meetings.index') }}" class="btn btn-primary btn-lg">Go back to list page</a>
                </div>
            </div>
        </div>
    </div>
@endsection

<li class="dropdown" style="top:32px;left:-57px;">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Per Page <b class="caret"></b></a>
            <?php $total = $paginator->total();
                  $drop_down_number = 10;
                  $remainder = $total%$drop_down_number;
                  $per_page_count = intval($total/$drop_down_number);
            ?>
<ul class="dropdown-menu" role="menu" style="min-width:10px">
    @for($i=0;$i<$drop_down_number;$i++)
    @if( $i == ($drop_down_number-1) )
    <li><a href="#">{{($i*$per_page_count+$per_page_count+$remainder)}}</a></li>
    @else
    <li><a href="#">{{($i*$per_page_count+$per_page_count)}}</a></li>
    @endif
    @endfor
</ul>
</li>