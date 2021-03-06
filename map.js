var map;
var View;
var currentMarker;

//初期設定
function init(){
    //日本測地系の宣言
    // proj4.defs('EPSG:4301', "+proj=longlat +ellps=bessel +towgs84=-146.336,506.832,680.254,0,0,0,0 + no_defs"); //Proj4jsエラー発生時の動作を登録
    // proj4.reportError = function(msg) {console.log(msg);} //日本測地系から世界測地系への変換関数

    setMapSize();

    View = new ol.View({
        // center: ol.proj.transform([139.69325423, 35.68970870], 'EPSG:4326', 'EPSG:3857'),   //都庁
        center: ol.proj.transform([139.76581335, 35.68144572], 'EPSG:4326', 'EPSG:3857'),   //東京駅
        // center: ol.proj.transform([130.70642710, 32.80549584], 'EPSG:4326', 'EPSG:3857'),   //熊本城
        // center: ol.proj.transform([139.88040268, 35.63292175], 'EPSG:4326', 'EPSG:3857'),   //ディズニーランド
        zoom: 15
    });

    map = new ol.Map({
        target: 'map',
        layers: [
            new ol.layer.Tile({source: new ol.source.OSM()})
        ],
        view: View
    });

    //地図クリックイベント設定
    map.on('click', function(evt){
        var coordinate = evt.coordinate;
        var stringifyFunc = ol.coordinate.createStringXY(8);
        var outstr = stringifyFunc(ol.proj.transform(coordinate, "EPSG:3857", "EPSG:4326"));
        //HTMLにLonLatを表示
        document.getElementById('lonlat').innerHTML = outstr;

        //現在地Markerを表示
        if(currentMarker!=0){
            map.removeOverlay(currentMarker);
        }
        var splitlonlat = outstr.split(",");
        var clon = +splitlonlat[0];
        var clat = +splitlonlat[1];
        var cmarker = new ol.Feature({
            geometry: new ol.geom.Point(ol.proj.transform([clon,clat], 'EPSG:4326', 'EPSG:3857')),
        });
        currentMarker = new ol.layer.Vector({
            source: new ol.source.Vector({
                features:[cmarker]
            }),
            style: new ol.style.Style({
                image: new ol.style.Icon(/** @type {olx.style.IconOptions} */ {
                    anchor: [0.5, 1],
                    anchorXUnits: 'fraction',
                    anchorYUnits: 'fraction',
                    opacity: 0.75,
                    src: "icon/pin2.png",
                    scale: 0.8
                })
            })
        });
        currentMarker.setZIndex(200);
        map.addOverlay(currentMarker);
    });

    $(window).resize(setMapSize);

    //ボタン配置
    $("#button01").click(function(){
        console.log("ボタン1");
    });

    $("#button02").click(function(){
        console.log("ボタン2");
    });

    $("#button03").click(function(){
        console.log("ボタン3");
    });

    $("#button04").click(function(){
        console.log("ボタン4");
    });

    $("#button_ok").click(function(){
        console.log("ok");
    });

    $("#button_cancel").click(function(){
        console.log("cancel");
    });

}



//予め用意したスタイルをマーカーに適用て表示する
function drawMarker(lon,lat,text,icon){
    console.log("lon:"+lon+" lat:"+lat+" type:"+icon);
    var j2w = transformJ2W(lon, lat);　//日本測地系→世界測地系
    var marker = new ol.Feature({
        geometry: new ol.geom.Point(ol.proj.transform([j2w[0], j2w[1]], 'EPSG:4326', 'EPSG:3857')),
        text:text,
        textColor:"#0000ff"
    });
    marker.setStyle(markerStyle);
    var vector = new ol.layer.Vector({
        source: new ol.source.Vector({
            features:[marker]
        })
    });
    vector.setZIndex(100);
    map.addOverlay(vector);

}

//マーカーのスタイルを定義
var markerStyle = function(resolution) {
    var styles = [
        new ol.style.Style({
            image: new ol.style.Icon(/** @type {olx.style.IconOptions} */ {
                anchor: [0.5, 1],
                anchorXUnits: 'fraction',
                anchorYUnits: 'fraction',
                opacity: 0.75,
                src: "icon/pin1.png"
            }),
            text: new ol.style.Text({
                fill: new ol.style.Fill({color: this.get("textColor")}),
                stroke: new ol.style.Stroke({color: "#ffffff", width: 2}),
                scale: 1.6,
                textAlign: "center",
                textBaseline: "top",
                offsetY: 0,
                text: this.get("text"),
                font: "Courier New, monospace"
            })
        })
    ];
    return styles;
};


//マップの高さを画面に合わせる
function setMapSize(){
    var windowHeight = $(window).height();
    if($(window).width()<=991){
        $("#map").css("height",(windowHeight-30-51-150)+"px");
    }else{
        $("#map").css("height",(windowHeight-30-51)+"px");
    }
}
