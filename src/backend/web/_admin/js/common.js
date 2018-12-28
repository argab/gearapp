if (typeof _csrf === 'undefined') var _csrf = yii.getCsrfToken();

function utf8_encode(string)
{
    string = string.replace(/\r\n/g, "\n");
    var utftext = "";

    for (var n = 0; n < string.length; n++)
    {
        var c = string.charCodeAt(n);

        if (c < 128)
        {
            utftext += String.fromCharCode(c);
        }
        else if ((c > 127) && (c < 2048))
        {
            utftext += String.fromCharCode((c >> 6) | 192);
            utftext += String.fromCharCode((c & 63) | 128);
        }
        else
        {
            utftext += String.fromCharCode((c >> 12) | 224);
            utftext += String.fromCharCode(((c >> 6) & 63) | 128);
            utftext += String.fromCharCode((c & 63) | 128);
        }

    }

    return utftext;
}

/**
 * find if a array contains the object using indexOf or a simple polyFill
 * @param {Array} src
 * @param {String} find
 * @param {String} [findByKey]
 * @return {Boolean|Number} false when not found, or the index
 */
function inArray(src, find, findByKey)
{
    if (src.indexOf && !findByKey)
    {
        return src.indexOf(find);
    }
    else
    {
        var i = 0;
        while (i < src.length)
        {
            if ((findByKey && src[i][findByKey] == find) || (!findByKey && src[i] === find))
            {
                return i;
            }
            i++;
        }
        return -1;
    }
}

function utf8_decode(utftext)
{
    var string = "";
    var i = 0;
    var c = c1 = c2 = 0;

    while (i < utftext.length)
    {
        c = utftext.charCodeAt(i);

        if (c < 128)
        {
            string += String.fromCharCode(c);
            i++;
        }
        else if ((c > 191) && (c < 224))
        {
            c2 = utftext.charCodeAt(i + 1);
            string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
            i += 2;
        }
        else
        {
            c2 = utftext.charCodeAt(i + 1);
            c3 = utftext.charCodeAt(i + 2);
            string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
            i += 3;
        }

    }

    return string;
}

function base64_encode(data)
{   // Encodes data with MIME base64
    //
    // +   original by: Tyler Akins (http://rumkin.com)
    // +   improved by: Bayron Guevara

    var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    var o1, o2, o3, h1, h2, h3, h4, bits, i = 0, enc = '';

    data = utf8_encode(data);

    do { // pack three octets into four hexets
        o1 = data.charCodeAt(i++);
        o2 = data.charCodeAt(i++);
        o3 = data.charCodeAt(i++);

        bits = o1 << 16 | o2 << 8 | o3;

        h1 = bits >> 18 & 0x3f;
        h2 = bits >> 12 & 0x3f;
        h3 = bits >> 6 & 0x3f;
        h4 = bits & 0x3f;

        // use hexets to index into b64, and append result to encoded string
        enc += b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
    }
    while (i < data.length);

    switch (data.length % 3)
    {
        case 1:
            enc = enc.slice(0, -2) + '==';
            break;
        case 2:
            enc = enc.slice(0, -1) + '=';
            break;
    }

    return enc;
}

function base64_decode(data)
{   // Decodes data encoded with MIME base64
    var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    var o1, o2, o3, h1, h2, h3, h4, bits, i = 0, enc = '';

    do {  // unpack four hexets into three octets using index points in b64
        h1 = b64.indexOf(data.charAt(i++));
        h2 = b64.indexOf(data.charAt(i++));
        h3 = b64.indexOf(data.charAt(i++));
        h4 = b64.indexOf(data.charAt(i++));

        bits = h1 << 18 | h2 << 12 | h3 << 6 | h4;

        o1 = bits >> 16 & 0xff;
        o2 = bits >> 8 & 0xff;
        o3 = bits & 0xff;

        if (h3 == 64)
        {
            enc += String.fromCharCode(o1);
        }
        else if (h4 == 64)
        {
            enc += String.fromCharCode(o1, o2);
        }
        else
        {
            enc += String.fromCharCode(o1, o2, o3);
        }
    }
    while (i < data.length);

    return utf8_decode(enc);
}

