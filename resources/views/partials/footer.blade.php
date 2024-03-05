<footer class="main-footer text-center">
    &copy; {{ date('Y')}}  <strong>{{config('constants.COMPANY_NAME')}}</strong><br>
    {{config('constants.SITE_NAME')}} {{config('constants.SITE_VERSION')}}<br>
    <span id="livetimerfooter">{{ \Carbon\Carbon::now()->tz(Auth::user()->timezone)->format('Y-m-d H:i:s')}}</span>
</footer>