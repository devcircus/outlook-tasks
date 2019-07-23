@extends('pdf.layouts.multiple')

@section('content')
@foreach($data as $category => $tasks)
    <caption class="heading">{{ ucfirst($category) }} Tasks</caption>

    <table class="table table-striped">
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
    <div class="page-break" />
@endforeach
@stop
