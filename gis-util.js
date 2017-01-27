//緯度経度の測地系変換
function transformJ2W(lon,lat){
    var p = proj4('EPSG:4301','EPSG:4326',[lon,lat]);
    return p;
}

//Geometryの測地系変換
function transGeomJ2W(Jgeom){
    var Jcoordinates = Jgeom.getCoordinates();
    var Wcoordinates = new Array();

    for(i=0;i<Jcoordinates.length;i++){
        Wcoordinates[i] = new Array();
        for(j=0;j<Jcoordinates[i].length;j++){
            var lonlat = transformJ2W(Jcoordinates[i][j][0], Jcoordinates[i][j][1]);
            Wcoordinates[i][j] = new Array();
            Wcoordinates[i][j][0] = lonlat[0];
            Wcoordinates[i][j][1] = lonlat[1];
        }
    }
    var Wgeom = new ol.geom.MultiLineString(Wcoordinates);
    return Wgeom;
}

//緯度経度からメッシュコードを計算する
function calcMeshCode(lon, lat, resolution = 1){
    var p = Math.floor((lat * 60) / 40);
    var a = (lat * 60) % 40;
    var q = Math.floor(a / 5);
    var b = a % 5;
    var r = Math.floor((b * 60) / 30);
    var c = (b * 60) % 30;
    var s = Math.floor(c / 15);
    var d = c % 15;
    var t = Math.floor(d / 7.5);

    var u = Math.floor(lon - 100);
    var f = lon - 100 - u;
    var v = Math.floor((f * 60) / 7.5);
    var g = (f * 60) % 7.5;
    var w = Math.floor((g * 60) / 45);
    var h = (g * 60) % 45;
    var x = Math.floor(h / 22.5);
    var i = h % 22.5;
    var y = Math.floor(i / 11.25);

    var m = (s * 2) + (x + 1);
    var n = (t * 2) + (y + 1);

    //1次メッシュ
    var mesh = "" + p + u;
    //2次メッシュ
    if(resolution >= 2){
        mesh = mesh + q + v;
        //3次メッシュ
        if(resolution >= 3){
            mesh = mesh + r + w;
            // 1/2メッシュ
            if(resolution >= 4){
                mesh = mesh + m;
                // 1/4メッシュ
                if(resolution >=5){
                    mesh = mesh + n;
                }
            }
        }
    }

    return mesh;
}