$.insertStyle = function (name, options, override)
{
    var stylesheet = $(document).find('style[data-stylesheet="' + name + '"]');
    var css = '';
    var hash = stylesheet.length ? stylesheet.data('hash') : null;
    var ov = typeof(override) !== 'undefined' ? override : true;

    if (!ov && $(document).find('style[data-stylesheet="' + name + '"]').length)
    {
        return false;
    }

    if (!hash)
    {
        $('<style type="text/css" data-stylesheet="' + name + '"></style>').appendTo(document.head);
    }

    var rgx = new RegExp('^([\\+]+)(.*?)', 'gi');
    var rgxx = new RegExp('(.*?)(\\})?$', 'gi');

    $.each(options, function (i, val)
    {
        var m = i.match(rgx);
        if (m) css = css.replace(rgxx, "$1");
        css += (m ? i.replace(rgx, "$2") : i) + '{' + val.join(';') + (i.match(/{/) ? '}}' : '}');
    });

    var cssHash = (md5(css)).substring(0, 10);

    if (cssHash !== hash)
    {
        $(document).find('style[data-stylesheet="' + name + '"]')
            .append(css)
            .attr('data-hash', cssHash)
        ;
    }
};

function ajaxLoader(el, options, process)
{
    var defaults = {
        bgColor:      '#fff',
        duration:     500,
        opacity:      0.7,
        classOveride: true,
        margin:       'auto',
        padding:      'auto',
        process_bg:   '/images/ajax_loader.gif',
        process_w:    '36px',
        process_h:    '36px',
        process_txt:  null
    };

    this.options = $.extend(defaults, options);
    this.container = $.isPlainObject(el) ? el : $(el);
    this.pos = this.container.offset();
    this.process = !!process;

    this.setProcessPosition = function ()
    {

        this.process.css({
            left: (this.process.parent().width() / 2 - this.process.width() / 2) + 'px',
            top:  (this.process.parent().parent().height() / 2 - this.process.height() / 2) + 'px'
        });

        if (this.options.process_txt)
        {
            var txt = this.process.next('._ajax_loader_process_txt');

            txt.css({
                top:  (this.process.position().top
                + (this.process.height() ? this.process.height() : -txt.height() / 2)) + 'px',
                left: ((this.container.width() / 2 - txt.width() / 2) + 15) + 'px'
            });
        }
    };

    this.init = function ()
    {
        var container = this.container;

        var pos = typeof this.pos !== "undefined" ? this.pos : null;

        var index = this.container.index();

        var body = $('body');

        this.remove();

        var overlay = body.find('#ajax-overlay-' + index);

        if (!overlay.length)
        {
            body.append($('<div id="ajax-overlay-' + index + '"></div>'));

            overlay = body.find('#ajax-overlay-' + index);
        }

        overlay.css({
                'background-color': this.options.bgColor,
                'opacity':          this.options.opacity,
                'width':            ("width" in this.options) ? this.options.width : container.outerWidth(),
                'height':           ("height" in this.options) ? this.options.height : container.outerHeight(true),
                'position':         'absolute',
                'top':              (("top" in this.options) ? this.options.top : (pos !== null ? pos.top : 0)),
                'left':             (("left" in this.options) ? this.options.left : (pos !== null ? pos.left : 0)),
                'z-index':          (("z-index" in this.options) ? this.options['z-index'] : '9999'),
                'margin':           this.options.margin,
                'padding':          this.options.padding
            })
            .fadeIn(this.options.duration);

        if (this.options.classOveride)
        {
            overlay.addClass(this.options.classOveride);
        }

        var process = '<div class="_ajax_loader_process"></div>';

        $.insertStyle('_ajax_loader', {
            '._ajax_loader_process':     [
                'position: absolute'
            ],
            '._ajax_loader_process_txt': [
                'position: absolute'
            ]
        }, false);

        if (!overlay.find('._ajax_loader_process').length)
        {
            overlay.append('<div style="position: relative;width:100%">' + process + '</div>');
        }

        var appendProcess = this.process;

        this.process = overlay.find('._ajax_loader_process');

        if (appendProcess)
        {
            this.process.css({
                width:      this.options.process_w,
                height:     this.options.process_h,
                background: 'url(' + this.options.process_bg + ') center no-repeat'
            });
        }
        else
        {
            this.process.css({
                width:      0,
                height:     0,
                background: 'none'
            });
        }

        if (this.options.process_txt && !overlay.find('._ajax_loader_process_txt').length)
        {
            $(
                '<div class="_ajax_loader_process_txt">'
                + this.options.process_txt
                + '</div>'
            )
                .insertAfter(this.process);
        }

        this.setProcessPosition();

    };

    this.remove = function ()
    {

        var overlay = $('body').find('#ajax-overlay-' + this.container.index());

        if (overlay.length)
        {
            overlay.fadeOut(this.options.classOveride, function ()
            {
                overlay.remove();
            });
        }
    };

    this.init();
}

function smoothTo(el, speed)
{
    var target = $.isPlainObject(el) ? el : $(el);
    if (target.length)
    {
        $('html, body').animate({
            scrollTop: target.offset().top
        }, speed ? speed : 1000);
        return false;
    }
}

$.pageUp = function (options, el, clickCallback)
{
    var $t = this;

    $t.body = $('body');
    $t.el = el ? ($.isPlainObject(el) ? el : $(el)) : $t.body;

    if (!el.length) return false;

    $t.options = $.extend({
            id:            "page-up",
            classOverride: false,
            start_from:    200,
            arrow_class:   'fa fa-chevron-up',
            arrow_bg:      null,
            float:         "left",
            scrollSpeed:   400,
            fadeInSpeed:   0,
            fadeOutSpeed:  200
        },
        $.isPlainObject(options) ? options : {}
    );

    $t.id = $t.options.id;
    $t.item = "#" + $t.id;
    $t.pos = $t.el.offset().top;
    $t.float = $.inArray($t.options.float, ["left", "right"]) >= 0 ? $t.options.float : "left";
    $t.outFlow = false;
    $t.clickCallback = typeof(clickCallback) !== 'undefined' ? clickCallback : null;
    $t.up = $t.body.find($t.item).length ? $t.body.children($t.item) : null;
    $t.scrollOffsetTop = 0;

    $(document).on('scroll', function ()
    {

        $t.scrollOffsetTop = $(window).scrollTop();

        $t.outFlow = $t.scrollOffsetTop > $t.pos + $t.options.start_from;

        if (!$t.up)
        {
            if (!$t.options.classOverride)
            {
                var opt = {};

                opt[$t.item] = [
                    'position: fixed',
                    $t.float + ': 35px',
                    'bottom: 80px',
                    '-moz-border-radius: 50%',
                    '-webkit-border-radius: 50%',
                    'border-radius: 50%',
                    '-webkit-transition: all 0.3s',
                    '-o-transition: all 0.3s',
                    '-moz-transition: all 0.3s',
                    'transition: all 0.3s',
                    'box-shadow: 1px 0 2px #888',
                    'border: 2px solid #fff',
                    'z-index: 9999',
                    'background: rgba(28, 28, 28, 0.68)',
                    'cursor: pointer',
                    'font-size: 30pt',
                    'text-align: center',
                    'display: none'
                ];

                opt[$t.item + ':hover'] = [
                    'background: #1c1c1c'
                ];

                opt[$t.item + ">i"] = [
                    'display: block',
                    'color: #fff',
                    'width: 65px',
                    'height: 65px',
                    'padding-top: 10%'
                ];

                opt['@media screen and (max-width: 1430px) {' + $t.item] = [
                    $t.float + ':35px',
                    'bottom:35px',
                    'font-size: 25pt',
                    'background: rgba(28, 28, 28, 0.37)'
                ];

                opt['+' + $t.item + ">i"] = [
                    'width: 50px',
                    'height: 50px',
                    '}'
                ];

                if ($t.options.arrow_bg)
                {
                    opt[$t.item + ">i"].push(['background: url(' + $t.options.arrow_bg + ') center no-repeat']);
                    $t.options.arrow_class = '';
                }

                $.insertStyle('$.pageUp', opt, false);
            }

            $t.up = $('<div id="' + $t.id + '"></div>').appendTo($t.body);

            $t.up.html('<i class="' + $t.options.arrow_class + '"></i>');
        }

        $t.outFlow
            ? ($t.options.fadeInSpeed ? $t.up.fadeIn($t.options.fadeInSpeed) : $t.up.show())
            : ($t.options.fadeOutSpeed ? $t.up.fadeOut($t.options.fadeOutSpeed) : $t.up.hide())
        ;

    });

    $(document).on('click', $t.item, function ()
    {

        if (!$t.up || ($t.clickCallback && $t.clickCallback($t.scrollOffsetTop, $t.up, $t.outFlow)))
        {
            return false;
        }

        if ($t.outFlow)
        {
            $('html, body').animate({scrollTop: $t.pos}, $t.options.scrollSpeed);
        }
    });
};

function parse_int(str, lower, upper)
{
    var result = parseInt(parse_number(str));

    if (isNaN(result))
    {
        result = 0;
    }

    if (lower !== undefined && result < lower)
    {
        result = lower;
    }

    if (upper !== undefined && result > upper)
    {
        result = upper;
    }

    return result;
}

function parse_float(str, lower, upper)
{
    var result = parseFloat(parse_number(str));

    if (isNaN(result))
    {
        result = 0;
    }

    if (lower !== undefined && result < lower)
    {
        result = lower;
    }

    if (upper !== undefined && result > upper)
    {
        result = upper;
    }

    return result;
}

function parse_number(str)
{
    var result = '';

    for (var i = 0; i < str.length; i++)
    {
        if ('0' <= str[i] && str[i] <= '9' || str[i] == '-')
        {
            result += str[i];
        }
        else if (str[i] == '.' || str[i] == ',')
        {
            result += '.';
        }
    }

    return result;
}

function number_format(number, decimals, dec_point, thousands_sep)
{  // Format a number with grouped thousands
    //
    // +   original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     bugfix by: Michael White (http://crestidg.com)

    var i, j, kw, kd, km;

    // input sanitation & defaults
    if (isNaN(decimals = Math.abs(decimals)))
    {
        decimals = 2;
    }
    if (dec_point == undefined)
    {
        dec_point = ",";
    }
    if (thousands_sep == undefined)
    {
        thousands_sep = ".";
    }

    i = parseInt(number = (+number || 0).toFixed(decimals)) + "";

    if ((j = i.length) > 3)
    {
        j = j % 3;
    }
    else
    {
        j = 0;
    }

    km = (j ? i.substr(0, j) + thousands_sep : "");
    kw = i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep);
    //kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).slice(2) : "");
    kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).replace(/-/, 0).slice(2) : "");

    return km + kw + kd;
}

