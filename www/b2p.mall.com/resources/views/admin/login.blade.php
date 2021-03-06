<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta charset="utf-8" />
        <title>登陆 - 后台管理</title>

        <meta name="description" content="User login page" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

        <!-- bootstrap & fontawesome -->
        <link rel="stylesheet" href="{{ elixir('css/admin/bootstrap.css') }}" />
        <link rel="stylesheet" href="{{ elixir('css/admin/font-awesome.min.css') }}"/>

       <!-- text fonts -->
        <link rel="stylesheet" href="{{ elixir('css/admin/ace-fonts.css') }}" />

        <!-- ace styles -->
        <link rel="stylesheet" href="{{ elixir('css/admin/ace.css') }}"/>

        <!--[if lte IE 9]>
            <link rel="stylesheet" href="{{ elixir('css/admin/ace-part2.css') }}" class="ace-main-stylesheet" />
        <![endif]-->
        <link rel="stylesheet" href="{{ elixir('css/admin/ace-rtl.css') }}" />

        <!--[if lte IE 9]>
          <link rel="stylesheet" href="{{ elixir('css/admin/ace-ie.css') }}" />
        <![endif]-->

        <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

        <!--[if lte IE 8]>
            <script src="{{ elixir('js/admin/html5shiv.min.js') }}"></script>
            <script src="{{ elixir('js/admin/respond.min.js') }}"></script>
        <![endif]-->
    </head>

    <body class="login-layout light-login">
        <div class="main-container">
            <div class="main-content">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="login-container">
                            <div class="center">
                                <h1 class="red">
                                    后台管理系统
                                </h1>
                            </div>

                            <div class="space-6"></div>

                            <div class="position-relative">
                                <div id="login-box" class="login-box visible widget-box no-border">
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <h4 class="header blue lighter bigger">
                                                <i class="ace-icon fa fa-coffee green"></i>
                                                请输入登陆信息
                                            </h4>

                                            <div class="space-6"></div>

                                            <form action="{{route('admin-login')}}" method="POST">
                                                <fieldset>
                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="text" class="form-control" placeholder="用户名" name="user_name"/>
                                                            <i class="ace-icon fa fa-user"></i>
                                                        </span>
                                                    </label>

                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="password" class="form-control" placeholder="密码" name="password"/>
                                                            <i class="ace-icon fa fa-lock"></i>
                                                        </span>
                                                    </label>

                                                    <div class="space"></div>

                                                    <div class="clearfix">
                                                        <button type="submit" class="width-35 pull-right btn btn-sm btn-primary">
                                                            <i class="ace-icon fa fa-key"></i>
                                                            <span class="bigger-110">登陆</span>
                                                        </button>
                                                    </div>

                                                    <div class="space-4"></div>
                                                </fieldset>
                                            </form>
                                        </div><!-- /.widget-main -->

                                        <div class="toolbar clearfix">
                                            <h4 class="center">
                                                Superman &copy; 2016 - 2020
                                            </h4>
                                        </div>
                                    </div><!-- /.widget-body -->
                                </div><!-- /.login-box -->
                            </div><!-- /.position-relative -->
                        </div>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.main-content -->
        </div><!-- /.main-container -->
        <!-- basic scripts -->
        <script src="{{ elixir('js/jquery.js') }}"></script>
    </body>
</html>
