<!doctype html>
<html lang="en">
  {%include _head.php%}
  <body>
  {%include _header.php%}
    <div class="container marketing">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div style="margin-top:30px"></div>
                <h2 class="text-center">Abigeater Home Page</h2>
                <hr>
                <b>内容导航：</b>
                <ul>
                    {{list}}
                </ul>
                <hr>
        </div>
    </div>
    {%include _footer.php%}
  </body>
</html>