function ArrayToURL(array)
{
    var pairs = [];

    for (var key in array)
    {
        if (array.hasOwnProperty(key))
        {
            if (array[key] instanceof Array)
            {
                for (var i = 0; i < array[key].length; i++)
                {
                    pairs.push(encodeURIComponent(key + '[]') + '=' + encodeURIComponent(array[key][i]));
                }
            }
            else
            {
                pairs.push(encodeURIComponent(key) + '=' + encodeURIComponent(array[key]));
            }
        }
    }

    return pairs.join('&');
}

function URLToArray(url)
{
    var request = {};
    var pairs = url.substring(url.indexOf('?') + 1).split('&');
    var pair;

    for (var i = 0; i < pairs.length; i++)
    {
        if (!pairs[i])
        {
            continue;
        }
        pair = pairs[i].split('=');

        pair[0] = decodeURIComponent(pair[0]);
        pair[1] = decodeURIComponent(pair[1]);

        if (endsWith(pair[0], '[]'))
        {
            pair[0] = pair[0].substr(0, pair[0].length - 2);
            if (!(pair[0] in request))
            {
                request[pair[0]] = [];
            }
            request[pair[0]].push(pair[1]);
        }
        else
        {
            request[pair[0]] = pair[1];
        }
    }

    return request;
}

function startsWith(str, prefix)
{
    return str.indexOf(prefix) == 0;
}

function endsWith(str, suffix)
{
    return str.indexOf(suffix, str.length - suffix.length) !== -1;
}

function toggleForm(el, toggle)
{
    if (toggle === true)
    {
        el.toggle();
    }

    el.find('input,select,textarea').each(function ()
    {
        if ($(this)[0].hasAttribute('disabled'))
        {
            $(this).removeAttr('disabled');
        }
        else
        {
            $(this).attr('disabled', 'disabled');
        }
    })
}

