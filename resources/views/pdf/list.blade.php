@extends('pdf.layouts.multiple')

@section('content')
@foreach($data as $category => $tasks)
    <table class="table table-striped">
        <caption class="heading">{{ ucfirst($category) }} Tasks</caption>
        <thead>
            <tr>
                <th style="width:100px;">Due Date</th>
                <th>Title</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
            <tr>
                <td style="width:100px;">{{ $task['display_due_date'] }}</td>
                <td>{{ $task['title'] }}</td>
                <td>&nbsp;</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endforeach
@stop
