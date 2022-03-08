@extends('layout')
@section('main')
    <main class="container" style="background-color: rgb(11, 12, 12)">
        <section id="contact-us">
            <h1 style="padding-top: 50px">Create new post</h1>

            @include('includes.flash-message')

            <div class="contact-form">
                <form action="{{ route('blog.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for="">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}">
                    @error('title')
                        <p style="margin-bottom: 15px; color:red">{{ $message }}</p>
                    @enderror


                    <label for="image">Image</label>
                    <input type="file" name="image" id="image">
                    @error('image')
                        <p style="margin-bottom: 15px; color:red">{{ $message }}</p>
                    @enderror

                    <label for="categories">Category</label>

                    <select name="category_id" id="category_id">

                        <option selected disabled>Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <p style="margin-bottom: 15px; color:red">{{ $message }}</p>
                @enderror




                    <label for="">Body</label>
                    <textarea name="body" id="body" cols="30" rows="10">{{ old('body') }}</textarea>
                    @error('body')
                        <p style="margin-bottom: 20px; color:red">{{ $message }}</p>
                    @enderror




                    <input type="submit" value="Submit">
                </form>
            </div>
        </section>
    </main>
@endsection
