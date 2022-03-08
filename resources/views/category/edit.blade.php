@extends('layout')
@section('main')
    <main class="container" style="background-color: rgb(11, 12, 12)">
        <section id="contact-us">
            <h1 style="padding-top: 50px">Create new category</h1>

            @include('includes.flash-message')

            <div class="contact-form">
                <form action="{{ route('categories.update', $category) }}" method="POST" >
                    @method('put')
                    @csrf
                    <label for="">Name</label>
                    <input type="text" name="name" value="{{ $category->name }}">
                    @error('name')
                        <p style="margin-bottom: 15px; color:red">{{ $message }}</p>
                    @enderror
                    <input type="submit" value="Submit">
                </form>
                <div class="create-categories">
                    <a href="{{route('categories.index')}}">Categories list <span>&#8594;</span></a>
                </div>
            </div>
        </section>
    </main>
@endsection