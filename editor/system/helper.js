define([], function(){
    alt.factory('Helper', function(){
        var api={};


        var tbl = function(nilai) {
            var angka = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
            var x = parseInt(nilai);
            if (x < 12 && !isNaN(x)) return " " + angka[x];
            if (x < 20 && !isNaN(x)) return tbl(x - 10) + " Belas";
            if (x < 100) return tbl(x / 10) + " Puluh" + tbl(x % 10);
            if (x < 200) return " Seratus" + tbl(x - 100);
            if (x < 1000) return tbl(x / 100) + " Ratus" + tbl(x % 100);
            if (x < 2000) return " Seribu" + tbl(x - 1000);
            if (x < 1000000) return tbl(x / 1000) + " Ribu" + tbl(x % 1000);
            if (x < 1000000000) return tbl(x / 1000000) + " Juta" + tbl(x % 1000000);
            if (x < 1000000000000) return tbl(x / 1000000000) + " Milyar" + tbl(x / 1000000) + " Juta" + tbl(x % 1000000);
            if (x < 1000000000000000) return tbl(x / 1000000000000) + " Trilyun" + tbl(x / 1000000000) + " Milyar" + tbl(x / 1000000) + " Juta" + tbl(x % 1000000);
        }

        api.getBulan=function(){
           return [
            {val:"0", name:"-"},
            {val:"1", name:"Januari"},
            {val:"2", name:"Februari"},
            {val:"3", name:"Maret"},
            {val:"4", name:"April"},
            {val:"5", name:"Mei"},
            {val:"6", name:"Juni"},
            {val:"7", name:"Juli"},
            {val:"8", name:"Agustus"},
            {val:"9", name:"September"},
            {val:"10", name:"Oktober"},
            {val:"11", name:"November"},
            {val:"12", name:"Desember"}
          ];
        }

        api.extractFileName=function(fullpath){
          return fullpath.replace(/^.*[\\\/]/, '');
        }
        api.toNumber=function(nilai){
          if (nilai==''||nilai==undefined) return 0;
          var v=nilai.replace(/[^0-9]/g,'');
          return parseInt(v);
        }

        api.toCurrency=function(val){
          if(val==''||val==undefined||val==0||val=='0') return '';
          var v=val.toString().replace(/[^0-9]/g,'');
          return v.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
        }

        api.terbilang=function(val){
          var v=tbl(val);
          return v;
        }

        api.test=function(){
          console.log('this is funct');
        }

        /* export table ke excel
         * parameter table = id table
         * style jangan menggunakan css
         * lebih baik pake hidden div sebagai wrapper-nya
         * misal
         * <div style="display:none">
         *    <table id="table-excel">..</table>
         * </div>
         * <button type="button" ng-click="ConvertExcel('table-excel','test')">Convert</button>
         * dalam controller.js
         * $scope.ConvertExcel=function(table,name){
         *    Helper.exportToExcel(table,name);
         * }
         */
        api.exportToExcel=function(table,name){
          var t=document.getElementById(table);
          var base64 = function (s) { return window.btoa(unescape(encodeURIComponent(s))) };
          var format = function (s) { return s.replace(/{(\w+)}/g,"")};
          var  uri = 'data:application/vnd.ms-excel;base64,',
          template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" '+
                     'xmlns:x="urn:schemas-microsoft-com:office:excel" '+
                     'xmlns="http://www.w3.org/TR/REC-html40" lang="en">'+
                     '<head><meta charset="utf-8"> <meta http-equiv="content-language" content="en"> '+
                     '<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>'+
                     '<x:Name>'+name+'</x:Name>'+
                     '<x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions>'+
                     '</x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook>'+
                     '</xml><![endif]--></head><body>'+
                     '<table>'+t.innerHTML+'</table></body></html>';
          /*
          pake method ini bisa
             window.location.href=uri + base64(format(template));

          tapi lebih smooth pake bawah ini,
          nama file bisa diganti dengan easy human readingfile
            - buat element anchor dengan attribut href dan download
            - diklik
            - element dihapus..
          */

          var ref = uri + base64(format(template));
          var a = document.createElement('A'),
              b = document.createAttribute('href'),
              c = document.createAttribute('download');

          b.value=ref;
          c.value=name+'.xls';
          a.setAttributeNode(b);
          a.setAttributeNode(c);
          document.body.appendChild(a);
          a.click();
          document.body.removeChild(a);
        }
        api.filterTime=function(){
          return [ {key:'Bulan-Tahun',value:2},{key:'Diantara Tanggal',value:1}];
        }
        


        return api;
    });
});
