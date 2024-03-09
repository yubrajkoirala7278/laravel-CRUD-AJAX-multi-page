<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogRequest;
use App\Models\Blog;
use App\Service\Blogservice;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BlogController extends Controller
{
    private $blogService;
    public function __construct() {
        $this->blogService =new Blogservice();
    }
    /**
     * Display a listing of the resource.
     */
    public function home()
    {
        return view('admin.blogs.index');
    }


    public function index()
    {
        try {
            $blogs = $this->blogService->fetchBlogs();
            return DataTables::of($blogs)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $viewUrl = route('blogs.show', ['slug' => $row->slug]);
                    $editUrl = route('blogs.edit', ['slug' => $row->slug]);
                    return '<a href="' . $editUrl . '" class="btn btn-info editButton" >Edit</a> 
                    <a href="javascript:void(0)" class="btn btn-danger delButton" data-slug="' . $row->slug . '">Delete</a> 
                    <a href="' . $viewUrl . '" class="btn btn-success">View</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return view('admin.blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogRequest $request)
    {
        try{
            $this->blogService->addService($request->validated());
            return redirect()->route('blogs')->with('success','Blog added successfully');
        }catch(\Throwable $th){
            return back()->with('error',$th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        try {
            $blog = $this->blogService->fetchSingleBlog($slug);
            return view('admin.blogs.view', compact('blog'));
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        try {
            $blog = $this->blogService->fetchSingleBlog($slug);
            return view('admin.blogs.edit', compact('blog'));
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogRequest $request, Blog $blog)
    {
        try{
            $this->blogService->updateService($request->validated(),$blog);
            return redirect()->route('blogs')->with('success','Blog updated successfully');
        }catch(\Throwable $th){
            dd($th->getMessage());
            return back()->with('error',$th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($slug)
    {
        try{
            $blog = Blog::where('slug', $slug)->first();
            if (!$blog) {
                abort(404);
            }
            $this->blogService->delete($blog);
            return response()->json([
                'success' => 'Blog Deleted Successfully'
            ], 201);
        }catch(\Throwable $th){
            return back()->with('error',$th->getMessage());
        }
    }
}
