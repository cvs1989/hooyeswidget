﻿/// <reference path="jquery-1.4.1-vsdoc.js" />
/* @hooyes 2010.11 */
/* jquery.metadata */
(function ($) {

    $.extend({
        metadata: {
            defaults: {
                type: 'class',
                name: 'metadata',
                cre: /({.*})/,
                single: 'metadata'
            },
            setType: function (type, name) {
                this.defaults.type = type;
                this.defaults.name = name;
            },
            get: function (elem, opts) {
                var settings = $.extend({}, this.defaults, opts);
                // check for empty string in single property
                if (!settings.single.length) settings.single = 'metadata';

                var data = $.data(elem, settings.single);
                // returned cached data if it already exists
                if (data) return data;

                data = "{}";

                if (settings.type == "class") {
                    var m = settings.cre.exec(elem.className);
                    if (m)
                        data = m[1];
                } else if (settings.type == "elem") {
                    if (!elem.getElementsByTagName)
                        return undefined;
                    var e = elem.getElementsByTagName(settings.name);
                    if (e.length)
                        data = $.trim(e[0].innerHTML);
                } else if (elem.getAttribute != undefined) {
                    var attr = elem.getAttribute(settings.name);
                    if (attr)
                        data = attr;
                }

                if (data.indexOf('{') < 0)
                    data = "{" + data + "}";

                data = eval("(" + data + ")");

                $.data(elem, settings.single, data);
                return data;
            }
        }
    });

    /**
    * Returns the metadata object for the first member of the jQuery object.
    *
    * @name metadata
    * @descr Returns element's metadata object
    * @param Object opts An object contianing settings to override the defaults
    * @type jQuery
    * @cat Plugins/Metadata
    */
    $.fn.metadata = function (opts) {
        return $.metadata.get(this[0], opts);
    };

})(jQuery);
/* jquery.mbMenu */
(function ($) {
    $.mbMenu = {
        name: "mbMenu",
        author: "Matteo Bicocchi",
        version: "2.8.5rc5",
        actualMenuOpener: false,
        options: {
            template: "yourMenuVoiceTemplate", // the url that returns the menu voices via ajax. the data passed in the request is the "menu" attribute value as "menuId"
            additionalData: "",
            menuSelector: ".menuContainer",
            menuWidth: 200,
            openOnRight: false,
            containment: "window",
            iconPath: "ico/",
            hasImages: true,
            fadeInTime: 100,
            fadeOutTime: 200,
            menuTop: 0,
            menuLeft: 0,
            submenuTop: 0,
            submenuLeft: 4,
            opacity: 1,
            openOnClick: true,
            closeOnMouseOut: true,
            closeAfter: 1000,
            minZindex: "auto", // or number
            hoverIntent: 0, //if you use jquery.hoverIntent.js set this to time in milliseconds; 0= false;
            submenuHoverIntent: 200, //if you use jquery.hoverIntent.js set this to time in milliseconds; 0= false;
            onContextualMenu: function () { } //it pass 'o' (the menu you clicked on) and 'e' (the event)
        },
        buildMenu: function (options) {
            return this.each(function () {
                var thisMenu = this;
                thisMenu.id = !this.id ? "M-E-N-U_" + Math.floor(Math.random() * 1000) : this.id;
                this.options = {};
                $.extend(this.options, $.mbMenu.options);
                $.extend(this.options, options);

                $(".mbmenu").hide();
                thisMenu.clicked = false;
                thisMenu.rootMenu = false;
                thisMenu.actualOpenedMenu = false;
                thisMenu.menuvoice = false;
                var root = $(this);
                var openOnClick = this.options.openOnClick;
                var closeOnMouseOut = this.options.closeOnMouseOut;

                //build roots
                $(root).each(function () {

                    /*
                    *using metadata plugin you can add attribute writing them inside the class attr with a JSON sintax
                    * for ex: class="rootVoice {menu:'menu_2'}"
                    */
                    if ($.metadata) {
                        $.metadata.setType("class");
                        thisMenu.menuvoice = $(this).find(".rootVoice");
                        $(thisMenu.menuvoice).each(function () {
                            if ($(this).metadata().menu) $(this).attr("menu", $(this).metadata().menu);
                            if ($(this).metadata().disabled) $(this).attr("isDisable", $(this).metadata().disabled);
                        });
                    }

                    thisMenu.menuvoice = $(this).find("[menu]").add($(this).filter("[menu]"));
                    thisMenu.menuvoice.filter("[isDisable]").addClass("disabled");

                    $(thisMenu.menuvoice).css("white-space", "nowrap");

                    if (openOnClick) {
                        $(thisMenu.menuvoice).bind("click", function () {
                            $(document).unbind("click.closeMbMenu");
                            if (!$(this).attr("isOpen")) {
                                $(this).buildMbMenu(thisMenu, $(this).attr("menu"));
                                $(this).attr("isOpen", "true");
                            } else {
                                $(this).removeMbMenu(thisMenu, true);
                                $(this).addClass("selected");
                            }

                            //empty
                            if ($(this).attr("menu") == "empty") {
                                if (thisMenu.actualOpenedMenu) {
                                    $("[isOpen]").removeAttr("isOpen");
                                }
                                $(this).removeMbMenu(thisMenu);
                            }
                            $(document).unbind("click.closeMbMenu");
                        });
                    }
                    var mouseOver = $.browser.msie ? "mouseenter" : "mouseover";
                    var mouseOut = $.browser.msie ? "mouseleave" : "mouseout";

                    $(thisMenu.menuvoice).mb_hover(
                  this.options.hoverIntent,
                  function () {
                      if (!$(this).attr("isOpen"))
                          $("[isOpen]").removeAttr("isOpen");
                      if (closeOnMouseOut) clearTimeout($.mbMenu.deleteOnMouseOut);
                      if (!openOnClick) $(thisMenu).find(".selected").removeClass("selected");
                      if (thisMenu.actualOpenedMenu) { $(thisMenu.actualOpenedMenu).removeClass("selected"); }
                      $(this).addClass("selected");
                      if ((thisMenu.clicked || !openOnClick) && !$(this).attr("isOpen")) {
                          $(this).removeMbMenu(thisMenu);
                          $(this).buildMbMenu(thisMenu, $(this).attr("menu"));
                          if ($(this).attr("menu") == "empty") {
                              $(this).removeMbMenu(thisMenu);
                          }
                          $(this).attr("isOpen", "true");
                      }
                  },
                  function () {
                      if (closeOnMouseOut)
                          $.mbMenu.deleteOnMouseOut = setTimeout(function () {
                              $(this).removeMbMenu(thisMenu, true);
                              $(document).unbind("click.closeMbMenu");
                          }, $(root)[0].options.closeAfter);

                      if ($(this).attr("menu") == "empty") {
                          $(this).removeClass("selected");
                      }
                      if (!thisMenu.clicked)
                          $(this).removeClass("selected");
                      $(document).one("click.closeMbMenu", function () {
                          $("[isOpen]").removeAttr("isOpen");
                          $(this).removeClass("selected");
                          $(this).removeMbMenu(thisMenu, true);
                          thisMenu.rootMenu = false; thisMenu.clicked = false;
                      });
                  }
                  );
                });
            });
        },
        buildContextualMenu: function (options) {
            return this.each(function () {
                var thisMenu = this;
                thisMenu.options = {};
                $.extend(thisMenu.options, $.mbMenu.options);
                $.extend(thisMenu.options, options);
                $(".mbmenu").hide();
                thisMenu.clicked = false;
                thisMenu.rootMenu = false;
                thisMenu.actualOpenedMenu = false;
                thisMenu.menuvoice = false;

                /*
                *using metadata plugin you can add attribut writing them inside the class attr with a JSON sintax
                * for ex: class="rootVoice {menu:'menu_2'}"
                */
                var cMenuEls;
                if ($.metadata) {
                    $.metadata.setType("class");
                    cMenuEls = $(this).find(".cmVoice");
                    $(cMenuEls).each(function () {
                        if ($(this).metadata().cMenu) $(this).attr("cMenu", $(this).metadata().cMenu);
                    });
                }
                cMenuEls = $(this).find("[cMenu]").add($(this).filter("[cMenu]"));

                $(cMenuEls).each(function () {
                    $(this).css({ "-webkit-user-select": "none", "-moz-user-select": "none" });
                    var cm = this;
                    cm.id = !cm.id ? "menu_" + Math.floor(Math.random() * 100) : cm.id;
                    $(cm).css({ cursor: "default" });
                    $(cm).bind("contextmenu", "mousedown", function (event) {
                        event.preventDefault();
                        event.stopPropagation();
                        event.cancelBubble = true;

                        $.mbMenu.lastContextMenuEl = cm;

                        if ($.mbMenu.options.actualMenuOpener) {
                            $(thisMenu).removeMbMenu($.mbMenu.options.actualMenuOpener);
                        }
                        /*add custom behavior to contextMenuEvent passing the el and the event
                        *you can for example store to global var the obj that is fireing the event
                        *mbActualContextualMenuObj=cm;
                        *
                        * you can for example create a function that manipulate the voices of the menu
                        * you are opening according to a certain condition...
                        */

                        thisMenu.options.onContextualMenu(this, event);

                        $(this).buildMbMenu(thisMenu, $(this).attr("cMenu"), "cm", event);
                        $(this).attr("isOpen", "true");

                    });
                });
            });
        }
    };
    $.fn.extend({
        buildMbMenu: function (op, m, type, e) {
            var msie6 = $.browser.msie && $.browser.version == "6.0";
            var mouseOver = $.browser.msie ? "mouseenter" : "mouseover";
            var mouseOut = $.browser.msie ? "mouseleave" : "mouseout";
            if (e) {
                this.mouseX = $(this).getMouseX(e);
                this.mouseY = $(this).getMouseY(e);
            }

            if ($.mbMenu.options.actualMenuOpener && $.mbMenu.options.actualMenuOpener != op)
                $(op).removeMbMenu($.mbMenu.options.actualMenuOpener);
            $.mbMenu.options.actualMenuOpener = op;
            if (!type || type == "cm") {
                if (op.rootMenu) {
                    $(op.rootMenu).removeMbMenu(op);
                    $(op.actualOpenedMenu).removeAttr("isOpen");
                    $("[isOpen]").removeAttr("isOpen");
                }
                op.clicked = true;
                op.actualOpenedMenu = this;
                $(op.actualOpenedMenu).attr("isOpen", "true");
                $(op.actualOpenedMenu).addClass("selected");
            }

            //empty
            if ($(this).attr("menu") == "empty") {
                return;
            }

            var opener = this;
            var where = (!type || type == "cm") ? $(document.body) : $(this).parent().parent();

            var menuClass = op.options.menuSelector.replace(".", "");

            if (op.rootMenu) menuClass += " submenuContainer";
            if (!op.rootMenu && $(opener).attr("isDisable")) menuClass += " disabled";

            where.append("<div class='menuDiv'><div class='" + menuClass + " '></div></div>");
            this.menu = where.find(".menuDiv");
            $(this.menu).css({ width: 0, height: 0 });
            if (op.options.minZindex != "auto") {
                $(this.menu).css({ zIndex: op.options.minZindex++ });
            } else {
                $(this.menu).mb_bringToFront();
            }
            this.menuContainer = $(this.menu).find(op.options.menuSelector);

            $(this.menuContainer).bind(mouseOver, function () {
                $(opener).addClass("selected");
            });
            $(this.menuContainer).css({
                position: "absolute",
                opacity: op.options.opacity
            });
//            if (!$("#" + m).html()) {
//                $.ajax({
//                    type: "POST",
//                    url: op.options.template,
//                    cache: false,
//                    async: false,
//                    data: "menuId=" + m + (op.options.additionalData != "" ? "&" + op.options.additionalData : ""),
//                    success: function (html) {
//                        $("body").append(html);
//                        $("#" + m).hide();
//                    }
//                });
//            }
            $(this.menuContainer).attr("id", "mb_" + m).hide();

            //LITERAL MENU SUGGESTED BY SvenDowideit
            var isBoxmenu = $("#" + m).hasClass("boxMenu");

            if (isBoxmenu) {
                this.voices = $("#" + m).clone(true);
                this.voices.css({ display: "block" });
                this.voices.attr("id", m + "_clone");
            } else {
                //TODO this will break <a rel=text> - if there are nested a's
                this.voices = $("#" + m).find("a").clone(true);
            }

            /*
            *using metadata plugin you can add attribut writing them inside the class attr with a JSON sintax
            * for ex: class="rootVoice {menu:'menu_2'}"
            */
            if ($.metadata) {
                $.metadata.setType("class");
                $(this.voices).each(function () {
                    if ($(this).metadata().disabled) $(this).attr("isdisable", $(this).metadata().disabled);
                    if ($(this).metadata().img) $(this).attr("img", $(this).metadata().img);
                    if ($(this).metadata().menu) $(this).attr("menu", $(this).metadata().menu);
                    if ($(this).metadata().action) $(this).attr("action", $(this).metadata().action);
                });
            }


            // build each voices of the menu
            $(this.voices).each(function (i) {

                var voice = this;
                var imgPlace = "";

                var isText = $(voice).attr("rel") == "text";
                var isTitle = $(voice).attr("rel") == "title";
                var isDisabled = $(voice).is("[isdisable]");
                if (!op.rootMenu && $(opener).attr("isDisable"))
                    isDisabled = true;

                var isSeparator = $(voice).attr("rel") == "separator";

                // boxMenu SUGGESTED by Sven Dowideit
                if (op.options.hasImages && !isText && !isBoxmenu) {

                    var imgPath = $(voice).attr("img") ? $(voice).attr("img") : "blank.gif";
                    imgPath = (imgPath.length > 3 && imgPath.indexOf(".") > -1) ? "<img class='imgLine' src='" + op.options.iconPath + imgPath + "'>" : imgPath;
                    imgPlace = "<td class='img'>" + imgPath + "</td>";
                }

                var line = "<table id='" + m + "_" + i + "' class='line" + (isTitle ? " title" : "") + "' cellspacing='0' cellpadding='0' border='0' style='width:100%;' width='100%'><tr>" + imgPlace + "<td class='voice' nowrap></td></tr></table>";

                if (isSeparator)
                    line = "<p class='separator' style='width:100%;'></p>";

                if (isText)
                    line = "<div style='width:100%; display:table' class='line' id='" + m + "_" + i + "'><div class='voice'></div></div>";

                // boxMenu SUGGESTED by Sven Dowideit
                if (isBoxmenu)
                    line = "<div style='width:100%; display:inline' class='' id='" + m + "_" + i + "'><div class='voice'></div></div>";

                $(opener.menuContainer).append(line);

                var menuLine = $(opener.menuContainer).find("#" + m + "_" + i);
                var menuVoice = menuLine.find(".voice");
                if (!isSeparator) {
                    menuVoice.append(this);
                    if ($(this).attr("menu") && !isDisabled) {
                        menuLine.find(".voice a").wrap("<div class='menuArrow'></div>");
                        menuLine.find(".menuArrow").addClass("subMenuOpener");
                        menuLine.css({ cursor: "default" });
                        this.isOpener = true;
                    }
                    if (isText) {
                        menuVoice.addClass("textBox");
                        if ($.browser.msie) menuVoice.css({ maxWidth: op.options.menuWidth });
                        this.isOpener = true;
                    }
                    if (isDisabled) {
                        menuLine.addClass("disabled").css({ cursor: "default" });
                    }

                    if (!(isText || isTitle || isDisabled || isBoxmenu)) {
                        menuLine.css({ cursor: "pointer" });

                        menuLine.bind("mouseover", function () {
                            clearTimeout($.mbMenu.deleteOnMouseOut);
                            $(this).addClass("selected");
                        });

                        menuLine.bind("mouseout", function () {
                            $(this).removeClass("selected");
                        });

                        menuLine.mb_hover(
                    op.options.submenuHoverIntent,
                    function (event) {
                        if (opener.menuContainer.actualSubmenu && !$(voice).attr("menu")) {
                            $(opener.menu).find(".menuDiv").remove();
                            $(opener.menuContainer.actualSubmenu).removeClass("selected");
                            opener.menuContainer.actualSubmenu = false;
                        }
                        if ($(voice).attr("menu")) {
                            if (opener.menuContainer.actualSubmenu && opener.menuContainer.actualSubmenu != this) {
                                $(opener.menu).find(".menuDiv").remove();
                                $(opener.menuContainer.actualSubmenu).removeClass("selected");
                                opener.menuContainer.actualSubmenu = false;
                            }
                            if (!$(voice).attr("action")) $(opener.menuContainer).find("#" + m + "_" + i).css("cursor", "default");
                            if (!opener.menuContainer.actualSubmenu || opener.menuContainer.actualSubmenu != this) {
                                $(opener.menu).find(".menuDiv").remove();

                                opener.menuContainer.actualSubmenu = false;
                                $(this).buildMbMenu(op, $(voice).attr("menu"), "sm", event);
                                opener.menuContainer.actualSubmenu = this;
                            }
                            $(this).attr("isOpen", "true");
                            return false;
                        }
                    },
                    function () { }
                    );
                    }
                    if (isDisabled || isTitle || isText || isBoxmenu) {
                        $(this).removeAttr("href");
                        menuLine.bind(mouseOver, function () {
                            if (closeOnMouseOut) clearTimeout($.mbMenu.deleteOnMouseOut);
                            if (opener.menuContainer.actualSubmenu) {
                                $(opener.menu).find(".menuDiv").remove();
                                opener.menuContainer.actualSubmenu = false;
                            }
                        }).css("cursor", "default");
                    }
                    menuLine.bind("click", function () {
                        if (($(voice).attr("action") || $(voice).attr("href")) && !isDisabled && !isBoxmenu && !isText) {
                            var target = $(voice).attr("target") ? $(voice).attr("target") : "_self";
                            if ($(voice).attr("href") && $(voice).attr("href").indexOf("javascript:") > -1) {
                                $(voice).attr("action", $(voice).attr("href").replace("javascript:", ""));
                            }
                            var link = $(voice).attr("action") ? $(voice).attr("action") : "window.open('" + $(voice).attr("href") + "', '" + target + "')";
                            $(voice).removeAttr("href");
                            eval(link);
                            $(this).removeMbMenu(op, true);
                        } else {
                            $(document).unbind("click.closeMbMenu");
                        }
                    });
                }
            });

            // Close on Mouseout

            var closeOnMouseOut = $(op)[0].options.closeOnMouseOut;
            if (closeOnMouseOut) {
                $(opener.menuContainer).bind("mouseenter", function () {
                    clearTimeout($.mbMenu.deleteOnMouseOut);
                });
                $(opener.menuContainer).bind("mouseleave", function () {
                    var menuToRemove = $.mbMenu.options.actualMenuOpener;
                    $.mbMenu.deleteOnMouseOut = setTimeout(function () { $(this).removeMbMenu(menuToRemove, true); $(document).unbind("click.closeMbMenu"); }, $(op)[0].options.closeAfter);
                });
            }

            //positioning opened
            var t = 0, l = 0;
            $(this.menuContainer).css({
                minWidth: op.options.menuWidth
            });
            if ($.browser.msie) $(this.menuContainer).css("width", $(this.menuContainer).width() + 2);

            switch (type) {
                case "sm":
                    t = $(this).position().top + op.options.submenuTop;

                    l = $(this).position().left + $(this).width() - op.options.submenuLeft;
                    break;
                case "cm":
                    t = this.mouseY - 5;
                    l = this.mouseX - 5;
                    break;
                default:
                    if (op.options.openOnRight) {
                        t = $(this).offset().top - ($.browser.msie ? 2 : 0) + op.options.menuTop;
                        l = $(this).offset().left + $(this).outerWidth() - op.options.menuLeft - ($.browser.msie ? 2 : 0);
                    } else {
                        t = $(this).offset().top + $(this).outerHeight() - (!$.browser.mozilla ? 2 : 0) + op.options.menuTop;
                        l = $(this).offset().left + op.options.menuLeft;
                    }
                    break;
            }

            $(this.menu).css({
                position: "absolute",
                top: t,
                left: l
            });

            if (!type || type == "cm") op.rootMenu = this.menu;
            $(this.menuContainer).bind(mouseOut, function () {
                $(document).one("click.closeMbMenu", function () { $(document).removeMbMenu(op, true); });
            });

            if (op.options.fadeInTime > 0) $(this.menuContainer).fadeIn(op.options.fadeInTime);
            else $(this.menuContainer).show();

            var wh = (op.options.containment == "window") ? $(window).height() : $("#" + op.options.containment).offset().top + $("#" + op.options.containment).outerHeight();
            var ww = (op.options.containment == "window") ? $(window).width() : $("#" + op.options.containment).offset().left + $("#" + op.options.containment).outerWidth();

            var mh = $(this.menuContainer).outerHeight();
            var mw = $(this.menuContainer).outerWidth();

            var actualX = $(where.find(".menuDiv:first")).offset().left - $(window).scrollLeft();
            var actualY = $(where.find(".menuDiv:first")).offset().top - $(window).scrollTop();
            switch (type) {
                case "sm":
                    if ((actualX + mw) >= ww && mw < ww) {
                        l -= ((op.options.menuWidth * 2) - (op.options.submenuLeft * 2));
                    }
                    break;
                case "cm":
                    if ((actualX + (op.options.menuWidth * 1.5)) >= ww && mw < ww) {
                        l -= ((op.options.menuWidth) - (op.options.submenuLeft));
                    }
                    break;
                default:
                    if ((actualX + mw) >= ww && mw < ww) {
                        l -= ($(this.menuContainer).offset().left + mw) - ww + 18;
                    }
                    break;
            }
            if ((actualY + mh) >= wh - 10 && mh < wh) {
                t -= ((actualY + mh) - wh) + 10;
            }

            $(this.menu).css({
                top: t,
                left: l
            });
        },

        removeMbMenu: function (op, fade) {
            if (!op) op = $.mbMenu.options.actualMenuOpener;
            if (!op) return;
            if (op.rootMenu) {
                $(op.actualOpenedMenu)
                .removeAttr("isOpen")
                .removeClass("selected");
                $("[isOpen]").removeAttr("isOpen");
                $(op.rootMenu).css({ width: 1, height: 1 });
                if (fade) $(op.rootMenu).fadeOut(op.options.fadeOutTime, function () { $(this).remove(); });
                else $(op.rootMenu).remove();
                op.rootMenu = false;
                op.clicked = false;
            }
        },

        //mouse  Position
        getMouseX: function (e) {
            var mouseX;
            if ($.browser.msie) mouseX = e.clientX + document.documentElement.scrollLeft;
            else mouseX = e.pageX;
            if (mouseX < 0) mouseX = 0;
            return mouseX;
        },
        getMouseY: function (e) {
            var mouseY;
            if ($.browser.msie) mouseY = e.clientY + document.documentElement.scrollTop;
            else mouseY = e.pageY;
            if (mouseY < 0) mouseY = 0;
            return mouseY;
        },
        //get max z-inedex of the page
        mb_bringToFront: function () {
            var zi = 10;
            $('*').each(function () {
                if ($(this).css("position") == "absolute" || $(this).css("position") == "fixed") {
                    var cur = parseInt($(this).css('zIndex'));
                    zi = cur > zi ? parseInt($(this).css('zIndex')) : zi;
                }
            });

            $(this).css('zIndex', zi += 10);
        },
        mb_hover: function (hoverIntent, fn1, fn2) {
            if (hoverIntent == 0)
                $(this).hover(fn1, fn2);
            else
                $(this).hoverIntent({
                    sensitivity: 30,
                    interval: hoverIntent,
                    timeout: 0,
                    over: fn1,
                    out: fn2
                });
        }
    });
    $.fn.buildMenu = $.mbMenu.buildMenu;
    $.fn.buildContextualMenu = $.mbMenu.buildContextualMenu;
})(jQuery);
/* IOF Adpater hooyes*/
(function($){
  $.extend({
    Request:function(m){
    var url=location.href;
    var query = url.replace(/^[^\?]+\??/, '');
    var Params = {};
    if (!query) { return null; } // return null
    var Pairs = query.split(/[;&]/);
    for (var i = 0; i < Pairs.length; i++) {
        var KeyVal = Pairs[i].split('=');
        if (!KeyVal || KeyVal.length != 2) { continue; }
        var key = unescape(KeyVal[0]);
        var val = unescape(KeyVal[1]);
        val = val.replace(/\+/g, ' ');
        Params[key] = val;
    }
    return Params[m];
    },
    SmartMenu:function(DataSouce, ContainerID, Config){
      MenuCreateHtml(DataSouce, ContainerID, Config);
    }
  });
})(jQuery);
(function($){
$.fn.extend({
    SmartMenu: function (DataSouce, Config) {
        MenuCreateHtml(DataSouce, this, Config);
    }
});
})(jQuery);

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


