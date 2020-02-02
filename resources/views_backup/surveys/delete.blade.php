@extends('layouts.app')
@section('title', 'Delete Meeting Schedule')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <td class="col-xs-2">Department</td>
                                <td>{{ $survey->department->name }}</td>
                            </tr>
                            <tr>
                                <td class="col-xs-2">Project</td>
                                <td>{{ $survey->project->name }}</td>
                            </tr>
                            <tr>
                                <td class="col-xs-2">Name</td>
                                <td>{{ $survey->name }}</td>
                            </tr>
                            <tr>
                                <td class="col-xs-2">Remarks</td>
                                <td>{{ $survey->remarks }}</td>
                            </tr>
                        </table>
                    </div>
                    <h2 class="text-center">Questions</h2>
                    <div class="table-responsive">
                        @foreach($survey->questions as $question)
                            <table class="table table-bordered">
                                <tr>
                                    <td class="col-xs-1">{{ $loop->index+1 }}</td>
                                    <td colspan="{{ $question->answerOptions->count() }}">{{ $question->body }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        @foreach($question->answerOptions as $answerOption)
                                            {{ $answerOption->key }}: {{ $answerOption->body }} <br />
                                        @endforeach
                                    </td>
                                </tr>
                            </table>
                        @endforeach
                    </div>
                    <br />
                    <hr />
                    <form method="POST" action="{{ route('surveys.confirmDelete', ['id' => $survey->id]) }}">
                        {{ csrf_field() }}

                            <div class="row text-center input-area">
                                    <button type="submit">
                                        Confirm Delete
                                    </button>
                                <a href="{{ route('surveys.index') }}" class="big-btn">Back</a>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection