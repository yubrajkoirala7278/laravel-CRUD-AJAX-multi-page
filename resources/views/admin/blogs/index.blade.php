@extends('admin.layouts.master')
@section('content')
{{-- Go to create page --}}
<a href="{{route('blogs.create')}}" class="btn btn-success">Add Blog</a>
{{-- end of go to create page --}}
{{-- =========DISPLAY BLOGS================= --}}
<table id="blog-table">
    <thead>
        <tr>
            <th>S.No</th>
            <th>Title</th>
            <th>Slug</th>
            <th>Description</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
    </thead>
</table>
{{-- =========END OF DISPLAY BLOGS=============== --}}
@endsection

@push('script')
<script>
    $(document).ready(function() {
        // =====setup csrf token======
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
         // ===========READ DATA FROM DB(READ)====================//
         var table =  $('#blog-table').DataTable({
            "processing": true,
            "serverSide": true,
            "deferRender": true,
            "ordering": false,
            searchDelay:3000,
            "ajax": {
                url: "{{ route('blogs.index') }}",
                type: 'GET',
                dataType: 'JSON'
            },
            "columns": [
                { data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false },
                { data: 'title', name: 'title', },
                { data: 'slug', name: 'slug' },
                { data: 'description', name: 'description' },
                {
                    data: "image",
                    name:"image",
                    "render": function(data, type, full, meta) {
                    return '<img src="{{ asset('storage/images/blogs/') }}/' + data + '" alt="Image" style="height:20px">';
                    }
                },
                {data: 'action',name: 'action',orderable: false,searchable: false},
            ],
            "lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "All"]],
            "pagingType": "simple_numbers"
        });
        // ===================================================================//

          // ================DELETE BLOG==============================//
          $('body').on('click', '.delButton', function() {
                let slug = $(this).data('slug');
                if (confirm('Are you sure you want to delete it')) {
                    $.ajax({
                        url: '{{ url('admin/blogs/destroy', '') }}' + '/' + slug,
                        method: 'DELETE',
                        success: function(response) {
                            // refresh the table after delete
                            table.draw();
                            // display the delete success message
                            toastify().success(response.success);
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                }
            });
        // =====================================================================//
    });

</script>
@endpush