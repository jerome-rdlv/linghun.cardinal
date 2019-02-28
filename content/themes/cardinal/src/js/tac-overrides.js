/* global tarteaucitron */
'use strict';

// add cookie management link
(function () {
    var menu = document.querySelector('.footer ul:last-child');
    if (menu) {
        var item = document.createElement('li');
        menu.appendChild(item);
        item.setAttribute('class', item.previousElementSibling.getAttribute('class'));
        var link = document.createElement('a');
        link.appendChild(document.createTextNode('Gestion des cookies'));
        link.setAttribute('href', '#');
        item.appendChild(link);

        link.addEventListener('click', function (e) {
            e.preventDefault();
            tarteaucitron.userInterface.openPanel();
        });
    }
})();

// youtube
tarteaucitron.services.rdlv_youtube = {
    "key": "rdlv_youtube",
    "type": "video",
    "name": "YouTube",
    "uri": "https://www.google.fr/intl/fr/policies/privacy/",
    "needConsent": true,
    "cookies": ['VISITOR_INFO1_LIVE', 'YSC', 'PREF', 'GEUP'],
    "js": function () {
        "use strict";
        tarteaucitron.fallback(['youtube_player'], function (x) {
            var video_src = x.getAttribute("data-src"),
                video_width = x.getAttribute("data-width"),
                frame_width = 'width=',
                video_height = x.getAttribute("data-height"),
                frame_height = 'height=',
                video_frame;

            if (video_src === undefined) {
                return "";
            }
            if (video_width !== undefined) {
                frame_width += '"' + video_width + '" ';
            } else {
                frame_width += '"" ';
            }
            if (video_height !== undefined) {
                frame_height +=  '"' + video_height + '" ';
            } else {
                frame_height += '"" ';
            }
            video_frame = '<iframe type="text/html" ' + frame_width + frame_height + ' src="'+ video_src +'" frameborder="0"></iframe>';
            return video_frame;
        });
    },
    "fallback": function () {
        "use strict";
        var id = 'rdlv_youtube';
        tarteaucitron.fallback(['youtube_player'], function (elem) {
            elem.style.width = elem.getAttribute('data-width') + 'px';
            elem.style.height = elem.getAttribute('data-height') + 'px';
            return tarteaucitron.engage(id);
        });
    }
};

// vimeo
tarteaucitron.services.rdlv_vimeo = {
    "key": "rdlv_vimeo",
    "type": "video",
    "name": "Vimeo",
    "uri": "http://vimeo.com/privacy",
    "needConsent": true,
    "cookies": ['__utmt_player', '__utma', '__utmb', '__utmc', '__utmv', 'vuid', '__utmz', 'player'],
    "js": function () {
        "use strict";
        tarteaucitron.fallback(['vimeo_player'], function (x) {
            var video_src = x.getAttribute("data-src"),
                video_width = x.getAttribute("data-width"),
                frame_width = 'width=',
                video_height = x.getAttribute("data-height"),
                frame_height = 'height=',
                video_frame;

            if (video_src === undefined) {
                return "";
            }
            if (video_width !== undefined) {
                frame_width += '"' + video_width + '" ';
            } else {
                frame_width += '"" ';
            }
            if (video_height !== undefined) {
                frame_height +=  '"' + video_height + '" ';
            } else {
                frame_height += '"" ';
            }
            video_frame = '<iframe src="' + video_src + '" ' + frame_width + frame_height + ' frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
            return video_frame;
        });
    },
    "fallback": function () {
        "use strict";
        var id = 'rdlv_vimeo';
        tarteaucitron.fallback(['vimeo_player'], function (elem) {
            elem.style.width = elem.getAttribute('data-width') + 'px';
            elem.style.height = elem.getAttribute('data-height') + 'px';
            return tarteaucitron.engage(id);
        });
    }
};