function MenuCreateHtml(dataSouce, ContainerID, Config) {
    var id_SN="Div-Q-"+Math.floor(Math.random() * 100)+"-J-L";
    var root_SN = "H-00-Y" + Math.floor(Math.random() * 100)+"-E-S";
    var ContainerObj = null
    if (typeof ContainerID == "string") {
        ContainerObj = $("#" + ContainerID);
    } else {
        ContainerObj = $(ContainerID);
    }

    ContainerObj.empty();
    var sb = new StringBuilder();
    sb.AppendFormat("<table class='{0} rootVoices' cellspacing='0' cellpadding='0' border='0'><tr>", root_SN);
    for (var i = 0; i < dataSouce.length; i++) {
        if (!dataSouce[i].enable) {
            continue;
        }
        if (dataSouce[i].single) {
           // sb.AppendFormat("<td class=\"rootVoice {menu: 'Div-Q-J-L{1}'} \" onmouseover=\"SingleOn(this)\" onmouseout=\"SingleOn(this,true)\" >{0}</td>", dataSouce[i].vMenu, i);
            var target = Config ? (Config.target ? Config.target : "_self") : "_self";
            sb.AppendFormat("<td class=\"rootVoice {menu: 'empty'} \" ><a href='{1}' target='{2}' style='display:block'>{0}</a></td>", dataSouce[i].vMenu,dataSouce[i].vLink,target);
        } else {
            sb.AppendFormat("<td class=\"rootVoice {menu: '{2}{1}'}\" >{0}</td>", dataSouce[i].vMenu, i,id_SN);
        }
    }
    sb.Append("</tr></table>");
    ContainerObj.append(sb.ToString());
    var sub = SubMenuCreateHtml(dataSouce, Config,id_SN)
    ContainerObj.append(sub);
    if (Config) {
        $("." + root_SN).buildMenu(Config);
    } else {
        $("." + root_SN).buildMenu();
    }
}
function prop(n) { return n && n.constructor == Number ? n + 'px' : n; }
function selectIE6(){
 if ( $.browser.msie && /6.0/.test(navigator.userAgent) ) {
 var s = {
top: 'auto', // auto == .currentStyle.borderTopWidth
left: 'auto', // auto == .currentStyle.borderLeftWidth
width: 'auto', // auto == offsetWidth
height: 'auto', // auto == offsetHeight
opacity: true,
src: 'about:blank'}
var ie6hack = '<iframe class="bgiframe"frameborder="0"tabindex="-1" src="' + s.src + '"' +
		    'style="display:block;position:absolute;z-index:-1;' +
			    (s.opacity !== false ? 'filter:Alpha(Opacity=\'0\');' : '') +
				'top:' + (s.top == 'auto' ? 'expression(((parseInt(this.parentNode.currentStyle.borderTopWidth)||0)*-1)+\'px\')' : prop(s.top)) + ';' +
				'left:' + (s.left == 'auto' ? 'expression(((parseInt(this.parentNode.currentStyle.borderLeftWidth)||0)*-1)+\'px\')' : prop(s.left)) + ';' +
				'width:' + (s.width == 'auto' ? 'expression(this.parentNode.offsetWidth+\'px\')' : prop(s.width)) + ';' +
				'height:' + (s.height == 'auto' ? 'expression(this.parentNode.offsetHeight+\'px\')' : prop(s.height)) + ';' +
		'"/>';
   //alert(ie6hack);
   return ie6hack;
 }else{
   return "";
 }
}
function SubMenuCreateHtml(data, Config,id_SN) {
    var cols;
    var autoCols = true;
    if (Config) {
        if (Config.submenuCols) {
            cols = Config.submenuCols;
        }
    }
    if (cols == "auto") {
        autoCols = true;
    }else if(cols>0){
        autoCols = false;
    }
    var subMenu = new StringBuilder();
    var id = id_SN;
    var fl = Math.floor(Math.random() * 100);
    for (var k = 0; k < data.length; k++) {
        if (!data[k].enable) {
            continue;
        }
        if(data[k].single){
            continue;
        }
        var v = data[k].vSubMenu;
        var link = [];
        if (data[k].vSubLink) {
            link=data[k].vSubLink
        }
        if (autoCols) {
            cols = AutoBreak(v.length);
        }
        var f = 0;
        //f++;
        var sb = new StringBuilder();
        sb.AppendFormat('<div id="{0}{1}" class="mbmenu boxMenu iebox">', id, k);
        sb.Append(selectIE6());
        sb.Append('<table style="border:0;" ><tr><td>');
//        if (fl >= 50) {
//            sb.Append('<div style="height:40px"><img src="http://crm.italkcs.com/images/company_logo.gif" alt="patapage" width="200"></div>');
//        } else {
//            sb.Append('<div style="height:60px"><img src="http://italklite.com/LT/chtu/img/logo.gif" alt="patapage" ></div>');
//        }
        for (var i = 0; i < v.length; i++) {
            if (f == cols) {
                sb.Append("</td><td>");
                f = 0;
            }
            var target = Config ? (Config.target ? Config.target : "_self") : "_self";
            sb.AppendFormat('<a href="{0}" target="{2}">{1}</a>', link[i],v[i],target);
            f++;
        }
        sb.Append('</td></tr></table>');
        sb.Append("</div>");

        subMenu.Append(sb.ToString());

    }


    return subMenu.ToString();
}
function AutoBreak(n) {
    var r = 5;
    if (n <= 7) {
        r = 7;
    }
    if (n > 7 && n <= 14) {
        if (n == 7) {
            r = n;
        } else {
            r = (n / 2) + 1;
        }
    }
    if (n > 14) {
        r = 9;
    }
    return r;
}
//function rnd() {
//    var i = Math.random() * 100;
//    return i;
//}
