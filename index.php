<?php
echo <<<EOM

<!DOCTYPE HTML>
<html>
	<head>
  	<meta charset="UTF-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<title>Map</title>
  	<link href="bootstrap-3.2.0-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="openlayers/css/ol.css" rel="stylesheet">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  	<script src="bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
  	<script src="openlayers/build/ol.js"></script>
		<!-- <script src="proj4js-2.3.12/dist/proj4.js"></script> -->
    <script type="text/javascript">

      var map;
      var View;
      var currentMarker;


			//緯度経度の測地系変換
			function transformJ2W(lon,lat){
				// var p = proj4('EPSG:4301','EPSG:4326',[lon,lat]);
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

			//初期設定
      function init(){
				//日本測地系の宣言
				// proj4.defs('EPSG:4301', "+proj=longlat +ellps=bessel +towgs84=-146.336,506.832,680.254,0,0,0,0 + no_defs"); //Proj4jsエラー発生時の動作を登録
				// proj4.reportError = function(msg) {console.log(msg);} //日本測地系から世界測地系への変換関数

				setMapSize();

        View = new ol.View({
          // center: ol.proj.transform([139.69325423, 35.68970870], 'EPSG:4326', 'EPSG:3857'),   //都庁
					// center: ol.proj.transform([139.76581335, 35.68144572], 'EPSG:4326', 'EPSG:3857'),   //東京駅
<<<<<<< HEAD
<<<<<<< HEAD
					center: ol.proj.transform([130.70642710, 32.80549584], 'EPSG:4326', 'EPSG:3857'),   //熊本城

=======
					center: ol.proj.transform([139.88040268, 35.63292175], 'EPSG:4326', 'EPSG:3857'),   //ディズニーランド
>>>>>>> 25916f7f39d513af719f32e936cc5080035155dd
=======
					center: ol.proj.transform([139.88040268, 35.63292175], 'EPSG:4326', 'EPSG:3857'),   //ディズニーランド
>>>>>>> 005b8526fbcdf1941ce197a89f1b9f14ae8e9497
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


    </script>
  </head>



  <body onload="init();" style = "padding-top: 1%;" >
    <!-- Nav bar -->
    <div class="navbar navbar-inverse navbar-fixed-top">
    	<div class="container-fluid">
    		<div class="navbar-header">
    			<a class="navbar-brand" href="javascript:void(0);">
						Map
    			</a>
        </div>
      </div>
    </div>

    <div class="container-fluid" >
    	<div class="page-header" style="margin-bottom: 0px;"></div>

			<div class="row">
	      <!-- Map -->
	    	<div class="col-sm-12 col-md-10">
	    		<!-- <h4 class="content-header">Map</h4> -->
	    		<div id="map"></div>

	    	</div>

	      <!-- Side Information -->
	    	<div id="side-info" class="col-sm-12 col-md-2">

	        <div style="margin-bottom: 5px;">
	          <span>LonLat: </span> <span id="lonlat"></span>
	        </div>
	        <div class="container-fluid" style="margin-bottom: 15px;">
						<div class="row">
							<input type="button" class="btn btn-md btn-success col-md-12 col-sm-12" value="Button1" id="button01" style="margin-right: 5px;margin-bottom: 5px;" />
							<input type="button" class="btn btn-md btn-success col-md-12 col-sm-12" value="Button2" id="button02" style="margin-right: 5px;margin-bottom: 5px;" />
							<input type="button" class="btn btn-md btn-success col-md-12 col-sm-12" value="Button3" id="button03" style="margin-right: 5px;margin-bottom: 5px;" />
							<input type="button" class="btn btn-md btn-success col-md-12 col-sm-12" value="Button4" id="button04" style="margin-right: 5px;margin-bottom: 5px;" />

							<div class="row">
								<div class="col-xs-6">
									<span>Input A</span><input type="text" id="input_a" class="form-control"/>
								</div>
								<div class="col-xs-6">
									<span>Input B</span><input type="text" id="input_b" class="form-control"/>
								</div>
							</div>
						</div>
	        </div>

	        <div class="container-fluid" style="margin-bottom: 15px;">
						<div class="row">
		          <input type="button" class="btn btn-md btn-warning" id="button_ok" value="OK"/>
		          <input type="button" class="btn btn-md btn-info pull-right" id="button_cancel" value="Cancel" />
		          <br/>
		          <div class="container-fluid" style="height: 30px;margin-left: -15px;margin-top: 15px;">
		              <!-- <img id="loading" src="img/loading.gif" style="display: none;margin-right: 10px;" /> -->
		              <!-- <span id="status" class="lead"></span> -->
		          </div>
						</div>
	        </div>

	        <div class="container-fluid" id="result" style="margin-left: -15px;margin-bottom: 15px;">
	          <h4 class="content-header">Info</h4>
	          <div id="info" class="container-fluid" style="margin-left: -15px;margin-bottom: 15px;">
							<table class="table">
								<tbody id="info_table">
								</tbody>
							</table>
	          </div>
	        </div>

				</div><!-- Side Information -->
			</div>

    <!-- .container --></div>



  </body>
</html>
EOM;

?>
