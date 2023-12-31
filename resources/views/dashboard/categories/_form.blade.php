@if ($errors->any())
    <div class="alret alert-danger">
        <h3>Error Occured!</h3>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-group">
    <x-form.input  label="Category Name" name="name" :value="$category->name"  />
</div>

<div class="form-group">
    <label for="">Category Parent</label>
    <select name="parent_id" class="form-control foem-select">
        <option value="">Primary Category</option>
        @foreach ($parents as $parent)
            <option value="{{ $parent->id }}" @selected(old('parent_id',$category->parent_id) == $parent->id)>{{ $parent->name }}</option>
        @endforeach
    </select>
    
</div>

<div class="form-group">
    <label for="">Description</label>
    <x-form.textarea name="description" :value="$category->description" />
</div>

<div class="form-group">
<x-form.label for="image" >Image</x-form.label>
    <x-form.input type="file" name="image" />
    @if ($category->image)
    <img src="{{ asset('storage/'. $category->image) }}" alt="" height="60">
    @endif
</div>

<div class="form-group">
    <label for="">Status</label>
    <div>
        <x-form.radio name="status" :checked="$category->status" :options="['active' => 'Active' , 'archived' => 'Archived']" />
    </div>
</div>
<div class="form-group">
    <button type="submit" class="btn btn-primary">{{ $button_lable ?? 'Save' }}</button>
</div>