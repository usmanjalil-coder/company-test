@extends('layouts.app')

@section('content')
<div class="content">
    <div class="card mt-3">
        <div class="card-body">
            <h5 class="card-title">Edit Authority Letter</h5>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('authority-letters.update', $authorityLetter->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="client_id" class="form-label">Client</label>
                    <select name="client_id" class="form-control" required>
                        <option value="">Select Client</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id', $authorityLetter->client_id) == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">Content</label>
                    <textarea name="content" class="form-control" required>{{ old('content', $authorityLetter->content) }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" name="date" class="form-control" value="{{ old('date', $authorityLetter->date->format('Y-m-d')) }}" required>
                </div>
                <div class="mb-3">
                    <label for="file" class="form-label">Upload PDF</label>
                    @if ($authorityLetter->file_path)
                        <a href="{{ Storage::url($authorityLetter->file_path) }}" target="_blank">View Current File</a>
                    @endif
                    <input type="file" name="file" class="form-control" accept=".pdf">
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('authority-letters.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection