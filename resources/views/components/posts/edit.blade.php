 @push('style')
     <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
 @endpush

 <div class="max-w-4xl relative p-4 bg-white rounded-lg border dark:bg-gray-800 sm:p-5">
     <!-- Modal header -->
     <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
         <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Add Post</h3>
     </div>

     <!-- Modal body -->
     <form action="/dashboard/{{ $post->slug }}" method="POST" id="post-form">
         @csrf
         @method('PATCH')
         <div class="gap-4 mb-4 sm:grid-cols-2">
             <div class="mb-4">
                 <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Title</label>
                 <input type="text" name="title" id="title"
                     class="@error('title') border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500 @enderror border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                     placeholder="Type post title" autofocus value="{{ old('title', $post->title )}}">
                 @error('title')
                     <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                         {{ $message }}
                     </p>
                 @enderror
             </div>
             <div class="mb-4">
                 <label for="category"
                     class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
                 <select name="category_id" id="category"
                     class="@error('category_id') bg-red-50 border-red-500 text-red-700 placeholder-red-700 focus:ring-red-500 focus:border-red-500 @enderror border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                     <option selected="" value="">Select post category</option>
                     @foreach (App\Models\Category::get() as $category)
                         <option value="{{ $category->id }}" @selected((old('category_id', $post->category->id)) == $category->id)>{{ $category->name }}
                         </option>
                     @endforeach
                 </select>
                 @error('category_id')
                     <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                         {{ $message }}
                     </p>
                 @enderror
             </div>
             <div class="mb-4"><label for="description"
                     class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Body</label>
                 <textarea id="body" name="body" rows="4"
                     class="hidden @error('body') border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500 @enderror block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                     placeholder="Write post body here">{{ old('body', $post->body)}}</textarea>

                 <div id="editor">{!! old('body', $post->body) !!}</div>

                 @error('body')
                     <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                         {{ $message }}
                     </p>
                 @enderror
             </div>
         </div>
         <div class="flex gap-2">
             <button type="submit"
                 class="text-white inline-flex items-center bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                 <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 20 20" fill="currentColor"
                     aria-hidden="true">
                     <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                     <path fill-rule="evenodd" clip-rule="evenodd"
                         d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                 </svg>
                 Update post
             </button>
             <a href="/dashboard"
                 class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                 <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewbox="0 0 20 20"
                     xmlns="http://www.w3.org/2000/svg">
                     <path fill-rule="evenodd"
                         d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                         clip-rule="evenodd" />
                 </svg>
                 Cancel
             </a>
         </div>
     </form>
 </div>



 @push('script')
     <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

     <script>
         const quill = new Quill('#editor', {
             theme: 'snow',
             placeholder: 'Write post body here'
         });


         const postForm = document.querySelector('#post-form');
         const postBody = document.querySelector('#body');
         const quillEditor = document.querySelector('#editor');

         postForm.addEventListener('submit', function(e) {
             e.preventDefault();

             const content = quillEditor.children[0].innerHTML;

             postBody.value = content;

             this.submit();
         });
     </script>
 @endpush