function md5(str)
{
    (function ($)
    {
        'use strict';

        /*
         * Add integers, wrapping at 2^32. This uses 16-bit operations internally
         * to work around bugs in some JS interpreters.
         */
        function safe_add(x, y)
        {
            var lsw = (x & 0xFFFF) + (y & 0xFFFF),
                msw = (x >> 16) + (y >> 16) + (lsw >> 16);
            return (msw << 16) | (lsw & 0xFFFF);
        }

        /*
         * Bitwise rotate a 32-bit number to the left.
         */
        function bit_rol(num, cnt)
        {
            return (num << cnt) | (num >>> (32 - cnt));
        }

        /*
         * These functions implement the four basic operations the algorithm uses.
         */
        function md5_cmn(q, a, b, x, s, t)
        {
            return safe_add(bit_rol(safe_add(safe_add(a, q), safe_add(x, t)), s), b);
        }

        function md5_ff(a, b, c, d, x, s, t)
        {
            return md5_cmn((b & c) | ((~b) & d), a, b, x, s, t);
        }

        function md5_gg(a, b, c, d, x, s, t)
        {
            return md5_cmn((b & d) | (c & (~d)), a, b, x, s, t);
        }

        function md5_hh(a, b, c, d, x, s, t)
        {
            return md5_cmn(b ^ c ^ d, a, b, x, s, t);
        }

        function md5_ii(a, b, c, d, x, s, t)
        {
            return md5_cmn(c ^ (b | (~d)), a, b, x, s, t);
        }

        /*
         * Calculate the MD5 of an array of little-endian words, and a bit length.
         */
        function binl_md5(x, len)
        {
            /* append padding */
            x[len >> 5] |= 0x80 << ((len) % 32);
            x[(((len + 64) >>> 9) << 4) + 14] = len;

            var i, olda, oldb, oldc, oldd,
                a = 1732584193,
                b = -271733879,
                c = -1732584194,
                d = 271733878;

            for (i = 0; i < x.length; i += 16)
            {
                olda = a;
                oldb = b;
                oldc = c;
                oldd = d;

                a = md5_ff(a, b, c, d, x[i], 7, -680876936);
                d = md5_ff(d, a, b, c, x[i + 1], 12, -389564586);
                c = md5_ff(c, d, a, b, x[i + 2], 17, 606105819);
                b = md5_ff(b, c, d, a, x[i + 3], 22, -1044525330);
                a = md5_ff(a, b, c, d, x[i + 4], 7, -176418897);
                d = md5_ff(d, a, b, c, x[i + 5], 12, 1200080426);
                c = md5_ff(c, d, a, b, x[i + 6], 17, -1473231341);
                b = md5_ff(b, c, d, a, x[i + 7], 22, -45705983);
                a = md5_ff(a, b, c, d, x[i + 8], 7, 1770035416);
                d = md5_ff(d, a, b, c, x[i + 9], 12, -1958414417);
                c = md5_ff(c, d, a, b, x[i + 10], 17, -42063);
                b = md5_ff(b, c, d, a, x[i + 11], 22, -1990404162);
                a = md5_ff(a, b, c, d, x[i + 12], 7, 1804603682);
                d = md5_ff(d, a, b, c, x[i + 13], 12, -40341101);
                c = md5_ff(c, d, a, b, x[i + 14], 17, -1502002290);
                b = md5_ff(b, c, d, a, x[i + 15], 22, 1236535329);

                a = md5_gg(a, b, c, d, x[i + 1], 5, -165796510);
                d = md5_gg(d, a, b, c, x[i + 6], 9, -1069501632);
                c = md5_gg(c, d, a, b, x[i + 11], 14, 643717713);
                b = md5_gg(b, c, d, a, x[i], 20, -373897302);
                a = md5_gg(a, b, c, d, x[i + 5], 5, -701558691);
                d = md5_gg(d, a, b, c, x[i + 10], 9, 38016083);
                c = md5_gg(c, d, a, b, x[i + 15], 14, -660478335);
                b = md5_gg(b, c, d, a, x[i + 4], 20, -405537848);
                a = md5_gg(a, b, c, d, x[i + 9], 5, 568446438);
                d = md5_gg(d, a, b, c, x[i + 14], 9, -1019803690);
                c = md5_gg(c, d, a, b, x[i + 3], 14, -187363961);
                b = md5_gg(b, c, d, a, x[i + 8], 20, 1163531501);
                a = md5_gg(a, b, c, d, x[i + 13], 5, -1444681467);
                d = md5_gg(d, a, b, c, x[i + 2], 9, -51403784);
                c = md5_gg(c, d, a, b, x[i + 7], 14, 1735328473);
                b = md5_gg(b, c, d, a, x[i + 12], 20, -1926607734);

                a = md5_hh(a, b, c, d, x[i + 5], 4, -378558);
                d = md5_hh(d, a, b, c, x[i + 8], 11, -2022574463);
                c = md5_hh(c, d, a, b, x[i + 11], 16, 1839030562);
                b = md5_hh(b, c, d, a, x[i + 14], 23, -35309556);
                a = md5_hh(a, b, c, d, x[i + 1], 4, -1530992060);
                d = md5_hh(d, a, b, c, x[i + 4], 11, 1272893353);
                c = md5_hh(c, d, a, b, x[i + 7], 16, -155497632);
                b = md5_hh(b, c, d, a, x[i + 10], 23, -1094730640);
                a = md5_hh(a, b, c, d, x[i + 13], 4, 681279174);
                d = md5_hh(d, a, b, c, x[i], 11, -358537222);
                c = md5_hh(c, d, a, b, x[i + 3], 16, -722521979);
                b = md5_hh(b, c, d, a, x[i + 6], 23, 76029189);
                a = md5_hh(a, b, c, d, x[i + 9], 4, -640364487);
                d = md5_hh(d, a, b, c, x[i + 12], 11, -421815835);
                c = md5_hh(c, d, a, b, x[i + 15], 16, 530742520);
                b = md5_hh(b, c, d, a, x[i + 2], 23, -995338651);

                a = md5_ii(a, b, c, d, x[i], 6, -198630844);
                d = md5_ii(d, a, b, c, x[i + 7], 10, 1126891415);
                c = md5_ii(c, d, a, b, x[i + 14], 15, -1416354905);
                b = md5_ii(b, c, d, a, x[i + 5], 21, -57434055);
                a = md5_ii(a, b, c, d, x[i + 12], 6, 1700485571);
                d = md5_ii(d, a, b, c, x[i + 3], 10, -1894986606);
                c = md5_ii(c, d, a, b, x[i + 10], 15, -1051523);
                b = md5_ii(b, c, d, a, x[i + 1], 21, -2054922799);
                a = md5_ii(a, b, c, d, x[i + 8], 6, 1873313359);
                d = md5_ii(d, a, b, c, x[i + 15], 10, -30611744);
                c = md5_ii(c, d, a, b, x[i + 6], 15, -1560198380);
                b = md5_ii(b, c, d, a, x[i + 13], 21, 1309151649);
                a = md5_ii(a, b, c, d, x[i + 4], 6, -145523070);
                d = md5_ii(d, a, b, c, x[i + 11], 10, -1120210379);
                c = md5_ii(c, d, a, b, x[i + 2], 15, 718787259);
                b = md5_ii(b, c, d, a, x[i + 9], 21, -343485551);

                a = safe_add(a, olda);
                b = safe_add(b, oldb);
                c = safe_add(c, oldc);
                d = safe_add(d, oldd);
            }
            return [a, b, c, d];
        }

        /*
         * Convert an array of little-endian words to a string
         */
        function binl2rstr(input)
        {
            var i,
                output = '';
            for (i = 0; i < input.length * 32; i += 8)
            {
                output += String.fromCharCode((input[i >> 5] >>> (i % 32)) & 0xFF);
            }
            return output;
        }

        /*
         * Convert a raw string to an array of little-endian words
         * Characters >255 have their high-byte silently ignored.
         */
        function rstr2binl(input)
        {
            var i,
                output = [];
            output[(input.length >> 2) - 1] = undefined;
            for (i = 0; i < output.length; i += 1)
            {
                output[i] = 0;
            }
            for (i = 0; i < input.length * 8; i += 8)
            {
                output[i >> 5] |= (input.charCodeAt(i / 8) & 0xFF) << (i % 32);
            }
            return output;
        }

        /*
         * Calculate the MD5 of a raw string
         */
        function rstr_md5(s)
        {
            return binl2rstr(binl_md5(rstr2binl(s), s.length * 8));
        }

        /*
         * Calculate the HMAC-MD5, of a key and some data (raw strings)
         */
        function rstr_hmac_md5(key, data)
        {
            var i,
                bkey = rstr2binl(key),
                ipad = [],
                opad = [],
                hash;
            ipad[15] = opad[15] = undefined;
            if (bkey.length > 16)
            {
                bkey = binl_md5(bkey, key.length * 8);
            }
            for (i = 0; i < 16; i += 1)
            {
                ipad[i] = bkey[i] ^ 0x36363636;
                opad[i] = bkey[i] ^ 0x5C5C5C5C;
            }
            hash = binl_md5(ipad.concat(rstr2binl(data)), 512 + data.length * 8);
            return binl2rstr(binl_md5(opad.concat(hash), 512 + 128));
        }

        /*
         * Convert a raw string to a hex string
         */
        function rstr2hex(input)
        {
            var hex_tab = '0123456789abcdef',
                output  = '',
                x,
                i;
            for (i = 0; i < input.length; i += 1)
            {
                x = input.charCodeAt(i);
                output += hex_tab.charAt((x >>> 4) & 0x0F) +
                    hex_tab.charAt(x & 0x0F);
            }
            return output;
        }

        /*
         * Encode a string as utf-8
         */
        function str2rstr_utf8(input)
        {
            return unescape(encodeURIComponent(input));
        }

        /*
         * Take string arguments and return either raw or hex encoded strings
         */
        function raw_md5(s)
        {
            return rstr_md5(str2rstr_utf8(s));
        }

        function hex_md5(s)
        {
            return rstr2hex(raw_md5(s));
        }

        function raw_hmac_md5(k, d)
        {
            return rstr_hmac_md5(str2rstr_utf8(k), str2rstr_utf8(d));
        }

        function hex_hmac_md5(k, d)
        {
            return rstr2hex(raw_hmac_md5(k, d));
        }

        $.md5 = function (string, key, raw)
        {
            if (!key)
            {
                if (!raw)
                {
                    return hex_md5(string);
                }
                else
                {
                    return raw_md5(string);
                }
            }
            if (!raw)
            {
                return hex_hmac_md5(key, string);
            }
            else
            {
                return raw_hmac_md5(key, string);
            }
        };

    }(typeof jQuery === 'function' ? jQuery : this));

    return $.md5(str);

}

/**********************************************************************************************
 * Extract parameters from url string
 * */
$.urlParam = function (name)
{
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    return results[1] || 0;
};

/**********************************************************************************************
 * @url: http://stackoverflow.com/questions/8897289/how-to-check-if-an-element-is-off-screen
 * */
$.expr.pseudos.offscreen = function (el)
{
    var rect = el.getBoundingClientRect();
    return (
        (rect.x + rect.width) < 0
        || (rect.y + rect.height) < 0
        || (rect.x > window.innerWidth || rect.y > window.innerHeight)
    );
};

