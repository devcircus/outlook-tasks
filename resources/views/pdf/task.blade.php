@extends('pdf.layout')

@section('content')
<div class="w-1/2">
    <div class="w-full">
        <h1 class="text-2xl text-gray-700 font-semibold">{{ $task->title }}</h1>
        <h2 class="text-lg text-blue-700 font-semibold uppercase">{{ $task->category->name }}</h2>
        <span class="text-base text-gray-700">Due: <span class="text-blue-500">{{ $task->display_due_date }}</span></span>
    </div>
    <div class="w-full">
        <p class="text-sm text-gray-800">{{ $task->description }}</p>
    </div>
</div>
@stop
