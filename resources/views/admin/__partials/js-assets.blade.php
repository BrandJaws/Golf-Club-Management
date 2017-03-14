<!-- build:js scripts/app.html.js -->
<!-- jQuery -->
<script src="{{asset('/libs/jquery/jquery/dist/jquery.js')}}"></script>
<script src="{{asset('/libs/helpers.js')}}"></script>
<script src="{{asset('/libs/momentjs/moment.min.js')}}"></script>
<script src="{{asset('/scripts/jquery-ui.min.js')}}"></script>
<script src="{{asset('/scripts/tags-input/bootstrap-tagsinput.js')}}"></script>

<!-- VueJS -->
<script src="{{asset('/libs/vuejs/vue.js')}}"></script>


<!-- Bootstrap -->
<script src="{{asset('/libs/jquery/tether/dist/js/tether.min.js')}}"></script>
<script src="{{asset('/libs/jquery/bootstrap/dist/js/bootstrap.js')}}"></script>
<script src="{{asset('/libs/jquery/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('/chat/script.js')}}"></script>
<!-- core -->

<!-- owl -->
<script src="{{asset('/scripts/owl/js/owl.carousel.js')}}"></script>
<!-- owl -->
<script src="{{asset('/libs/jquery/underscore/underscore-min.js')}}"></script>
<script
	src="{{asset('/libs/jquery/jQuery-Storage-API/jquery.storageapi.min.js')}}"></script>
<script src="{{asset('/libs/jquery/PACE/pace.min.js')}}"></script>
<script src="{{asset('/scripts/config.lazyload.js')}}"></script>
<script src="{{asset('/scripts/palette.js')}}"></script>
<script src="{{asset('/scripts/ui-load.js')}}"></script>
<script src="{{asset('/scripts/ui-jp.js')}}"></script>
<script src="{{asset('/scripts/ui-include.js')}}"></script>
<script src="{{asset('/scripts/ui-device.js')}}"></script>
<script src="{{asset('/scripts/ui-form.js')}}"></script>
<script src="{{asset('/scripts/ui-nav.js')}}"></script>
<script src="{{asset('/scripts/ui-screenfull.js')}}"></script>
<script src="{{asset('/scripts/ui-scroll-to.js')}}"></script>
<script src="{{asset('/scripts/ui-toggle-class.js')}}"></script>
<script src="{{asset('/scripts/app.js')}}"></script>


<!-- ajax -->
<script src="{{asset('/libs/jquery/jquery-pjax/jquery.pjax.js')}}"></script>
<script src="{{asset('/scripts/ajax.js')}}"></script>
<!-- endbuild -->
<!-- inline-date-picker -->
<script src="{{asset('/assets/js-webshim/minified/polyfiller.js')}}"></script>
<script>
    //webshim.setOptions('basePath', '{{asset('/assets/js-webshim/minified/shims')}}');

    //request the features you need:
    //webshim.polyfill('forms forms-ext');

    webshim.setOptions('forms-ext', {
        replaceUI: 'auto',
        types: 'date',
        date: {
            startView: 2,
            inlinePicker: true,
            classes: 'hide-inputbtns'
        }
    });
    webshim.setOptions('forms', {
        lazyCustomMessages: true
    });
    //start polyfilling
    //webshim.polyfill('forms forms-ext');
    webshim.polyfill('forms forms-ext');

    //only last example using format display
    $(function () {
        $('.format-date').each(function () {
            var $display = $('.date-display', this);
            $(this).on('change', function (e) {
                //webshim.format will automatically format date to according to webshim.activeLang or the browsers locale
                var localizedDate = webshim.format.date($.prop(e.target, 'value'));
                $display.html(localizedDate);
            });
        });
    });
</script>