$.isOutflow = function (el, container, less, more)
{
    var offset1 = $(el).offset();
    var offset2 = $(container).offset();

    if (less)
    {
        return (
            offset1.top < offset2.top + $(container).scrollTop()
            || offset1.left < offset2.left
        );
    }

    if (more)
    {
        return (
            offset1.top > (offset2.top + $(container).height()) + $(container).scrollTop()
            || offset1.left > (offset2.left + $(container).width()) + $(container).scrollLeft()
        );
    }

    return (
        offset1.top > (offset2.top + $(container).height()) + $(container).scrollTop()
        || offset1.left > (offset2.left + $(container).width()) + $(container).scrollLeft()
        || offset1.top < offset2.top + $(container).scrollTop()
        || offset1.left < offset2.left
    );
};

/*******************************************************************************
 * Insert text into textarea at cursor position (Javascript)
 * */
$.insertAtCaret = function (element, val)
{
    var domElement = element[0];
    val = val.toString();

    if (document.selection)
    {
        domElement.focus();
        var sel = document.selection.createRange();
        sel.text = val;
        domElement.focus();
    }
    else if (domElement.selectionStart || domElement.selectionStart === 0)
    {
        var startPos = domElement.selectionStart;
        var endPos = domElement.selectionEnd;
        var scrollTop = domElement.scrollTop;
        domElement.value = domElement.value.substring(0, startPos)
            + val + domElement.value.substring(endPos, domElement.value.length);
        domElement.scrollTop = scrollTop;

        if (domElement.createTextRange)
        {
            var range = domElement.createTextRange();
            range.collapse(true);
            range.moveEnd('character', endPos);
            range.moveStart('character', endPos);
            range.select();
        }
        else if (domElement.setSelectionRange)
        {
            domElement.focus();
            domElement.setSelectionRange(endPos + val.length, endPos + val.length);
        }
    }
    else
    {
        domElement.value += val;
        domElement.focus();
    }
};

/*****************************************************
 **** Alert Sound
 * http://ionden.com/a/plugins/ion.sound/demo_advanced.html
 *
 * beer_can_opening
 * bell_ring
 * branch_break
 * button_click
 * button_click_on
 * button_push
 * button_tiny
 * camera_flashing
 * camera_flashing_2
 * cd_tray
 * computer_error
 * door_bell
 * door_bump
 * glass
 * keyboard_desk
 * light_bulb_breaking
 * metal_plate
 * metal_plate_2
 * pop_cork
 * snap
 * staple_gun
 * tap
 * water_droplet
 * water_droplet_2
 * water_droplet_3
 * * */

function play_sound(name, loop, volume)
{
    var lp = loop ? loop : 1;
    var vl = volume ? volume : 0.5;
    name = $.trim(name, "/");
    var path = name.split('/');

    if (path.length > 1)
    {
        name = path[path.length - 1];
        path.splice(-1, 1);
        path = path.join('/');
    }
    else
    {
        path = '';
    }

    ion.sound({
        sounds: [
            {
                name:      name,
                preload:   true,
                multiplay: true
            }
        ],
        path:   "/admin/assets/js/ion/sounds/" + path + "/",
        volume: vl
    });

    ion.sound.play(name, {
        loop: lp
    });

    // Set master volume
    // ion.sound.volume({volume: vl});
}

/*********************************************************************************
 * New Lines to <br>
 * */
