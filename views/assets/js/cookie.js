var Cookie =
    {
        set: function (name, value, days) {
            var domain, domainParts, date, expires, host;

            if (days) {
                date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toGMTString();
            } else {
                expires = "";
            }

            host = location.host;
            if (host.split('.').length === 1) {
                document.cookie = name + "=" + value + expires + "; path=/";
            } else {
                domainParts = host.split('.');
                domainParts.shift();
                domain = '.' + domainParts.join('.');
                document.cookie = name + "=" + value + expires + "; path=/; domain=" + domain;
                if (Cookie.get(name) == null || Cookie.get(name) != value) {
                    domain = '.' + host;
                    document.cookie = name + "=" + value + expires + "; path=/; domain=" + domain;
                }
            }
        },

        get: function (name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1, c.length);
                }
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        },

        erase: function (name) {
            Cookie.set(name, '', -1);
        }
    };
