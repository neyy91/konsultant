{{-- 
    Админ панель на страницах сайта.
 --}}
 @section('head')
    @parent
    <link rel="stylesheet" type="text/css" href="{{ asset2('assets/styles/admin-front.css') }}">
 @stop
 @section('end')
    @parent
     <script src="{{ asset2('assets/scripts/admin-front.js') }}"></script>
     <div class="admin-links" id="adminLinks">
        <ul class="clearfix items">
            @section('admin-links')
                <li class="item"><a href="{{ route('admin', ['iframe' => 'y']) }}" target="iframeAdmin" class="link" data-target="#modalAdmin"><span class="icon glyphicon glyphicon-th" aria-hidden="true"></span> <span class="text">{{ trans('admin.title') }}</span></a></li>
            @show
        </ul>
     </div>

     <div class="modal fade modal-admin" id="modalAdmin" tabindex="-1" role="dialog" aria-labelledby="modalAdminLabel">
         <div class="modal-dialog modal-xl" role="document">
             <div class="modal-content">
                <div class="clearfix modal-body">
                     <button type="button" class="btn btn-danger btn-xs close-top close-modal-admin" aria-hidden="true" aria-label="{{ trans('app.close') }}"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
                     <iframe src="{{ route('admin') }}" id="iframeAdmin" name="iframeAdmin" class="iframe-admin"></iframe>
                     <div class="updating-page-text">{{ trans('app.please_wait_updating_page') }}</div>
                </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-default close-modal-admin">{{ trans('app.close') }}</button>
                 </div>
             </div>
         </div>
     </div>
 @stop