function nl2br(str, is_xhtml)
{
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

/*********************************************************************************
 * Get IP + geoLocation
 * */
function LookupGeoIP()
{
    var $this = this;

    $this.getData = function (data, fn)
    {
        $.ajax({
            url:      "//freegeoip.net/json/",
            dataType: "JSON",
            type:     "GET",
            data:     typeof data === 'object' ? data : {},
            success:  function (response)
                      {
                          fn(response);
                      }
        });
        return $this;
    };

    $this.getCookieData = function (cookieName, expires)
    {
        $this.getData(null, function (dat)
        {
            if (!Cookies.get(cookieName))
            {
                Cookies.set(cookieName, JSON.stringify(dat), {expires: expires});
            }
        });
        return Cookies.get(cookieName);
    };
}

/************************************************************************************
 * Toggle checkbox Value
 * */
$(document).on('click', '.js-toggle-checkbox', function ()
{
    var checked = $(this).prop('checked') ? 1 : 0;

    if ($(this).next('input[name="' + $(this).attr('name') + '"]').length)
    {
        $(this).next().val(checked);

        return true;
    }

    $('<input ' +
        'type="hidden" ' +
        'name="' + $(this).attr('name') + '" ' +
        'value="' + checked + '">'
    )
        .insertAfter($(this));
});

/**************************************************************************************
 * Input fill selection
 * @param string data-url - route get
 * @param string data-loop - select item value template, example: '%v% <b>%k%</b>'
 * @param string data-target-name - hidden input name inserted after source input
 * @param bool data-submit - submit input closest form on select drop item
 * @param bool data-insert - source input show value on drop item selection
 * */

$(document).on('keydown', 'input.js-input-select', function (event)
{
    var $t = $(this);
    var tgn = $t.data('target-name');
    var val = $t.val();
    var select_id = 'input-select-' + $t.index();
    var select_ul = 'ul#' + select_id;
    var keyCode = event.keyCode;
    var dataInsert = typeof $t.data('insert') !== 'undefined' ? $t.data('insert') : null;
    var loop = typeof $t.data('loop') !== 'undefined' ? $t.data('loop') : null;

    $t.loop = function (str, k, v)
    {
        return str.replace('%k%', k).replace('%v%', v)
    };

    $t.attr('autocomplete', 'off');

    if (keyCode === 40 || keyCode === 38)
    {
        var hover = $(select_ul).find('li.js-hover:not(.js-input-select-items-close)').length
            ? $(select_ul).find('li.js-hover:not(.js-input-select-items-close):first')
            : $(select_ul).find('li:not(.js-input-select-items-close):first');

        if (!((keyCode === 40 ? hover.next('li') : hover.prev('li')).length))
        {
            return false;
        }

        if (hover.hasClass('js-hover'))
        {
            (keyCode === 40 ? hover.next() : hover.prev()).addClass('js-hover');
        }

        var height = hover.outerHeight();

        if (hover.position().top > ($(select_ul).outerHeight() - height)
            || hover.position().top < ($(select_ul).scrollTop() + height)
        )
        {
            $(select_ul).animate(
                {
                    scrollTop: $(select_ul).scrollTop()
                               + hover.position().top
                               + (keyCode === 40 ? -height : -(height + ($(select_ul).height() / 2)))
                },
                {duration: 20, easing: 'swing'}
            );
        }

        hover.toggleClass('js-hover');

        return false;
    }

    if (keyCode === 13)
    {
        $(select_ul + ' li.js-hover').trigger('click');
        return false;
    }

    function inputSelectSetPosition(el, rel)
    {
        el.css({
            top:   rel.offset().top + rel.outerHeight() + 'px',
            left:  rel.offset().left + 'px',
            width: rel.outerWidth() + 'px'
        })
    }

    var targetName = typeof(tgn) !== 'undefined' ? tgn : $(this).attr('name');

    var target = $t.next('input[name="' + targetName + '"]').length
        ? $t.next('input[name="' + targetName + '"]')
        : '<input type="hidden" name="' + targetName + '">'
    ;

    if ($.isPlainObject(target) === false)
    {
        $(target).insertAfter($t);
        target = $t.next('input[name="' + targetName + '"]');
    }

    target.attr('disabled', 'disabled');

    if ($.trim(val) === '')
    {
        target.val('');
    }
    else if ((val.length % 2) === 0)
    {
        $.get($t.data('url'), {name: val}, function (data)
        {
            if (!data)
            {
                return;
            }

            if (!$(select_ul).length)
            {
                if (!$(document).find('style[data-stylesheet="js-input-select"]').length)
                {
                    var css =
                            'ul.js-input-select-items {'
                            + 'z-index: 9999;'
                            + 'background: #fff;'
                            + 'box-shadow: 0 6px 12px rgba(0, 0, 0, .175);'
                            + 'position: absolute;'
                            + 'display: none;'
                            + 'border: #ddd 1px solid;'
                            + 'left: 0;'
                            + 'top: 0;'
                            + 'list-style-type: none;'
                            + 'padding: 0;'
                            + 'margin: -1px 0 0;'
                            + 'max-height: 250px;'
                            + 'overflow-y: auto;'
                            + '}'
                            + 'ul.js-input-select-items>li.js-input-select-items-close {'
                            + 'background-color: cadetblue;'
                            + 'color: #fff;'
                            + '}'
                            + 'ul.js-input-select-items>li{'
                            + 'padding: 5px 10px;'
                            + 'border-bottom: #ddd 1px solid;'
                            + 'cursor: pointer;'
                            + 'color: #007373'
                            + '}'
                            + 'ul.js-input-select-items>li.js-hover,'
                            + 'ul.js-input-select-items>li:not(.js-input-select-items-close):hover{'
                            + 'color: #fff;'
                            + 'background: cadetblue;'
                            + '}'
                            + 'ul.js-input-select-items>li:last-child{'
                            + 'border-bottom: none'
                            + '}'
                    ;

                    $('<style type="text/css" data-stylesheet="js-input-select">' + css + '</style>')
                        .appendTo(document.head);
                }

                $('<ul id="' + select_id + '" class="js-input-select-items">'
                    + '<li class="js-input-select-items-close" '
                    + 'onclick="$(this).parent(\'ul\').hide()">X</li></ul>'
                ).appendTo($('body'));

                $(select_ul).css({width: '100%'});
            }

            $(select_ul).find('li:first').nextAll('li').remove();

            $.each(data, function (i, v)
            {
                if (loop)
                {
                    v = $t.loop(loop, i, v);
                }
                $(select_ul).append('<li data-id="' + i + '">' + v + '</li>')
            });

            inputSelectSetPosition($(select_ul), $t);

            $(select_ul).show();
        });

        $(document).on('scroll', function ()
        {
            inputSelectSetPosition($(select_ul), $t);
        });

        $(window).on('resize', function ()
        {
            inputSelectSetPosition($(select_ul), $t);
        });

        $(document).on('click', select_ul + ' li:not(.js-input-select-items-close)', function ()
        {

            target.removeAttr('disabled').val($(this).data('id'));

            var insert = dataInsert
                ? dataInsert.replace('%k%', $(this).data('id')).replace('%v%', $(this).text())
                : $(this).text();

            $t.val(insert);

            $(select_ul + ' .js-input-select-items-close').trigger('click');

            if (typeof($t.data('submit')) !== 'undefined'
                && $t.data('submit')
                && $t.closest('form').length)
            {
                $t.closest('form').submit();
            }
        });
    }
});

/*************************************************************************
 * Toggle input disabled
 * */
(function ($)
{
    $.fn.toggleDisabled = function (toggle)
    {
        return this.each(function ()
        {
            this.disabled = typeof toggle === "boolean" ? toggle : !this.disabled;
        });
    };
})(jQuery);

function toggleTableCellInputs(event, el, toggle)
{
    event.preventDefault();

    var tr = el.closest('tr');
    tr.find('td').not(el.closest('td')).each(function ()
    {
        tgl = typeof toggle === "boolean"
            ? toggle : !($(this).find('input,select,textarea').is(':disabled'));
        $(this).find('div').toggleClass('hidden', tgl);
        $(this).find('input,select,textarea').toggleDisabled(tgl);
        $(this).find('span').toggle(tgl);
    })
}

/******************************************************************************
 * Modals
 * CSS Bootstrap Modal Handler
 * Call Ex:
 * <button
 *   type="button"
 *   class="btn btn-orange"
 *   onclick="return false"
 *   data-href="/some-url"
 *   data-modal="js-modal footer-controls"
 *   data-modal-id="modal-md"
 *   data-modal-title="Модальное окно"
 *   data-modal-submit="Сохранить"
 *   data-fn="js-tiny-mce">Кнопка</button>
 * */

function load_modal(el, modal_id, event)
{
    event.preventDefault();

    event.stopPropagation();

    var t = el ? el : null;
    var mid = t ? $('#' + t.attr('data-modal-id')) : $('#' + modal_id);
    var data = t ? t.attr('data-modal') : '';
    var fn = t ? t.attr('data-fn') : '';
    var submitBtn = t ? t.attr('data-modal-submit') : '';

    if (data.match(/footer-controls/))
    {
        if (!mid.find('.modal-footer').length)
        {
            $('<div class="modal-footer">'
                + '<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button> '
                + '<button type="button" class="btn btn-info js-modal-submit">' + (submitBtn ? submitBtn
                    : 'Сохранить') + '</button></div>'
            ).insertAfter(mid.find('.modal-body'));
        }
    }
    else
    {
        mid.find('.modal-footer').remove();
    }

    mid.attr('data-backdrop', 'static');

    if (t ? t.data('modal-title') : null)
    {
        mid.find('.modal-title').remove();

        mid.find('.modal-header').append('<h3 class="modal-title">' + t.data('modal-title') + '</h3>');
    }
    else
    {
        mid.find('.modal-header').find('.modal-title').remove()
    }
    if (mid.find('.modal-header .modal-tabs').length)
    {
        mid.find('.modal-header .modal-tabs').empty();
    }

    if (t)
    {
        var href = t.data('href') ? t.data('href')
            : (t.attr('href') ? t.attr('href')
                : (t.find('[href]') ? t.find('[href]').first().attr('href')
                    : t.find('[data-href]').first().attr('data-href')));
    }

    if (typeof href !== 'undefined')
    {
        mid.find('.modal-body').empty();

        $.get(href, function (data)
        {
            if (data.html)
            {
                mid.find('.modal-body').html(data.html);
            }
            else
            {
                mid.find('.modal-body').html(data);
            }
            if (data.title)
            {
                mid.find('.modal-header .modal-title').remove();
                mid.find('.modal-header').append('<h3 class="modal-title">' + data.title + '</h3>');
            }
        });
    }

    mid.modal('show');

    if (fn && typeof fn !== 'undefined')
    {
        var interval = setInterval(function ()
        {
            if (mid.find('.modal-body').is(':hidden') === false)
            {
                setTimeout(function ()
                {
                    if (mid.find('.modal-body').is(':empty') === false)
                    {
                        runFunctionByName(fn);

                        clearInterval(interval);
                    }
                }, 200)
            }

        }, 10);
    }

    return false;
}

function link_modal(el, event)
{
    event.preventDefault();

    var mb = el.closest('.modal-body');
    var fn = el.data('fn');
    var debug = el.data('debug');

    $.get(el.attr('href') ? el.attr('href') : el.attr('data-href'), function (data)
    {
        if (data.reload)
        {
            $(document).location.reload();
            return false;
        }
        if (data.html)
        {
            mb.html(data.html);
        }
        if (typeof fn !== 'undefined')
        {
            runFunctionByName(fn)
        }
        if ('success' in data && data.success === true)
        {
            alert('Изменения сохранены!');
        }
        else if ('errors' in data && !$.isEmptyObject(data.errors))
        {
            arr = $.map(data.errors, function (a)
            {
                return a;
            });
            alert(arr.join(";\n"));
        }
        if (typeof debug !== 'undefined')
        {
            mb.prepend(data + '<br><br>');
        }
    });
}

function submit_modal(el, event)
{
    event.preventDefault();

    var contents = el ? el.parents('div.modal-content') : $('.modal div.modal-content');
    var form = contents.find('#ajax-form:visible').length
        ? contents.find('#ajax-form:visible')
        : contents.find('.modal-body').find('form:visible:first');
    var fn = form.data('fn');
    var container = form.data('response-container')
        ? $(form.data('response-container'))
        : contents.find('.modal-body');

    var box = new ajaxLoader(contents, {duration: 100, margin: ''});

    if (!form.length)
    {
        box.remove();

        return false;
    }

    var formData = new FormData(form[0]);

    $.ajax({
        type:        "POST",
        dataType:    "JSON",
        processData: false,
        contentType: false,
        url:         form.attr('action'),
        data:        formData,
        complete:    function ()
                     {
                         box.remove();
                     },
        error:       function (data)
                     {
                         alert('Данное действие отклонено.');
                     },
        success:     function (data)
                     {
                         if ('html' in data)
                         {
                             (data.container ? contents.find(data.container) : container)
                                 .html(data.html);
                         }
                         if (typeof fn !== 'undefined')
                         {
                             runFunctionByName(fn)
                         }
                         if ('success' in data && data.success)
                         {
                             if (data.success === true)
                             {
                                 if (confirm('Изменения сохранены. Перезагрузить страницу?'))
                                 {
                                     data['reload'] = true;
                                 }
                             }
                             else if (typeof data.success === 'string')
                             {
                                 contents.find('.modal-body').prepend('<div class="alert alert-success" role="alert">'
                                     + data.success
                                     + '</div>'
                                 );
                             }
                             setTimeout(function ()
                             {
                                 $('.modal').modal('hide')
                             }, 5000);
                         }
                         else if ('errors' in data && !$.isEmptyObject(data.errors))
                         {
                             var arr = $.map(data.errors, function (a)
                             {
                                 return a;
                             });
                             alert(arr.join(";\n"))
                         }
                         if ('reload' in data)
                         {
                             $('#' + contents.parents('.modal').attr('id')).modal('hide');
                             location.reload();
                         }
                     }
    });
}

function scrollModal(elm)
{
    var h  = $(window).height() - 120,
        hh = (Math.abs(elm.find('.modal-header').height() || 0)
            + (Math.abs(elm.find('.modal-footer').height()) || 0))
            * 2;

    elm.find('.modal-content').css('height', h + 'px');
    elm.find('.modal-body').css({
        "height":     h - hh + "px",
        "overflow-x": "auto",
        "overflow-y": "auto"
    });
}

/*************************************************************
 * Jquery-ui date picker
 * */

function appendDatePicker(sel)
{
    var selector = sel ? sel : '.js-date-picker';
    $.datepicker.regional['ru'] = {
        closeText:          "Закрыть",
        prevText:           "&#x3C;Пред",
        nextText:           "След&#x3E;",
        currentText:        "Сегодня",
        monthNames:         [
            "Январь", "Февраль", "Март", "Апрель", "Май", "июнь",
            "июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"
        ],
        monthNamesShort:    [
            "Янв", "Фев", "Мар", "Апр", "Май", "июн",
            "июл", "Авг", "Сен", "Окт", "Ноя", "Дек"
        ],
        dayNames:           ["воскресенье", "понедельник", "вторник", "среда", "четверг", "пятница", "суббота"],
        dayNamesShort:      ["вск", "пнд", "втр", "срд", "чтв", "птн", "сбт"],
        dayNamesMin:        ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
        weekHeader:         "Нед",
        dateFormat:         "dd.mm.yy",
        firstDay:           1,
        isRTL:              false,
        showMonthAfterYear: false,
        yearSuffix:         ""
    };
    $.datepicker.setDefaults($.datepicker.regional['ru']);
    $(selector).datepicker('option', 'dateFormat', 'yy-mm-dd');
    $(selector).datepicker();
}

function appendTimePicker(sel, data)
{
    var selector = sel ? sel : '.js-time-picker';

    try
    {
        jQuery.datetimepicker.setLocale('ru');

        var param = {
            format:   'd.m.Y H:i',
            language: 'ru'
        };

        $(selector).datetimepicker($.isPlainObject(data) ? data : param);
    }
    catch (exception)
    {
    }

}

/*************************************************************
 * Text Editor
 * @param data-link-host show host url at links
 * @param data-readonly remove menubar, toolbar
 * */

function appendTinyMCE(sel, readOnly)
{
    var el = sel ? sel : $('textarea.js-tiny-mce');

    if (typeof tinymce !== "object")
    {
        return false;
    }

    if ($.blockUI)
    {
        $.unblockUI();
    }

    while (tinymce.editors.length > 0)
    {
        tinymce.execCommand('mceRemoveEditor', true, tinymce.editors[0].id);
    }

    el.each(function ()
    {
        var read = typeof el.data('readonly') !== 'undefined' ? el.data('readonly') : (readOnly ? 1 : 0);
        var link_host = typeof el.data('link-host') !== 'undefined'
            ? (el.data('link_host') === '0')
            : true;

        if ($(this).is(':disabled') || $(this).is(':hidden'))
        {
            return true;
        }

        var id = $(this).attr('id');
        var height = $(this).data('height') ? $(this).data('height') : $(this).height();

        //delete tinymce.EditorManager.editors[id];

        tinymce.init({
            theme:                     "modern",
            setup:                     function (editor)
                                       {
                                           editor.on('change', function ()
                                           {
                                               tinymce.triggerSave();
                                           });
                                       },
            selector:                  '#' + id,
            language:                  'ru',
            height:                    height,
            // jbimages
            plugins:                   read ? ['preview fullscreen print'] : [
                'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
                'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
                'save table contextmenu directionality emoticons template paste textcolor responsivefilemanager'
            ],
            contextmenu:               "link image inserttable | cell row column deletetable",
            //content_css: '/admin/build/css/content.css',
            // jbimages
            menubar:                   !read,
            toolbar:                   !read
                                           ? 'insertfile undo redo | styleselect | bold italic ' +
                '| alignleft aligncenter alignright alignjustify | bullist numlist outdent indent ' +
                '| link image responsivefilemanager ' +
                '| print preview media fullpage | fontselect fontsizeselect forecolor backcolor emoticons'
                                           : '| print preview fullscreen |',
            font_formats:              'Andale Mono=andale mono,times;' +
                                       'Arial=arial,helvetica,sans-serif;' +
                                       'Arial Black=arial black,avant garde;' +
                                       'Book Antiqua=book antiqua,palatino;' +
                                       'Comic Sans MS=comic sans ms,sans-serif;' +
                                       'Courier New=courier new,courier;' +
                                       'Georgia=georgia,palatino;' +
                                       'Helvetica=helvetica;' +
                                       'Impact=impact,chicago;' +
                                       'Symbol=symbol;' +
                                       'Tahoma=tahoma,arial,helvetica,sans-serif;' +
                                       'Terminal=terminal,monaco;' +
                                       'Times New Roman=times new roman,times;' +
                                       'Trebuchet MS=trebuchet ms,geneva;' +
                                       'Verdana=verdana,geneva;' +
                                       'Webdings=webdings;' +
                                       'Wingdings=wingdings,zapf dingbats',
            fontsize_formats:          '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
            //link_context_toolbar:      true,
            force_p_newlines:          false,
            force_br_newlines:         false,
            verify_html:               false,
            forced_root_block:         "",
            image_advtab:              true,
            relative_urls:             false,
            remove_script_host:        link_host,
            //convert_urls:              true,
            // file_picker_callback: function (cb, value, meta) {
            //     // input.click();
            // },
            external_filemanager_path: "/filemanager/",
            filemanager_title:         "Файл менеджер",
            external_plugins:          {"filemanager": "/filemanager/plugin.min.js"}
        });

    });

    /**
     * Fix for tinymce link cotextmenu in modal bootstrap
     * this workaround makes magic happen
     * thanks @harry: http://stackoverflow.com/questions/18111582/tinymce-4-links-plugin-modal-in-not-editable
     */
    $(document).on('focusin', function (event)
    {
        if ($(event.target).closest(".mce-window").length)
        {
            event.stopImmediatePropagation();
        }
    });
}

/*************************************************************
 * get function By Name
 * */

function runFunctionByName(str)
{
    var names = str.split(';');
    var keys = {
        'js-date-picker': 'appendDatePicker',
        'js-time-picker': 'appendTimePicker',
        'js-tiny-mce':    'appendTinyMCE'
    };
    var fn = '';
    $.each(names, function (i, val)
    {
        var s = val.split(':');
        fn = $.trim(s[0] in keys ? keys[s[0]] : s[0]);
        if (typeof window[fn] === "function")
        {
            var args = s.length > 1 ? s[1].split(',') : null;
            args ? window[fn].apply(null, args) : window[fn].apply();
        }
    });
}

/*************************************************************************
 * Toggle input disabled
 * */

(function ($)
{
    $.fn.toggleDisabled = function (toggle)
    {
        return this.each(function ()
        {
            this.disabled = typeof toggle === "boolean" ? toggle : !this.disabled;
        });
    };
})(jQuery);

function toggleTableCellInputs(event, el, toggle)
{
    event.preventDefault();

    var tr = el.closest('tr');
    tr.find('td').not(el.closest('td')).each(function ()
    {
        var tgl = typeof toggle === "boolean"
            ? toggle
            : $(this).find('div:first').is(':visible');
        $(this).find('div:first').toggleClass('hidden', tgl);
        $(this).find('input,select,textarea').toggleDisabled(tgl);
        $(this).find('span:first').toggle(tgl);
    })
}

/*************************************************************************
 * Delete Row
 * */

function delete_row(el, event)
{

    event.preventDefault();

    var element = $.isPlainObject(el) ? el : $(el);
    var row = element.closest('[data-row]').data('row');
    var container = element.closest(row).length ? element.closest(row) : null;

    if (!container || !confirm('Вы действительно хотите удалить этот элемент?'))
    {
        return false;
    }

    var box = new ajaxLoader(container);

    $.post(element.attr('href'), function (data)
    {
        if (data.success)
        {
            container.hide('fast');
            box.remove();
        }
    });

    event.stopImmediatePropagation();

    return false;

}

/******************************************************************************
 * sort Object
 */
function sortObject(obj, sort)
{
    var sortable = [];

    for (var k in obj) sortable.push([k, obj[k]]);

    sortable.sort(function (a, b)
    {
        return sort(a, b)
    });

    return sortable;
}

/******************************************************************************
 * cut long words in string
 */
function cutWords(string, ln)
{
    var str = '';
    var length = ln ? ln : 20;
    $.map(string.split(' '), function (s)
    {
        str += ' ' + (s.length > length ? s.substring(1, length) + '...' : s);
    });
    return str;
}

/******************************************************************************
 * Nav Tabs
 */
$(document).on('click', '.nav-pills>li>a', function (event)
{
    event.preventDefault();
    var cn = $(this).closest('.nav-tab-container');
    cn.find('.nav-pills>li,.nav-tab').removeClass('active');
    $(this).parent('li').toggleClass('active');
    cn.find('.nav-tab').eq($(this).parent('li').index()).toggleClass('active');
});

/******************************************************************************
 * JS input placeholder toggle
 */

$(function ()
{
    var plh = '.js-placeholder';
    var pli = '.js-input-placeholder';

    $(plh).each(function ()
    {
        var inp = $(this).closest(pli).find('input').first();
        inp.closest(pli).css({position: 'relative'});
        $(this).css({
            position:   'absolute',
            left:       inp.css('padding-left'),
            top:        inp.css('padding-top'),
            height:     inp.height() + 'px',
            background: '#fff',
            color:      '#8c8c8c'
        });
        $(this).show();
    });

    $(document).on('click', pli, function (event)
    {
        var e = $(event.target);
        var inp = (e.hasClass(pli) ? e : e.closest(pli)).find('input').first();
        if (!inp.length) return false;
        var pl = inp.closest(pli).find(plh).first();
        pl.hide();
        inp.focus();
    });

    $(document).on('click', 'body', function (event)
    {
        var e = $(event.target);
        if (!e.is('input') && !e.is(plh))
        {
            $(plh).each(function ()
            {
                var inp = $(this).closest(pli).find('input').first();
                if (inp && $.trim(inp.val()) === '')
                {
                    $(this).show()
                }
            });
        }
    });

    $(document).on('change', pli + ' input:first', function ()
    {
        $(this).trigger('click')
    });

});

/******************************************************************************
 * Checkbox touchable
 */
$(function ()
{
    var cn = '.js-checkbox-touchable';

    $(cn).find('input[type="hidden"]').attr('disabled', true);

    $(document).on('click', cn + ' input[type="checkbox"]', function ()
    {
        $(cn).find('input[name="' + $(this).attr('name') + '"][type="hidden"]').attr('disabled', false)
    });

});
