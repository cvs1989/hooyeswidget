/// <reference path="jquery-1.4.1-vsdoc.js" />
/// <reference path="jquery.colorbox.js" />
function Auth() {
    this.isLogin = false;
    this.AuthUrl = "";
    //this.Init();
    this.UserID = null;
    this.AppPath = "/";
}
Auth.prototype.Login = function (fn) {
    var t = this;
    if (this.AuthUrl == "") {
        setTimeout(function () {
            t.Login(fn);
        }, 200);
    } else {
        $.colorbox({ href: this.AuthUrl, width: "50%", height: "85%", iframe: true, onClosed: function () {
            t.callbck(fn);
        }
        });
    }
}
Auth.prototype.CheckLogin = function (fn) {
    var t = this;
    if (fn) {
        fn(t.isLogin);
    }
}
Auth.prototype.logout = function (fn) {
    var t = this;
    $.ajax({
        url: t.AppPath+'_oAuth/cl',
        dataType: 'html',
        type: 'POST',
        success: function (data) {
            if (data == "ok") {
                t.isLogin = false;
                if (fn) {
                    fn(t.isLogin);
                }
            }
        },
        error: function () {
            alert("error");
        }
    });

    t.cache("UserID", null, { expires: '2001-01-01' });
}
Auth.prototype.Init = function (nav, appRoot) {
    $(function () {

        auth.Nav(nav);
        $("#login").click(function () {
            auth.Login(cl);
        });
        $("#logout").click(function () {
            auth.logout(cl);
        });


        auth.CheckLogin(cl);

    });
    var t = this;
    if (appRoot != null) {
        t.AppPath = appRoot;
    }
    if (t.cache("UserID") == null) {
        $.ajax({
            url: t.AppPath + '_oAuth/GetAuthUrl',
            dataType: 'json',
            type: 'POST',
            success: function (data) {
                t.AuthUrl = data.AuthUrl;
            },
            error: function () {
                alert("error");
            }
        });
    } else {
        t.UserID = t.cache("UserID");
        t.isLogin = true;
    }

}
Auth.prototype.callbck = function (fn) {
    var t = this;
    $.ajax({
        url: t.AppPath + '_oAuth/getToken',
        dataType: 'json',
        type: 'POST',
        success: function (data) {
            t.UserID = data.UserID;

            if (t.UserID != null) {
                t.cache("UserID", t.UserID);
                t.isLogin = true;
                fn(t.isLogin);
            }
        },
        error: function () {
            alert("error");
        }
    });
}
Auth.prototype.test = function () {
    alert(this.UserID);
}
Auth.prototype.cache = function (name, value, options) {
    return $.cookie(name, value, options);
}
Auth.prototype.Nav = function (id) {
    var sb = new StringBuilder();
    sb.AppendFormat("<span id=\"c_user\"></span><a id=\"login\" href=\"javascript:void(0)\">您还未登录，请登录</a><a id=\"logout\" href=\"javascript:void(0)\">Logout</a>");
    $(id).html(sb.ToString());
}
function cl(islogin) {
    if (islogin) {
        $("#login").hide();
        $("#logout").show();
        $("#c_user").html(auth.UserID);
    } else {
        $("#logout").hide();
        $("#login").show();
        $("#c_user").html("");
    }
}

jQuery.cookie = function (name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        // CAUTION: Needed to parenthesize options.path and options.domain
        // in the following expressions, otherwise they evaluate to undefined
        // in the packed version for some reason...
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};

function StringBuilder() { this.hooyesStr = ""; }
StringBuilder.prototype.Append = function (str) { this.hooyesStr += str; }
StringBuilder.prototype.AppendFormat = function () {
    if (arguments.length > 1) {
        var TString = arguments[0];
        if (arguments[1] instanceof Array) {
            for (var i = 0; i < arguments[1].length; i++) {
                var jIndex = i; var re = eval("/\\{" + jIndex + "\\}/g;");
                TString = TString.replace(re, arguments[1][i]);
            }
        } else {
            for (var i = 1; i < arguments.length; i++) {
                var jIndex = i - 1;
                var re = eval("/\\{" + jIndex + "\\}/g;");
                TString = TString.replace(re, arguments[i]);
            }
        }
        this.Append(TString);
    }
    if (arguments.length == 1) { this.Append(arguments[0]); }
}
StringBuilder.prototype.ToString = function () { return this.hooyesStr; }