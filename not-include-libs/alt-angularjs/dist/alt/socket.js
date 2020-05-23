alt.socketUrl = "", alt.modules.socket = angular.module("alt-socket", []).provider("socketFactory", [function() {
  "use strict";
  var n = "socket:";
  this.$get = ["$rootScope", "$timeout", function(t, e) {
    var o = function(n, t) {
      return t ? function() {
        var o = arguments;
        e(function() {
          t.apply(n, o)
        }, 0)
      } : angular.noop
    };
    return function(e) {
      e = e || {};
      var r = e.ioSocket || io.connect(),
        c = void 0 === e.prefix ? n : e.prefix,
        u = e.scope || t,
        a = function(n, t) {
          r.on(n, t.__ng = o(r, t))
        },
        i = function(n, t) {
          r.once(n, t.__ng = o(r, t))
        },
        s = {
          on: a,
          addListener: a,
          once: i,
          emit: function(n, t, e) {
            var c = arguments.length - 1,
              e = arguments[c];
            return "function" == typeof e && (e = o(r, e), arguments[c] = e), r.emit.apply(r, arguments)
          },
          removeListener: function(n, t) {
            return t && t.__ng && (arguments[1] = t.__ng), r.removeListener.apply(r, arguments)
          },
          removeAllListeners: function() {
            return r.removeAllListeners.apply(r, arguments)
          },
          disconnect: function(n) {
            return r.disconnect(n)
          },
          connect: function() {
            return r.connect()
          },
          forward: function(n, t) {
            n instanceof Array == !1 && (n = [n]), t || (t = u), n.forEach(function(n) {
              var e = c + n,
                u = o(r, function() {
                  Array.prototype.unshift.call(arguments, e), t.$broadcast.apply(t, arguments)
                });
              t.$on("$destroy", function() {
                r.removeListener(n, u)
              }), r.on(n, u)
            })
          }
        };
      return s
    }
  }]
}]).factory("$socket", ["socketFactory", function(n) {
  return n({
    ioSocket: io.connect(alt.socketUrl || alt.serverUrl)
  })
}]), alt.module("alt-socket", alt.modules.socket);
