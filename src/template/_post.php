<!doctype html>
<html lang="en">
    {%include _head.php%}
  <body>
    {%include _header.php%}
    <div class="container marketing">
        <div class="row">
            <div class="col-md-6 offset-md-3" style="margin-left: 0px; flex: unset; max-width: unset;">
                <div style="margin-top:30px"></div>
                <h2 class="text-center">{{post_title}}</h2>
                <hr>
                    {{content}}
                <hr>
            </div>
        </div>
    </div>
    {%include _footer.php%}
  </body>
</html>
