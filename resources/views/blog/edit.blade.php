@extends('layout')
@section('main')
    <main class="container" style="background-color: rgb(11, 12, 12)">
        <section id="contact-us">
            <h1 style="padding-top: 50px">Edit a post</h1>

          @include('includes.flash-message')

            <div class="contact-form">
                <form action="{{ route('blog.update', $post) }}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <label for="">Title</label>
                    <input type="text" name="title" value="{{$post->title }}">
                    @error('title')
                        <p style="margin-bottom: 15px; color:red">{{ $message }}</p>
                    @enderror


                    <label for="image">Image</label>
                    <input type="file" name="image" id="image">
                    @error('image')
                        <p style="margin-bottom: 15px; color:red">{{ $message }}</p>
                    @enderror

                    <label for="">Body</label>
                    <textarea name="body" id="body" cols="30" rows="10">{{ $post->body }}</textarea>
                    @error('body')
                        <p style="margin-bottom: 20px; color:red">{{ $message }}</p>
                    @enderror




                    <input type="submit" value="Submit">
                </form>
            </div>
        </section>
    </main>
@endsection
