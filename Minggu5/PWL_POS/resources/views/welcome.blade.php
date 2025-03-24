{{-- <!DOCTYPE html>
 <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
     <head>
         <meta charset="utf-8">
 @@ -130,4 +130,30 @@
             </div>
         </div>
     </body>
 </html> --}}
 @extends('layouts.template')
 
 {{-- Customize layout sections --}}
 
 @section('content')
 
 <div class="card">
    <div class="card-header">
        <h3 class="card-title">Halo, apakabar! !!</h3>
        <div class="card-tools"></div>
    </div>
    <div class="card-body" >
        Selamat datang semua,ini adalah halaman utama dari aplikasi ini.
    </div>
</div>
 {{-- Content body: main page content --}}
 
 {{-- @section('content_body')
     <p>Welcome to this beautiful admin panel.</p>
 @stop --}}
 
 {{-- Push extra CSS --}}
 
 {{-- @push('css')
     Add here extra stylesheets
     <link rel="stylesheet" href="/css/admin_custom.css">
 @endpush --}}
 
 {{-- Push extra scripts --}}
 
 {{-- @push('js')
     <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script> --}}
 {{-- @endpush --}}
 @endsection