alt.language="id",alt.dictionary=alt.extend({id:{}},alt.dictionary),alt.modules.i18n=angular.module("alt-i18n",[]).factory("$i18n",["$log",function(a){return function(a){return alt.dictionary[alt.language]?alt.dictionary[alt.language][a]||a:a}}]),alt.module("alt-i18n",alt.modules.i18n);